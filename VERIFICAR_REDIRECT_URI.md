# 🔍 Verificar Redirect URI para Google Calendar

## Problema: Error 400: redirect_uri_mismatch

Este error significa que la URL de redirección que tu aplicación está enviando a Google **NO coincide** con las URLs configuradas en Google Cloud Console.

## ✅ Solución Paso a Paso

### 1. Verificar qué URL está generando tu aplicación

Ejecuta este comando en tu servidor de producción:

```bash
php artisan tinker --execute="echo route('google-calendar.callback');"
```

Esto te mostrará la URL exacta que Laravel está generando.

### 2. Verificar APP_URL en .env

Asegúrate de que en tu archivo `.env` de producción tengas:

```env
APP_URL=https://websolutions.work
```

**NO** debe tener barra final (`/`) al final.

### 3. Agregar URLs en Google Cloud Console

Ve a: https://console.cloud.google.com/

1. Selecciona el proyecto: `nn888-475104`
2. Ve a: **APIs y servicios** > **Credenciales**
3. Abre tu cliente OAuth 2.0 (Client ID: `139552047075-v4had5pcv9qvk06sfas3n2putfstu6n5`)
4. En **"URIs de redirección autorizados"**, agrega **EXACTAMENTE** estas URLs (una por línea):

```
https://websolutions.work/google-calendar/callback
http://localhost:8000/google-calendar/callback
https://websolutions.work/auth/google/callback
http://localhost:8000/auth/google/callback
```

### 4. Verificar que NO haya diferencias

- ❌ **NO** agregues barras finales: `https://websolutions.work/google-calendar/callback/` (incorrecto)
- ✅ **SÍ** usa exactamente: `https://websolutions.work/google-calendar/callback` (correcto)
- ❌ **NO** uses `www`: `https://www.websolutions.work/...` (a menos que tu dominio lo use)
- ✅ Verifica mayúsculas/minúsculas (son case-sensitive)

### 5. Esperar a que se apliquen los cambios

Los cambios en Google Cloud Console pueden tardar **hasta 5 minutos** en aplicarse.

### 6. Verificar en los logs

Después de intentar conectar, revisa los logs de Laravel:

```bash
tail -f storage/logs/laravel.log | grep "Google Calendar OAuth"
```

Esto te mostrará qué URL se está enviando realmente.

## 🔧 URLs que DEBEN estar en Google Cloud Console

Basado en tu configuración actual, estas son las URLs que **DEBEN** estar configuradas:

```
https://websolutions.work/google-calendar/callback
http://localhost:8000/google-calendar/callback
https://websolutions.work/auth/google/callback
http://localhost:8000/auth/google/callback
```

## ⚠️ Errores Comunes

1. **Barras finales**: `https://websolutions.work/google-calendar/callback/` ❌
2. **www vs sin www**: Si tu APP_URL no tiene www, no lo agregues en Google Cloud Console
3. **Puerto incorrecto**: Asegúrate de usar el puerto correcto para localhost (8000)
4. **HTTP vs HTTPS**: Usa HTTPS para producción, HTTP solo para localhost

## 📝 Nota

El archivo `google-credentials.json` local puede tener las URLs, pero **Google Cloud Console es la fuente de verdad**. Las URLs deben coincidir exactamente en ambos lugares.

