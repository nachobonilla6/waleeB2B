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
        Schema::create('whatsapp_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->comment('Número de teléfono (con código de país)');
            $table->string('contact_name')->nullable()->comment('Nombre del contacto');
            $table->string('contact_image')->nullable()->comment('URL de la imagen del contacto');
            $table->text('last_message')->nullable()->comment('Último mensaje de la conversación');
            $table->timestamp('last_message_at')->nullable()->comment('Fecha del último mensaje');
            $table->boolean('is_archived')->default(false)->comment('Si la conversación está archivada');
            $table->boolean('is_pinned')->default(false)->comment('Si la conversación está fijada');
            $table->integer('unread_count')->default(0)->comment('Cantidad de mensajes no leídos');
            $table->timestamps();
            
            $table->index('phone_number');
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_conversations');
    }
};

