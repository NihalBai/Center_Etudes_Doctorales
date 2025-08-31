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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->enum('formation',['MPA','RNES','MI']);
            $table->integer('num');
            $table->date('date');
            $table->enum('etat', ['Refusée','Acceptée','En attente']);
            $table->unsignedBigInteger('id_these');
            $table->unsignedBigInteger('id_session')->nullable();
            $table->foreign('id_these')->references('id')->on('theses');
            $table->foreign('id_session')->references('id')->on('demande_sessions')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
