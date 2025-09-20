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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();
            $table->text('description')->nullable();
            $table->json('permissions'); // ['gestion_membres', 'gestion_cotisations', 'gestion_evenements']
            $table->tinyInteger('niveau_priorite')->unsigned()->default(1);
            $table->timestamps();

            $table->index('nom');
            $table->index('niveau_priorite');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

