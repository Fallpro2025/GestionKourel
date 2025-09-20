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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['absence_repetitive', 'absence_non_justifiee', 'retard_excessif', 'cotisation_retard', 'evenement_majeur']);
            $table->unsignedBigInteger('membre_id')->nullable();
            $table->unsignedBigInteger('activite_id')->nullable();
            $table->unsignedBigInteger('evenement_id')->nullable();
            $table->text('message');
            $table->enum('niveau_urgence', ['info', 'warning', 'error', 'critical'])->default('warning');
            $table->enum('statut', ['nouveau', 'envoye', 'lu', 'resolu'])->default('nouveau');
            $table->json('canal_notification')->nullable(); // ['email', 'sms', 'whatsapp', 'push']
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('niveau_urgence');
            $table->index('statut');
            $table->index('membre_id');
            $table->index('created_at');

            $table->foreign('membre_id')->references('id')->on('membres')->onDelete('cascade');
            $table->foreign('activite_id')->references('id')->on('activites')->onDelete('cascade');
            $table->foreign('evenement_id')->references('id')->on('evenements')->onDelete('cascade');
            $table->foreign('resolved_by')->references('id')->on('membres')->onDelete('set null');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};

