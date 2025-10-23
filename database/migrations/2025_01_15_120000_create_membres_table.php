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
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('email')->unique();
            $table->string('telephone', 20)->unique()->nullable();
            $table->date('date_naissance')->nullable();
            $table->date('date_adhesion')->default(now());
            $table->enum('statut', ['actif', 'inactif', 'suspendu', 'ancien'])->default('actif');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->json('preferences_notifications')->nullable();
            $table->text('adresse')->nullable();
            $table->string('profession', 100)->nullable();
            $table->string('niveau_etude', 50)->nullable();
            $table->json('competences')->nullable(); // ['déclamation', 'chorale', 'animation']
            $table->json('disponibilites')->nullable(); // Jours et heures disponibles
            $table->timestamps();

            // Index pour optimiser les performances
            $table->index(['nom', 'prenom']);
            $table->index('email');
            $table->index('statut');
            $table->index('date_adhesion');
            // Note: fullText non supporté par SQLite, remplacé par un index composite
            $table->index(['nom', 'prenom', 'email', 'profession']);
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};

