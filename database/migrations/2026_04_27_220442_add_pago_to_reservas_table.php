<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->enum('metodo_pago', ['nequi', 'bancolombia_pse', 'tarjeta'])->nullable()->after('estado');
            $table->string('referencia_pago', 20)->nullable()->after('metodo_pago');
            $table->enum('estado_pago', ['pendiente', 'pagado', 'fallido'])->default('pendiente')->after('referencia_pago');
        });
    }
 
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['metodo_pago', 'referencia_pago', 'estado_pago']);
        });
    }
};
 