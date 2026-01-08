#!/bin/bash

# Script para hacer deploy a Hostinger
# Uso: ./deploy-hostinger.sh

echo "🚀 Iniciando deploy a Hostinger..."

# URL del webhook de n8n
WEBHOOK_URL="https://n8n.srv1137974.hstgr.cloud/webhook/waleeb2b"

# Comando completo: git pull + composer install + migraciones + limpiar caché + optimizar
# Actualizado para el nuevo dominio: ghostwhite-parrot-934435.hostingersite.com
# Configurar git para evitar problemas con el editor en merges
COMMAND="cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com && git config pull.rebase false && git config merge.commit no-edit && git config core.editor true && (git merge --abort 2>/dev/null || true) && git pull origin main --no-edit && composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan filament:cache-components && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan optimize:clear && php artisan optimize"

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

