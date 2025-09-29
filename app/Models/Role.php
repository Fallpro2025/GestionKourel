<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'permissions',
        'niveau_priorite',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Relation avec les membres ayant ce rôle (pour compatibilité)
     */
    public function membresDirects(): HasMany
    {
        return $this->hasMany(Membre::class);
    }

    /**
     * Relation many-to-many avec les membres
     */
    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(Membre::class, 'membre_role')
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
     * Compter le nombre de membres ayant ce rôle
     */
    public function compterMembres(): int
    {
        return $this->membres()->count();
    }

    /**
     * Vérifier si le rôle a une permission spécifique
     */
    public function aPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Ajouter une permission au rôle
     */
    public function ajouterPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    /**
     * Retirer une permission du rôle
     */
    public function retirerPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_filter($permissions, fn($p) => $p !== $permission);
        $this->update(['permissions' => array_values($permissions)]);
    }
}

