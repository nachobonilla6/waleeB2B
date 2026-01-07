#!/bin/bash

# Script para subir google-credentials.json al servidor
# Uso: ./subir-google-credentials.sh

# Configuración del servidor
SERVER_USER="u655097049"
SERVER_HOST="websolutions.work"
SERVER_PATH="/home/u655097049/domains/websolutions.work/storage/app/"

# Archivo local
LOCAL_FILE="storage/app/google-credentials.json"

# Verificar que el archivo existe localmente
if [ ! -f "$LOCAL_FILE" ]; then
    echo "Error: El archivo $LOCAL_FILE no existe localmente."
    exit 1
fi

echo "Subiendo $LOCAL_FILE al servidor..."
echo "Destino: $SERVER_USER@$SERVER_HOST:$SERVER_PATH"

# Subir el archivo usando SCP
scp "$LOCAL_FILE" "$SERVER_USER@$SERVER_HOST:$SERVER_PATH"

if [ $? -eq 0 ]; then
    echo "✓ Archivo subido exitosamente!"
    echo "El archivo está ahora en: $SERVER_PATH/google-credentials.json"
else
    echo "✗ Error al subir el archivo. Verifica:"
    echo "  1. Que tengas acceso SSH al servidor"
    echo "  2. Que la ruta del servidor sea correcta"
    echo "  3. Que tengas permisos de escritura en el directorio"
fi



