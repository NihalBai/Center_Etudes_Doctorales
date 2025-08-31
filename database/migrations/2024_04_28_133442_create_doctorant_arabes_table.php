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
        Schema::create('doctorant_arabes', function (Blueprint $table) {
            $table->foreignId('id')->constrained('doctorants');
            $table->string('nom');
            $table->string('prenom');
            $table->string('discipline');
            $table->string('specialite')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctorant_arabes');
    }
};
