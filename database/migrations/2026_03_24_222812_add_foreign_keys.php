<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // empresas → users
        Schema::table('empresas', function (Blueprint $table) {
            $table->foreign('usuario_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });

        // reservas → users y hoteles
        Schema::table('reservas', function (Blueprint $table) {
            $table->foreign('usuario_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
            $table->foreign('hotel_id')
                  ->references('id')->on('hoteles')
                  ->cascadeOnDelete();
        });

        // comentarios → users y lugares
        Schema::table('comentarios', function (Blueprint $table) {
            $table->foreign('usuario_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
            $table->foreign('lugar_id')
                  ->references('id')->on('lugares')
                  ->cascadeOnDelete();
        });

        // calificaciones → users
        Schema::table('calificaciones', function (Blueprint $table) {
            $table->foreign('usuario_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });

        // favoritos → users
        Schema::table('favoritos', function (Blueprint $table) {
            $table->foreign('usuario_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });

        // notificaciones_admin → empresas
        Schema::table('notificaciones_admin', function (Blueprint $table) {
            $table->foreign('empresa_id')
                  ->references('id')->on('empresas')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notificaciones_admin', fn($t) => $t->dropForeign(['empresa_id']));
        Schema::table('favoritos',            fn($t) => $t->dropForeign(['usuario_id']));
        Schema::table('calificaciones',       fn($t) => $t->dropForeign(['usuario_id']));
        Schema::table('comentarios',          fn($t) => $t->dropForeign(['usuario_id', 'lugar_id']));
        Schema::table('reservas',             fn($t) => $t->dropForeign(['usuario_id', 'hotel_id']));
        Schema::table('empresas',             fn($t) => $t->dropForeign(['usuario_id']));
    }
};