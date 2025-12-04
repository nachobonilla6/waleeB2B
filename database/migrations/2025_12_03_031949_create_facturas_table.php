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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('numero_factura')->unique();
            $table->date('fecha_emision');
            $table->string('concepto'); // diseno_web, redes_sociales, seo, publicidad, mantenimiento, hosting
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('metodo_pago'); // transferencia, sinpe, tarjeta, efectivo, paypal
            $table->string('estado')->default('pendiente'); // pendiente, pagada, vencida, cancelada
            $table->date('fecha_vencimiento')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
