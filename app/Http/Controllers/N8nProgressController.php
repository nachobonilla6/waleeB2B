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
                'step' => $request->step,
                'progress' => $request->progress ?? $workflowRun->progress,
            ];

            // Actualizar timestamps según el estado
            if ($request->status === 'running' && !$workflowRun->started_at) {
                $updateData['started_at'] = now();
            }

            if (in_array($request->status, ['completed', 'failed'])) {
                $updateData['completed_at'] = now();
            }

            // Guardar result si viene
            if ($request->has('result')) {
                $updateData['result'] = $request->result;
            }

            // Guardar data adicional si viene
            if ($request->has('data')) {
                $updateData['data'] = $request->data;
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
