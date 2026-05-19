<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('tipo_empresa', 100)->nullable()->after('direccion');
            $table->json('servicios')->nullable()->after('tipo_empresa');
            $table->text('descripcion')->nullable()->after('servicios');
            $table->string('logo', 500)->nullable()->after('descripcion');
            $table->string('nit', 20)->nullable()->after('logo');
            $table->string('sitio_web', 300)->nullable()->after('nit');
            $table->string('instagram', 200)->nullable()->after('sitio_web');
            $table->string('facebook', 200)->nullable()->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_empresa', 'servicios', 'descripcion',
                'logo', 'nit', 'sitio_web', 'instagram', 'facebook',
            ]);
        });
    }
};
