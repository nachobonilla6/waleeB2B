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
        // Eliminar tablas de n8n si existen
        Schema::dropIfExists('n8n_posts');
        Schema::dropIfExists('n8n_errors');
        Schema::dropIfExists('n8n_bots');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No recreamos las tablas ya que fueron eliminadas intencionalmente
        // Si necesitas revertir, tendrías que recrear las migraciones originales
    }
};
