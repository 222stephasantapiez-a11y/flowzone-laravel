<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas_paquete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paquete_id')->constrained('paquetes_turisticos')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha_reserva');
            $table->integer('num_adultos')->default(1);
            $table->integer('num_ninos')->default(0);
            $table->decimal('precio_total', 10, 2);
            $table->enum('estado', ['pendiente','confirmada','cancelada','completada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->string('telefono_contacto', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas_paquete');
    }
};
