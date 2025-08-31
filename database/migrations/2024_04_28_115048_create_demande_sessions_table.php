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
        Schema::create('demande_sessions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable(); // Remplacez 'other_column' par le nom de la colonne après laquelle vous souhaitez ajouter la nouvelle colonne
            $table->string('pv_global_signe')->nullable(); // Modifier le type si nécessaire
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_sessions');
    }
};
