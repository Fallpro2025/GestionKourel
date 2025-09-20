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
        Schema::create('activites', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['repetition', 'prestation', 'goudi_aldiouma', 'formation', 'reunion']);
            $table->string('nom', 200);
            $table->text('description')->nullable();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('lieu', 200)->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->enum('statut', ['planifie', 'confirme', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->json('configuration')->nullable(); // {'repetition_hebdomadaire': true, 'jours': ['lundi', 'mercredi']}
            $table->timestamps();

            $table->index('type');
            $table->index(['date_debut', 'date_fin']);
            $table->index('statut');
            $table->index('responsable_id');

            $table->foreign('responsable_id')->references('id')->on('membres')->onDelete('set null');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activites');
    }
};

