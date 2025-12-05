<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nBot extends Model
{
    protected $fillable = [
        'name',
        'workflow_id',
        'trigger_type',
        'webhook_url',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * Ejecuta el workflow de n8n ahora
     * 
     * @param array $data Datos opcionales para enviar al workflow
     * @return \Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function runNow(array $data = []): \Illuminate\Http\Client\Response
    {
        $n8nUrl = config('services.n8n.url', 'https://n8n.srv1137974.hstgr.cloud');
        $n8nApiKey = config('services.n8n.api_key');

        if ($this->trigger_type === 'webhook') {
            // Ejecutar vía webhook
            if (empty($this->webhook_url)) {
                throw new \Exception('El bot no tiene una URL de webhook configurada.');
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->webhook_url, $data);

            Log::info('N8n Bot ejecutado vía webhook', [
                'bot_id' => $this->id,
                'bot_name' => $this->name,
                'webhook_url' => $this->webhook_url,
                'status' => $response->status(),
            ]);

            return $response;
        } else {
            // Ejecutar vía API de n8n
            if (empty($n8nUrl)) {
                throw new \Exception('N8N_URL no está configurado en el archivo .env');
            }

            if (empty($n8nApiKey)) {
                throw new \Exception('N8N_API_KEY no está configurado en el archivo .env');
            }

            // Construir la URL de la API de n8n para ejecutar el workflow
            $apiUrl = rtrim($n8nUrl, '/') . '/api/v1/workflows/' . $this->workflow_id . '/execute';

            $response = Http::timeout(60)
                ->withHeaders([
                    'X-N8N-API-KEY' => $n8nApiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($apiUrl, [
                    'data' => $data,
                ]);

            Log::info('N8n Bot ejecutado vía API', [
                'bot_id' => $this->id,
                'bot_name' => $this->name,
                'workflow_id' => $this->workflow_id,
                'status' => $response->status(),
            ]);

            return $response;
        }
    }
}
