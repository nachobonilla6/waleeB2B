<?php

namespace App\Services;

use App\Models\Client;
use App\Models\EmailRecibido;
use Google_Client;
use Google_Service_Gmail;
use Illuminate\Support\Facades\Log;

class GmailService
{
    protected ?Google_Client $client = null;
    protected ?Google_Service_Gmail $service = null;

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
            $client->addScope(Google_Service_Gmail::GMAIL_READONLY);
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
                Log::error('No hay token de acceso guardado para Gmail');
                return null;
            }

            $this->client = $client;
            return $this->client;
        } catch (\Exception $e) {
            Log::error('Error inicializando Google Client para Gmail: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener servicio de Gmail
     */
    protected function getService(): ?Google_Service_Gmail
    {
        if ($this->service !== null) {
            return $this->service;
        }

        $client = $this->getClient();
        if (!$client) {
            return null;
        }

        $this->service = new Google_Service_Gmail($client);
        return $this->service;
    }

    /**
     * Obtener token guardado
     */
    protected function getStoredAccessToken(): ?array
    {
        $tokenPath = storage_path('app/google-gmail-token.json');
        
        if (file_exists($tokenPath)) {
            $token = json_decode(file_get_contents($tokenPath), true);
            return $token;
        }

        // Intentar usar el token de calendar si existe
        $calendarTokenPath = storage_path('app/google-calendar-token.json');
        if (file_exists($calendarTokenPath)) {
            $token = json_decode(file_get_contents($calendarTokenPath), true);
            return $token;
        }
        
        return null;
    }

    /**
     * Guardar token de acceso
     */
    protected function saveAccessToken(array $token): void
    {
        $tokenPath = storage_path('app/google-gmail-token.json');
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
     * Obtener URL de autorización
     */
    public function getAuthUrl(): ?string
    {
        try {
            $client = new Google_Client();
            $credentialsPath = config('services.google.credentials_path');
            
            if (!$credentialsPath || !file_exists($credentialsPath)) {
                Log::error('GOOGLE_CREDENTIALS_PATH no está configurado');
                return null;
            }
            
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Google_Service_Gmail::GMAIL_READONLY);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $client->setRedirectUri(route('gmail.callback'));
            
            return $client->createAuthUrl();
        } catch (\Exception $e) {
            Log::error('Error generando URL de autorización Gmail: ' . $e->getMessage());
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
            $client->setRedirectUri(route('gmail.callback'));
            
            $token = $client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                Log::error('Error obteniendo token Gmail: ' . $token['error']);
                return false;
            }
            
            $this->saveAccessToken($token);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error en callback de OAuth2 Gmail: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extraer email de from_email (puede venir como "Nombre <email@example.com>" o solo "email@example.com")
     */
    protected function extractEmail(string $fromEmail): string
    {
        // Si contiene < >, extraer el email de adentro
        if (preg_match('/<(.+?)>/', $fromEmail, $matches)) {
            return strtolower(trim($matches[1]));
        }
        
        // Si no, usar el valor completo
        return strtolower(trim($fromEmail));
    }

    /**
     * Verificar si el email pertenece a un cliente en proceso
     */
    protected function belongsToClient(string $fromEmail): bool
    {
        $email = $this->extractEmail($fromEmail);
        
        // Obtener lista de emails de clientes_en_proceso
        $clientEmails = Client::whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->map(function($clientEmail) {
                return strtolower(trim($clientEmail));
            })
            ->toArray();
        
        return in_array($email, $clientEmails);
    }

    /**
     * Sincronizar emails desde Gmail
     */
    public function syncEmails(int $maxResults = 50): array
    {
        try {
            $service = $this->getService();
            if (!$service) {
                throw new \Exception('No se pudo inicializar el servicio de Gmail. Verifica la autorización.');
            }

            // Obtener lista de mensajes
            $messages = $service->users_messages->listUsersMessages('me', [
                'maxResults' => $maxResults,
                'q' => 'is:unread OR in:inbox', // Emails no leídos o en inbox
            ]);

            $syncedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            // Obtener emails de clientes_en_proceso para filtrar
            $clientEmails = Client::whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email')
                ->map(function($email) {
                    return strtolower(trim($email));
                })
                ->toArray();

            foreach ($messages->getMessages() as $message) {
                try {
                    // Obtener el mensaje completo
                    $msg = $service->users_messages->get('me', $message->getId(), ['format' => 'full']);
                    
                    $headers = $msg->getPayload()->getHeaders();
                    $fromEmail = '';
                    $fromName = null;
                    $subject = '';
                    $date = null;

                    // Extraer headers
                    foreach ($headers as $header) {
                        $name = strtolower($header->getName());
                        if ($name === 'from') {
                            $from = $header->getValue();
                            // Extraer nombre y email
                            if (preg_match('/^(.+?)\s*<(.+?)>$/', $from, $matches)) {
                                $fromName = trim($matches[1], '"\'');
                                $fromEmail = $matches[2];
                            } else {
                                $fromEmail = $from;
                            }
                        } elseif ($name === 'subject') {
                            $subject = $header->getValue();
                        } elseif ($name === 'date') {
                            $date = $header->getValue();
                        }
                    }

                    // Filtrar: solo guardar si el email pertenece a un cliente_en_proceso
                    $emailExtracted = $this->extractEmail($fromEmail);
                    if (!in_array($emailExtracted, $clientEmails)) {
                        $skippedCount++;
                        continue;
                    }

                    // Extraer cuerpo del mensaje
                    $body = '';
                    $bodyHtml = '';
                    $payload = $msg->getPayload();
                    
                    if ($payload->getBody() && $payload->getBody()->getData()) {
                        $body = base64_decode(str_replace(['-', '_'], ['+', '/'], $payload->getBody()->getData()));
                    }
                    
                    if ($parts = $payload->getParts()) {
                        foreach ($parts as $part) {
                            if ($part->getMimeType() === 'text/plain' && $part->getBody() && $part->getBody()->getData()) {
                                $body = base64_decode(str_replace(['-', '_'], ['+', '/'], $part->getBody()->getData()));
                            } elseif ($part->getMimeType() === 'text/html' && $part->getBody() && $part->getBody()->getData()) {
                                $bodyHtml = base64_decode(str_replace(['-', '_'], ['+', '/'], $part->getBody()->getData()));
                            }
                        }
                    }

                    // Verificar si el email ya existe
                    $messageId = $msg->getId();
                    $exists = EmailRecibido::where('message_id', $messageId)->exists();
                    
                    if ($exists) {
                        $skippedCount++;
                        continue;
                    }

                    // Crear el email recibido
                    EmailRecibido::create([
                        'message_id' => $messageId,
                        'uid' => $messageId,
                        'folder' => 'INBOX',
                        'from_email' => $fromEmail,
                        'from_name' => $fromName,
                        'subject' => $subject ?: 'Sin asunto',
                        'body' => $body,
                        'body_html' => $bodyHtml,
                        'attachments' => [],
                        'is_read' => false,
                        'is_starred' => false,
                        'received_at' => $date ? date('Y-m-d H:i:s', strtotime($date)) : now(),
                    ]);

                    $syncedCount++;
                } catch (\Exception $e) {
                    Log::error('Error procesando mensaje Gmail: ' . $e->getMessage());
                    $errorCount++;
                }
            }

            return [
                'success' => true,
                'synced' => $syncedCount,
                'skipped' => $skippedCount,
                'errors' => $errorCount,
                'total' => $syncedCount + $skippedCount + $errorCount,
            ];
        } catch (\Exception $e) {
            Log::error('Error sincronizando emails desde Gmail: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}

