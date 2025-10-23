<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjetCotisation extends Model
{
    use HasFactory;

    protected $table = 'projets_cotisation';

    protected $fillable = [
        'nom',
        'description',
        'montant_total',
        'montant_collecte',
        'date_debut',
        'date_fin',
        'statut',
        'type_cotisation',
        'created_by',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'montant_collecte' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Relation avec les assignations de cotisation
     */
    public function assignations(): HasMany
    {
        return $this->hasMany(AssignationCotisation::class, 'projet_id');
    }

    /**
     * Relation avec le créateur du projet
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(Membre::class, 'created_by');
    }

    /**
     * Accessor pour le pourcentage de collecte
     */
    public function getPourcentageCollecteAttribute(): float
    {
        if ($this->montant_total == 0) {
            return 0.0;
        }
        
        return round(($this->montant_collecte / $this->montant_total) * 100, 2);
    }

    /**
     * Accessor pour le montant restant à collecter
     */
    public function getMontantRestantAttribute(): float
    {
        return $this->montant_total - $this->montant_collecte;
    }

    /**
     * Scope pour les projets actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les projets en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('date_fin', '<', now())
                    ->where('statut', 'actif');
    }

    /**
     * Calculer le montant collecté automatiquement
     */
    public function calculerMontantCollecte(): void
    {
        $montantCollecte = $this->assignations()
            ->where('statut_paiement', 'paye')
            ->sum('montant_payé');

        $this->update(['montant_collecte' => $montantCollecte]);
    }

    /**
     * Obtenir les statistiques du projet
     */
    public function getStatistiques(): array
    {
        $totalAssignations = $this->assignations()->count();
        $assignationsPayees = $this->assignations()->where('statut_paiement', 'paye')->count();
        $assignationsPartielles = $this->assignations()->where('statut_paiement', 'partiel')->count();
        $assignationsNonPayees = $this->assignations()->where('statut_paiement', 'non_paye')->count();

        return [
            'total_assignations' => $totalAssignations,
            'assignations_payees' => $assignationsPayees,
            'assignations_partielles' => $assignationsPartielles,
            'assignations_non_payees' => $assignationsNonPayees,
            'taux_recouvrement' => $totalAssignations > 0 ? round(($assignationsPayees / $totalAssignations) * 100, 2) : 0,
        ];
    }
}

