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
            $table->enum('recurrencia', ['none', 'diaria', 'semanal', 'mensual'])->default('none')->after('estado');
            $table->dateTime('recurrencia_fin')->nullable()->after('recurrencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn(['recurrencia', 'recurrencia_fin']);
        });
    }
};

