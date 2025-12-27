# Configuración de Google Calendar para otra cuenta

Para usar otro calendario de otra cuenta de Google, necesitas cambiar las siguientes variables en tu archivo `.env`:

## Variables a cambiar:

1. **GOOGLE_CLIENT_ID** - ID del cliente OAuth2 de la nueva cuenta
2. **GOOGLE_CLIENT_SECRET** - Secret del cliente OAuth2 de la nueva cuenta
3. **GOOGLE_CREDENTIALS_PATH** - Ruta al archivo JSON de credenciales (opcional, si usas archivo)
4. **GOOGLE_CALENDAR_ID** - ID del calendario específico (opcional, si no quieres usar 'primary')

## Pasos:

1. **Obtener credenciales OAuth2 de la nueva cuenta:**
   - Ve a [Google Cloud Console](https://console.cloud.google.com/)
   - Crea un nuevo proyecto o selecciona uno existente
   - Habilita la API de Google Calendar
   - Crea credenciales OAuth2 (Tipo: Aplicación web)
   - Configura las URIs de redirección autorizadas:
     - `http://tu-dominio.com/google-calendar/callback`
     - `https://tu-dominio.com/google-calendar/callback`

2. **Actualizar el .env:**
   ```env
   GOOGLE_CLIENT_ID=tu-nuevo-client-id
   GOOGLE_CLIENT_SECRET=tu-nuevo-client-secret
   GOOGLE_CREDENTIALS_PATH=storage/app/google-credentials-nueva-cuenta.json
   GOOGLE_CALENDAR_ID=primary
   ```

3. **Si usas archivo de credenciales JSON:**
   - Descarga el archivo JSON de credenciales desde Google Cloud Console
   - Guárdalo en `storage/app/google-credentials-nueva-cuenta.json`
   - Actualiza `GOOGLE_CREDENTIALS_PATH` con la ruta completa

4. **Reautorizar la nueva cuenta:**
   - Ve a `/admin/google-calendar-auth` en tu aplicación
   - Haz clic en "Autorizar Google Calendar"
   - Inicia sesión con la nueva cuenta de Google
   - Autoriza el acceso

5. **Obtener el ID del calendario específico (opcional):**
   - Ejecuta: `php artisan google-calendar:list`
   - Esto mostrará todos los calendarios disponibles
   - Copia el ID del calendario que quieres usar
   - Actualiza `GOOGLE_CALENDAR_ID` en el .env con ese ID

## Nota importante:

- El token de acceso se guarda en `storage/app/google-calendar-token.json`
- Si cambias de cuenta, deberías eliminar este archivo para forzar una nueva autorización
- El sistema buscará automáticamente un calendario llamado "WEBSOLUTIONS-TEST" si no especificas un ID

