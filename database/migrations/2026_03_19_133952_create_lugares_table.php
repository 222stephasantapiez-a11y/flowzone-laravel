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
        Schema::create('lugares', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 150);
    $table->text('descripcion')->nullable();
    $table->string('ubicacion', 200)->nullable();
    $table->decimal('latitud', 9, 6)->nullable();
    $table->decimal('longitud', 9, 6)->nullable();
    $table->string('categoria', 100)->nullable();
    $table->string('imagen')->nullable();
    $table->decimal('precio_entrada', 10, 2)->default(0);
    $table->string('horario', 100)->nullable();
    $table->timestamps();

    $table->index('categoria');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lugares');
    }
};
