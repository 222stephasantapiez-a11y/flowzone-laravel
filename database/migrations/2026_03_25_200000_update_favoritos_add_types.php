<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ampliar tipo para que soporte los mismos valores que calificaciones
        Schema::table('favoritos', function (Blueprint $table) {
            $table->string('tipo', 30)->change();
        });
    }

    public function down(): void
    {
        Schema::table('favoritos', function (Blueprint $table) {
            $table->enum('tipo', ['lugar', 'hotel'])->change();
        });
    }
};
