# URLs de Redireccionamiento para Google OAuth2

## URLs que debes agregar en Google Cloud Console

Ve a [Google Cloud Console](https://console.cloud.google.com/) > **APIs & Services** > **Credentials** > Tu OAuth 2.0 Client ID > **Edit**

Agrega estos URLs en el campo **Authorized redirect URIs**:

### Para Desarrollo Local:
```
http://localhost:8000/auth/google/callback
```

### Para Producción:
```
https://websolutions.work/auth/google/callback
```

O si tu dominio es diferente, usa:
```
https://TU-DOMINIO.com/auth/google/callback
```

## Nota Importante

- Agrega **ambos URLs** si vas a usar la aplicación en desarrollo y producción
- El URL debe coincidir **exactamente** con el que uses en tu aplicación
- No agregues barras finales (`/`) a menos que tu aplicación las use
- Los URLs son case-sensitive (sensibles a mayúsculas/minúsculas)

## Verificar tu dominio

Para verificar cuál es tu dominio de producción, revisa tu archivo `.env`:
```env
APP_URL=https://websolutions.work
```

O ejecuta:
```bash
php artisan config:show app.url
```







