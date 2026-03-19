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
       Schema::create('notificaciones_admin', function (Blueprint $table) {
    $table->id();
    $table->foreignId('empresa_id'); //->constrained('empresas')->cascadeOnDelete();
    $table->text('mensaje');
    $table->boolean('leido')->default(0);
    $table->timestamps();

    $table->index('leido');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones_admin');
    }
};
