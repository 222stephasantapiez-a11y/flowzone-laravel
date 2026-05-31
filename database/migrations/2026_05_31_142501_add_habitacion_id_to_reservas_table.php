<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->foreignId('habitacion_id')->nullable()->after('hotel_id')
                  ->constrained('habitaciones')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Habitacion::class);
            $table->dropColumn('habitacion_id');
        });
    }
};