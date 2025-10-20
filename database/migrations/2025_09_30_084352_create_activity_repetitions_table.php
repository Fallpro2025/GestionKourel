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
        Schema::create('activity_repetitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activite_id');
            $table->date('date_repetition');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('lieu', 200)->nullable();
            $table->enum('statut', ['planifie', 'confirme', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->timestamps();

            $table->index('activite_id');
            $table->index('date_repetition');
            $table->index('statut');
            $table->index('responsable_id');

            $table->foreign('activite_id')->references('id')->on('activites')->onDelete('cascade');
            $table->foreign('responsable_id')->references('id')->on('membres')->onDelete('set null');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_repetitions');
    }
};
