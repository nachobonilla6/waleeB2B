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
        Schema::create('ordenes_programadas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['extraccion_clientes', 'emails_automaticos'])->comment('Tipo de orden programada');
            $table->boolean('activo')->default(true)->comment('Si la orden está activa');
            $table->decimal('recurrencia_horas', 5, 2)->nullable()->comment('Recurrencia en horas (0.5, 1, 2, etc.)');
            $table->timestamp('last_run')->nullable()->comment('Última vez que se ejecutó');
            $table->json('configuracion')->nullable()->comment('Configuración adicional en JSON');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Usuario que creó la orden');
            $table->timestamps();
            
            $table->index('tipo');
            $table->index('activo');
            $table->index('last_run');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_programadas');
    }
};
