# 📋 Información Necesaria para Crear Credenciales OAuth 2.0 de Google

## 🔗 URLs de Redirección Autorizadas

Agrega estas URLs exactas en Google Cloud Console (una por línea):

### Para Desarrollo Local:
```
http://localhost:8000/google-calendar/callback
http://localhost:8000/auth/google/callback
```

### Para Producción:
```
https://websolutions.work/google-calendar/callback
https://websolutions.work/auth/google/callback
```

---

## 📝 Configuración en Google Cloud Console

### 1. Tipo de Aplicación
- **Tipo**: Aplicación web
- **Nombre**: `Walee Calendar Web Client` (o el nombre que prefieras)

### 2. Pantalla de Consentimiento OAuth
- **Tipo de usuario**: Externo (o Interno si solo es para tu organización)
- **Nombre de la aplicación**: `Walee Calendar`
- **Correo de soporte**: Tu correo electrónico
- **Dominios autorizados**: `websolutions.work`
- **Scopes necesarios**: 
  - `https://www.googleapis.com/auth/calendar`
  - `https://www.googleapis.com/auth/calendar.events`

### 3. APIs que debes habilitar
- ✅ **Google Calendar API**

---

## 📄 Estructura del Archivo JSON

Después de crear las credenciales, el archivo `storage/app/google-credentials.json` debe tener esta estructura:

```json
{
  "web": {
    "client_id": "TU_CLIENT_ID.apps.googleusercontent.com",
    "project_id": "walee-sheets",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "TU_CLIENT_SECRET",
    "redirect_uris": [
      "http://localhost:8000/auth/google/callback",
      "https://websolutions.work/auth/google/callback",
      "http://localhost:8000/google-calendar/callback",
      "https://websolutions.work/google-calendar/callback"
    ]
  }
}
```

---

## 🔑 Información que Obtendrás de Google

Después de crear las credenciales, Google te dará:

1. **Client ID**: Algo como `123456789-abcdefghijklmnop.apps.googleusercontent.com`
2. **Client Secret**: Algo como `GOCSPX-abcdefghijklmnopqrstuvwxyz`

---

## 📍 Pasos Rápidos

1. Ve a: https://console.cloud.google.com/
2. Selecciona proyecto: `walee-sheets` (o crea uno nuevo)
3. **APIs y servicios** > **Biblioteca** > Busca "Google Calendar API" > **Habilitar**
4. **APIs y servicios** > **Pantalla de consentimiento OAuth** > Configura y guarda
5. **APIs y servicios** > **Credenciales** > **Crear credenciales** > **ID de cliente OAuth**
6. Tipo: **Aplicación web**
7. Agrega las 4 URLs de redirección listadas arriba
8. **Crear**
9. Copia el **Client ID** y **Client Secret**
10. Actualiza `storage/app/google-credentials.json` con los nuevos valores

---

## ⚠️ Importante

- Las URLs de redirección deben coincidir **exactamente** (sin barras finales, case-sensitive)
- Después de crear las credenciales, puede tardar unos minutos en aplicarse
- Si cambias las URLs después, actualiza tanto en Google Cloud Console como en el archivo JSON
- El `project_id` puede ser diferente si creas un proyecto nuevo

---

## 🔍 Verificar Rutas en Laravel

Para verificar qué URLs está usando tu aplicación, ejecuta:

```bash
php artisan route:list --name=google-calendar.callback
```

Esto mostrará la ruta completa que Laravel está generando.


