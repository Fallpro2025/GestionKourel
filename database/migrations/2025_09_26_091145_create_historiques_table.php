<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->string('modele_type'); // 'App\Models\Membre', 'App\Models\Role', etc.
            $table->unsignedBigInteger('modele_id'); // ID du modèle concerné
            $table->string('action'); // 'created', 'updated', 'deleted', 'role_added', etc.
            $table->string('description');
            $table->json('donnees_avant')->nullable(); // Données avant modification
            $table->json('donnees_apres')->nullable(); // Données après modification
            $table->string('utilisateur')->nullable(); // Qui a fait l'action
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['modele_type', 'modele_id']);
            $table->index(['action', 'created_at']);
            $table->index('utilisateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};
