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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'error'])->default('info');
            $table->enum('canal', ['email', 'sms', 'app'])->default('app');
            $table->boolean('envoyee')->default(false);
            $table->timestamp('envoyee_le')->nullable();
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['membre_id', 'envoyee']);
            $table->index(['type', 'canal']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};