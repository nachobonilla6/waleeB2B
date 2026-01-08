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
        Schema::table('producto_supers', function (Blueprint $table) {
            if (!Schema::hasColumn('producto_supers', 'cantidad')) {
                $table->integer('cantidad')->default(0)->after('stock');
            }
            if (!Schema::hasColumn('producto_supers', 'fecha_entrada')) {
                $table->date('fecha_entrada')->nullable()->after('cantidad');
            }
            if (!Schema::hasColumn('producto_supers', 'fecha_limite_venta')) {
                $table->date('fecha_limite_venta')->nullable()->after('fecha_entrada');
            }
            if (!Schema::hasColumn('producto_supers', 'fecha_salida')) {
                $table->date('fecha_salida')->nullable()->after('fecha_limite_venta');
            }
            if (!Schema::hasColumn('producto_supers', 'foto_qr')) {
                $table->string('foto_qr')->nullable()->after('imagen');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_supers', function (Blueprint $table) {
            if (Schema::hasColumn('producto_supers', 'cantidad')) {
                $table->dropColumn('cantidad');
            }
            if (Schema::hasColumn('producto_supers', 'fecha_entrada')) {
                $table->dropColumn('fecha_entrada');
            }
            if (Schema::hasColumn('producto_supers', 'fecha_limite_venta')) {
                $table->dropColumn('fecha_limite_venta');
            }
            if (Schema::hasColumn('producto_supers', 'fecha_salida')) {
                $table->dropColumn('fecha_salida');
            }
            if (Schema::hasColumn('producto_supers', 'foto_qr')) {
                $table->dropColumn('foto_qr');
            }
        });
    }
};
