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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); //temperatura, humedad, etc
            $table->string('unidad'); //C, %, etc
            $table->string('informacion'); //informacion adicional
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('dispositivos_id');
            $table->foreign('dispositivos_id')->references('id')->on('dispositivos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
