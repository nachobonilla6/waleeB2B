<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * DLC = Date / Fecha de Lote de Caducidad (Lot Expiration Date)
     */
    public function up(): void
    {
        Schema::table('producto_supers', function (Blueprint $table) {
            if (!Schema::hasColumn('producto_supers', 'dlc')) {
                $table->date('dlc')->nullable()->after('fecha_salida');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_supers', function (Blueprint $table) {
            if (Schema::hasColumn('producto_supers', 'dlc')) {
                $table->dropColumn('dlc');
            }
        });
    }
};

