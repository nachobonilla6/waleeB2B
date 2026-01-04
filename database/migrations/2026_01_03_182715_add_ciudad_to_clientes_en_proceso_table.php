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
        Schema::table('clientes_en_proceso', function (Blueprint $table) {
            $table->string('ciudad', 255)->nullable()->after('horario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes_en_proceso', function (Blueprint $table) {
            $table->dropColumn('ciudad');
        });
    }
};
