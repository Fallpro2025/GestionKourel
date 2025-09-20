<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'activite_id',
        'statut',
        'heure_arrivee',
        'minutes_retard',
        'justification',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'heure_arrivee' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relation avec le membre
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Relation avec l'activité
     */
    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
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
     * Scope pour les présences avec géolocalisation
     */
    public function scopeAvecGeolocalisation($query)
    {
        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude');
    }

    /**
     * Scope pour les présences d'un membre spécifique
     */
    public function scopeDuMembre($query, int $membreId)
    {
        return $query->where('membre_id', $membreId);
    }

    /**
     * Scope pour les présences d'une activité spécifique
     */
    public function scopeDeLActivite($query, int $activiteId)
    {
        return $query->where('activite_id', $activiteId);
    }

    /**
     * Scope pour les présences d'une période
     */
    public function scopeDeLaPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereHas('activite', function ($q) use ($dateDebut, $dateFin) {
            $q->whereBetween('date_debut', [$dateDebut, $dateFin]);
        });
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

