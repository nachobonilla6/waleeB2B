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
        // Primero, eliminar todas las foreign keys que referencian clientes_en_proceso
        $foreignKeys = [
            ['table' => 'rproductos', 'column' => 'cliente_id', 'constraint' => 'rproductos_cliente_id_foreign'],
            ['table' => 'citas', 'column' => 'client_id', 'constraint' => 'citas_client_id_foreign'],
            ['table' => 'posts', 'column' => 'cliente_id', 'constraint' => 'posts_cliente_id_foreign'],
            ['table' => 'propuestas_personalizadas', 'column' => 'cliente_id', 'constraint' => 'propuestas_personalizadas_cliente_id_foreign'],
            ['table' => 'notes', 'column' => 'client_id', 'constraint' => 'notes_client_id_foreign'],
        ];

        foreach ($foreignKeys as $fk) {
            if (Schema::hasTable($fk['table']) && Schema::hasColumn($fk['table'], $fk['column'])) {
                try {
                    Schema::table($fk['table'], function (Blueprint $table) use ($fk) {
                        $table->dropForeign([$fk['column']]);
                    });
                } catch (\Exception $e) {
                    // Si la foreign key no existe o tiene otro nombre, intentar con el nombre estándar
                    try {
                        DB::statement("ALTER TABLE `{$fk['table']}` DROP FOREIGN KEY `{$fk['constraint']}`");
                    } catch (\Exception $e2) {
                        // Ignorar si no existe
                    }
                }
            }
        }

        // Renombrar la tabla
        Schema::rename('clientes_en_proceso', 'suppliers');

        // Recrear las foreign keys con el nuevo nombre de tabla
        foreach ($foreignKeys as $fk) {
            if (Schema::hasTable($fk['table']) && Schema::hasColumn($fk['table'], $fk['column'])) {
                Schema::table($fk['table'], function (Blueprint $table) use ($fk) {
                    $table->foreign($fk['column'])->references('id')->on('suppliers')->onDelete(
                        $fk['table'] === 'citas' || $fk['table'] === 'propuestas_personalizadas' ? 'set null' : 'cascade'
                    );
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar foreign keys
        $foreignKeys = [
            ['table' => 'rproductos', 'column' => 'cliente_id'],
            ['table' => 'citas', 'column' => 'client_id'],
            ['table' => 'posts', 'column' => 'cliente_id'],
            ['table' => 'propuestas_personalizadas', 'column' => 'cliente_id'],
            ['table' => 'notes', 'column' => 'client_id'],
        ];

        foreach ($foreignKeys as $fk) {
            if (Schema::hasTable($fk['table']) && Schema::hasColumn($fk['table'], $fk['column'])) {
                try {
                    Schema::table($fk['table'], function (Blueprint $table) use ($fk) {
                        $table->dropForeign([$fk['column']]);
                    });
                } catch (\Exception $e) {
                    // Ignorar si no existe
                }
            }
        }

        // Renombrar la tabla de vuelta
        Schema::rename('suppliers', 'clientes_en_proceso');

        // Recrear las foreign keys con el nombre original
        foreach ($foreignKeys as $fk) {
            if (Schema::hasTable($fk['table']) && Schema::hasColumn($fk['table'], $fk['column'])) {
                Schema::table($fk['table'], function (Blueprint $table) use ($fk) {
                    $table->foreign($fk['column'])->references('id')->on('clientes_en_proceso')->onDelete(
                        $fk['table'] === 'citas' || $fk['table'] === 'propuestas_personalizadas' ? 'set null' : 'cascade'
                    );
                });
            }
        }
    }
};
