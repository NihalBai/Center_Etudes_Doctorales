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
        Schema::create('valider', function (Blueprint $table) {
            $table->id();
        
            // Clé primaire étrangère id_membres
            $table->foreignId('id_membre')->constrained('membres')->primary();
            
            // Clé primaire étrangère id_these
            $table->foreignId('id_these')->constrained('theses')->primary();
            
            // Colonnes supplémentaires
            $table->enum('avis', ['accepté','refusé']);
            $table->string('lien_rapport');
            $table->foreignId('id_utilisateur')->constrained('users');
            
            // Vous pouvez également ajouter les timestamps si nécessaire
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valider');
    }
};
