<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE reservas DROP CONSTRAINT reservas_metodo_pago_check');
        DB::statement("ALTER TABLE reservas ADD CONSTRAINT reservas_metodo_pago_check 
            CHECK (metodo_pago IN ('efectivo', 'tarjeta', 'transferencia', 'wompi'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE reservas DROP CONSTRAINT reservas_metodo_pago_check');
        DB::statement("ALTER TABLE reservas ADD CONSTRAINT reservas_metodo_pago_check 
            CHECK (metodo_pago IN ('efectivo', 'tarjeta', 'transferencia'))");
    }
};