# Configuración de Google Calendar en Hostinger

## Pasos para configurar en el servidor

### 1. Subir el archivo de credenciales

El archivo `storage/app/google-credentials.json` debe estar en el servidor. Si no está, créalo con este contenido:

```json
{
  "web": {
    "client_id": "139552047075-9sgc1tqe90h8bv6rdpbs23b2a7btrkgp.apps.googleusercontent.com",
    "project_id": "websolutions-calendar",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "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",
    "redirect_uris": [
      "https://websolutions.work/google-calendar/callback",
      "http://localhost/google-calendar/callback"
    ]
  }
}
```

**Ubicación en el servidor:** `/home/u655097049/domains/websolutions.work/storage/app/google-credentials.json`

### 2. Instalar dependencias

Ejecuta en el servidor:
```bash
cd /home/u655097049/domains/websolutions.work
composer install
```

Esto instalará el paquete `google/apiclient` que es necesario.

### 3. Configurar Redirect URI en Google Cloud Console

1. Ve a https://console.cloud.google.com/
2. Selecciona tu proyecto
3. Ve a "APIs y servicios" → "Credenciales"
4. Haz clic en tu OAuth 2.0 Client ID (el que tiene el Client ID: 139552047075-...)
5. En "URIs de redirección autorizados", agrega:
   ```
   https://websolutions.work/google-calendar/callback
   ```
6. Guarda los cambios

### 4. Variables de entorno (.env)

No necesitas agregar variables adicionales al .env porque todo está en el archivo JSON. Pero si quieres personalizar, puedes agregar:

```env
GOOGLE_CALENDAR_ID=primary
```

(El ID del calendario, por defecto es "primary" que es el calendario principal)

### 5. Permisos de archivos

Asegúrate de que el directorio storage tenga los permisos correctos:
```bash
chmod -R 775 storage
chown -R u655097049:u655097049 storage
```

### 6. Limpiar caché

Después de subir los archivos, ejecuta:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Cómo usar

1. Ve a https://websolutions.work/admin
2. En el menú lateral, busca "Autorizar Google Calendar" (en el grupo "Configuración")
3. Haz clic en "Autorizar con Google Calendar"
4. Se abrirá Google para autorizar
5. Autoriza y serás redirigido de vuelta
6. El token se guardará automáticamente en `storage/app/google-calendar-token.json`

## Notas importantes

- El archivo `google-credentials.json` contiene información sensible, NO debe estar en git (ya está en .gitignore)
- El archivo `google-calendar-token.json` se genera automáticamente después de autorizar
- Si el token expira, se refrescará automáticamente usando el refresh token

