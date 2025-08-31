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
        Schema::disableForeignKeyConstraints();
        
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->text('titreOriginal');
            $table->text('titreFinal')->nullable();
            $table->enum('acceptationDirecteur', ['Oui','Non']);
            $table->string('formation');
            $table->unsignedBigInteger('id_doctorant');
            $table->foreign('id_doctorant')->references('id')->on('doctorants');
            $table->string('structure_recherche')->nullable();
            $table->string('other_structure')->nullable();
            $table->date('date_premiere_inscription')->nullable();
            $table->integer('nombre_publications_article')->default(0);
            $table->integer('nombre_publications_conference')->default(0);
            $table->integer('nombre_communications')->default(0);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theses');
    }};
