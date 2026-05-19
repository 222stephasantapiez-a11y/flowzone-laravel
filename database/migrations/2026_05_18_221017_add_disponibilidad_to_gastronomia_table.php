<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gastronomia', function (Blueprint $table) {
            $table->boolean('disponible_hoy')->default(true)->after('empresa_id');
            $table->time('hora_inicio')->nullable()->after('disponible_hoy');
            $table->time('hora_fin')->nullable()->after('hora_inicio');
            $table->integer('stock_diario')->nullable()->after('hora_fin');
            $table->integer('stock_actual')->nullable()->after('stock_diario');
            $table->json('dias_semana')->nullable()->after('stock_actual');
        });
    }

    public function down(): void
    {
        Schema::table('gastronomia', function (Blueprint $table) {
            $table->dropColumn(['disponible_hoy','hora_inicio','hora_fin','stock_diario','stock_actual','dias_semana']);
        });
    }
};
