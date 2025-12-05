<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.n8n.url', 'https://n8n.srv1137974.hstgr.cloud');
        $this->apiKey = config('services.n8n.api_key', env('N8N_API_KEY'));
    }

    /**
     * Obtiene todos los workflows de n8n
     */
    public function getWorkflows(): array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl . '/api/v1/workflows');

            if ($response->successful()) {
                return $response->json('data', []);
            }

            Log::error('Error al obtener workflows de n8n', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Excepción al obtener workflows de n8n', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Obtiene un workflow específico por ID
     */
    public function getWorkflow(string $workflowId): ?array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl . '/api/v1/workflows/' . $workflowId);

            if ($response->successful()) {
                return $response->json('data');
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Excepción al obtener workflow de n8n', [
                'workflow_id' => $workflowId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Ejecuta un workflow
     */
    public function executeWorkflow(string $workflowId, array $data = []): ?array
    {
        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/api/v1/workflows/' . $workflowId . '/execute', [
                    'data' => $data,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Error al ejecutar workflow de n8n', [
                'workflow_id' => $workflowId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Excepción al ejecutar workflow de n8n', [
                'workflow_id' => $workflowId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Obtiene las ejecuciones de un workflow
     */
    public function getWorkflowExecutions(string $workflowId, int $limit = 10): array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl . '/api/v1/executions', [
                    'workflowId' => $workflowId,
                    'limit' => $limit,
                ]);

            if ($response->successful()) {
                return $response->json('data', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Excepción al obtener ejecuciones de workflow', [
                'workflow_id' => $workflowId,
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Activa o desactiva un workflow
     */
    public function toggleWorkflow(string $workflowId, bool $active): bool
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->patch($this->baseUrl . '/api/v1/workflows/' . $workflowId, [
                    'active' => $active,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Excepción al cambiar estado de workflow', [
                'workflow_id' => $workflowId,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

