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
        if (Schema::hasTable('publicidad_eventos')) {
            if (!Schema::hasColumn('publicidad_eventos', 'texto')) {
                Schema::table('publicidad_eventos', function (Blueprint $table) {
                    $table->text('texto')->nullable()->after('descripcion');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publicidad_eventos', function (Blueprint $table) {
            if (Schema::hasColumn('publicidad_eventos', 'texto')) {
                $table->dropColumn('texto');
            }
        });
    }
};
