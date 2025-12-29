<?php

namespace App\Http\Controllers;

use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappWebhookController extends Controller
{
    /**
     * Recibir webhook de n8n con mensajes de WhatsApp
     */
    public function handleWebhook(Request $request)
    {
        Log::info('WhatsApp webhook recibido', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
        ]);

        try {
            $data = $request->all();
            
            // Soportar múltiples formatos de entrada de n8n
            $messages = [];
            
            // Si viene como array directo
            if (isset($data[0]) && is_array($data[0])) {
                $messages = $data;
            }
            // Si viene dentro de un campo 'data' o 'messages'
            elseif (isset($data['data']) && is_array($data['data'])) {
                $messages = $data['data'];
            }
            elseif (isset($data['messages']) && is_array($data['messages'])) {
                $messages = $data['messages'];
            }
            // Si viene como un solo mensaje
            elseif (isset($data['phone_number']) || isset($data['from']) || isset($data['to'])) {
                $messages = [$data];
            }
            // Si viene con estructura n8n con 'json'
            elseif (isset($data[0]['json'])) {
                $messages = array_map(function($item) {
                    return $item['json'] ?? $item;
                }, $data);
            }

            $processed = 0;
            foreach ($messages as $messageData) {
                try {
                    $this->processMessage($messageData);
                    $processed++;
                } catch (\Exception $e) {
                    Log::error('Error procesando mensaje individual', [
                        'message' => $e->getMessage(),
                        'data' => $messageData,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Se procesaron {$processed} mensaje(s)",
                'processed' => $processed,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error en webhook de WhatsApp', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error procesando webhook',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Procesar un mensaje individual
     */
    private function processMessage(array $messageData)
    {
        // Extraer información del mensaje
        // Formato esperado puede variar, intentamos múltiples campos
        $phoneNumber = $messageData['phone_number'] 
            ?? $messageData['from'] 
            ?? $messageData['to'] 
            ?? $messageData['number']
            ?? null;

        if (!$phoneNumber) {
            throw new \Exception('Número de teléfono no encontrado en el mensaje');
        }

        // Normalizar número de teléfono (remover espacios, guiones, etc.)
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);

        // Determinar dirección del mensaje
        // Si tiene 'from' y 'to', comparar con nuestro número para determinar dirección
        $direction = 'incoming'; // Por defecto entrante
        
        if (isset($messageData['direction'])) {
            $direction = $messageData['direction'];
        } elseif (isset($messageData['type']) && in_array($messageData['type'], ['sent', 'outgoing', 'sent_message'])) {
            $direction = 'outgoing';
        } elseif (isset($messageData['type']) && in_array($messageData['type'], ['received', 'incoming', 'received_message'])) {
            $direction = 'incoming';
        }

        // Obtener o crear conversación
        $conversation = WhatsappConversation::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'contact_name' => $messageData['contact_name'] 
                    ?? $messageData['name'] 
                    ?? $messageData['contactName']
                    ?? null,
                'contact_image' => $messageData['contact_image'] 
                    ?? $messageData['image'] 
                    ?? $messageData['contactImage']
                    ?? null,
            ]
        );

        // Actualizar información del contacto si viene
        if (isset($messageData['contact_name']) || isset($messageData['name'])) {
            $conversation->update([
                'contact_name' => $messageData['contact_name'] ?? $messageData['name'] ?? $conversation->contact_name,
                'contact_image' => $messageData['contact_image'] ?? $messageData['image'] ?? $conversation->contact_image,
            ]);
        }

        // Extraer contenido del mensaje
        $content = $messageData['content'] 
            ?? $messageData['text'] 
            ?? $messageData['message'] 
            ?? $messageData['body']
            ?? $messageData['textBody']
            ?? '';

        // Extraer ID del mensaje de WhatsApp
        $messageId = $messageData['message_id'] 
            ?? $messageData['id'] 
            ?? $messageData['messageId']
            ?? $messageData['wa_id']
            ?? null;

        // Verificar si el mensaje ya existe (evitar duplicados)
        if ($messageId) {
            $existingMessage = WhatsappMessage::where('message_id', $messageId)->first();
            if ($existingMessage) {
                Log::info('Mensaje duplicado ignorado', ['message_id' => $messageId]);
                return;
            }
        }

        // Extraer tipo de mensaje
        $messageType = $messageData['message_type'] 
            ?? $messageData['type'] 
            ?? 'text';

        // Extraer información de media si existe
        $mediaUrl = $messageData['media_url'] 
            ?? $messageData['mediaUrl'] 
            ?? $messageData['image_url'] 
            ?? $messageData['video_url']
            ?? $messageData['document_url']
            ?? null;

        $mediaType = $messageData['media_type'] 
            ?? $messageData['mediaType']
            ?? null;

        $mediaName = $messageData['media_name'] 
            ?? $messageData['mediaName'] 
            ?? $messageData['filename']
            ?? null;

        // Extraer timestamp
        $whatsappTimestamp = null;
        if (isset($messageData['timestamp'])) {
            $whatsappTimestamp = is_numeric($messageData['timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($messageData['timestamp'])
                : \Carbon\Carbon::parse($messageData['timestamp']);
        } elseif (isset($messageData['whatsapp_timestamp'])) {
            $whatsappTimestamp = is_numeric($messageData['whatsapp_timestamp']) 
                ? \Carbon\Carbon::createFromTimestamp($messageData['whatsapp_timestamp'])
                : \Carbon\Carbon::parse($messageData['whatsapp_timestamp']);
        } elseif (isset($messageData['date'])) {
            $whatsappTimestamp = \Carbon\Carbon::parse($messageData['date']);
        }

        // Estado del mensaje
        $status = $messageData['status'] 
            ?? ($direction === 'outgoing' ? 'sent' : 'delivered')
            ?? 'sent';

        // Crear el mensaje
        $message = WhatsappMessage::create([
            'conversation_id' => $conversation->id,
            'direction' => $direction,
            'content' => $content,
            'message_id' => $messageId,
            'message_type' => $messageType,
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
            'media_name' => $mediaName,
            'status' => $status,
            'whatsapp_timestamp' => $whatsappTimestamp,
            'metadata' => $messageData['metadata'] ?? null,
        ]);

        // Actualizar última conversación
        $conversation->updateLastMessage(
            $content ?: ($mediaName ?? 'Media'),
            $whatsappTimestamp ?? now()
        );

        // Incrementar contador de no leídos si es mensaje entrante
        if ($direction === 'incoming') {
            $conversation->incrementUnread();
        }

        Log::info('Mensaje de WhatsApp guardado', [
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'direction' => $direction,
        ]);

        return $message;
    }
}

