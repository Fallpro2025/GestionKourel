<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations.
     */
    public function up(): void
    {
        Schema::create('membre_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->boolean('est_principal')->default(false); // Rôle principal du membre
            $table->date('date_attribution')->default(now()); // Date d'attribution du rôle
            $table->text('notes')->nullable(); // Notes sur l'attribution du rôle
            $table->timestamps();

            // Index pour optimiser les performances
            $table->index(['membre_id', 'role_id']);
            $table->index('est_principal');
            $table->index('date_attribution');
            
            // Contrainte unique pour éviter les doublons
            $table->unique(['membre_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membre_role');
    }
};
