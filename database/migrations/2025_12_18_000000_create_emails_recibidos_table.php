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
        Schema::create('emails_recibidos', function (Blueprint $table) {
            $table->id();
            
            // Identificación del email
            $table->string('message_id')->nullable()->unique()->comment('Message-ID del email (único)');
            $table->string('uid')->nullable()->comment('UID de IMAP');
            $table->string('folder')->nullable()->comment('Carpeta/bandeja donde está el email (INBOX, Sent, etc.)');
            
            // Información del remitente
            $table->string('from_email')->comment('Email del remitente');
            $table->string('from_name')->nullable()->comment('Nombre del remitente');
            $table->string('reply_to')->nullable()->comment('Email de respuesta');
            
            // Información de destinatarios
            $table->text('to_email')->nullable()->comment('Email(s) destinatario(s) - puede ser múltiple separado por coma');
            $table->text('to_name')->nullable()->comment('Nombre(s) destinatario(s)');
            $table->text('cc')->nullable()->comment('CC - emails separados por coma');
            $table->text('bcc')->nullable()->comment('BCC - emails separados por coma');
            
            // Contenido del email
            $table->string('subject')->comment('Asunto del email');
            $table->longText('body')->nullable()->comment('Cuerpo del email en texto plano');
            $table->longText('body_html')->nullable()->comment('Cuerpo del email en HTML');
            $table->json('attachments')->nullable()->comment('Array de adjuntos');
            
            // Metadatos IMAP
            $table->json('headers')->nullable()->comment('Headers completos del email');
            $table->string('in_reply_to')->nullable()->comment('Message-ID del email al que responde');
            $table->text('references')->nullable()->comment('Referencias del thread');
            $table->string('priority')->nullable()->comment('Prioridad del email (high, normal, low)');
            
            // Estados y flags
            $table->boolean('is_read')->default(false)->comment('Email leído');
            $table->boolean('is_starred')->default(false)->comment('Email destacado/favorito');
            $table->boolean('is_important')->default(false)->comment('Email importante');
            $table->boolean('has_attachments')->default(false)->comment('Tiene adjuntos');
            $table->json('flags')->nullable()->comment('Flags de IMAP (Seen, Answered, Flagged, etc.)');
            
            // Fechas
            $table->timestamp('received_at')->nullable()->comment('Fecha de recepción');
            $table->timestamp('sent_at')->nullable()->comment('Fecha de envío');
            
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index('from_email');
            $table->index('to_email');
            $table->index('is_read');
            $table->index('is_starred');
            $table->index('received_at');
            $table->index('folder');
            $table->index('uid');
            $table->index('in_reply_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails_recibidos');
    }
};

