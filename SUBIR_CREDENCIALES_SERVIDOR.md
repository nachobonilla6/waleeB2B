# Instrucciones para subir google-credentials.json al servidor

## Opción 1: Git Pull (Recomendado)

Conéctate al servidor por SSH y ejecuta:

```bash
cd /home/u655097049/domains/websolutions.work/public_html
git pull origin main
```

Esto descargará el archivo `storage/app/google-credentials.json` automáticamente.

## Opción 2: Subir manualmente por FTP/SCP

Si el `git pull` no funciona, puedes subir el archivo manualmente:

1. **Ubicación del archivo local:**
   - `storage/app/google-credentials.json`

2. **Ubicación en el servidor:**
   - `/home/u655097049/domains/websolutions.work/storage/app/google-credentials.json`
   - O también puede estar en: `/home/u655097049/domains/websolutions.work/public_html/storage/app/google-credentials.json`

3. **Contenido del archivo:**
```json
{
  "web": {
    "client_id": "446686950572-bjgit2enli9eagk56flucvqok1gngleo.apps.googleusercontent.com",
    "project_id": "responsive-task-480005-c8",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "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",
    "redirect_uris": [
      "https://websolutions.work/auth/google/callback",
      "https://websolutions.work/google-calendar/callback"
    ]
  }
}
```

4. **Verificar permisos:**
   Después de subir el archivo, asegúrate de que tenga los permisos correctos:
   ```bash
   chmod 644 storage/app/google-credentials.json
   ```

## Opción 3: Crear el archivo directamente en el servidor

Si tienes acceso SSH, puedes crear el archivo directamente:

```bash
cd /home/u655097049/domains/websolutions.work/public_html
mkdir -p storage/app
cat > storage/app/google-credentials.json << 'EOF'
{
  "web": {
    "client_id": "446686950572-bjgit2enli9eagk56flucvqok1gngleo.apps.googleusercontent.com",
    "project_id": "responsive-task-480005-c8",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "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",
    "redirect_uris": [
      "https://websolutions.work/auth/google/callback",
      "https://websolutions.work/google-calendar/callback"
    ]
  }
}
EOF
chmod 644 storage/app/google-credentials.json
```

## Verificar que el archivo existe

Después de subir el archivo, verifica que existe:

```bash
ls -la storage/app/google-credentials.json
cat storage/app/google-credentials.json
```

Si el archivo existe y tiene el contenido correcto, recarga la página del calendario y el error debería desaparecer.

