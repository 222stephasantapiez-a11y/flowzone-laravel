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
       Schema::create('eventos', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 150);
    $table->text('descripcion')->nullable();
    $table->date('fecha');
    $table->time('hora')->nullable();
    $table->string('ubicacion', 200)->nullable();
    $table->string('categoria', 100)->nullable();
    $table->string('imagen')->nullable();
    $table->decimal('precio', 10, 2)->default(0);
    $table->string('organizador', 150)->nullable();
    $table->string('contacto', 150)->nullable();
    $table->timestamps();

    $table->index('fecha');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
