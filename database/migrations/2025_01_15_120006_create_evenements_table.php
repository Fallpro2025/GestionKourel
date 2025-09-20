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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 200);
            $table->enum('type', ['magal', 'gamou', 'promokhane', 'conference', 'formation', 'autre']);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('lieu', 200)->nullable();
            $table->text('description')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->enum('statut', ['planifie', 'confirme', 'en_cours', 'termine', 'annule'])->default('planifie');
            $table->json('membres_selectionnes')->nullable(); // {'declamation': [1,2,3], 'chorale': [4,5,6], 'animation': [7,8,9]}
            $table->json('configuration')->nullable(); // {'dress_code': 'tenue traditionnelle', 'transport': 'organise'}
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index(['date_debut', 'date_fin']);
            $table->index('statut');
            $table->index('created_by');

            $table->foreign('created_by')->references('id')->on('membres')->onDelete('set null');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};

