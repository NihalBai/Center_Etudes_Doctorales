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
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->enum('sex', ['male', 'female']);
            $table->string('tele');
            $table->unsignedBigInteger('id_faculte')->nullable();
            $table->foreign('id_faculte')->references('id')->on('facultes');
            $table->unsignedBigInteger('id_autre')->nullable();
            $table->foreign('id_autre')->references('id')->on('autres');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
