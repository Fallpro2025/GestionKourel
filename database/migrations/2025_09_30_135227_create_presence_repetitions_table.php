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
        Schema::create('presence_repetitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membre_id');
            $table->unsignedBigInteger('repetition_id');
            $table->enum('statut', ['present', 'absent_justifie', 'absent_non_justifie', 'retard']);
            $table->timestamp('heure_arrivee')->nullable();
            $table->tinyInteger('minutes_retard')->unsigned()->default(0);
            $table->text('justification')->nullable();
            $table->decimal('latitude', 10, 8)->nullable(); // Géolocalisation
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('prestation_effectuee')->default(false);
            $table->text('notes_prestation')->nullable();
            $table->timestamps();

            $table->unique(['membre_id', 'repetition_id']);
            $table->index('statut');
            $table->index('membre_id');
            $table->index('repetition_id');
            $table->index('heure_arrivee');
            $table->index('prestation_effectuee');

            $table->foreign('membre_id')->references('id')->on('membres')->onDelete('cascade');
            $table->foreign('repetition_id')->references('id')->on('activity_repetitions')->onDelete('cascade');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence_repetitions');
    }
};
