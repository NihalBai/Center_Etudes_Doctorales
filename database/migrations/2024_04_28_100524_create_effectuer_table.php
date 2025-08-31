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

        Schema::create('effectuer', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_soutenance')->constrained('soutenances');
            $table->foreignId('id_doctorant')->constrained('doctorants');
            
            $table->bigInteger('numero_ordre');
            $table->timestamps();
        });
        

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('effectuer');
    }
};
