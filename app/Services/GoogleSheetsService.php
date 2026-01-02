<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Illuminate\Support\Facades\Log;

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
        try {
            $service = $this->getService();
            if (!$service) {
                return null;
            }

            // Si no se especifica rango, obtener toda la hoja
            if (!$range) {
                $range = 'A1:Z1000'; // Rango por defecto
            }

            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                return [];
            }

            return $values;
        } catch (\Exception $e) {
            Log::error('Error obteniendo datos de Google Sheets: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener información del spreadsheet
     */
    public function getSpreadsheetInfo(string $spreadsheetId): ?array
    {
        try {
            $service = $this->getService();
            if (!$service) {
                return null;
            }

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
        } catch (\Exception $e) {
            Log::error('Error obteniendo información del spreadsheet: ' . $e->getMessage());
            return null;
        }
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
}

