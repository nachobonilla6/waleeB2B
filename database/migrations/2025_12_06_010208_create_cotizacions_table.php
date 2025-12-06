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
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cotizacion')->unique();
            $table->date('fecha');
            $table->string('idioma')->default('es');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('tipo_servicio');
            $table->string('plan');
            $table->decimal('monto', 10, 2);
            $table->string('vigencia');
            $table->string('correo');
            $table->text('descripcion')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, enviada, aceptada, rechazada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacions');
    }
};
