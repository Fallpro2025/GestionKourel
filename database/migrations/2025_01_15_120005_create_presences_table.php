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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membre_id');
            $table->unsignedBigInteger('activite_id');
            $table->enum('statut', ['present', 'absent_justifie', 'absent_non_justifie', 'retard']);
            $table->timestamp('heure_arrivee')->nullable();
            $table->tinyInteger('minutes_retard')->unsigned()->default(0);
            $table->text('justification')->nullable();
            $table->decimal('latitude', 10, 8)->nullable(); // Géolocalisation
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();

            $table->unique(['membre_id', 'activite_id']);
            $table->index('statut');
            $table->index('membre_id');
            $table->index('activite_id');
            $table->index('heure_arrivee');

            $table->foreign('membre_id')->references('id')->on('membres')->onDelete('cascade');
            $table->foreign('activite_id')->references('id')->on('activites')->onDelete('cascade');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};

