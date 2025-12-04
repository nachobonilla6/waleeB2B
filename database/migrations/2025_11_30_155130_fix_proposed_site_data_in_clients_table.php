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
        // Mover los valores de propuesta (que son URLs) a proposed_site donde proposed_site esté NULL
        DB::statement("UPDATE clients SET proposed_site = propuesta WHERE proposed_site IS NULL AND propuesta IS NOT NULL AND (propuesta LIKE 'http%' OR propuesta LIKE '%.%')");
        
        // Limpiar propuesta donde contiene URLs (dejarla NULL o vacía)
        DB::statement("UPDATE clients SET propuesta = NULL WHERE propuesta IS NOT NULL AND (propuesta LIKE 'http%' OR propuesta LIKE '%.%') AND proposed_site IS NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay reversa necesaria, es solo una corrección de datos
    }
};
