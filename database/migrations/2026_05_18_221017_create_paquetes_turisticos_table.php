<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paquetes_turisticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nombre', 200);
            $table->text('descripcion');
            $table->text('itinerario')->nullable();
            $table->json('ruta')->nullable();
            $table->json('incluye')->nullable();
            $table->json('no_incluye')->nullable();
            $table->integer('duracion_dias')->default(1);
            $table->integer('duracion_horas')->nullable();
            $table->integer('cupo_maximo')->default(10);
            $table->integer('cupo_minimo')->default(1);
            $table->integer('cupo_disponible')->default(10);
            $table->decimal('precio_adulto', 10, 2);
            $table->decimal('precio_nino', 10, 2)->nullable();
            $table->string('punto_salida', 300)->nullable();
            $table->time('hora_salida')->nullable();
            $table->json('fechas_disponibles')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('imagen', 500)->nullable();
            $table->string('dificultad', 50)->nullable();
            $table->json('que_llevar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paquetes_turisticos');
    }
};
