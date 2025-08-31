<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctorant_id');
            $table->string('type');
            $table->string('path');
            $table->timestamps();
    
            $table->foreign('doctorant_id')->references('id')->on('doctorants')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('files');
    }
    
};
