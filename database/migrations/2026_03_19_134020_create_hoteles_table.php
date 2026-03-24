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
        Schema::create('hoteles', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 150);
    $table->text('descripcion')->nullable();
    $table->decimal('precio', 10, 2);
    $table->string('ubicacion', 200)->nullable();
    $table->decimal('latitud', 9, 6)->nullable();
    $table->decimal('longitud', 9, 6)->nullable();
    $table->string('imagen')->nullable();
    $table->text('servicios')->nullable();
    $table->integer('capacidad')->nullable();
    $table->boolean('disponibilidad')->default(true);
    $table->string('telefono', 20)->nullable();
    $table->string('email', 150)->nullable();
    $table->timestamps();

    $table->index('disponibilidad');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoteles');
    }
};
