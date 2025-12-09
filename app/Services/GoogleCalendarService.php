<?php

namespace App\Services;

use App\Models\Cita;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected string $apiKey;
    protected string $calendarId;
    protected ?Google_Client $client = null;
    protected ?Google_Service_Calendar $service = null;

    public function __construct()
    {
        $this->apiKey = (string) (config('services.google.calendar_api_key') ?? '');
        $this->calendarId = (string) (config('services.google.calendar_id') ?? 'primary');
    }

    /**
     * Obtener cliente de Google con OAuth2
     */
    protected function getClient(): ?Google_Client
    {
        if ($this->client !== null) {
            return $this->client;
        }

        try {
            $client = new Google_Client();
            
            // Si hay credenciales OAuth2 configuradas, usarlas
            $credentialsPath = config('services.google.credentials_path');
            $accessToken = $this->getStoredAccessToken();
            
            if ($credentialsPath && file_exists($credentialsPath)) {
                $client->setAuthConfig($credentialsPath);
            } elseif ($this->apiKey) {
                // Fallback a API key para calendarios públicos
                $client->setDeveloperKey($this->apiKey);
            } else {
                Log::warning('No hay credenciales de Google Calendar configuradas');
                return null;
            }

            // Configurar scopes necesarios (lectura y escritura)
            $client->addScope(Google_Service_Calendar::CALENDAR);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            
            // Configurar redirect URI
            $redirectUri = route('google-calendar.callback');
            $client->setRedirectUri($redirectUri);

            // Si hay un token de acceso guardado, usarlo
            if ($accessToken) {
                $client->setAccessToken($accessToken);
                
                // Si el token expiró, refrescarlo
                if ($client->isAccessTokenExpired()) {
                    $refreshToken = $client->getRefreshToken();
                    if ($refreshToken) {
                        $client->fetchAccessTokenWithRefreshToken($refreshToken);
                        $this->saveAccessToken($client->getAccessToken());
                    }
                }
            }

            $this->client = $client;
            return $this->client;
        } catch (\Exception $e) {
            Log::error('Error inicializando Google Client: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener URL de autorización
     */
    public function getAuthUrl(): ?string
    {
        try {
            $client = new Google_Client();
            $credentialsPath = config('services.google.credentials_path');
            
            if (!$credentialsPath) {
                Log::error('GOOGLE_CREDENTIALS_PATH no está configurado');
                return null;
            }
            
            if (!file_exists($credentialsPath)) {
                Log::error('Archivo de credenciales no encontrado en: ' . $credentialsPath);
                Log::error('Ruta absoluta esperada: ' . storage_path('app/google-credentials.json'));
                return null;
            }
            
            if (!is_readable($credentialsPath)) {
                Log::error('Archivo de credenciales no es legible: ' . $credentialsPath);
                return null;
            }
            
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Google_Service_Calendar::CALENDAR);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $client->setRedirectUri(route('google-calendar.callback'));
            
            return $client->createAuthUrl();
        } catch (\Exception $e) {
            Log::error('Error generando URL de autorización: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Manejar callback de OAuth2
     */
    public function handleCallback(string $code): bool
    {
        try {
            $client = new Google_Client();
            $credentialsPath = config('services.google.credentials_path');
            
            if (!$credentialsPath || !file_exists($credentialsPath)) {
                return false;
            }
            
            $client->setAuthConfig($credentialsPath);
            $client->setRedirectUri(route('google-calendar.callback'));
            
            $token = $client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                Log::error('Error obteniendo token: ' . $token['error']);
                return false;
            }
            
            $this->saveAccessToken($token);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error en callback de OAuth2: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener token guardado
     */
    protected function getStoredAccessToken(): ?array
    {
        $tokenPath = storage_path('app/google-calendar-token.json');
        
        if (file_exists($tokenPath)) {
            $token = json_decode(file_get_contents($tokenPath), true);
            return $token;
        }
        
        return null;
    }

    /**
     * Guardar token de acceso
     */
    protected function saveAccessToken(array $token): void
    {
        $tokenPath = storage_path('app/google-calendar-token.json');
        file_put_contents($tokenPath, json_encode($token));
    }

    /**
     * Verificar si está autorizado
     */
    public function isAuthorized(): bool
    {
        $token = $this->getStoredAccessToken();
        return $token !== null && !empty($token['access_token']);
    }

    /**
     * Obtener servicio de Google Calendar
     */
    protected function getService(): ?Google_Service_Calendar
    {
        if ($this->service !== null) {
            return $this->service;
        }

        $client = $this->getClient();
        if (!$client) {
            return null;
        }

        $this->service = new Google_Service_Calendar($client);
        return $this->service;
    }

    /**
     * Crear un evento en Google Calendar
     */
    public function createEvent(Cita $cita): ?string
    {
        try {
            $service = $this->getService();
            if (!$service) {
                Log::warning('No se pudo obtener el servicio de Google Calendar');
                return null;
            }

            $event = new \Google_Service_Calendar_Event();
            $event->setSummary($cita->titulo);
            
            if ($cita->descripcion) {
                $event->setDescription($cita->descripcion);
            }
            
            if ($cita->ubicacion) {
                $event->setLocation($cita->ubicacion);
            }

            $start = new \Google_Service_Calendar_EventDateTime();
            $start->setDateTime($cita->fecha_inicio->format(\DateTime::RFC3339));
            $start->setTimeZone(config('app.timezone', 'America/Mexico_City'));
            $event->setStart($start);

            $end = new \Google_Service_Calendar_EventDateTime();
            $fechaFin = $cita->fecha_fin ?? $cita->fecha_inicio->copy()->addHour();
            $end->setDateTime($fechaFin->format(\DateTime::RFC3339));
            $end->setTimeZone(config('app.timezone', 'America/Mexico_City'));
            $event->setEnd($end);

            $createdEvent = $service->events->insert($this->calendarId, $event);
            
            return $createdEvent->getId();
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
        try {
            if (empty($cita->google_event_id)) {
                // Si no tiene ID, crear uno nuevo
                $eventId = $this->createEvent($cita);
                if ($eventId) {
                    $cita->google_event_id = $eventId;
                    return $cita->save();
                }
                return false;
            }

            $service = $this->getService();
            if (!$service) {
                Log::warning('No se pudo obtener el servicio de Google Calendar');
                return false;
            }

            // Obtener el evento existente
            $event = $service->events->get($this->calendarId, $cita->google_event_id);
            
            // Actualizar los campos
            $event->setSummary($cita->titulo);
            
            if ($cita->descripcion) {
                $event->setDescription($cita->descripcion);
            } else {
                $event->setDescription('');
            }
            
            if ($cita->ubicacion) {
                $event->setLocation($cita->ubicacion);
            } else {
                $event->setLocation('');
            }

            $start = new \Google_Service_Calendar_EventDateTime();
            $start->setDateTime($cita->fecha_inicio->format(\DateTime::RFC3339));
            $start->setTimeZone(config('app.timezone', 'America/Mexico_City'));
            $event->setStart($start);

            $end = new \Google_Service_Calendar_EventDateTime();
            $fechaFin = $cita->fecha_fin ?? $cita->fecha_inicio->copy()->addHour();
            $end->setDateTime($fechaFin->format(\DateTime::RFC3339));
            $end->setTimeZone(config('app.timezone', 'America/Mexico_City'));
            $event->setEnd($end);

            $service->events->update($this->calendarId, $cita->google_event_id, $event);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error al actualizar evento en Google Calendar: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar un evento de Google Calendar
     */
    public function deleteEvent(Cita $cita): bool
    {
        if (empty($cita->google_event_id)) {
            return true;
        }

        try {
            $service = $this->getService();
            if (!$service) {
                Log::warning('No se pudo obtener el servicio de Google Calendar');
                // Limpiar el ID localmente
                $cita->google_event_id = null;
                return $cita->save();
            }

            $service->events->delete($this->calendarId, $cita->google_event_id);
            
            // Limpiar el ID localmente
            $cita->google_event_id = null;
            return $cita->save();
        } catch (\Exception $e) {
            Log::error('Error al eliminar evento de Google Calendar: ' . $e->getMessage());
            // Limpiar el ID localmente aunque falle
            $cita->google_event_id = null;
            return $cita->save();
        }
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
        try {
            // Intentar usar Google Client con OAuth2 primero
            $service = $this->getService();
            if ($service) {
                return $this->getEventsWithOAuth($service, $timeMin, $timeMax);
            }

            // Fallback a API key si está configurada (solo para calendarios públicos)
            if ($this->apiKey) {
                return $this->getEventsWithApiKey($timeMin, $timeMax);
            }

            Log::warning('No hay credenciales de Google Calendar configuradas');
            return [];
        } catch (\Exception $e) {
            Log::error('Error al obtener eventos de Google Calendar: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener eventos usando OAuth2 (Google Client)
     */
    protected function getEventsWithOAuth(Google_Service_Calendar $service, \DateTime $timeMin = null, \DateTime $timeMax = null): array
    {
        try {
            // Si no se proporcionan fechas, usar el mes actual
            if (!$timeMin) {
                $timeMin = now()->startOfMonth();
            }
            if (!$timeMax) {
                $timeMax = now()->endOfMonth()->endOfDay();
            }

            $optParams = [
                'maxResults' => 2500,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => $timeMin->format(\DateTime::RFC3339),
                'timeMax' => $timeMax->format(\DateTime::RFC3339),
            ];

            $results = $service->events->listEvents($this->calendarId, $optParams);
            $events = $results->getItems();

            $eventsArray = [];
            foreach ($events as $event) {
                $eventsArray[] = [
                    'id' => $event->getId(),
                    'summary' => $event->getSummary(),
                    'description' => $event->getDescription(),
                    'location' => $event->getLocation(),
                    'start' => [
                        'dateTime' => $event->getStart()->getDateTime(),
                        'date' => $event->getStart()->getDate(),
                    ],
                    'end' => [
                        'dateTime' => $event->getEnd()->getDateTime(),
                        'date' => $event->getEnd()->getDate(),
                    ],
                ];
            }

            return $eventsArray;
        } catch (\Exception $e) {
            Log::error('Error obteniendo eventos con OAuth2: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener eventos usando API key (solo calendarios públicos)
     */
    protected function getEventsWithApiKey(\DateTime $timeMin = null, \DateTime $timeMax = null): array
    {
        try {
            // Si no se proporcionan fechas, usar el mes actual
            if (!$timeMin) {
                $timeMin = now()->startOfMonth();
            }
            if (!$timeMax) {
                $timeMax = now()->endOfMonth()->endOfDay();
            }

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
                'maxResults' => 2500,
            ];

            $response = Http::get($url, $params);

            if (!$response->successful()) {
                Log::error('Error al obtener eventos con API key: ' . $response->status() . ' - ' . $response->body());
                return [];
            }

            $data = $response->json();
            return $data['items'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error obteniendo eventos con API key: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Convertir evento de Google Calendar a formato Cita
     */
    public function convertGoogleEventToCita(array $googleEvent): ?array
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

