<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembreRole extends Model
{
    use HasFactory;

    protected $table = 'membre_role';

    protected $fillable = [
        'membre_id',
        'role_id',
        'est_principal',
        'date_attribution',
        'notes',
    ];

    protected $casts = [
        'est_principal' => 'boolean',
        'date_attribution' => 'date',
    ];

    /**
     * Relation avec le membre
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Relation avec le rôle
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
