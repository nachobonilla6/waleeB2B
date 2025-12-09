<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\ChatMessage;

class ChatStreamController extends Controller
{
    public function stream(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $apiKey = config('services.openai.api_key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'OpenAI API key not configured'], 500);
        }

        $user = $request->user();

        // Construir historial breve (20 últimos en orden cronológico)
        $history = ChatMessage::where('user_id', $user?->id)
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

        $messages = array_merge(
            [[
                'role' => 'system',
                'content' => 'Eres WALEE, asistente de websolutions.work. Responde breve, claro y en español. Cuando haya enlaces, preséntalos con texto descriptivo. Puedes ayudar a revisar disponibilidad en calendario y redactar correos si el usuario lo solicita.',
            ]],
            $history,
            [[
                'role' => 'user',
                'content' => $request->string('message'),
            ]]
        );

        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'temperature' => 0.6,
            'max_tokens' => 600,
            'stream' => true,
        ];

        $stream = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->withBody(json_encode($payload), 'application/json')
            ->timeout(120)
            ->send('POST', 'https://api.openai.com/v1/chat/completions', ['stream' => true]);

        if ($stream->failed()) {
            Log::error('OpenAI stream error', ['status' => $stream->status(), 'body' => $stream->body()]);
            return response()->json(['error' => 'Error al iniciar streaming'], 500);
        }

        return new StreamedResponse(function () use ($stream) {
            foreach ($stream->stream() as $chunk) {
                $data = $chunk->getContent();
                if ($data) {
                    echo $data;
                    ob_flush();
                    flush();
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}

