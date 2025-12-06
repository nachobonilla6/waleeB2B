#!/bin/bash

# Script para hacer deploy a Hostinger
# Uso: ./deploy-hostinger.sh

echo "🚀 Iniciando deploy a Hostinger..."

# URL del webhook de n8n
WEBHOOK_URL="https://n8n.srv1137974.hstgr.cloud/webhook/1ec6c667-1b0d-46c9-ad95-8140cc041bba"

# Comando completo: git pull + migraciones + limpiar caché + optimizar
COMMAND="cd /home/u655097049/domains/websolutions.work && git pull origin main && php artisan migrate --force && php artisan filament:cache-components && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan optimize:clear && php artisan optimize"

# Ejecutar el webhook
RESPONSE=$(curl -s -X POST "$WEBHOOK_URL" \
  -H "Content-Type: application/json" \
  -d "{
    \"command\": \"$COMMAND\",
    \"timestamp\": \"$(date -Iseconds)\",
    \"triggered_by\": \"CLI\"
  }")

echo "📤 Respuesta del servidor:"
echo "$RESPONSE" | jq '.' 2>/dev/null || echo "$RESPONSE"

echo ""
echo "✅ Deploy iniciado. Espera unos segundos y verifica en Hostinger."

