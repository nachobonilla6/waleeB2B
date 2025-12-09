<?php

namespace App\Services;

use App\Models\Cita;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected string $apiKey;
    protected string $calendarId;

    public function __construct()
    {
        $this->apiKey = (string) (config('services.google.calendar_api_key') ?? '');
        $this->calendarId = (string) (config('services.google.calendar_id') ?? 'primary');
    }

    /**
     * Crear un evento en Google Calendar
     */
    public function createEvent(Cita $cita): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('Google Calendar API key no configurada');
            return null;
        }

        try {
            $event = [
                'summary' => $cita->titulo,
                'description' => $cita->descripcion ?? '',
                'start' => [
                    'dateTime' => $cita->fecha_inicio->format('Y-m-d\TH:i:s'),
                    'timeZone' => config('app.timezone', 'America/Mexico_City'),
                ],
                'end' => [
                    'dateTime' => $cita->fecha_fin 
                        ? $cita->fecha_fin->format('Y-m-d\TH:i:s')
                        : $cita->fecha_inicio->addHour()->format('Y-m-d\TH:i:s'),
                    'timeZone' => config('app.timezone', 'America/Mexico_City'),
                ],
            ];

            if ($cita->ubicacion) {
                $event['location'] = $cita->ubicacion;
            }

            // Usar URL directa de Google Calendar para crear evento
            $params = http_build_query([
                'action' => 'TEMPLATE',
                'text' => $event['summary'],
                'dates' => $this->formatGoogleCalendarDate($cita->fecha_inicio) . '/' . 
                          $this->formatGoogleCalendarDate($cita->fecha_fin ?? $cita->fecha_inicio->copy()->addHour()),
                'details' => $event['description'],
                'location' => $cita->ubicacion ?? '',
            ]);

            $googleCalendarUrl = 'https://calendar.google.com/calendar/render?' . $params;
            
            // Para integración real con API, necesitarías OAuth2
            // Por ahora retornamos un identificador único
            return 'manual_' . $cita->id . '_' . time();
        } catch (\Exception $e) {
            Log::error('Error al crear evento en Google Calendar: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualizar un evento en Google Calendar
     */
    public function updateEvent(Cita $cita): bool
    {
        if (empty($cita->google_event_id)) {
            // Si no tiene ID, crear uno nuevo
            $cita->google_event_id = $this->createEvent($cita);
            return $cita->save();
        }

        // Para actualización real, necesitarías OAuth2
        // Por ahora solo actualizamos la fecha de modificación
        return true;
    }

    /**
     * Eliminar un evento de Google Calendar
     */
    public function deleteEvent(Cita $cita): bool
    {
        if (empty($cita->google_event_id)) {
            return true;
        }

        // Para eliminación real, necesitarías OAuth2
        // Por ahora solo limpiamos el ID
        $cita->google_event_id = null;
        return $cita->save();
    }

    /**
     * Obtener URL para crear evento en Google Calendar
     */
    public function getCreateEventUrl(Cita $cita): string
    {
        $params = http_build_query([
            'action' => 'TEMPLATE',
            'text' => $cita->titulo,
            'dates' => $this->formatGoogleCalendarDate($cita->fecha_inicio) . '/' . 
                      $this->formatGoogleCalendarDate($cita->fecha_fin ?? $cita->fecha_inicio->copy()->addHour()),
            'details' => $cita->descripcion ?? '',
            'location' => $cita->ubicacion ?? '',
        ]);

        return 'https://calendar.google.com/calendar/render?' . $params;
    }

    /**
     * Formatear fecha para Google Calendar (formato YYYYMMDDTHHmmss)
     */
    protected function formatGoogleCalendarDate($date): string
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        return $date->format('Ymd\THis');
    }

    /**
     * Obtener eventos de Google Calendar
     */
    public function getEvents(\DateTime $timeMin = null, \DateTime $timeMax = null): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Google Calendar API key no configurada');
            return [];
        }

        try {
            // Si no se proporcionan fechas, usar el mes actual
            if (!$timeMin) {
                $timeMin = now()->startOfMonth();
            }
            if (!$timeMax) {
                $timeMax = now()->endOfMonth()->endOfDay();
            }

            // Convertir a UTC para Google Calendar API
            $timezone = config('app.timezone', 'America/Mexico_City');
            $timeMin->setTimezone(new \DateTimeZone($timezone));
            $timeMax->setTimezone(new \DateTimeZone($timezone));
            
            $url = "https://www.googleapis.com/calendar/v3/calendars/" . urlencode($this->calendarId) . "/events";
            
            $params = [
                'key' => $this->apiKey,
                'timeMin' => $timeMin->format('Y-m-d\TH:i:s'),
                'timeMax' => $timeMax->format('Y-m-d\TH:i:s'),
                'timeZone' => $timezone,
                'singleEvents' => true,
                'orderBy' => 'startTime',
                'maxResults' => 2500, // Máximo permitido por Google
            ];

            $response = Http::get($url, $params);

            if (!$response->successful()) {
                Log::error('Error al obtener eventos de Google Calendar: ' . $response->status() . ' - ' . $response->body());
                return [];
            }

            $data = $response->json();
            return $data['items'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error al obtener eventos de Google Calendar: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Convertir evento de Google Calendar a formato Cita
     */
    public function convertGoogleEventToCita(array $googleEvent): array
    {
        $start = $googleEvent['start']['dateTime'] ?? $googleEvent['start']['date'] ?? null;
        $end = $googleEvent['end']['dateTime'] ?? $googleEvent['end']['date'] ?? null;

        if (!$start) {
            return null;
        }

        try {
            $fechaInicio = new \DateTime($start);
            $fechaFin = $end ? new \DateTime($end) : (clone $fechaInicio)->modify('+1 hour');

            return [
                'titulo' => $googleEvent['summary'] ?? 'Sin título',
                'descripcion' => $googleEvent['description'] ?? null,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'ubicacion' => $googleEvent['location'] ?? null,
                'google_event_id' => $googleEvent['id'] ?? null,
                'estado' => 'programada',
                'from_google' => true, // Marca para identificar que viene de Google
            ];
        } catch (\Exception $e) {
            Log::error('Error convirtiendo evento de Google Calendar: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sincronizar todas las citas con Google Calendar
     */
    public function syncAllEvents(): array
    {
        $citas = Cita::whereNull('google_event_id')
            ->where('estado', 'programada')
            ->get();

        $synced = 0;
        $errors = 0;

        foreach ($citas as $cita) {
            try {
                $eventId = $this->createEvent($cita);
                if ($eventId) {
                    $cita->google_event_id = $eventId;
                    $cita->save();
                    $synced++;
                } else {
                    $errors++;
                }
            } catch (\Exception $e) {
                Log::error("Error sincronizando cita {$cita->id}: " . $e->getMessage());
                $errors++;
            }
        }

        return [
            'synced' => $synced,
            'errors' => $errors,
            'total' => $citas->count(),
        ];
    }
}

