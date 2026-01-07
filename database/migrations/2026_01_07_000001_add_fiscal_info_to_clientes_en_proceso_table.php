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
            if (!Schema::hasColumn('clientes_en_proceso', 'fiscal_name')) {
                $table->string('fiscal_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('clientes_en_proceso', 'fiscal_tax_id')) {
                $table->string('fiscal_tax_id')->nullable()->after('fiscal_name');
            }
            if (!Schema::hasColumn('clientes_en_proceso', 'fiscal_country')) {
                $table->string('fiscal_country', 100)->nullable()->after('fiscal_tax_id');
            }
            if (!Schema::hasColumn('clientes_en_proceso', 'fiscal_city')) {
                $table->string('fiscal_city', 100)->nullable()->after('fiscal_country');
            }
            if (!Schema::hasColumn('clientes_en_proceso', 'fiscal_postal_code')) {
                $table->string('fiscal_postal_code', 20)->nullable()->after('fiscal_city');
            }
            if (!Schema::hasColumn('clientes_en_proceso', 'fiscal_address')) {
                $table->string('fiscal_address', 255)->nullable()->after('fiscal_postal_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes_en_proceso', function (Blueprint $table) {
            if (Schema::hasColumn('clientes_en_proceso', 'fiscal_name')) {
                $table->dropColumn('fiscal_name');
            }
            if (Schema::hasColumn('clientes_en_proceso', 'fiscal_tax_id')) {
                $table->dropColumn('fiscal_tax_id');
            }
            if (Schema::hasColumn('clientes_en_proceso', 'fiscal_country')) {
                $table->dropColumn('fiscal_country');
            }
            if (Schema::hasColumn('clientes_en_proceso', 'fiscal_city')) {
                $table->dropColumn('fiscal_city');
            }
            if (Schema::hasColumn('clientes_en_proceso', 'fiscal_postal_code')) {
                $table->dropColumn('fiscal_postal_code');
            }
            if (Schema::hasColumn('clientes_en_proceso', 'fiscal_address')) {
                $table->dropColumn('fiscal_address');
            }
        });
    }
};



