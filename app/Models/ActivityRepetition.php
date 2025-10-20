<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityRepetition extends Model
{
    use HasFactory;

    protected $fillable = [
        'activite_id',
        'date_repetition',
        'heure_debut',
        'heure_fin',
        'lieu',
        'statut',
        'notes',
        'responsable_id',
    ];

    protected $casts = [
        'date_repetition' => 'date',
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
    ];

    /**
     * Relation avec l'activité parente
     */
    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
    }

    /**
     * Relation avec le responsable de la répétition
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Membre::class, 'responsable_id');
    }

    /**
     * Relation avec les présences de cette répétition
     */
    public function presences(): HasMany
    {
        return $this->hasMany(PresenceRepetition::class, 'repetition_id');
    }

    /**
     * Accessor pour vérifier si la répétition est à venir
     */
    public function getAVenirAttribute(): bool
    {
        return $this->date_repetition > now()->toDateString();
    }

    /**
     * Accessor pour vérifier si la répétition est passée
     */
    public function getPasseeAttribute(): bool
    {
        return $this->date_repetition < now()->toDateString();
    }

    /**
     * Accessor pour vérifier si la répétition est aujourd'hui
     */
    public function getAujourdhuiAttribute(): bool
    {
        return $this->date_repetition->isToday();
    }

    /**
     * Obtenir les statistiques de présence pour cette répétition
     */
    public function getStatistiquesPresence(): array
    {
        $totalPresences = $this->presences()->count();
        $presents = $this->presences()->where('statut', 'present')->count();
        $absentsJustifies = $this->presences()->where('statut', 'absent_justifie')->count();
        $absentsNonJustifies = $this->presences()->where('statut', 'absent_non_justifie')->count();
        $retards = $this->presences()->where('statut', 'retard')->count();
        
        // Calculer le total des membres actifs
        $totalMembres = \App\Models\Membre::where('statut', 'actif')->count();
        
        // Calculer le taux de présence basé sur le total des membres
        $tauxPresence = $totalMembres > 0 ? round(($presents / $totalMembres) * 100, 2) : 0;

        return [
            'total_membres' => $totalMembres,
            'total_presences' => $totalPresences,
            'presents' => $presents,
            'absents' => $absentsJustifies + $absentsNonJustifies,
            'absents_justifies' => $absentsJustifies,
            'absents_non_justifies' => $absentsNonJustifies,
            'retards' => $retards,
            'taux_presence' => $tauxPresence,
        ];
    }

    /**
     * Scope pour les répétitions à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_repetition', '>', now()->toDateString());
    }

    /**
     * Scope pour les répétitions passées
     */
    public function scopePassees($query)
    {
        return $query->where('date_repetition', '<', now()->toDateString());
    }

    /**
     * Scope pour les répétitions d'aujourd'hui
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_repetition', now()->toDateString());
    }

    /**
     * Scope pour une activité spécifique
     */
    public function scopeDeLActivite($query, int $activiteId)
    {
        return $query->where('activite_id', $activiteId);
    }

    /**
     * Scope pour une période donnée
     */
    public function scopeDeLaPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_repetition', [$dateDebut, $dateFin]);
    }
}
