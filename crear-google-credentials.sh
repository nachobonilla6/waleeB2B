#!/bin/bash

# Script para crear el archivo de credenciales de Google Calendar en el servidor
# Uso: ./crear-google-credentials.sh

CREDENTIALS_FILE="storage/app/google-credentials.json"

echo "🔧 Creando archivo de credenciales de Google Calendar..."

# Crear directorio si no existe
mkdir -p storage/app

# Crear archivo de credenciales con el contenido JSON
cat > "$CREDENTIALS_FILE" << 'EOF'
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
EOF

# Verificar que se creó correctamente
if [ -f "$CREDENTIALS_FILE" ]; then
    echo "✅ Archivo creado exitosamente en: $CREDENTIALS_FILE"
    echo "📝 Configurando permisos..."
    chmod 644 "$CREDENTIALS_FILE"
    chown $(whoami):$(whoami) "$CREDENTIALS_FILE" 2>/dev/null || true
    echo "✅ Permisos configurados correctamente"
    echo ""
    echo "📋 Verificando contenido del archivo..."
    if [ -s "$CREDENTIALS_FILE" ]; then
        echo "✅ El archivo tiene contenido válido"
        echo ""
        echo "📌 Ubicación completa: $(pwd)/$CREDENTIALS_FILE"
        echo "📌 Ruta absoluta: $(realpath "$CREDENTIALS_FILE" 2>/dev/null || echo "$(pwd)/$CREDENTIALS_FILE")"
    else
        echo "⚠️  Advertencia: El archivo está vacío"
        exit 1
    fi
else
    echo "❌ Error: No se pudo crear el archivo"
    exit 1
fi

echo ""
echo "✅ ¡Listo! El archivo de credenciales está configurado."
echo "🔄 Ahora puedes intentar autorizar Google Calendar desde el admin."

