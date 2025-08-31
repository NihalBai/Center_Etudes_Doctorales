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
        Schema::create('scolarites', function (Blueprint $table) {
            $table->id();
            $table->string('niveau');
            $table->string('specialite');
            $table->string('mois');
            $table->integer('annee');
            $table->string('mention');
            $table->unsignedBigInteger('id_doctorant');
            $table->foreign('id_doctorant')->references('id')->on('doctorants');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scolarites');
    }
};
