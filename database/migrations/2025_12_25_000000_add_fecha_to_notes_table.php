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
        Schema::table('notes', function (Blueprint $table) {
            $table->date('fecha')->nullable()->after('cliente_id');
        });
        
        // Asignar fecha de hoy (25 dic 2025) a todas las notas existentes
        DB::table('notes')->update(['fecha' => '2025-12-25']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('fecha');
        });
    }
};

