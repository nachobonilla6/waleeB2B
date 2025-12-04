<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class N8nError extends Model
{
    protected $fillable = [
        'execution_id',
        'execution_url',
        'retry_of',
        'mode',
        'error_message',
        'error_stack',
        'last_node_executed',
        'workflow_id',
        'workflow_name',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Crear error desde datos de n8n
     */
    public static function createFromN8n(array $data): self
    {
        // Soportar formato plano o anidado
        $execution = $data['execution'] ?? $data;
        $workflow = $data['workflow'] ?? [];
        $error = $execution['error'] ?? [];

        return self::create([
            'execution_id' => $data['execution_id'] ?? $execution['id'] ?? null,
            'execution_url' => $data['url'] ?? $execution['url'] ?? null,
            'retry_of' => $data['retryof'] ?? $execution['retryOf'] ?? null,
            'mode' => $data['mode'] ?? $execution['mode'] ?? null,
            'error_message' => $data['error_message'] ?? $error['message'] ?? null,
            'error_stack' => $data['stack'] ?? $error['stack'] ?? null,
            'last_node_executed' => $data['node'] ?? $execution['lastNodeExecuted'] ?? null,
            'workflow_id' => $data['workflow_id'] ?? $workflow['id'] ?? null,
            'workflow_name' => $data['workflow'] ?? $workflow['name'] ?? null,
            'status' => 'new',
        ]);
    }
}
