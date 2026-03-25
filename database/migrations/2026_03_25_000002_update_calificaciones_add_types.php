<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ampliar el enum tipo para incluir gastronomia y empresa
        // En SQLite/MySQL se maneja diferente; usamos string para flexibilidad
        Schema::table('calificaciones', function (Blueprint $table) {
            $table->string('tipo', 30)->change();
            $table->text('comentario')->nullable()->after('calificacion');
        });
    }

    public function down(): void
    {
        Schema::table('calificaciones', function (Blueprint $table) {
            $table->dropColumn('comentario');
        });
    }
};
