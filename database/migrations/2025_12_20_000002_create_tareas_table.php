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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_id')->constrained('listas')->onDelete('cascade');
            $table->text('texto');
            $table->dateTime('fecha_hora')->nullable();
            $table->string('tipo')->nullable();
            $table->boolean('favorito')->default(false);
            $table->enum('estado', ['pending', 'completado'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};

