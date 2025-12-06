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
        $this->baseUrl = env('N8N_URL', 'https://n8n.srv1137974.hstgr.cloud');
        $this->apiKey = env('N8N_API_KEY', '');
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
                ])
                ->get("{$this->baseUrl}/api/v1/workflows");

            if ($response->successful()) {
                return $response->json() ?? [];
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
                ])
                ->get("{$this->baseUrl}/api/v1/workflows/{$workflowId}");

            if ($response->successful()) {
                return $response->json();
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
            $response = Http::timeout(120)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                ])
                ->post("{$this->baseUrl}/api/v1/workflows/{$workflowId}/execute", $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Error al ejecutar workflow de n8n', [
                'workflow_id' => $workflowId,
                'status' => $response->status(),
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
     * Activa o desactiva un workflow
     */
    public function toggleWorkflow(string $workflowId, bool $active): bool
    {
        try {
            $workflow = $this->getWorkflow($workflowId);
            if (!$workflow) {
                return false;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                ])
                ->put("{$this->baseUrl}/api/v1/workflows/{$workflowId}", [
                    'active' => $active,
                    'name' => $workflow['name'] ?? '',
                    'nodes' => $workflow['nodes'] ?? [],
                    'connections' => $workflow['connections'] ?? [],
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Excepción al cambiar estado del workflow', [
                'workflow_id' => $workflowId,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Actualiza los nodos de un workflow
     */
    public function updateWorkflowNodes(string $workflowId, array $nodes, array $connections = []): bool
    {
        try {
            $workflow = $this->getWorkflow($workflowId);
            if (!$workflow) {
                return false;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-N8N-API-KEY' => $this->apiKey,
                ])
                ->put("{$this->baseUrl}/api/v1/workflows/{$workflowId}", [
                    'name' => $workflow['name'] ?? '',
                    'nodes' => $nodes,
                    'connections' => $connections ?: ($workflow['connections'] ?? []),
                    'active' => $workflow['active'] ?? false,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Excepción al actualizar nodos del workflow', [
                'workflow_id' => $workflowId,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

