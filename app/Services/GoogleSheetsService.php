<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GoogleSheetsService
{
    protected ?Google_Client $client = null;
    protected ?Google_Service_Sheets $service = null;

    public function __construct()
    {
        // Constructor vacío, inicializamos cuando sea necesario
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
            $credentialsPath = config('services.google.credentials_path');
            $accessToken = $this->getStoredAccessToken();

            if (!$credentialsPath || !file_exists($credentialsPath)) {
                Log::error('GOOGLE_CREDENTIALS_PATH no está configurado o no existe');
                return null;
            }

            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/spreadsheets.readonly');
            $client->addScope('https://www.googleapis.com/auth/spreadsheets');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            // Si hay un token de acceso guardado, usarlo
            if ($accessToken) {
                $client->setAccessToken($accessToken);

                // Si el token expiró, refrescarlo
                if ($client->isAccessTokenExpired()) {
                    $refreshToken = $client->getRefreshToken();
                    if ($refreshToken) {
                        $client->fetchAccessTokenWithRefreshToken($refreshToken);
                        $this->saveAccessToken($client->getAccessToken());
                    } else {
                        Log::error('Token expirado y no hay refresh token');
                        return null;
                    }
                }
            } else {
                Log::error('No hay token de acceso guardado para Google Sheets');
                return null;
            }

            $this->client = $client;
            return $this->client;
        } catch (\Exception $e) {
            Log::error('Error inicializando Google Client para Sheets: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener servicio de Google Sheets
     */
    protected function getService(): ?Google_Service_Sheets
    {
        if ($this->service !== null) {
            return $this->service;
        }

        $client = $this->getClient();
        if (!$client) {
            return null;
        }

        $this->service = new Google_Service_Sheets($client);
        return $this->service;
    }

    /**
     * Obtener token guardado
     */
    protected function getStoredAccessToken(): ?array
    {
        $tokenPath = storage_path('app/google-sheets-token.json');
        
        if (file_exists($tokenPath)) {
            $token = json_decode(file_get_contents($tokenPath), true);
            return $token;
        }

        // Intentar usar el token de Gmail si existe
        $gmailTokenPath = storage_path('app/google-gmail-token.json');
        if (file_exists($gmailTokenPath)) {
            $token = json_decode(file_get_contents($gmailTokenPath), true);
            return $token;
        }

        return null;
    }

    /**
     * Guardar token de acceso
     */
    protected function saveAccessToken(array $token): void
    {
        $tokenPath = storage_path('app/google-sheets-token.json');
        file_put_contents($tokenPath, json_encode($token));
    }

    /**
     * Obtener datos de un Google Sheet
     * 
     * @param string $spreadsheetId ID del spreadsheet
     * @param string|null $range Rango de celdas (ej: 'Sheet1!A1:Z100' o 'A1:Z100')
     * @return array|null
     */
    public function getSheetData(string $spreadsheetId, ?string $range = null): ?array
    {
        // Si no se especifica rango, obtener toda la hoja
        if (!$range) {
            $range = 'A1:Z1000'; // Rango por defecto
        }

        // Primero intentar con OAuth2 (para sheets privados)
        try {
            $service = $this->getService();
            if ($service) {
                $response = $service->spreadsheets_values->get($spreadsheetId, $range);
                $values = $response->getValues();

                if (!empty($values)) {
                    return $values;
                }
            }
        } catch (\Exception $e) {
            Log::debug('Error con OAuth2, intentando API pública: ' . $e->getMessage());
        }

        // Si falla OAuth2 o no hay credenciales, intentar con exportación CSV (para sheets públicos)
        try {
            // Para sheets públicos, usar exportación CSV
            // Extraer el nombre de la hoja del rango si está especificado
            $sheetName = 'Sheet1'; // Por defecto
            if (strpos($range, '!') !== false) {
                $parts = explode('!', $range);
                $sheetName = $parts[0];
                $range = $parts[1] ?? 'A1:Z1000';
            }
            
            // URL de exportación CSV (funciona para sheets públicos)
            $url = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/gviz/tq?tqx=out:csv&sheet={$sheetName}";
            
            $response = Http::get($url);
            
            if ($response->successful()) {
                $csvData = $response->body();
                
                // Parsear CSV
                $lines = str_getcsv($csvData, "\n");
                $values = [];
                
                foreach ($lines as $line) {
                    if (!empty(trim($line))) {
                        $values[] = str_getcsv($line);
                    }
                }
                
                if (empty($values)) {
                    return [];
                }
                
                // Aplicar filtro de rango si es necesario (simplificado)
                // Por ahora, devolvemos todos los datos
                return $values;
            } else {
                // Si CSV falla, intentar con API v4 usando API key si está disponible
                $apiKey = config('services.google.calendar_api_key');
                if ($apiKey) {
                    $encodedRange = urlencode($range);
                    $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$encodedRange}?key={$apiKey}";
                    
                    $response = Http::get($url);
                    
                    if ($response->successful()) {
                        $data = $response->json();
                        $values = $data['values'] ?? [];
                        
                        if (empty($values)) {
                            return [];
                        }
                        
                        return $values;
                    }
                }
                
                Log::error('Error en exportación CSV de Google Sheets: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error obteniendo datos de Google Sheets (método público): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener información del spreadsheet
     */
    public function getSpreadsheetInfo(string $spreadsheetId): ?array
    {
        // Primero intentar con OAuth2
        try {
            $service = $this->getService();
            if ($service) {
                $spreadsheet = $service->spreadsheets->get($spreadsheetId);
                
                $sheets = [];
                foreach ($spreadsheet->getSheets() as $sheet) {
                    $sheets[] = [
                        'id' => $sheet->getProperties()->getSheetId(),
                        'title' => $sheet->getProperties()->getTitle(),
                    ];
                }

                return [
                    'title' => $spreadsheet->getProperties()->getTitle(),
                    'sheets' => $sheets,
                ];
            }
        } catch (\Exception $e) {
            Log::debug('Error obteniendo info con OAuth2, intentando API pública: ' . $e->getMessage());
        }

        // Si falla OAuth2, intentar con API pública
        try {
            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}";
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                $sheets = [];
                if (isset($data['sheets'])) {
                    foreach ($data['sheets'] as $sheet) {
                        $properties = $sheet['properties'] ?? [];
                        $sheets[] = [
                            'id' => $properties['sheetId'] ?? null,
                            'title' => $properties['title'] ?? 'Sin nombre',
                        ];
                    }
                }

                return [
                    'title' => $data['properties']['title'] ?? 'Sin título',
                    'sheets' => $sheets,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error obteniendo información del spreadsheet (API pública): ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Obtener URL de autorización
     */
    public function getAuthUrl(): ?string
    {
        try {
            $client = $this->getClient();
            if (!$client) {
                return null;
            }

            return $client->createAuthUrl();
        } catch (\Exception $e) {
            Log::error('Error generando URL de autorización: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verificar si está autenticado
     */
    public function isAuthenticated(): bool
    {
        $client = $this->getClient();
        return $client !== null;
    }

    /**
     * Actualizar una celda específica en el Google Sheet
     * 
     * @param string $spreadsheetId ID del spreadsheet
     * @param string $range Rango de la celda (ej: 'Sheet1!A1' o 'A1')
     * @param mixed $value Valor a escribir
     * @return bool
     */
    public function updateCell(string $spreadsheetId, string $range, $value): bool
    {
        try {
            $service = $this->getService();
            if (!$service) {
                Log::error('No se puede actualizar: servicio no disponible');
                return false;
            }

            // Preparar los datos para actualizar
            $body = new \Google_Service_Sheets_ValueRange([
                'values' => [[$value]]
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $service->spreadsheets_values->update(
                $spreadsheetId,
                $range,
                $body,
                $params
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Error actualizando celda en Google Sheets: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar una fila completa en el Google Sheet
     * 
     * @param string $spreadsheetId ID del spreadsheet
     * @param string $range Rango de la fila (ej: 'Sheet1!A1:Z1' o 'A1:Z1')
     * @param array $values Array de valores para la fila
     * @return bool
     */
    public function updateRow(string $spreadsheetId, string $range, array $values): bool
    {
        try {
            $service = $this->getService();
            if (!$service) {
                Log::error('No se puede actualizar: servicio no disponible');
                return false;
            }

            // Preparar los datos para actualizar
            $body = new \Google_Service_Sheets_ValueRange([
                'values' => [$values]
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $service->spreadsheets_values->update(
                $spreadsheetId,
                $range,
                $body,
                $params
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Error actualizando fila en Google Sheets: ' . $e->getMessage());
            return false;
        }
    }
}

