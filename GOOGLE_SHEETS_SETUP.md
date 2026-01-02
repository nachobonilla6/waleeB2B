# Guía de Configuración de Google Sheets API

Esta guía te ayudará a configurar las credenciales de Google OAuth2 para poder leer y escribir en Google Sheets.

## Paso 1: Crear un Proyecto en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Nombra el proyecto (ej: "Walee Sheets API")

## Paso 2: Habilitar Google Sheets API

1. En el menú lateral, ve a **APIs & Services** > **Library**
2. Busca "Google Sheets API"
3. Haz clic en **Enable** para habilitar la API

## Paso 3: Crear Credenciales OAuth2

1. Ve a **APIs & Services** > **Credentials**
2. Haz clic en **Create Credentials** > **OAuth client ID**
3. Si es la primera vez, te pedirá configurar el consent screen:
   - Selecciona **External** (a menos que tengas Google Workspace)
   - Completa la información requerida:
     - App name: "Walee"
     - User support email: tu email
     - Developer contact: tu email
   - Haz clic en **Save and Continue**
   - En Scopes, haz clic en **Save and Continue**
   - En Test users, agrega tu email y haz clic en **Save and Continue**
   - Revisa y haz clic en **Back to Dashboard**

4. Ahora crea las credenciales OAuth2:
   - Application type: **Web application**
   - Name: "Walee Sheets Client"
   - Authorized redirect URIs: 
     - `http://localhost:8000/auth/google/callback` (para desarrollo)
     - `https://tu-dominio.com/auth/google/callback` (para producción)
   - Haz clic en **Create**

5. Descarga el archivo JSON de credenciales:
   - Se descargará un archivo como `client_secret_xxxxx.json`
   - Renómbralo a `google-credentials.json`

## Paso 4: Configurar en Laravel

1. Coloca el archivo `google-credentials.json` en `storage/app/`
   ```bash
   cp ~/Downloads/google-credentials.json storage/app/google-credentials.json
   ```

2. Actualiza tu archivo `.env`:
   ```env
   GOOGLE_CREDENTIALS_PATH=storage/app/google-credentials.json
   ```

## Paso 5: Obtener Token de Acceso (Primera vez)

Para obtener el token de acceso inicial, usa el script incluido en el proyecto:

```bash
php artisan-auth-google-sheets.php
```

El script te guiará paso a paso:
1. Te mostrará una URL para autorizar
2. Abre la URL en tu navegador
3. Autoriza la aplicación
4. Copia el código de la URL (el parámetro `code=...`)
5. Pégalo en el script

El token se guardará automáticamente en `storage/app/google-sheets-token.json`

**Nota**: Si ya tienes un token válido, el script te lo indicará. Si está expirado, intentará refrescarlo automáticamente.

### Si prefieres hacerlo manualmente:

Crea un archivo `auth-google-sheets.php` en la raíz del proyecto:

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Google_Client;
use Google_Service_Sheets;

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/storage/app/google-credentials.json');
$client->addScope('https://www.googleapis.com/auth/spreadsheets.readonly');
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

$authUrl = $client->createAuthUrl();
echo "Abre esta URL en tu navegador:\n";
echo $authUrl . "\n\n";
echo "Después de autorizar, copia el código de la URL y pégalo aquí: ";

$authCode = trim(fgets(STDIN));

$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
$client->setAccessToken($accessToken);

// Guardar token
file_put_contents(__DIR__ . '/storage/app/google-sheets-token.json', json_encode($accessToken));

echo "\nToken guardado correctamente!\n";
```

Ejecuta:
```bash
php auth-google-sheets.php
```

### Opción B: Usar la ruta de autenticación (si ya tienes una)

Si ya tienes una ruta de autenticación de Google configurada, úsala para obtener el token.

## Paso 6: Compartir el Google Sheet

Para que la aplicación pueda escribir en el sheet:

1. Abre tu Google Sheet
2. Haz clic en **Share** (Compartir)
3. En "Add people and groups", agrega el email asociado a tu proyecto de Google Cloud
   - Este email aparece en las credenciales OAuth2 que descargaste
   - Generalmente es algo como: `xxxxx@xxxxx.iam.gserviceaccount.com` (si usas service account)
   - O tu email personal si usas OAuth2 con tu cuenta
4. Dale permisos de **Editor**
5. Haz clic en **Send**

## Paso 7: Verificar Configuración

1. Verifica que el archivo de credenciales existe:
   ```bash
   ls -la storage/app/google-credentials.json
   ```

2. Verifica que el token existe (después de autenticarte):
   ```bash
   ls -la storage/app/google-sheets-token.json
   ```

3. Prueba accediendo al dashboard:
   ```
   /walee-sheets-dashboard?spreadsheet_id=TU_SHEET_ID
   ```

## Solución de Problemas

### Error: "No se puede actualizar: servicio no disponible"
- Verifica que `GOOGLE_CREDENTIALS_PATH` esté configurado correctamente
- Verifica que el archivo de credenciales existe y es legible
- Verifica que el token existe y no ha expirado

### Error: "Permission denied"
- Verifica que el Google Sheet esté compartido con la cuenta correcta
- Verifica que los permisos sean de "Editor" o "Owner"

### Error: "Token expirado"
- El token se refresca automáticamente si tienes un refresh token
- Si no funciona, vuelve a autenticarte usando el script de autenticación

### Para sheets públicos (solo lectura)
Si el sheet es público y solo quieres leerlo, no necesitas OAuth2. El sistema usará la API pública automáticamente. Sin embargo, para escribir necesitas OAuth2.

## Notas Importantes

- El token de acceso expira después de un tiempo, pero el refresh token permite renovarlo automáticamente
- Mantén seguros los archivos de credenciales y tokens
- No subas estos archivos a Git (deben estar en `.gitignore`)
- Para producción, considera usar variables de entorno o un servicio de gestión de secretos

