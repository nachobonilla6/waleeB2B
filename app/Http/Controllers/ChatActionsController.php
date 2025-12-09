<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\ChatMessage;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class ChatActionsController extends Controller
{
    /**
     * Persiste la conversación tras el streaming, opcionalmente genera audio y agenda/envía correo.
     */
    public function finalize(Request $request)
    {
        $data = $request->validate([
            'user_message' => 'required|string',
            'assistant_message' => 'required|string',
            'voice_enabled' => 'sometimes|boolean',
            'skip_actions' => 'sometimes|boolean',
        ]);

        $user = $request->user();
        $voiceEnabled = $data['voice_enabled'] ?? true;
        $skipActions = $data['skip_actions'] ?? false;

        // Guardar mensaje del usuario
        $userChatMessage = ChatMessage::create([
            'user_id' => $user?->id,
            'message' => trim($data['user_message']),
            'type' => 'user',
        ]);

        // Guardar respuesta del asistente
        $assistantChatMessage = ChatMessage::create([
            'user_id' => $user?->id,
            'message' => trim($data['assistant_message']),
            'type' => 'assistant',
        ]);

        $audioUrl = null;
        if ($voiceEnabled) {
            $audioUrl = $this->generateAudio($assistantChatMessage->message, $assistantChatMessage->id);
            if ($audioUrl) {
                $assistantChatMessage->update(['audio_url' => $audioUrl]);
            }
        }

        // Agenda + email si corresponde (y si no se indica omitir)
        $note = null;
        if (!$skipActions) {
            $note = $this->maybeScheduleAndEmail($data['user_message'], $assistantChatMessage->message, $user);
            if ($note) {
                $assistantChatMessage->update(['message' => $assistantChatMessage->message . "\n\n" . $note]);
            }
        }

        return response()->json([
            'assistant_message' => $assistantChatMessage->message,
            'audio_url' => $audioUrl,
            'note' => $note,
        ]);
    }

    private function generateAudio($text, $messageId)
    {
        try {
            $apiKey = config('services.openai.api_key');
            
            if (empty($apiKey)) {
                Log::warning('OpenAI API key not configured');
                return null;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/audio/speech', [
                    'model' => 'tts-1',
                    'input' => $text,
                    'voice' => 'alloy',
                    'response_format' => 'mp3',
                ]);

            if ($response->successful()) {
                $audioContent = $response->body();
                $directory = 'chat-audio';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                
                $filename = $directory . '/' . $messageId . '_' . time() . '.mp3';
                $saved = Storage::disk('public')->put($filename, $audioContent);
                
                if ($saved) {
                    return route('chat.audio', ['filename' => basename($filename)]);
                }
            } else {
                Log::error('OpenAI TTS API error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error generating audio with OpenAI TTS: ' . $e->getMessage());
        }
        return null;
    }

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

        if ($cursor->addMinutes($durationMinutes)->lte($endWindow)) {
            return ['start' => $cursor->copy(), 'end' => $cursor->copy()->addMinutes($durationMinutes)];
        }

        return null;
    }

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

    private function sendEmail(string $to, string $subject, string $body): void
    {
        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}

