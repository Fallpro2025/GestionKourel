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
        Schema::create('projets_cotisation', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 200);
            $table->text('description')->nullable();
            $table->decimal('montant_total', 15, 2);
            $table->decimal('montant_collecte', 15, 2)->default(0.00);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['planifie', 'actif', 'suspendu', 'termine', 'annule'])->default('planifie');
            $table->enum('type_cotisation', ['obligatoire', 'volontaire', 'evenement'])->default('obligatoire');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('statut');
            $table->index(['date_debut', 'date_fin']);
            $table->index('type_cotisation');
            $table->index('created_by');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets_cotisation');
    }
};

