<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('ruta', 500);
            $table->string('titulo', 200)->nullable();
            $table->string('categoria', 100)->nullable();
            $table->smallInteger('orden')->default(0);
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'activa', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_imagenes');
    }
};
