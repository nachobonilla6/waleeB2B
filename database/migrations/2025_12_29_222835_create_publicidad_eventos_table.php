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
        Schema::create('publicidad_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->text('texto')->nullable(); // Texto de la publicación (contenido principal)
            $table->text('notas')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('tipo_publicidad')->nullable(); // post, historia, reel, anuncio, etc.
            $table->string('plataforma')->nullable(); // facebook, instagram, tiktok, etc.
            $table->string('estado')->default('programado'); // programado, publicado, cancelado
            $table->string('color')->default('#8b5cf6'); // Color por defecto violeta
            $table->enum('recurrencia', ['none', 'semanal', 'mensual', 'anual'])->default('none');
            $table->dateTime('recurrencia_fin')->nullable();
            $table->json('recurrencia_dias')->nullable();
            $table->string('url_post')->nullable();
            $table->string('imagen_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicidad_eventos');
    }
};
