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
        Schema::rename('clients', 'clientes_en_proceso');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('clientes_en_proceso', 'clients');
    }
};
