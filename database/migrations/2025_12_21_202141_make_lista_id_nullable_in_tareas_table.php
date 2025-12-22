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
            $table->foreignId('lista_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Primero eliminar la foreign key constraint
            $table->dropForeign(['lista_id']);
            // Luego hacer que no sea nullable
            $table->foreignId('lista_id')->nullable(false)->change();
            // Recrear la foreign key constraint
            $table->foreign('lista_id')->references('id')->on('listas')->onDelete('cascade');
        });
    }
};

