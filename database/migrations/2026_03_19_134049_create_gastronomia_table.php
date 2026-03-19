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
       Schema::create('gastronomia', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 150);
    $table->text('descripcion')->nullable();
    $table->string('tipo', 100)->nullable();
    $table->decimal('precio_promedio', 10, 2)->nullable();
    $table->string('restaurante', 150)->nullable();
    $table->string('direccion', 200)->nullable();
    $table->string('telefono', 20)->nullable();
    $table->string('imagen')->nullable();
    $table->text('ingredientes')->nullable();
    $table->timestamps();

    $table->index('tipo');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastronomia');
    }
};
