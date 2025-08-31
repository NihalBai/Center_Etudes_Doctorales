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
        
        Schema::create('soutenances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('heure');
            $table->string('etat');
            $table->unsignedBigInteger('id_localisation');
            $table->foreign('id_localisation')->references('id')->on('localisations');
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soutenances');
    }
};
