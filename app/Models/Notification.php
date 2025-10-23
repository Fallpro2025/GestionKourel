<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'titre',
        'message',
        'type',
        'canal',
        'envoyee',
        'envoyee_le',
        'metadata'
    ];

    protected $casts = [
        'envoyee' => 'boolean',
        'envoyee_le' => 'datetime',
        'metadata' => 'array'
    ];

    /**
     * Relation avec le membre
     */
    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Scope pour les notifications envoyées
     */
    public function scopeEnvoyees($query)
    {
        return $query->where('envoyee', true);
    }

    /**
     * Scope pour les notifications non envoyées
     */
    public function scopeNonEnvoyees($query)
    {
        return $query->where('envoyee', false);
    }

    /**
     * Scope par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Marquer comme envoyée
     */
    public function marquerCommeEnvoyee()
    {
        $this->update([
            'envoyee' => true,
            'envoyee_le' => now()
        ]);
    }

    /**
     * Obtenir le canal formaté
     */
    public function getCanalFormateAttribute()
    {
        $labels = [
            'email' => 'Email',
            'sms' => 'SMS',
            'app' => 'Application',
            'whatsapp' => 'WhatsApp',
            'push' => 'Notification Push'
        ];

        return $labels[$this->canal] ?? $this->canal;
    }

    /**
     * Obtenir la couleur du type
     */
    public function getCouleurTypeAttribute()
    {
        $couleurs = [
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red'
        ];

        return $couleurs[$this->type] ?? 'gray';
    }

    /**
     * Obtenir l'icône du type
     */
    public function getIconeTypeAttribute()
    {
        $icones = [
            'info' => 'fas fa-info-circle',
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle'
        ];

        return $icones[$this->type] ?? 'fas fa-bell';
    }
}
