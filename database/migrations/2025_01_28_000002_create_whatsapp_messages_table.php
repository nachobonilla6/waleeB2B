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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('whatsapp_conversations')->onDelete('cascade');
            $table->enum('direction', ['incoming', 'outgoing'])->comment('Dirección del mensaje');
            $table->text('content')->comment('Contenido del mensaje');
            $table->string('message_id')->nullable()->unique()->comment('ID único del mensaje de WhatsApp');
            $table->string('message_type')->default('text')->comment('Tipo de mensaje: text, image, video, audio, document, etc.');
            $table->string('media_url')->nullable()->comment('URL del archivo multimedia si aplica');
            $table->string('media_type')->nullable()->comment('Tipo de media: image, video, audio, document');
            $table->string('media_name')->nullable()->comment('Nombre del archivo');
            $table->string('status')->default('sent')->comment('Estado: sent, delivered, read, failed');
            $table->timestamp('whatsapp_timestamp')->nullable()->comment('Timestamp del mensaje en WhatsApp');
            $table->json('metadata')->nullable()->comment('Metadatos adicionales del mensaje');
            $table->timestamps();
            
            $table->index('conversation_id');
            $table->index('direction');
            $table->index('whatsapp_timestamp');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};

