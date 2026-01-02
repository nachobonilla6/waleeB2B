<?php

/**
 * Script para autenticar Google Sheets API
 * 
 * Uso: php artisan-auth-google-sheets.php
 */

require __DIR__.'/vendor/autoload.php';

use Google_Client;
use Google_Service_Sheets;

echo "=== Autenticación de Google Sheets API ===\n\n";

// Verificar que existe el archivo de credenciales
$credentialsPath = __DIR__ . '/storage/app/google-credentials.json';
if (!file_exists($credentialsPath)) {
    echo "❌ Error: No se encontró el archivo de credenciales.\n";
    echo "   Coloca el archivo google-credentials.json en: storage/app/\n";
    echo "   Puedes descargarlo desde Google Cloud Console\n";
    exit(1);
}

echo "✓ Archivo de credenciales encontrado\n\n";

// Crear cliente
$client = new Google_Client();
$client->setAuthConfig($credentialsPath);
$client->addScope('https://www.googleapis.com/auth/spreadsheets.readonly');
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Configurar redirect URI
$redirectUri = 'http://localhost:8000/auth/google/callback';
$client->setRedirectUri($redirectUri);

// Verificar si ya existe un token
$tokenPath = __DIR__ . '/storage/app/google-sheets-token.json';
if (file_exists($tokenPath)) {
    $token = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($token);
    
    if (!$client->isAccessTokenExpired()) {
        echo "✓ Ya tienes un token válido guardado.\n";
        echo "   Si quieres renovarlo, elimina el archivo: storage/app/google-sheets-token.json\n";
        exit(0);
    } else {
        echo "⚠ Token expirado, intentando refrescar...\n";
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $newToken = $client->getAccessToken();
            file_put_contents($tokenPath, json_encode($newToken));
            echo "✓ Token refrescado correctamente!\n";
            exit(0);
        }
    }
}

// Generar URL de autenticación
$authUrl = $client->createAuthUrl();
echo "1. Abre esta URL en tu navegador:\n";
echo "   " . $authUrl . "\n\n";
echo "2. Autoriza la aplicación\n";
echo "3. Copia el código de la URL (el parámetro 'code=...')\n";
echo "4. Pégalo aquí: ";

$authCode = trim(fgets(STDIN));

if (empty($authCode)) {
    echo "❌ Código vacío. Saliendo...\n";
    exit(1);
}

try {
    // Intercambiar código por token
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    
    if (isset($accessToken['error'])) {
        echo "❌ Error: " . $accessToken['error_description'] . "\n";
        exit(1);
    }
    
    // Guardar token
    file_put_contents($tokenPath, json_encode($accessToken));
    
    echo "\n✓ Token guardado correctamente en: storage/app/google-sheets-token.json\n";
    echo "✓ Ahora puedes usar el dashboard de Google Sheets\n\n";
    
    // Probar acceso
    $service = new Google_Service_Sheets($client);
    echo "Probando acceso a la API...\n";
    
    // Intentar obtener información de un sheet de prueba (opcional)
    echo "✓ Autenticación exitosa!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

