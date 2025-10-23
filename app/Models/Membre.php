<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_naissance',
        'date_adhesion',
        'statut',
        'role_id',
        'photo_url',
        'preferences_notifications',
        'adresse',
        'profession',
        'niveau_etude',
        'competences',
        'disponibilites',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_adhesion' => 'date',
        'preferences_notifications' => 'array',
        'competences' => 'array',
        'disponibilites' => 'array',
    ];

    /**
     * Relation avec le rôle principal du membre (pour compatibilité)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation many-to-many avec les rôles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'membre_role')
                    ->withPivot(['est_principal', 'date_attribution', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Relation avec les attributions de rôles
     */
    public function membreRoles(): HasMany
    {
        return $this->hasMany(MembreRole::class);
    }

    /**
     * Obtenir le rôle principal du membre
     */
    public function rolePrincipal(): ?Role
    {
        return $this->roles()->wherePivot('est_principal', true)->first();
    }

    /**
     * Vérifier si le membre a un rôle spécifique
     */
    public function aRole(string $nomRole): bool
    {
        return $this->roles()->where('nom', $nomRole)->exists();
    }

    /**
     * Vérifier si le membre est administrateur
     */
    public function estAdministrateur(): bool
    {
        return $this->aRole('Administrateur');
    }

    /**
     * Ajouter un rôle au membre
     */
    public function ajouterRole(int $roleId, bool $estPrincipal = false, string $notes = null): void
    {
        $this->roles()->attach($roleId, [
            'est_principal' => $estPrincipal,
            'date_attribution' => now(),
            'notes' => $notes
        ]);
    }

    /**
     * Retirer un rôle du membre
     */
    public function retirerRole(int $roleId): void
    {
        $this->roles()->detach($roleId);
    }

    /**
     * Définir le rôle principal
     */
    public function definirRolePrincipal(int $roleId): void
    {
        // Retirer le statut principal de tous les autres rôles
        $this->roles()->updateExistingPivot($this->roles()->pluck('id'), ['est_principal' => false]);
        
        // Définir le nouveau rôle principal
        $this->roles()->updateExistingPivot($roleId, ['est_principal' => true]);
    }

    /**
     * Relation avec les assignations de cotisation
     */
    public function assignationsCotisation(): HasMany
    {
        return $this->hasMany(AssignationCotisation::class);
    }

    /**
     * Relation avec les présences
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Relation avec les activités où le membre est responsable
     */
    public function activitesResponsable(): HasMany
    {
        return $this->hasMany(Activite::class, 'responsable_id');
    }

    /**
     * Relation avec les événements créés par le membre
     */
    public function evenementsCrees(): HasMany
    {
        return $this->hasMany(Evenement::class, 'created_by');
    }

    /**
     * Relation avec les alertes du membre
     */
    public function alertes(): HasMany
    {
        return $this->hasMany(Alerte::class);
    }

    /**
     * Accessor pour le nom complet
     */
    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Accessor pour l'âge
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    /**
     * Scope pour les membres actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour rechercher par nom ou prénom
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'like', "%{$terme}%")
              ->orWhere('prenom', 'like', "%{$terme}%")
              ->orWhere('email', 'like', "%{$terme}%");
        });
    }

    /**
     * Calculer le taux de présence du membre
     */
    public function calculerTauxPresence(): float
    {
        $totalPresences = $this->presences()->count();
        
        if ($totalPresences === 0) {
            return 0.0;
        }

        $presencesEffectives = $this->presences()
            ->where('statut', 'present')
            ->count();

        return round(($presencesEffectives / $totalPresences) * 100, 2);
    }

    /**
     * Obtenir le montant total des cotisations en retard
     */
    public function getMontantCotisationsRetard(): float
    {
        return $this->assignationsCotisation()
            ->where('statut_paiement', '!=', 'paye')
            ->where('date_echeance', '<', now())
            ->sum('montant_assigné');
    }

    /**
     * Générer un matricule unique
     */
    public static function genererMatricule(): string
    {
        $prefixe = 'MFTB-';
        $dernierMembre = self::orderBy('id', 'desc')->first();
        
        if ($dernierMembre && $dernierMembre->matricule) {
            // Extraire le numéro du dernier matricule
            $dernierNumero = (int) str_replace($prefixe, '', $dernierMembre->matricule);
            $nouveauNumero = $dernierNumero + 1;
        } else {
            // Premier membre
            $nouveauNumero = 1;
        }
        
        // Formater avec des zéros à gauche (ex: MFTB-0001)
        return $prefixe . str_pad($nouveauNumero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method pour générer automatiquement le matricule
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($membre) {
            if (empty($membre->matricule)) {
                $membre->matricule = self::genererMatricule();
            }
        });
    }
}

