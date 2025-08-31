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
        Schema::create('evaluer', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_membre')->constrained('membres');
        $table->foreignId('id_soutenance')->constrained('soutenances');
        $table->enum('qualite', ['Président', 'Président/rapporteur', 'Directeur de thèse', 'Examinateur', 'Rapporteur', 'Co_directeur']);
        $table->timestamps();
        }
        );

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluer');
    }

};
