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
        Schema::table('facturas', function (Blueprint $table) {
            $table->decimal('monto_pagado', 10, 2)->default(0)->after('total');
            $table->string('concepto_pago')->nullable()->after('concepto'); // Para conceptos específicos como SEO, mantenimiento, etc.
            $table->string('serie')->nullable()->after('numero_factura'); // Para agrupar facturas por serie
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn(['monto_pagado', 'concepto_pago', 'serie']);
        });
    }
};

