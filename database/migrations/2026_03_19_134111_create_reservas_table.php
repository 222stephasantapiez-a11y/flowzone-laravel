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
       Schema::create('reservas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('usuario_id'); //->constrained('usuarios')->cascadeOnDelete();
    $table->foreignId('hotel_id');  //->constrained('hoteles')->cascadeOnDelete();
    $table->date('fecha_entrada');
    $table->date('fecha_salida');
    $table->integer('num_personas');
    $table->decimal('precio_total', 10, 2);
    $table->enum('estado', ['pendiente','confirmada','cancelada'])->default('pendiente');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
