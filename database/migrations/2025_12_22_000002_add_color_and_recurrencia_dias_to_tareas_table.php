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
        Schema::table('tareas', function (Blueprint $table) {
            $table->string('color')->default('#8b5cf6')->after('recurrencia_fin'); // Color por defecto violeta
            $table->json('recurrencia_dias')->nullable()->after('color'); // Días específicos de la semana (0=Domingo, 1=Lunes, etc.) o días del mes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn(['color', 'recurrencia_dias']);
        });
    }
};

