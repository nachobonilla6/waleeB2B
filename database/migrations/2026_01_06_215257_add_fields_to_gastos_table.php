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
        Schema::table('gastos', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('nombre');
            $table->enum('tipo', ['mensual', 'anual'])->default('mensual')->after('descripcion');
            $table->date('proxima_fecha_pago')->nullable()->after('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'tipo', 'proxima_fecha_pago']);
        });
    }
};
