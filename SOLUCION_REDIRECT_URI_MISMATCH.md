# 🔧 Solución: Error 400: redirect_uri_mismatch

## 📋 Pasos para Solucionar el Error

### Paso 1: Verificar qué URL está usando tu aplicación

En tu servidor de producción, ejecuta este comando:

```bash
php artisan tinker --execute="echo route('auth.google.callback');"
```

Esto te mostrará la URL exacta que Laravel está generando. Debería ser algo como:
- `https://websolutions.work/auth/google/callback`

### Paso 2: Agregar las URLs en Google Cloud Console

1. **Ve a Google Cloud Console:**
   - Abre: https://console.cloud.google.com/
   - Inicia sesión con tu cuenta de Google

2. **Selecciona el proyecto correcto:**
   - En la parte superior, haz clic en el selector de proyectos
   - Selecciona: `nn888-475104`

3. **Ve a Credenciales:**
   - En el menú lateral izquierdo, haz clic en **"APIs y servicios"**
   - Luego haz clic en **"Credenciales"**

4. **Abre tu cliente OAuth 2.0:**
   - Busca el cliente con este Client ID: `139552047075-v4had5pcv9qvk06sfas3n2putfstu6n5`
   - Haz clic en el lápiz (✏️) o en el nombre para editarlo

5. **Agrega las URLs de redirección:**
   - Busca la sección **"URIs de redirección autorizados"**
   - Haz clic en **"+ AGREGAR URI"** o **"+ ADD URI"**
   - Agrega **UNA POR UNA** estas URLs exactas (sin barras finales):

   ```
   https://websolutions.work/auth/google/callback
   ```

   Luego agrega esta segunda:

   ```
   https://websolutions.work/google-calendar/callback
   ```

   **IMPORTANTE:**
   - ✅ NO agregues barras finales (`/`)
   - ✅ NO uses `www` a menos que tu dominio lo use
   - ✅ Copia y pega exactamente como está arriba
   - ✅ Agrega ambas URLs (por si acaso)

6. **Guarda los cambios:**
   - Haz clic en **"GUARDAR"** o **"SAVE"** en la parte inferior

### Paso 3: Esperar a que se apliquen los cambios

- Los cambios en Google Cloud Console pueden tardar **2-5 minutos** en aplicarse
- Espera unos minutos antes de intentar conectar nuevamente

### Paso 4: Verificar en el servidor

1. **Asegúrate de tener los últimos cambios:**
   ```bash
   git pull origin main
   ```

2. **Limpia el caché:**
   ```bash
   php artisan route:clear
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Verifica la URL que se está generando:**
   ```bash
   php artisan tinker --execute="echo route('auth.google.callback');"
   ```

### Paso 5: Intentar conectar nuevamente

- Ve a tu página de calendario
- Haz clic en "Conectar Google Calendar"
- Debería funcionar ahora

---

## ⚠️ Errores Comunes

### ❌ Error: "redirect_uri_mismatch"

**Causas posibles:**
1. La URL no está agregada en Google Cloud Console
2. La URL tiene una diferencia (barra final, www, mayúsculas/minúsculas)
3. Los cambios aún no se han aplicado (espera 2-5 minutos)

**Solución:**
- Verifica que la URL en Google Cloud Console coincida **EXACTAMENTE** con la que genera Laravel
- Asegúrate de haber guardado los cambios en Google Cloud Console
- Espera unos minutos y vuelve a intentar

### ❌ La URL tiene una barra final

**Incorrecto:**
```
https://websolutions.work/auth/google/callback/
```

**Correcto:**
```
https://websolutions.work/auth/google/callback
```

### ❌ Usas www cuando no deberías

Si tu `APP_URL` en `.env` es `https://websolutions.work` (sin www), entonces:
- ✅ Usa: `https://websolutions.work/auth/google/callback`
- ❌ NO uses: `https://www.websolutions.work/auth/google/callback`

---

## 🔍 Verificar qué URL está usando la aplicación

Si quieres ver exactamente qué URL se está enviando a Google, revisa los logs:

```bash
tail -f storage/logs/laravel.log | grep "Google Calendar OAuth"
```

Esto mostrará la URL exacta que se está generando.

---

## 📝 Checklist Final

Antes de intentar conectar, verifica:

- [ ] Las URLs están agregadas en Google Cloud Console
- [ ] Las URLs NO tienen barras finales
- [ ] Las URLs coinciden exactamente (sin www si tu dominio no lo usa)
- [ ] Has guardado los cambios en Google Cloud Console
- [ ] Has esperado 2-5 minutos después de guardar
- [ ] Has hecho `git pull` en el servidor
- [ ] Has limpiado el caché en el servidor

---

## 🆘 Si aún no funciona

1. **Verifica los logs:**
   ```bash
   tail -n 50 storage/logs/laravel.log | grep -i "google\|oauth\|redirect"
   ```

2. **Verifica la configuración:**
   - Revisa tu archivo `.env` y asegúrate de que `APP_URL=https://websolutions.work` (sin barra final)

3. **Verifica en Google Cloud Console:**
   - Asegúrate de que las URLs estén guardadas correctamente
   - Verifica que estés editando el cliente OAuth correcto (el que tiene el Client ID correcto)

4. **Contacta soporte:**
   - Si nada funciona, puede haber un problema con la configuración del proyecto en Google Cloud Console

