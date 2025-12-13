<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkflowRun;
use Illuminate\Support\Facades\Log;

class N8nProgressController extends Controller
{
    /**
     * Endpoint para que n8n reporte el progreso del workflow
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'job_id' => 'required|uuid',
                'status' => 'required|string|in:pending,running,completed,failed',
                'step' => 'nullable|string',
                'progress' => 'nullable|integer|min:0|max:100',
                'result' => 'nullable',
                'data' => 'nullable',
                'workflow_name' => 'nullable|string',
                'error_message' => 'nullable|string',
                'message' => 'nullable|string',
            ]);

            $workflowRun = WorkflowRun::where('job_id', $request->job_id)->first();

            if (!$workflowRun) {
                Log::warning('Workflow run no encontrado', ['job_id' => $request->job_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Workflow run no encontrado',
                ], 404);
            }

            $updateData = [
                'status' => $request->status,
                'progress' => $request->progress ?? $workflowRun->progress,
            ];

            // Actualizar step - si es failed y no viene step, usar mensaje de error o "Error"
            if ($request->status === 'failed') {
                $updateData['step'] = $request->step 
                    ?? $request->error_message 
                    ?? 'Error en la ejecución';
                // NO marcar completed_at cuando falla
                $updateData['completed_at'] = null;
            } else {
                $updateData['step'] = $request->step ?? $workflowRun->step;
            }

            // Actualizar timestamps según el estado
            if ($request->status === 'running' && !$workflowRun->started_at) {
                $updateData['started_at'] = now();
            }

            // Solo marcar completed_at si realmente se completó (no si falló)
            if ($request->status === 'completed') {
                $updateData['completed_at'] = now();
            } elseif ($request->status === 'failed') {
                // Asegurar que completed_at sea null en fallos
                $updateData['completed_at'] = null;
            }

            // Guardar result si viene
            if ($request->has('result')) {
                $updateData['result'] = $request->result;
            }

            // Guardar data adicional si viene
            if ($request->has('data')) {
                $updateData['data'] = $request->data;
            }
            
            // Si viene message, agregarlo a data (mergear con data existente si hay)
            if ($request->has('message')) {
                $currentData = $updateData['data'] ?? $workflowRun->data ?? [];
                if (!is_array($currentData)) {
                    $currentData = [];
                }
                $currentData['message'] = $request->message;
                $updateData['data'] = $currentData;
            }

            // Guardar nombre del workflow si viene
            if ($request->has('workflow_name')) {
                $updateData['workflow_name'] = $request->workflow_name;
            }

            // Guardar mensaje de error si viene
            if ($request->has('error_message')) {
                $updateData['error_message'] = $request->error_message;
            }

            $workflowRun->update($updateData);

            Log::info('Progreso de workflow actualizado', [
                'job_id' => $request->job_id,
                'status' => $request->status,
                'progress' => $updateData['progress'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Progreso actualizado correctamente',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en actualización de workflow', [
                'errors' => $e->errors(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error actualizando progreso de workflow', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar progreso: ' . $e->getMessage(),
            ], 500);
        }
    }
}
