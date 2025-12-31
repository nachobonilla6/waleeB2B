<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tarea;
use App\Models\User;
use App\Mail\TareaNotificacionMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendTareaNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tareas:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía notificaciones por email automáticas para tareas pendientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $toleranceMinutes = 5; // Rango de tolerancia: ±5 minutos
        
        // Buscar tareas con notificaciones habilitadas, estado pendiente y que no hayan pasado
        $tareas = Tarea::where('notificacion_habilitada', true)
            ->where('estado', 'pending')
            ->whereNotNull('fecha_hora')
            ->where('fecha_hora', '>', $now)
            ->with('lista')
            ->get();
        
        $emailsEnviados = 0;
        
        foreach ($tareas as $tarea) {
            $fechaNotificacion = $this->calcularFechaNotificacion($tarea);
            
            if (!$fechaNotificacion) {
                continue;
            }
            
            // Verificar si la notificación debe enviarse ahora (dentro del rango de tolerancia)
            $diferenciaMinutos = abs($now->diffInMinutes($fechaNotificacion, false));
            
            if ($diferenciaMinutos <= $toleranceMinutes && $fechaNotificacion->lte($now)) {
                // Verificar si ya se envió esta notificación (usando cache)
                $cacheKey = "tarea_notificacion_enviada_{$tarea->id}";
                
                if (!\Cache::has($cacheKey)) {
                    $this->enviarEmail($tarea);
                    \Cache::put($cacheKey, true, now()->addHours(24)); // Marcar como enviada por 24 horas
                    $emailsEnviados++;
                }
            }
        }
        
        $this->info("Emails enviados: {$emailsEnviados}");
        
        return Command::SUCCESS;
    }
    
    /**
     * Calcula la fecha y hora en que debe enviarse la notificación
     */
    private function calcularFechaNotificacion(Tarea $tarea): ?Carbon
    {
        if (!$tarea->fecha_hora) {
            return null;
        }
        
        $fechaTarea = Carbon::parse($tarea->fecha_hora);
        
        if ($tarea->notificacion_tipo === 'relativa') {
            // Notificación relativa: X minutos antes de la tarea
            $minutosAntes = $tarea->notificacion_minutos_antes ?? 60;
            return $fechaTarea->copy()->subMinutes($minutosAntes);
        } elseif ($tarea->notificacion_tipo === 'especifica') {
            // Notificación específica: fecha y hora exacta
            if ($tarea->notificacion_fecha_hora) {
                return Carbon::parse($tarea->notificacion_fecha_hora);
            }
        }
        
        return null;
    }
    
    /**
     * Envía el email de notificación a todos los usuarios con email
     */
    private function enviarEmail(Tarea $tarea): void
    {
        $users = User::whereNotNull('email')
            ->where('email', '!=', '')
            ->get();
        
        if ($users->isEmpty()) {
            \Log::warning('No hay usuarios con email para enviar notificación de tarea');
            return;
        }
        
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new TareaNotificacionMail($tarea));
                \Log::info("Email de notificación de tarea enviado a: {$user->email}");
            } catch (\Exception $e) {
                \Log::error('Error enviando email de notificación de tarea a usuario ' . $user->id . ' (' . $user->email . '): ' . $e->getMessage());
            }
        }
    }
}
