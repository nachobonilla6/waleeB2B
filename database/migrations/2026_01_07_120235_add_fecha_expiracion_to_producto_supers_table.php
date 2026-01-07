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
            if (!Schema::hasColumn('producto_supers', 'fecha_expiracion')) {
                $table->date('fecha_expiracion')->nullable()->after('stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_supers', function (Blueprint $table) {
            if (Schema::hasColumn('producto_supers', 'fecha_expiracion')) {
                $table->dropColumn('fecha_expiracion');
            }
        });
    }
};
