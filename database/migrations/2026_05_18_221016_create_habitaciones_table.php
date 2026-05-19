<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hoteles')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->enum('tipo', ['sencilla','doble','triple','suite','familiar']);
            $table->integer('num_camas')->default(1);
            $table->enum('tipo_cama', ['individual','doble','queen','king','mixta'])->default('doble');
            $table->integer('capacidad_personas')->default(2);
            $table->decimal('precio_noche', 10, 2);
            $table->boolean('disponible')->default(true);
            $table->text('descripcion')->nullable();
            $table->json('amenidades')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
