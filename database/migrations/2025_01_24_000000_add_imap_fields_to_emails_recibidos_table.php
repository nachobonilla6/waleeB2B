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
        Schema::table('emails_recibidos', function (Blueprint $table) {
            // Identificación IMAP
            $table->string('uid')->nullable()->after('message_id')->comment('UID de IMAP');
            $table->string('folder')->nullable()->after('uid')->comment('Carpeta/bandeja donde está el email');
            
            // Información adicional del remitente
            $table->string('reply_to')->nullable()->after('from_name')->comment('Email de respuesta');
            
            // Información de destinatarios
            $table->text('to_email')->nullable()->after('reply_to')->comment('Email(s) destinatario(s)');
            $table->text('to_name')->nullable()->after('to_email')->comment('Nombre(s) destinatario(s)');
            $table->text('cc')->nullable()->after('to_name')->comment('CC - emails separados por coma');
            $table->text('bcc')->nullable()->after('cc')->comment('BCC - emails separados por coma');
            
            // Cambiar body a nullable si no lo es
            if (Schema::hasColumn('emails_recibidos', 'body')) {
                $table->longText('body')->nullable()->change();
            }
            
            // Metadatos IMAP
            $table->json('headers')->nullable()->after('attachments')->comment('Headers completos del email');
            $table->string('in_reply_to')->nullable()->after('headers')->comment('Message-ID del email al que responde');
            $table->text('references')->nullable()->after('in_reply_to')->comment('Referencias del thread');
            $table->string('priority')->nullable()->after('references')->comment('Prioridad del email');
            
            // Estados y flags adicionales
            $table->boolean('is_important')->default(false)->after('is_starred')->comment('Email importante');
            $table->boolean('has_attachments')->default(false)->after('is_important')->comment('Tiene adjuntos');
            $table->json('flags')->nullable()->after('has_attachments')->comment('Flags de IMAP');
            
            // Fechas adicionales
            $table->timestamp('sent_at')->nullable()->after('received_at')->comment('Fecha de envío');
            
            // Índices adicionales
            $table->index('uid');
            $table->index('folder');
            $table->index('to_email');
            $table->index('in_reply_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails_recibidos', function (Blueprint $table) {
            $table->dropIndex(['uid']);
            $table->dropIndex(['folder']);
            $table->dropIndex(['to_email']);
            $table->dropIndex(['in_reply_to']);
            
            $table->dropColumn([
                'uid',
                'folder',
                'reply_to',
                'to_email',
                'to_name',
                'cc',
                'bcc',
                'headers',
                'in_reply_to',
                'references',
                'priority',
                'is_important',
                'has_attachments',
                'flags',
                'sent_at',
            ]);
        });
    }
};

