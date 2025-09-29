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
        Schema::create('disponibilites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->enum('jour_semaine', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('type', ['ponctuel', 'recurrent'])->default('recurrent');
            $table->date('date_debut')->nullable(); // Pour les disponibilités ponctuelles
            $table->date('date_fin')->nullable(); // Pour les disponibilités ponctuelles
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['membre_id', 'jour_semaine']);
            $table->index(['type', 'active']);
            $table->index('date_debut');
        });
        
        // Table pour les indisponibilités temporaires
        Schema::create('indisponibilites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->string('raison');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['planifiee', 'en_cours', 'terminee', 'annulee'])->default('planifiee');
            $table->timestamps();
            
            $table->index(['membre_id', 'date_debut']);
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indisponibilites');
        Schema::dropIfExists('disponibilites');
    }
};