<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerte extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'membre_id',
        'activite_id',
        'evenement_id',
        'message',
        'niveau_urgence',
        'statut',
        'canal_notification',
        'sent_at',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'canal_notification' => 'array',
        'sent_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Relation avec le membre concerné
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Relation avec l'activité concernée
     */
    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
    }

    /**
     * Relation avec l'événement concerné
     */
    public function evenement(): BelongsTo
    {
        return $this->belongsTo(Evenement::class);
    }

    /**
     * Relation avec le membre qui a résolu l'alerte
     */
    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(Membre::class, 'resolved_by');
    }

    /**
     * Accessor pour obtenir le type en français
     */
    public function getTypeFrancaisAttribute(): string
    {
        return match($this->type) {
            'absence_repetitive' => 'Absence répétitive',
            'absence_non_justifiee' => 'Absence non justifiée',
            'retard_excessif' => 'Retard excessif',
            'cotisation_retard' => 'Cotisation en retard',
            'evenement_majeur' => 'Événement majeur',
            default => 'Inconnu'
        };
    }

    /**
     * Accessor pour obtenir le niveau d'urgence en français
     */
    public function getNiveauUrgenceFrancaisAttribute(): string
    {
        return match($this->niveau_urgence) {
            'info' => 'Information',
            'warning' => 'Attention',
            'error' => 'Erreur',
            'critical' => 'Critique',
            default => 'Inconnu'
        };
    }

    /**
     * Accessor pour obtenir le statut en français
     */
    public function getStatutFrancaisAttribute(): string
    {
        return match($this->statut) {
            'nouveau' => 'Nouveau',
            'envoye' => 'Envoyé',
            'lu' => 'Lu',
            'resolu' => 'Résolu',
            default => 'Inconnu'
        };
    }

    /**
     * Accessor pour obtenir la couleur du niveau d'urgence
     */
    public function getCouleurUrgenceAttribute(): string
    {
        return match($this->niveau_urgence) {
            'info' => 'blue',
            'warning' => 'yellow',
            'error' => 'red',
            'critical' => 'purple',
            default => 'gray'
        };
    }

    /**
     * Accessor pour vérifier si l'alerte est résolue
     */
    public function getEstResolueAttribute(): bool
    {
        return $this->statut === 'resolu';
    }

    /**
     * Accessor pour vérifier si l'alerte est envoyée
     */
    public function getEstEnvoyeeAttribute(): bool
    {
        return $this->statut === 'envoye' || $this->statut === 'lu' || $this->statut === 'resolu';
    }

    /**
     * Scope pour les alertes non résolues
     */
    public function scopeNonResolues($query)
    {
        return $query->where('statut', '!=', 'resolu');
    }

    /**
     * Scope pour les alertes critiques
     */
    public function scopeCritiques($query)
    {
        return $query->where('niveau_urgence', 'critical');
    }

    /**
     * Scope pour les alertes d'un membre spécifique
     */
    public function scopeDuMembre($query, int $membreId)
    {
        return $query->where('membre_id', $membreId);
    }

    /**
     * Scope pour les alertes d'une activité spécifique
     */
    public function scopeDeLActivite($query, int $activiteId)
    {
        return $query->where('activite_id', $activiteId);
    }

    /**
     * Scope pour les alertes d'un événement spécifique
     */
    public function scopeDeLEvenement($query, int $evenementId)
    {
        return $query->where('evenement_id', $evenementId);
    }

    /**
     * Scope pour les alertes récentes (dernières 24h)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Marquer l'alerte comme envoyée
     */
    public function marquerCommeEnvoyee(): void
    {
        $this->update([
            'statut' => 'envoye',
            'sent_at' => now(),
        ]);
    }

    /**
     * Marquer l'alerte comme lue
     */
    public function marquerCommeLue(): void
    {
        if ($this->statut === 'envoye') {
            $this->update(['statut' => 'lu']);
        }
    }

    /**
     * Résoudre l'alerte
     */
    public function resoudre(int $resolvedBy): void
    {
        $this->update([
            'statut' => 'resolu',
            'resolved_at' => now(),
            'resolved_by' => $resolvedBy,
        ]);
    }

    /**
     * Obtenir le titre de l'alerte
     */
    public function getTitre(): string
    {
        $membre = $this->membre ? $this->membre->nom_complet : 'Membre inconnu';
        
        return match($this->type) {
            'absence_repetitive' => "Absences répétitives - {$membre}",
            'absence_non_justifiee' => "Absence non justifiée - {$membre}",
            'retard_excessif' => "Retards excessifs - {$membre}",
            'cotisation_retard' => "Cotisation en retard - {$membre}",
            'evenement_majeur' => "Événement majeur - " . ($this->evenement?->nom ?? 'Événement'),
            default => "Alerte - {$membre}"
        };
    }

    /**
     * Obtenir le message formaté
     */
    public function getMessageFormate(): string
    {
        $membre = $this->membre ? $this->membre->nom_complet : 'Membre inconnu';
        $activite = $this->activite ? $this->activite->nom : null;
        $evenement = $this->evenement ? $this->evenement->nom : null;
        
        $contexte = $activite ? " lors de l'activité '{$activite}'" : '';
        $contexte = $evenement ? " pour l'événement '{$evenement}'" : $contexte;
        
        return "{$membre}{$contexte}: {$this->message}";
    }
}

