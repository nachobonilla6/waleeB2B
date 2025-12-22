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
        Schema::table('citas', function (Blueprint $table) {
            $table->enum('recurrencia', ['none', 'semanal', 'mensual', 'anual'])->default('none')->after('estado');
            $table->dateTime('recurrencia_fin')->nullable()->after('recurrencia');
            $table->string('color')->default('#10b981')->after('recurrencia_fin'); // Color por defecto verde esmeralda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['recurrencia', 'recurrencia_fin', 'color']);
        });
    }
};

