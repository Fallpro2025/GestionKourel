<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresenceRepetition extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'repetition_id',
        'statut',
        'heure_arrivee',
        'minutes_retard',
        'justification',
        'latitude',
        'longitude',
        'prestation_effectuee',
        'notes_prestation',
    ];

    protected $casts = [
        'heure_arrivee' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'prestation_effectuee' => 'boolean',
    ];

    /**
     * Relation avec le membre
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Relation avec la répétition
     */
    public function repetition(): BelongsTo
    {
        return $this->belongsTo(ActivityRepetition::class, 'repetition_id');
    }

    /**
     * Accessor pour vérifier si c'est une présence effective
     */
    public function getEstPresentAttribute(): bool
    {
        return $this->statut === 'present';
    }

    /**
     * Accessor pour vérifier si c'est une absence justifiée
     */
    public function getEstAbsentJustifieAttribute(): bool
    {
        return $this->statut === 'absent_justifie';
    }

    /**
     * Accessor pour vérifier si c'est une absence non justifiée
     */
    public function getEstAbsentNonJustifieAttribute(): bool
    {
        return $this->statut === 'absent_non_justifie';
    }

    /**
     * Accessor pour vérifier si c'est un retard
     */
    public function getEstRetardAttribute(): bool
    {
        return $this->statut === 'retard';
    }

    /**
     * Accessor pour obtenir le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        return match($this->statut) {
            'present' => 'Présent',
            'absent_justifie' => 'Absent (justifié)',
            'absent_non_justifie' => 'Absent (non justifié)',
            'retard' => 'En retard',
            default => 'Inconnu'
        };
    }

    /**
     * Accessor pour obtenir la couleur du statut
     */
    public function getCouleurStatutAttribute(): string
    {
        return match($this->statut) {
            'present' => 'green',
            'absent_justifie' => 'yellow',
            'absent_non_justifie' => 'red',
            'retard' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Scope pour les présences effectives
     */
    public function scopePresents($query)
    {
        return $query->where('statut', 'present');
    }

    /**
     * Scope pour les absences justifiées
     */
    public function scopeAbsentsJustifies($query)
    {
        return $query->where('statut', 'absent_justifie');
    }

    /**
     * Scope pour les absences non justifiées
     */
    public function scopeAbsentsNonJustifies($query)
    {
        return $query->where('statut', 'absent_non_justifie');
    }

    /**
     * Scope pour les retards
     */
    public function scopeRetards($query)
    {
        return $query->where('statut', 'retard');
    }

    /**
     * Scope pour les présences avec prestation
     */
    public function scopeAvecPrestation($query)
    {
        return $query->where('prestation_effectuee', true);
    }

    /**
     * Scope pour les présences d'un membre spécifique
     */
    public function scopeDuMembre($query, int $membreId)
    {
        return $query->where('membre_id', $membreId);
    }

    /**
     * Scope pour les présences d'une répétition spécifique
     */
    public function scopeDeLaRepetition($query, int $repetitionId)
    {
        return $query->where('repetition_id', $repetitionId);
    }

    /**
     * Obtenir la distance depuis un point donné (en km)
     */
    public function calculerDistance(float $latitude, float $longitude): ?float
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // Rayon de la Terre en km

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($latitude);
        $lon2 = deg2rad($longitude);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Vérifier si la présence est dans un rayon acceptable (ex: 100m)
     */
    public function estDansRayonAcceptable(float $latitude, float $longitude, float $rayonMetres = 100): bool
    {
        $distance = $this->calculerDistance($latitude, $longitude);
        
        if ($distance === null) {
            return false;
        }

        return $distance <= ($rayonMetres / 1000); // Convertir en km
    }
}
