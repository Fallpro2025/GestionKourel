<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Relation avec les membres ayant ce rôle
     */
    public function membres(): HasMany
    {
        return $this->hasMany(Membre::class);
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

