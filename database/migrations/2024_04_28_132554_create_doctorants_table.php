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
        Schema::create('doctorants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('CINE')->unique();
            $table->enum('sex', ['male', 'female']);
            $table->string('photo_path');
            $table->string('email')->unique();
            $table->string('tele')->unique();
            $table->date('date_de_naissance');
            $table->string('lieu');
            $table->string('dossier');
            $table->string('discipline');
            $table->unsignedBigInteger('id_encadrant'); // Assuming it's unsigned
            $table->foreign('id_encadrant')->references('id')->on('membres');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctorants');
    }
};
