<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignationCotisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'projet_id',
        'montant_assigné',
        'montant_payé',
        'statut_paiement',
        'date_echeance',
        'date_dernier_paiement',
        'historique_paiements',
    ];

    protected $casts = [
        'montant_assigné' => 'decimal:2',
        'montant_payé' => 'decimal:2',
        'date_echeance' => 'date',
        'date_dernier_paiement' => 'datetime',
        'historique_paiements' => 'array',
    ];

    /**
     * Relation avec le membre
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Relation avec le projet de cotisation
     */
    public function projet(): BelongsTo
    {
        return $this->belongsTo(ProjetCotisation::class, 'projet_id');
    }

    /**
     * Accessor pour le montant restant
     */
    public function getMontantRestantAttribute(): float
    {
        return $this->montant_assigné - $this->montant_payé;
    }

    /**
     * Accessor pour le pourcentage payé
     */
    public function getPourcentagePayeAttribute(): float
    {
        if ($this->montant_assigné == 0) {
            return 0.0;
        }
        
        return round(($this->montant_payé / $this->montant_assigné) * 100, 2);
    }

    /**
     * Accessor pour vérifier si en retard
     */
    public function getEnRetardAttribute(): bool
    {
        return $this->statut_paiement !== 'paye' 
            && $this->date_echeance 
            && $this->date_echeance < now()->toDateString();
    }

    /**
     * Accessor pour le nombre de jours de retard
     */
    public function getJoursRetardAttribute(): int
    {
        if (!$this->en_retard) {
            return 0;
        }
        
        return now()->diffInDays($this->date_echeance);
    }

    /**
     * Scope pour les assignations en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('statut_paiement', '!=', 'paye')
                    ->where('date_echeance', '<', now());
    }

    /**
     * Scope pour les assignations payées
     */
    public function scopePayees($query)
    {
        return $query->where('statut_paiement', 'paye');
    }

    /**
     * Scope pour les assignations non payées
     */
    public function scopeNonPayees($query)
    {
        return $query->where('statut_paiement', 'non_paye');
    }

    /**
     * Enregistrer un paiement
     */
    public function enregistrerPaiement(float $montant, string $methode = 'espèces'): void
    {
        $nouveauMontantPaye = $this->montant_payé + $montant;
        
        // Déterminer le nouveau statut
        $nouveauStatut = 'partiel';
        if ($nouveauMontantPaye >= $this->montant_assigné) {
            $nouveauStatut = 'paye';
            $nouveauMontantPaye = $this->montant_assigné; // Ne pas dépasser le montant assigné
        }

        // Ajouter au historique
        $historique = $this->historique_paiements ?? [];
        $historique[] = [
            'date' => now()->toDateString(),
            'montant' => $montant,
            'methode' => $methode,
        ];

        $this->update([
            'montant_payé' => $nouveauMontantPaye,
            'statut_paiement' => $nouveauStatut,
            'date_dernier_paiement' => now(),
            'historique_paiements' => $historique,
        ]);

        // Mettre à jour le montant collecté du projet
        $this->projet->calculerMontantCollecte();
    }

    /**
     * Obtenir le résumé des paiements
     */
    public function getResumePaiements(): array
    {
        $historique = $this->historique_paiements ?? [];
        $totalPaiements = count($historique);
        $dernierPaiement = $totalPaiements > 0 ? $historique[$totalPaiements - 1] : null;

        return [
            'total_paiements' => $totalPaiements,
            'dernier_paiement' => $dernierPaiement,
            'montant_total_paye' => $this->montant_payé,
            'montant_restant' => $this->montant_restant,
            'pourcentage_paye' => $this->pourcentage_paye,
        ];
    }
}

