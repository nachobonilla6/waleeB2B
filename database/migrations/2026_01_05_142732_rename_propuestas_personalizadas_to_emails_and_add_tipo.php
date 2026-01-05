<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero agregar el campo tipo si la tabla existe
        if (Schema::hasTable('propuestas_personalizadas')) {
            Schema::table('propuestas_personalizadas', function (Blueprint $table) {
                if (!Schema::hasColumn('propuestas_personalizadas', 'tipo')) {
                    $table->string('tipo', 50)->default('propuesta_personalizada')->after('id');
                }
            });
            
            // Actualizar los registros existentes para que tengan el tipo "propuesta_personalizada"
            DB::table('propuestas_personalizadas')->update(['tipo' => 'propuesta_personalizada']);
        }
        
        // Renombrar la tabla
        if (Schema::hasTable('propuestas_personalizadas') && !Schema::hasTable('emails')) {
            Schema::rename('propuestas_personalizadas', 'emails');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Renombrar de vuelta
        if (Schema::hasTable('emails') && !Schema::hasTable('propuestas_personalizadas')) {
            Schema::rename('emails', 'propuestas_personalizadas');
        }
        
        // Eliminar el campo tipo
        if (Schema::hasTable('propuestas_personalizadas')) {
            Schema::table('propuestas_personalizadas', function (Blueprint $table) {
                if (Schema::hasColumn('propuestas_personalizadas', 'tipo')) {
                    $table->dropColumn('tipo');
                }
            });
        }
    }
};
