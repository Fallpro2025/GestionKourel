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
        Schema::create('assignations_cotisation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membre_id');
            $table->unsignedBigInteger('projet_id');
            $table->decimal('montant_assigné', 10, 2);
            $table->decimal('montant_payé', 10, 2)->default(0.00);
            $table->enum('statut_paiement', ['non_paye', 'partiel', 'paye', 'rembourse'])->default('non_paye');
            $table->date('date_echeance')->nullable();
            $table->timestamp('date_dernier_paiement')->nullable();
            $table->json('historique_paiements')->nullable(); // [{'date': '2025-01-15', 'montant': 5000, 'methode': 'espèces'}]
            $table->timestamps();

            $table->unique(['membre_id', 'projet_id']);
            $table->index('statut_paiement');
            $table->index('date_echeance');
            $table->index('membre_id');
            $table->index('projet_id');

            $table->foreign('membre_id')->references('id')->on('membres')->onDelete('cascade');
            $table->foreign('projet_id')->references('id')->on('projets_cotisation')->onDelete('cascade');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignations_cotisation');
    }
};

