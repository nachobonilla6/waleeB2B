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

## Solución al Error 403: access_denied

Si recibes el error "Error 403: access_denied" que dice "websolutions.work no completó el proceso de verificación de Google", necesitas agregar el email como usuario de prueba:

### Pasos para agregar usuarios de prueba:

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Selecciona tu proyecto
3. Ve a **APIs & Services** > **OAuth consent screen**
4. En la sección **Test users**, haz clic en **+ ADD USERS**
5. Agrega el email: `websolutionscrnow@gmail.com`
6. Guarda los cambios
7. Intenta autorizar nuevamente desde `/admin/google-calendar-auth`

### Alternativa: Cambiar a modo de producción (requiere verificación)

Si quieres que cualquier usuario pueda acceder:
1. En **OAuth consent screen**, cambia el **Publishing status** a **In production**
2. **IMPORTANTE**: Esto requiere que Google verifique tu aplicación
3. Puede tomar varios días y requiere información adicional

**Recomendación**: Para desarrollo/pruebas, usa la opción de agregar usuarios de prueba.

## Resolver warnings de verificación del proyecto

Si ves warnings en la página de "Verificación del proyecto", necesitas resolverlos:

### 1. Verificación de la cuenta de facturación (Warning)

**Problema**: "Tu app no tiene una cuenta de Facturación de Cloud asociada"

**Solución**:
1. Ve a **Billing** en Google Cloud Console
2. Crea o vincula una cuenta de facturación
3. **Nota**: Google ofrece créditos gratuitos, pero necesitas una tarjeta de crédito asociada
4. Una vez vinculada, el warning debería desaparecer

### 2. Contactos del proyecto (Warning)

**Problema**: "Tu app no tiene la cantidad correcta de propietarios o editores de proyectos"

**Solución**:
1. Ve a **IAM & Admin** > **IAM** en Google Cloud Console
2. Asegúrate de tener al menos:
   - 1 propietario (Owner) del proyecto
   - 1 editor (Editor) del proyecto (puede ser la misma persona)
3. Si solo tienes 1 persona, agrégala con ambos roles:
   - Rol: **Owner** (Propietario)
   - Rol: **Editor** (Editor)
4. Guarda los cambios

### 3. Información de contacto (✅ Completado)

Si ya tienes el check verde, esta parte está resuelta. Si no:
1. Ve a **APIs & Services** > **OAuth consent screen**
2. Completa todos los campos requeridos:
   - Email de soporte
   - Dominio de la aplicación
   - Política de privacidad
   - Términos de servicio
3. Guarda los cambios

## Nota importante:

- El token de acceso se guarda en `storage/app/google-calendar-token.json`
- Si cambias de cuenta, deberías eliminar este archivo para forzar una nueva autorización
- El sistema buscará automáticamente un calendario llamado "WEBSOLUTIONS-TEST" si no especificas un ID

