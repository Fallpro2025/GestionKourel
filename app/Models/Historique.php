<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    protected $fillable = [
        'modele_type',
        'modele_id',
        'action',
        'description',
        'donnees_avant',
        'donnees_apres',
        'utilisateur',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array'
    ];

    /**
     * Relation polymorphique avec les modèles
     */
    public function modele()
    {
        return $this->morphTo('modele', 'modele_type', 'modele_id');
    }

    /**
     * Scope pour les actions spécifiques
     */
    public function scopeParAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour un modèle spécifique
     */
    public function scopePourModele($query, $modeleType, $modeleId)
    {
        return $query->where('modele_type', $modeleType)
                    ->where('modele_id', $modeleId);
    }

    /**
     * Scope pour un utilisateur spécifique
     */
    public function scopeParUtilisateur($query, $utilisateur)
    {
        return $query->where('utilisateur', $utilisateur);
    }

    /**
     * Obtenir l'icône de l'action
     */
    public function getIconeActionAttribute()
    {
        $icones = [
            'created' => 'fas fa-plus-circle',
            'updated' => 'fas fa-edit',
            'deleted' => 'fas fa-trash',
            'role_added' => 'fas fa-user-tag',
            'role_removed' => 'fas fa-user-times',
            'photo_uploaded' => 'fas fa-camera',
            'photo_deleted' => 'fas fa-trash',
            'status_changed' => 'fas fa-toggle-on',
            'exported' => 'fas fa-download',
            'imported' => 'fas fa-upload'
        ];

        return $icones[$this->action] ?? 'fas fa-history';
    }

    /**
     * Obtenir la couleur de l'action
     */
    public function getCouleurActionAttribute()
    {
        $couleurs = [
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'role_added' => 'purple',
            'role_removed' => 'orange',
            'photo_uploaded' => 'indigo',
            'photo_deleted' => 'red',
            'status_changed' => 'yellow',
            'exported' => 'green',
            'imported' => 'blue'
        ];

        return $couleurs[$this->action] ?? 'gray';
    }

    /**
     * Méthode statique pour enregistrer une action
     */
    public static function enregistrer($modele, $action, $description, $donneesAvant = null, $donneesApres = null, $utilisateur = null)
    {
        return self::create([
            'modele_type' => get_class($modele),
            'modele_id' => $modele->id,
            'action' => $action,
            'description' => $description,
            'donnees_avant' => $donneesAvant,
            'donnees_apres' => $donneesApres,
            'utilisateur' => $utilisateur ?? 'Administrateur',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
