<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\WorkflowRun;

class CotizacionWorkflowController extends Controller
{
    /**
     * Inicia el workflow de cotización de sitios web y automatizaciones
     */
    public function iniciar(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telefono' => 'nullable|string|max:50',
                'tipo_servicio' => 'required|string|in:sitio_web,automatizacion',
                'mensaje' => 'nullable|string|max:1000',
            ]);

            $jobId = Str::uuid();

            // Crear el registro del workflow
            $workflowRun = WorkflowRun::create([
                'job_id' => $jobId,
                'status' => 'pending',
                'progress' => 0,
                'step' => 'En cola',
                'workflow_name' => 'Cotización Sitios Web y Automatizaciones',
            ]);

            // Obtener la URL del webhook de n8n
            $webhookUrl = config('services.n8n.cotizacion_webhook_url', env('N8N_COTIZACION_WEBHOOK_URL'));

            if (!$webhookUrl) {
                throw new \Exception('URL del webhook de cotización no configurada. Agrega N8N_COTIZACION_WEBHOOK_URL en tu .env');
            }

            // Preparar datos para enviar a n8n
            $payload = [
                'job_id' => $jobId,
                'progress_url' => url('/api/n8n/progress'),
                'nombre' => $request->nombre,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'tipo_servicio' => $request->tipo_servicio,
                'mensaje' => $request->mensaje,
            ];

            // Llamar al webhook de n8n
            $response = Http::timeout(120)->post($webhookUrl, $payload);

            if ($response->successful()) {
                $workflowRun->update([
                    'status' => 'running',
                    'step' => 'Workflow iniciado',
                    'progress' => 10,
                    'started_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud de cotización enviada correctamente',
                    'job_id' => $jobId,
                    'data' => $response->json(),
                ], 200);
            } else {
                $workflowRun->update([
                    'status' => 'failed',
                    'step' => 'Error al iniciar workflow',
                    'error_message' => 'Error al comunicarse con n8n: ' . $response->status(),
                ]);

                Log::error('Error al iniciar workflow de cotización', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar la solicitud. Por favor, intenta nuevamente.',
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Excepción al iniciar workflow de cotización', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene el estado de una cotización por job_id
     */
    public function estado($jobId)
    {
        try {
            $workflowRun = WorkflowRun::where('job_id', $jobId)->first();

            if (!$workflowRun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cotización no encontrada',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'job_id' => $workflowRun->job_id,
                    'status' => $workflowRun->status,
                    'step' => $workflowRun->step,
                    'progress' => $workflowRun->progress,
                    'result' => $workflowRun->result,
                    'error_message' => $workflowRun->error_message,
                    'created_at' => $workflowRun->created_at,
                    'started_at' => $workflowRun->started_at,
                    'completed_at' => $workflowRun->completed_at,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo estado de cotización', [
                'job_id' => $jobId,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el estado',
            ], 500);
        }
    }
}


