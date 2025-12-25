<?php

namespace App\Observers;

use App\Models\Cita;
use App\Services\N8nService;
use Illuminate\Support\Facades\Log;

class CitaObserver
{
    protected N8nService $n8nService;
    protected string $webhookUrl;

    public function __construct()
    {
        $this->n8nService = new N8nService();
        $this->webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/cce19daa-44ba-4a41-a3d3-2c0796c41bb8';
    }

    /**
     * Handle the Cita "created" event.
     */
    public function created(Cita $cita): void
    {
        try {
            // Cargar la relación del cliente si existe
            if ($cita->cliente_id) {
                $cita->load('cliente');
            }

            // Preparar los datos para el webhook
            $webhookData = [
                'event' => 'cita_creada',
                'cita' => [
                    'id' => $cita->id,
                    'titulo' => $cita->titulo,
                    'descripcion' => $cita->descripcion ?? '',
                    'fecha_inicio' => $cita->fecha_inicio ? $cita->fecha_inicio->format('Y-m-d H:i:s') : null,
                    'fecha_fin' => $cita->fecha_fin ? $cita->fecha_fin->format('Y-m-d H:i:s') : null,
                    'ubicacion' => $cita->ubicacion ?? '',
                    'estado' => $cita->estado ?? 'programada',
                    'recurrencia' => $cita->recurrencia ?? 'none',
                    'color' => $cita->color ?? '#10b981',
                ],
                'cliente' => null,
                'mensaje' => '',
            ];

            // Agregar información del cliente si existe
            if ($cita->cliente) {
                $webhookData['cliente'] = [
                    'id' => $cita->cliente->id,
                    'nombre_empresa' => $cita->cliente->nombre_empresa ?? '',
                    'correo' => $cita->cliente->correo ?? '',
                    'telefono' => $cita->cliente->telefono ?? '',
                ];

                // Construir mensaje con información de la cita
                $fechaInicio = $cita->fecha_inicio ? $cita->fecha_inicio->format('d/m/Y H:i') : 'Fecha no especificada';
                $fechaFin = $cita->fecha_fin ? $cita->fecha_fin->format('d/m/Y H:i') : '';
                
                $mensaje = "Nueva cita creada:\n\n";
                $mensaje .= "Título: {$cita->titulo}\n";
                $mensaje .= "Fecha de inicio: {$fechaInicio}\n";
                
                if ($fechaFin) {
                    $mensaje .= "Fecha de fin: {$fechaFin}\n";
                }
                
                if ($cita->ubicacion) {
                    $mensaje .= "Ubicación: {$cita->ubicacion}\n";
                }
                
                if ($cita->descripcion) {
                    $mensaje .= "Descripción: {$cita->descripcion}\n";
                }
                
                $mensaje .= "\nCliente:\n";
                $mensaje .= "Nombre: " . ($cita->cliente->nombre_empresa ?? 'Sin nombre') . "\n";
                $mensaje .= "Email: " . ($cita->cliente->correo ?? 'Sin email') . "\n";
                
                if ($cita->cliente->telefono) {
                    $mensaje .= "Teléfono: {$cita->cliente->telefono}\n";
                }

                $webhookData['mensaje'] = $mensaje;
            } else {
                // Si no hay cliente, crear mensaje básico
                $fechaInicio = $cita->fecha_inicio ? $cita->fecha_inicio->format('d/m/Y H:i') : 'Fecha no especificada';
                $fechaFin = $cita->fecha_fin ? $cita->fecha_fin->format('d/m/Y H:i') : '';
                
                $mensaje = "Nueva cita creada:\n\n";
                $mensaje .= "Título: {$cita->titulo}\n";
                $mensaje .= "Fecha de inicio: {$fechaInicio}\n";
                
                if ($fechaFin) {
                    $mensaje .= "Fecha de fin: {$fechaFin}\n";
                }
                
                if ($cita->ubicacion) {
                    $mensaje .= "Ubicación: {$cita->ubicacion}\n";
                }
                
                if ($cita->descripcion) {
                    $mensaje .= "Descripción: {$cita->descripcion}\n";
                }
                
                $mensaje .= "\nNota: Esta cita no tiene cliente asociado.";

                $webhookData['mensaje'] = $mensaje;
            }

            // Enviar webhook
            $this->n8nService->sendWebhook($this->webhookUrl, $webhookData);

        } catch (\Exception $e) {
            // Log del error pero no interrumpir la creación de la cita
            Log::error('Error al enviar webhook de cita creada', [
                'cita_id' => $cita->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Handle the Cita "updated" event.
     */
    public function updated(Cita $cita): void
    {
        //
    }

    /**
     * Handle the Cita "deleted" event.
     */
    public function deleted(Cita $cita): void
    {
        //
    }

    /**
     * Handle the Cita "restored" event.
     */
    public function restored(Cita $cita): void
    {
        //
    }

    /**
     * Handle the Cita "force deleted" event.
     */
    public function forceDeleted(Cita $cita): void
    {
        //
    }
}

