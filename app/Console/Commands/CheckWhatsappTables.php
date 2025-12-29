<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckWhatsappTables extends Command
{
    protected $signature = 'whatsapp:check-tables';
    protected $description = 'Verificar que las tablas de WhatsApp existan y funcionen';

    public function handle()
    {
        $this->info('Verificando tablas de WhatsApp...');
        
        // Verificar tabla de conversaciones
        if (Schema::hasTable('whatsapp_conversations')) {
            $this->info('✓ Tabla whatsapp_conversations existe');
            $count = DB::table('whatsapp_conversations')->count();
            $this->info("  - Conversaciones: {$count}");
        } else {
            $this->error('✗ Tabla whatsapp_conversations NO existe');
            $this->warn('  Ejecuta: php artisan migrate');
            return 1;
        }
        
        // Verificar tabla de mensajes
        if (Schema::hasTable('whatsapp_messages')) {
            $this->info('✓ Tabla whatsapp_messages existe');
            $count = DB::table('whatsapp_messages')->count();
            $this->info("  - Mensajes: {$count}");
        } else {
            $this->error('✗ Tabla whatsapp_messages NO existe');
            $this->warn('  Ejecuta: php artisan migrate');
            return 1;
        }
        
        // Probar crear una conversación de prueba
        $this->info('\nProbando creación de datos...');
        try {
            $conversation = \App\Models\WhatsappConversation::firstOrCreate(
                ['phone_number' => '+50600000000'],
                ['contact_name' => 'Test Contact']
            );
            $this->info("✓ Conversación de prueba creada (ID: {$conversation->id})");
            
            $message = \App\Models\WhatsappMessage::create([
                'conversation_id' => $conversation->id,
                'direction' => 'incoming',
                'content' => 'Mensaje de prueba',
                'message_type' => 'text',
            ]);
            $this->info("✓ Mensaje de prueba creado (ID: {$message->id})");
            
            // Limpiar datos de prueba
            $message->delete();
            $conversation->delete();
            $this->info('✓ Datos de prueba eliminados');
            
        } catch (\Exception $e) {
            $this->error('✗ Error al crear datos de prueba: ' . $e->getMessage());
            return 1;
        }
        
        $this->info('\n✓ Todas las verificaciones pasaron correctamente');
        return 0;
    }
}

