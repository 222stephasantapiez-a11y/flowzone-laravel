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
       Schema::create('calificaciones', function (Blueprint $table) {
    $table->id();
    $table->foreignId('usuario_id'); //->constrained('usuarios')->cascadeOnDelete();
    $table->enum('tipo', ['lugar','hotel']);
    $table->unsignedBigInteger('item_id');
    $table->integer('calificacion');
    $table->timestamps();

    $table->unique(['usuario_id', 'tipo', 'item_id']);
    $table->index(['tipo', 'item_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
