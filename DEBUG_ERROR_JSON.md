# 🔍 Debug: Error "Unexpected token '<', "<!DOCTYPE "... is not valid JSON"

## Problema
El servidor está devolviendo HTML en lugar de JSON cuando se intenta crear un evento.

## Pasos para Debug

### 1. Revisar los logs del servidor de producción

En el servidor, ejecuta:
```bash
tail -n 100 storage/logs/laravel.log | grep -i "calendario\|aplicaciones\|crear\|evento\|Error\|Exception"
```

### 2. Revisar la consola del navegador

1. Abre las herramientas de desarrollador (F12)
2. Ve a la pestaña "Network" (Red)
3. Intenta crear un evento
4. Busca la petición a `/walee-calendario-aplicaciones/crear`
5. Haz clic en ella y revisa:
   - **Status Code**: ¿Es 200, 401, 422, 500?
   - **Response**: ¿Qué está devolviendo exactamente?
   - **Headers**: ¿El Content-Type es `application/json`?

### 3. Verificar autenticación

El error puede ser que no estés autenticado y Laravel esté redirigiendo a la página de login (que es HTML).

Verifica que:
- Estés logueado en la aplicación
- La sesión no haya expirado
- El token CSRF sea válido

### 4. Verificar la ruta

Asegúrate de que la ruta esté correctamente configurada:
```bash
php artisan route:list | grep "calendario-aplicaciones"
```

### 5. Probar directamente con curl

En el servidor, prueba la ruta directamente:
```bash
curl -X POST https://websolutions.work/walee-calendario-aplicaciones/crear \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: TU_TOKEN" \
  -H "Cookie: TU_SESION" \
  -d '{
    "titulo": "Test",
    "fecha_inicio": "2026-01-06T10:00:00",
    "descripcion": "Test",
    "invitado_email": "test@example.com"
  }'
```

## Soluciones Comunes

### Si el error es 401 (Unauthorized)
- Verifica que estés autenticado
- Revisa el middleware de autenticación

### Si el error es 422 (Validation Error)
- Verifica que todos los campos requeridos estén presentes
- Revisa el formato de la fecha (debe ser ISO 8601)

### Si el error es 500 (Server Error)
- Revisa los logs del servidor
- Verifica que todas las dependencias estén instaladas
- Verifica que Google Calendar Service esté configurado correctamente

## Código Actualizado

El código ya tiene manejo de errores mejorado para devolver siempre JSON. Si el problema persiste, puede ser:

1. **Error antes de llegar al código**: Middleware, autenticación, etc.
2. **Error en el servidor de producción**: Versión diferente del código
3. **Problema de caché**: El servidor está usando código antiguo

## Acción Inmediata

1. Haz `git pull` en el servidor para obtener los últimos cambios
2. Limpia el caché: `php artisan cache:clear && php artisan config:clear && php artisan route:clear`
3. Revisa los logs del servidor
4. Prueba crear un evento nuevamente

