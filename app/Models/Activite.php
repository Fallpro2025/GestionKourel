<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activite extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'nom',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'responsable_id',
        'statut',
        'configuration',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'configuration' => 'array',
    ];

    /**
     * Relation avec le responsable de l'activité
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Membre::class, 'responsable_id');
    }

    /**
     * Relation avec les présences
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Relation avec les répétitions
     */
    public function repetitions(): HasMany
    {
        return $this->hasMany(ActivityRepetition::class);
    }

    /**
     * Accessor pour la durée de l'activité en minutes
     */
    public function getDureeMinutesAttribute(): int
    {
        return $this->date_debut->diffInMinutes($this->date_fin);
    }

    /**
     * Accessor pour vérifier si l'activité est en cours
     */
    public function getEnCoursAttribute(): bool
    {
        $now = now();
        return $now->between($this->date_debut, $this->date_fin);
    }

    /**
     * Accessor pour vérifier si l'activité est terminée
     */
    public function getTermineeAttribute(): bool
    {
        return $this->date_fin < now();
    }

    /**
     * Accessor pour vérifier si l'activité est à venir
     */
    public function getAVenirAttribute(): bool
    {
        return $this->date_debut > now();
    }

    /**
     * Scope pour les activités à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>', now());
    }

    /**
     * Scope pour les activités en cours
     */
    public function scopeEnCours($query)
    {
        $now = now();
        return $query->where('date_debut', '<=', $now)
                    ->where('date_fin', '>=', $now);
    }

    /**
     * Scope pour les activités terminées
     */
    public function scopeTerminees($query)
    {
        return $query->where('date_fin', '<', now());
    }

    /**
     * Scope pour un type d'activité spécifique
     */
    public function scopeDeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Obtenir les statistiques de présence pour cette activité
     */
    public function getStatistiquesPresence(): array
    {
        $totalPresences = $this->presences()->count();
        $presents = $this->presences()->where('statut', 'present')->count();
        $absentsJustifies = $this->presences()->where('statut', 'absent_justifie')->count();
        $absentsNonJustifies = $this->presences()->where('statut', 'absent_non_justifie')->count();
        $retards = $this->presences()->where('statut', 'retard')->count();

        $tauxPresence = $totalPresences > 0 ? round(($presents / $totalPresences) * 100, 2) : 0;

        return [
            'total_presences' => $totalPresences,
            'presents' => $presents,
            'absents_justifies' => $absentsJustifies,
            'absents_non_justifies' => $absentsNonJustifies,
            'retards' => $retards,
            'taux_presence' => $tauxPresence,
        ];
    }

    /**
     * Obtenir les membres présents
     */
    public function getMembresPresents()
    {
        return $this->presences()
            ->where('statut', 'present')
            ->with('membre')
            ->get()
            ->pluck('membre');
    }

    /**
     * Obtenir les membres absents
     */
    public function getMembresAbsents()
    {
        return $this->presences()
            ->whereIn('statut', ['absent_justifie', 'absent_non_justifie'])
            ->with('membre')
            ->get()
            ->pluck('membre');
    }

    /**
     * Obtenir les membres en retard
     */
    public function getMembresEnRetard()
    {
        return $this->presences()
            ->where('statut', 'retard')
            ->with('membre')
            ->get()
            ->pluck('membre');
    }

    /**
     * Marquer la présence d'un membre
     */
    public function marquerPresence(int $membreId, string $statut, ?string $justification = null): Presence
    {
        return $this->presences()->updateOrCreate(
            ['membre_id' => $membreId],
            [
                'statut' => $statut,
                'justification' => $justification,
                'heure_arrivee' => $statut === 'present' || $statut === 'retard' ? now() : null,
                'minutes_retard' => $statut === 'retard' ? $this->calculerRetard() : 0,
            ]
        );
    }

    /**
     * Calculer le retard en minutes
     */
    private function calculerRetard(): int
    {
        $retard = now()->diffInMinutes($this->date_debut);
        return max(0, $retard);
    }
}

