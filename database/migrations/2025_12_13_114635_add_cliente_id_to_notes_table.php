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
        Schema::table('notes', function (Blueprint $table) {
            // Hacer client_id nullable para soportar ambos tipos de clientes
            $table->foreignId('client_id')->nullable()->change();
            
            // Agregar cliente_id para la tabla clientes
            $table->foreignId('cliente_id')->nullable()->after('client_id')->constrained('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');
            // No revertir el cambio de nullable en client_id para evitar problemas
        });
    }
};
