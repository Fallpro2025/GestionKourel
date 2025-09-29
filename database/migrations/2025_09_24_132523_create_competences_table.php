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
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('categorie')->nullable(); // Technique, Soft skills, etc.
            $table->string('niveau')->default('debutant'); // debutant, intermediaire, avance, expert
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['categorie', 'active']);
            $table->index('nom');
        });
        
        // Table pivot pour les compétences des membres
        Schema::create('membre_competence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->foreignId('competence_id')->constrained()->onDelete('cascade');
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'expert'])->default('debutant');
            $table->integer('annees_experience')->default(0);
            $table->text('notes')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->boolean('certifiee')->default(false);
            $table->timestamps();
            
            $table->unique(['membre_id', 'competence_id']);
            $table->index(['competence_id', 'niveau']);
            $table->index('certifiee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membre_competence');
        Schema::dropIfExists('competences');
    }
};