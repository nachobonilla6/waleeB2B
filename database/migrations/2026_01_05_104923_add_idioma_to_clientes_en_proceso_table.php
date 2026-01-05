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
            if (!Schema::hasColumn('clientes_en_proceso', 'idioma')) {
                $table->string('idioma', 10)->nullable()->after('ciudad');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes_en_proceso', function (Blueprint $table) {
            if (Schema::hasColumn('clientes_en_proceso', 'idioma')) {
                $table->dropColumn('idioma');
            }
        });
    }
};
