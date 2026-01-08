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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código único del servicio (ej: diseno_web, custom_1, etc.)
            $table->string('nombre'); // Nombre del servicio
            $table->text('descripcion')->nullable(); // Descripción del servicio
            $table->string('tipo')->default('predefinido'); // predefinido o personalizado
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
