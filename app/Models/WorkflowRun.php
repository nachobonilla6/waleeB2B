<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorkflowRun extends Model
{
    protected $fillable = [
        'job_id',
        'status',
        'step',
        'progress',
        'result',
        'data',
        'workflow_name',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'result' => 'array',
        'data' => 'array',
        'progress' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'running' => 'primary',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Procesa el siguiente workflow en cola
     */
    public static function processNextPendingWorkflow(): void
    {
        try {
            // Buscar el siguiente workflow en cola (pending) ordenado por fecha de creación
            $nextWorkflow = self::where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$nextWorkflow) {
                return; // No hay workflows en cola
            }

            // Verificar que tenga los datos necesarios (nombre_lugar e industria)
            $workflowData = $nextWorkflow->data ?? [];
            if (empty($workflowData['nombre_lugar']) || empty($workflowData['industria'])) {
                Log::warning('Workflow en cola sin datos necesarios', [
                    'job_id' => $nextWorkflow->job_id,
                ]);
                return;
            }

            $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook/0c01d9a1-788c-44d2-9c1b-9457901d0a3c';

            // Preparar payload para n8n
            $payload = [
                'job_id' => $nextWorkflow->job_id,
                'progress_url' => url('/api/n8n/progress'),
                'nombre_lugar' => $workflowData['nombre_lugar'] ?? '',
                'industria' => $workflowData['industria'] ?? '',
            ];

            // Llamar al webhook de n8n
            $response = Http::timeout(120)->post($webhookUrl, $payload);

            if ($response->successful()) {
                $nextWorkflow->update([
                    'status' => 'running',
                    'step' => 'Iniciado - Buscando lugares',
                    'started_at' => now(),
                ]);

                Log::info('Siguiente workflow en cola enviado automáticamente', [
                    'job_id' => $nextWorkflow->job_id,
                ]);
            } else {
                $nextWorkflow->update([
                    'status' => 'failed',
                    'step' => 'Error al iniciar búsqueda',
                    'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                    'completed_at' => null,
                ]);

                Log::error('Error al enviar workflow en cola', [
                    'job_id' => $nextWorkflow->job_id,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error procesando siguiente workflow en cola', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
