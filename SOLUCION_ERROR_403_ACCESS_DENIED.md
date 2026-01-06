# Solución Error 403: access_denied

## Problema
Google está bloqueando el acceso porque la aplicación OAuth está en modo de prueba y el email `websolutionscrnow@gmail.com` no está autorizado como usuario de prueba.

## Solución: Agregar usuarios de prueba en Google Cloud Console

### Pasos:

1. **Ir a Google Cloud Console**
   - Ve a: https://console.cloud.google.com/
   - Selecciona el proyecto: `responsive-task-480005-c8`

2. **Navegar a la configuración de OAuth**
   - En el menú lateral, ve a **APIs & Services** > **OAuth consent screen**
   - O directamente: https://console.cloud.google.com/apis/credentials/consent?project=responsive-task-480005-c8

3. **Agregar usuarios de prueba**
   - En la sección **Test users** (Usuarios de prueba)
   - Haz clic en **+ ADD USERS** (Agregar usuarios)
   - Agrega el email: `websolutionscrnow@gmail.com`
   - Haz clic en **ADD** (Agregar)

4. **Guardar cambios**
   - Los cambios se guardan automáticamente

5. **Intentar de nuevo**
   - Vuelve a la página del calendario
   - Haz clic en "Conectar Google Calendar"
   - Ahora deberías poder autorizar con `websolutionscrnow@gmail.com`

## Nota importante

Si también quieres que otros usuarios puedan usar la aplicación, necesitarás agregarlos como usuarios de prueba, o bien:

- **Verificar la aplicación**: Esto permite que cualquier usuario de Google use la app, pero requiere un proceso de verificación más largo con Google
- **Mantener en modo de prueba**: Solo los usuarios agregados como "Test users" podrán usar la app

## Usuarios que deberías agregar como Test Users:

- `websolutionscrnow@gmail.com` (principal)
- Cualquier otro email que necesite acceso a la aplicación

## Verificar que está configurado correctamente

Después de agregar el usuario de prueba:
1. Ve a la página del calendario
2. Haz clic en "Conectar Google Calendar"
3. Deberías ver la pantalla de autorización de Google
4. Selecciona `websolutionscrnow@gmail.com` si tienes múltiples cuentas
5. Autoriza los permisos
6. Deberías ser redirigido de vuelta y ver "Google Calendar ha sido autorizado correctamente"

