<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->decimal('descuento_antes_impuestos', 10, 2)->default(0)->after('subtotal');
            $table->decimal('descuento_despues_impuestos', 10, 2)->default(0)->after('descuento_antes_impuestos');
            $table->string('numero_orden')->nullable()->after('numero_factura');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn(['descuento_antes_impuestos', 'descuento_despues_impuestos', 'numero_orden']);
        });
    }
};
