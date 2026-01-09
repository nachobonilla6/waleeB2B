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
            if (!Schema::hasColumn('producto_supers', 'cliente_id')) {
                $table->foreignId('cliente_id')->nullable()->after('id')->constrained('suppliers')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_supers', function (Blueprint $table) {
            if (Schema::hasColumn('producto_supers', 'cliente_id')) {
                $table->dropForeign(['cliente_id']);
                $table->dropColumn('cliente_id');
            }
        });
    }
};
