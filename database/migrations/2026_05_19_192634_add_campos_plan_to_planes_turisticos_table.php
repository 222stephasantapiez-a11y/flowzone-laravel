<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planes_turisticos', function (Blueprint $table) {
            $table->unsignedBigInteger('habitacion_id')->nullable()->after('lugar_id');
            $table->string('tipo_plan', 50)->nullable()->after('titulo'); // hotel, restaurante, agencia, etc.
            $table->text('descripcion')->nullable()->after('tipo_plan');
            $table->boolean('publicado')->default(false)->after('precio_final');
            $table->string('imagen', 500)->nullable()->after('publicado');
        });
    }

    public function down(): void
    {
        Schema::table('planes_turisticos', function (Blueprint $table) {
            $table->dropColumn(['habitacion_id','tipo_plan','descripcion','publicado','imagen']);
        });
    }
};
