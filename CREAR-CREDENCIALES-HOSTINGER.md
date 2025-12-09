# Crear Archivo de Credenciales en Hostinger

## Método 1: Usar el Script (Recomendado)

1. Conéctate por SSH a Hostinger
2. Ve al directorio del proyecto:
   ```bash
   cd /home/u655097049/domains/websolutions.work
   ```
3. Asegúrate de tener el script (debe estar en el repositorio):
   ```bash
   git pull origin main
   ```
4. Ejecuta el script:
   ```bash
   chmod +x crear-google-credentials.sh
   ./crear-google-credentials.sh
   ```

## Método 2: Crear Manualmente

1. Conéctate por SSH a Hostinger
2. Ve al directorio del proyecto:
   ```bash
   cd /home/u655097049/domains/websolutions.work
   ```
3. Crea el directorio si no existe:
   ```bash
   mkdir -p storage/app
   ```
4. Crea el archivo:
   ```bash
   nano storage/app/google-credentials.json
   ```
5. Pega exactamente este contenido:
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
6. Guarda el archivo:
   - Presiona `Ctrl + O` (guardar)
   - Presiona `Enter` (confirmar)
   - Presiona `Ctrl + X` (salir)
7. Configura permisos:
   ```bash
   chmod 644 storage/app/google-credentials.json
   ```
8. Verifica que se creó correctamente:
   ```bash
   ls -la storage/app/google-credentials.json
   cat storage/app/google-credentials.json
   ```

## Método 3: Usar echo (Una línea)

```bash
cd /home/u655097049/domains/websolutions.work && mkdir -p storage/app && cat > storage/app/google-credentials.json << 'JSONEOF'
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
JSONEOF
chmod 644 storage/app/google-credentials.json
```

## Verificación

Después de crear el archivo, verifica que existe y tiene el contenido correcto:

```bash
cd /home/u655097049/domains/websolutions.work
ls -la storage/app/google-credentials.json
cat storage/app/google-credentials.json
```

Deberías ver el contenido JSON completo.

## Siguiente Paso

Una vez creado el archivo, ve al admin de Filament y:
1. Navega a "Autorizar Google Calendar" (en el menú Configuración)
2. Haz clic en "Autorizar con Google Calendar"
3. Debería funcionar correctamente

