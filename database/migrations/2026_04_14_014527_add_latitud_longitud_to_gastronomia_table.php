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
        Schema::table('gastronomia', function (Blueprint $table) {
            $table->decimal('latitud', 9, 6)->nullable()->after('direccion');
            $table->decimal('longitud', 9, 6)->nullable()->after('latitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gastronomia', function (Blueprint $table) {
            $table->dropColumn(['latitud', 'longitud']);
        });
    }
};
