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
    { Schema::disableForeignKeyConstraints();

        Schema::create('resultats', function (Blueprint $table) {
            $table->id();
            $table->enum('observation', ['Valider','Rattraper']);
            $table->enum('mention', ['Passable', 'Honorable', 'Très Honorable', 'Très Honorable avec félicitations du jury']);

            $table->string('formationDoctorale');
            $table->string('specialite');
            $table->unsignedBigInteger('id_soutenance');
            $table->foreign('id_soutenance')->references('id')->on('soutenances');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultats');
    }

};
