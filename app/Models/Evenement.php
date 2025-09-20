<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'date_debut',
        'date_fin',
        'lieu',
        'description',
        'budget',
        'statut',
        'membres_selectionnes',
        'configuration',
        'created_by',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'budget' => 'decimal:2',
        'membres_selectionnes' => 'array',
        'configuration' => 'array',
    ];

    /**
     * Relation avec le créateur de l'événement
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(Membre::class, 'created_by');
    }

    /**
     * Relation avec les alertes liées à cet événement
     */
    public function alertes(): HasMany
    {
        return $this->hasMany(Alerte::class);
    }

    /**
     * Accessor pour la durée de l'événement en heures
     */
    public function getDureeHeuresAttribute(): float
    {
        return round($this->date_debut->diffInHours($this->date_fin), 1);
    }

    /**
     * Accessor pour vérifier si l'événement est en cours
     */
    public function getEnCoursAttribute(): bool
    {
        $now = now();
        return $now->between($this->date_debut, $this->date_fin);
    }

    /**
     * Accessor pour vérifier si l'événement est terminé
     */
    public function getTermineAttribute(): bool
    {
        return $this->date_fin < now();
    }

    /**
     * Accessor pour vérifier si l'événement est à venir
     */
    public function getAVenirAttribute(): bool
    {
        return $this->date_debut > now();
    }

    /**
     * Accessor pour obtenir le type en français
     */
    public function getTypeFrancaisAttribute(): string
    {
        return match($this->type) {
            'magal' => 'Magal',
            'gamou' => 'Gamou',
            'promokhane' => 'Promokhane',
            'conference' => 'Conférence',
            'formation' => 'Formation',
            'autre' => 'Autre',
            default => 'Inconnu'
        };
    }

    /**
     * Accessor pour obtenir le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'Planifié',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé',
            default => 'Inconnu'
        };
    }

    /**
     * Scope pour les événements à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>', now());
    }

    /**
     * Scope pour les événements en cours
     */
    public function scopeEnCours($query)
    {
        $now = now();
        return $query->where('date_debut', '<=', $now)
                    ->where('date_fin', '>=', $now);
    }

    /**
     * Scope pour les événements terminés
     */
    public function scopeTermines($query)
    {
        return $query->where('date_fin', '<', now());
    }

    /**
     * Scope pour un type d'événement spécifique
     */
    public function scopeDeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour les événements majeurs (Magal, Gamou, Promokhane)
     */
    public function scopeMajeurs($query)
    {
        return $query->whereIn('type', ['magal', 'gamou', 'promokhane']);
    }

    /**
     * Obtenir les membres sélectionnés pour une prestation spécifique
     */
    public function getMembresPourPrestation(string $prestation): array
    {
        $membresSelectionnes = $this->membres_selectionnes ?? [];
        return $membresSelectionnes[$prestation] ?? [];
    }

    /**
     * Ajouter un membre à une prestation
     */
    public function ajouterMembrePrestation(int $membreId, string $prestation): void
    {
        $membresSelectionnes = $this->membres_selectionnes ?? [];
        
        if (!isset($membresSelectionnes[$prestation])) {
            $membresSelectionnes[$prestation] = [];
        }
        
        if (!in_array($membreId, $membresSelectionnes[$prestation])) {
            $membresSelectionnes[$prestation][] = $membreId;
            $this->update(['membres_selectionnes' => $membresSelectionnes]);
        }
    }

    /**
     * Retirer un membre d'une prestation
     */
    public function retirerMembrePrestation(int $membreId, string $prestation): void
    {
        $membresSelectionnes = $this->membres_selectionnes ?? [];
        
        if (isset($membresSelectionnes[$prestation])) {
            $membresSelectionnes[$prestation] = array_filter(
                $membresSelectionnes[$prestation],
                fn($id) => $id !== $membreId
            );
            $this->update(['membres_selectionnes' => $membresSelectionnes]);
        }
    }

    /**
     * Obtenir le nombre total de membres sélectionnés
     */
    public function getNombreTotalMembresSelectionnes(): int
    {
        $membresSelectionnes = $this->membres_selectionnes ?? [];
        $total = 0;
        
        foreach ($membresSelectionnes as $prestation => $membres) {
            $total += count($membres);
        }
        
        return $total;
    }

    /**
     * Obtenir les statistiques de l'événement
     */
    public function getStatistiques(): array
    {
        return [
            'nombre_prestations' => count($this->membres_selectionnes ?? []),
            'nombre_total_membres' => $this->getNombreTotalMembresSelectionnes(),
            'duree_heures' => $this->duree_heures,
            'budget' => $this->budget,
            'statut' => $this->statut_francais,
            'type' => $this->type_francais,
        ];
    }

    /**
     * Vérifier si un membre est sélectionné pour une prestation
     */
    public function membreEstSelectionne(int $membreId, string $prestation): bool
    {
        $membresSelectionnes = $this->membres_selectionnes ?? [];
        $membresPrestation = $membresSelectionnes[$prestation] ?? [];
        
        return in_array($membreId, $membresPrestation);
    }

    /**
     * Obtenir toutes les prestations disponibles
     */
    public function getPrestationsDisponibles(): array
    {
        return ['declamation', 'chorale', 'animation', 'organisation', 'logistique'];
    }
}

