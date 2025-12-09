<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use App\Models\ChatMessage;

class ChatPage extends Component
{
    protected $listeners = ['finalizeMessage'];

    public $messages = [];
    public $newMessage = '';
    public $isLoading = false;
    public $voiceEnabled = true; // Toggle de voz activado por defecto

    public function mount()
    {
        // Cargar mensajes históricos del usuario
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $chatMessages = ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') // Ordenar por más recientes primero
            ->get();

        $this->messages = $chatMessages->map(function ($message) {
            return [
                'type' => $message->type,
                'content' => $message->message,
                'timestamp' => $message->created_at,
                'audio_url' => $message->audio_url,
            ];
        })->toArray();
    }

    /**
     * Generate audio from text using OpenAI TTS
     */
    private function generateAudio($text, $messageId)
    {
        try {
            $apiKey = config('services.openai.api_key');
            
            if (empty($apiKey)) {
                Log::warning('OpenAI API key not configured');
                return null;
            }

            // Call OpenAI TTS API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/audio/speech', [
                    'model' => 'tts-1',
                    'input' => $text,
                    'voice' => 'alloy', // Options: alloy, echo, fable, onyx, nova, shimmer
                    'response_format' => 'mp3',
                ]);

            if ($response->successful()) {
                // Save audio file to storage
                $audioContent = $response->body();
                
                // Crear directorio si no existe
                $directory = 'chat-audio';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                
                $filename = $directory . '/' . $messageId . '_' . time() . '.mp3';
                $saved = Storage::disk('public')->put($filename, $audioContent);
                
                if ($saved) {
                    // Return the public URL usando la ruta personalizada (sin symlink)
                    $url = route('chat.audio', ['filename' => basename($filename)]);
                    Log::info('Audio generado con OpenAI TTS', ['filename' => $filename, 'url' => $url, 'size' => strlen($audioContent)]);
                    return $url;
                } else {
                    Log::error('Error al guardar audio generado con OpenAI TTS');
                    return null;
                }
            } else {
                Log::error('OpenAI TTS API error: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error generating audio with OpenAI TTS: ' . $e->getMessage());
            return null;
        }
    }

    public function sendMessage()
    {
        if (empty(trim($this->newMessage ?? ''))) {
            return;
        }

        $userMessage = trim($this->newMessage);
        $user = auth()->user();

        // Guardar mensaje del usuario
        $userChatMessage = ChatMessage::create([
            'user_id' => $user?->id,
            'message' => $userMessage,
            'type' => 'user',
        ]);

        // Agregar mensaje del usuario al inicio (más reciente primero)
        array_unshift($this->messages, [
            'type' => 'user',
            'content' => $userMessage,
            'timestamp' => $userChatMessage->created_at,
        ]);

        $this->newMessage = '';
        $this->isLoading = true;

        try {
            // Historial breve (últimos 20 en orden cronológico)
            $history = ChatMessage::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->reverse()
                ->map(function ($message) {
                    return [
                        'role' => $message->type === 'user' ? 'user' : 'assistant',
                        'content' => $message->message,
                    ];
                })
                ->values()
                ->toArray();

            $assistantMessage = $this->generateAiResponse($history, $userMessage);

            $assistantChatMessage = ChatMessage::create([
                'user_id' => auth()->id(),
                'message' => $assistantMessage,
                'type' => 'assistant',
            ]);

            $audioUrl = null;
            if ($this->voiceEnabled) {
                $audioUrl = $this->generateAudio($assistantMessage, $assistantChatMessage->id);
                if ($audioUrl) {
                    $assistantChatMessage->update(['audio_url' => $audioUrl]);
                }
            }

            array_unshift($this->messages, [
                'type' => 'assistant',
                'content' => $assistantMessage,
                'timestamp' => $assistantChatMessage->created_at,
                'audio_url' => $audioUrl,
                'id' => $assistantChatMessage->id,
            ]);

            if ($audioUrl) {
                $this->dispatch('new-audio-message', audioUrl: $audioUrl);
            }

            // Intentar agenda + email si el usuario lo solicitó
            $actionsNote = $this->maybeScheduleAndEmail($userMessage, $assistantMessage, $user);
            if ($actionsNote) {
                // Actualizar mensaje mostrado con la nota
                $this->messages[0]['content'] = $assistantMessage . "\n\n" . $actionsNote;
                $assistantChatMessage->update(['message' => $assistantMessage . "\n\n" . $actionsNote]);
            }
        } catch (\Exception $e) {
            Log::error('Error al generar respuesta con OpenAI', [
                'message' => $e->getMessage(),
            ]);

            $errorMessage = 'Lo siento, hubo un problema al generar la respuesta. Intenta de nuevo.';

            $errorChatMessage = ChatMessage::create([
                'user_id' => auth()->id(),
                'message' => $errorMessage,
                'type' => 'assistant',
            ]);

            array_unshift($this->messages, [
                'type' => 'assistant',
                'content' => $errorMessage,
                'timestamp' => $errorChatMessage->created_at,
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Determina si el usuario está pidiendo agendar / calendario / email
     */
    private function shouldSchedule(string $text): bool
    {
        $text = mb_strtolower($text);
        return str_contains($text, 'agendar')
            || str_contains($text, 'agenda')
            || str_contains($text, 'cita')
            || str_contains($text, 'reunión')
            || str_contains($text, 'calendar')
            || str_contains($text, 'disponibilidad')
            || str_contains($text, 'evento');
    }

    /**
     * Intenta crear evento en Google Calendar y enviar email de confirmación.
     */
    private function maybeScheduleAndEmail(string $userMessage, string $assistantMessage, $user): ?string
    {
        if (!$this->shouldSchedule($userMessage)) {
            return null;
        }

        try {
            $service = $this->getCalendarService();
        } catch (\Exception $e) {
            Log::warning('No se pudo iniciar servicio de Calendar', ['error' => $e->getMessage()]);
            return '⚠️ No pude acceder al calendario. Verifica credenciales y token OAuth.';
        }

        try {
            $slot = $this->findNextAvailability($service, 60);
        } catch (\Exception $e) {
            Log::warning('No se pudo obtener disponibilidad', ['error' => $e->getMessage()]);
            return '⚠️ No pude comprobar disponibilidad en el calendario.';
        }

        if (!$slot) {
            return '⚠️ No encontré disponibilidad próxima para 1 hora.';
        }

        $summary = 'Reunión con ' . ($user?->name ?? 'Cliente');
        $description = $assistantMessage;
        $attendees = [];
        if (!empty($user?->email)) {
            $attendees[] = ['email' => $user->email];
        }

        try {
            $event = $this->createCalendarEvent($service, $summary, $description, $slot['start'], $slot['end'], $attendees);
        } catch (\Exception $e) {
            Log::warning('No se pudo crear evento', ['error' => $e->getMessage()]);
            return '⚠️ No pude crear el evento en el calendario.';
        }

        // Enviar correo con el contenido de la respuesta como cuerpo
        $emailSent = false;
        if (!empty($user?->email)) {
            try {
                $subject = 'Confirmación de evento: ' . $summary;
                $body = $assistantMessage . "\n\nEnlace del evento: " . ($event->htmlLink ?? '(no disponible)');
                $this->sendEmail($user->email, $subject, $body);
                $emailSent = true;
            } catch (\Exception $e) {
                Log::warning('No se pudo enviar email', ['error' => $e->getMessage()]);
            }
        }

        $note = '✅ Evento creado: ' . $slot['start']->format('d/m/Y H:i') . ' - ' . $slot['end']->format('H:i');
        if (!empty($event->htmlLink)) {
            $note .= "\n🔗 " . $event->htmlLink;
        }
        if ($emailSent) {
            $note .= "\n📧 Correo enviado a " . $user->email;
        } else {
            $note .= "\n⚠️ Correo no enviado (no hay email del usuario o falló el envío).";
        }

        return $note;
    }

    /**
     * Obtiene cliente de Google Calendar con credenciales + token
     */
    private function getCalendarService(): Google_Service_Calendar
    {
        $credentialsPath = config('services.google.credentials_path', storage_path('app/google-credentials.json'));
        $tokenPath = storage_path('app/google-calendar-token.json');

        if (!file_exists($credentialsPath)) {
            throw new \RuntimeException('Falta google-credentials.json en ' . $credentialsPath);
        }

        $client = new Google_Client();
        $client->setApplicationName('WALEE Chat');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig($credentialsPath);
        $client->setAccessType('offline');

        if (!file_exists($tokenPath)) {
            throw new \RuntimeException('Falta token OAuth en ' . $tokenPath . '. Autoriza Google Calendar.');
        }

        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);

        // Refresh token if expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            } else {
                throw new \RuntimeException('Token expirado y sin refresh token. Reautoriza Google Calendar.');
            }
        }

        return new Google_Service_Calendar($client);
    }

    /**
     * Busca el siguiente hueco libre de $duration minutos en los próximos 7 días
     */
    private function findNextAvailability(Google_Service_Calendar $service, int $durationMinutes = 60): ?array
    {
        $calendarId = 'primary';
        $now = Carbon::now();
        $endWindow = $now->copy()->addDays(7);

        $events = $service->events->listEvents($calendarId, [
            'timeMin' => $now->toRfc3339String(),
            'timeMax' => $endWindow->toRfc3339String(),
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ])->getItems();

        $cursor = $now->copy();
        foreach ($events as $event) {
            $start = Carbon::parse($event->getStart()->getDateTime() ?: $event->getStart()->getDate());
            $end = Carbon::parse($event->getEnd()->getDateTime() ?: $event->getEnd()->getDate());

            if ($cursor->lt($start) && $cursor->copy()->addMinutes($durationMinutes)->lte($start)) {
                return ['start' => $cursor->copy(), 'end' => $cursor->copy()->addMinutes($durationMinutes)];
            }

            if ($cursor->lt($end)) {
                $cursor = $end->copy();
            }
        }

        // Si no encontró hueco entre eventos, usar el cursor si cae antes del fin de ventana
        if ($cursor->addMinutes($durationMinutes)->lte($endWindow)) {
            return ['start' => $cursor->copy(), 'end' => $cursor->copy()->addMinutes($durationMinutes)];
        }

        return null;
    }

    /**
     * Crea evento en calendario
     */
    private function createCalendarEvent(Google_Service_Calendar $service, string $summary, string $description, Carbon $start, Carbon $end, array $attendees = [])
    {
        $event = new Google_Service_Calendar_Event([
            'summary' => $summary,
            'description' => $description,
            'start' => [
                'dateTime' => $start->toRfc3339String(),
                'timeZone' => config('app.timezone', 'UTC'),
            ],
            'end' => [
                'dateTime' => $end->toRfc3339String(),
                'timeZone' => config('app.timezone', 'UTC'),
            ],
            'attendees' => $attendees,
        ]);

        return $service->events->insert('primary', $event);
    }

    /**
     * Envia email de texto plano
     */
    private function sendEmail(string $to, string $subject, string $body): void
    {
        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    /**
     * Genera respuesta con OpenAI Chat
     */
    private function generateAiResponse(array $history, string $userMessage): string
    {
        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            throw new \RuntimeException('OpenAI API key no configurada');
        }

        $messages = array_merge(
            [
                [
                    'role' => 'system',
                    'content' => 'Eres WALEE, asistente de websolutions.work. Responde de forma breve, clara y en español. Cuando haya enlaces, preséntalos con texto descriptivo. Puedes ayudar a revisar disponibilidad en calendario y redactar correos si el usuario lo solicita.',
                ],
            ],
            $history,
            [
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ]
        );

        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.6,
                'max_tokens' => 500,
            ]);

        if (!$response->successful()) {
            Log::error('OpenAI Chat error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Error al generar respuesta con OpenAI');
        }

        $json = $response->json();
        return $json['choices'][0]['message']['content'] ?? 'Lo siento, no pude generar una respuesta en este momento.';
    }

    public function toggleVoice()
    {
        $this->voiceEnabled = !$this->voiceEnabled;
    }

    /**
     * Finaliza y guarda conversación tras streaming en frontend
     */
    public function finalizeMessage(array $payload): void
    {
        $userMessage = trim($payload['userMessage'] ?? '');
        $assistantMessage = trim($payload['assistantMessage'] ?? '');

        if ($userMessage === '' || $assistantMessage === '') {
            return;
        }

        $user = auth()->user();

        // Guardar mensaje del usuario
        $userChatMessage = ChatMessage::create([
            'user_id' => $user?->id,
            'message' => $userMessage,
            'type' => 'user',
        ]);

        array_unshift($this->messages, [
            'type' => 'user',
            'content' => $userMessage,
            'timestamp' => $userChatMessage->created_at,
        ]);

        // Guardar respuesta del asistente
        $assistantChatMessage = ChatMessage::create([
            'user_id' => $user?->id,
            'message' => $assistantMessage,
            'type' => 'assistant',
        ]);

        $audioUrl = null;
        if ($this->voiceEnabled) {
            $audioUrl = $this->generateAudio($assistantMessage, $assistantChatMessage->id);
            if ($audioUrl) {
                $assistantChatMessage->update(['audio_url' => $audioUrl]);
            }
        }

        array_unshift($this->messages, [
            'type' => 'assistant',
            'content' => $assistantMessage,
            'timestamp' => $assistantChatMessage->created_at,
            'audio_url' => $audioUrl,
            'id' => $assistantChatMessage->id,
        ]);

        if ($audioUrl) {
            $this->dispatch('new-audio-message', audioUrl: $audioUrl);
        }

        // Agenda + email si corresponde
        $actionsNote = $this->maybeScheduleAndEmail($userMessage, $assistantMessage, $user);
        if ($actionsNote) {
            // Actualizar mensaje mostrado con la nota
            $this->messages[0]['content'] .= "\n\n" . $actionsNote;
            $assistantChatMessage->update(['message' => $assistantMessage . "\n\n" . $actionsNote]);
        }
    }

    public function render()
    {
        return view('livewire.chat-page');
    }
}

