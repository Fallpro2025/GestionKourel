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
     * Relation avec le rôle du membre
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
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

