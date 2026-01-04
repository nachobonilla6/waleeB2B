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
        // Agregar cliente_id a rproductos
        if (!Schema::hasColumn('rproductos', 'cliente_id')) {
            Schema::table('rproductos', function (Blueprint $table) {
                $table->foreignId('cliente_id')->nullable()->after('id')->constrained('clientes_en_proceso')->onDelete('cascade');
            });
        }
        
        // Agregar webhook_productos a clientes_en_proceso
        if (!Schema::hasColumn('clientes_en_proceso', 'webhook_productos')) {
            Schema::table('clientes_en_proceso', function (Blueprint $table) {
                $table->string('webhook_productos')->nullable()->after('webhook_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('rproductos', 'cliente_id')) {
            Schema::table('rproductos', function (Blueprint $table) {
                $table->dropForeign(['cliente_id']);
                $table->dropColumn('cliente_id');
            });
        }
        
        if (Schema::hasColumn('clientes_en_proceso', 'webhook_productos')) {
            Schema::table('clientes_en_proceso', function (Blueprint $table) {
                $table->dropColumn('webhook_productos');
            });
        }
    }
};
