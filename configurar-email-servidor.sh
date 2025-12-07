#!/bin/bash

# Script para configurar email en el servidor de Hostinger
# Ejecutar en el servidor: bash configurar-email-servidor.sh

echo "📧 Configurando email en el servidor..."

# Ruta del proyecto en el servidor
PROJECT_PATH="/home/u655097049/domains/websolutions.work"
ENV_FILE="$PROJECT_PATH/.env"

# Contraseña de aplicación (sin espacios)
APP_PASSWORD="tpkfbtxiybeluhfh"

# Verificar si el archivo .env existe
if [ ! -f "$ENV_FILE" ]; then
    echo "❌ Error: No se encontró el archivo .env en $PROJECT_PATH"
    exit 1
fi

# Backup del .env
cp "$ENV_FILE" "$ENV_FILE.backup.$(date +%Y%m%d_%H%M%S)"
echo "✅ Backup creado: $ENV_FILE.backup.*"

# Configurar variables de email
echo ""
echo "🔧 Configurando variables de email..."

# Función para agregar o actualizar variable en .env
update_env_var() {
    local key=$1
    local value=$2
    
    if grep -q "^$key=" "$ENV_FILE"; then
        # Actualizar variable existente
        sed -i "s|^$key=.*|$key=$value|" "$ENV_FILE"
        echo "  ✓ Actualizado: $key"
    else
        # Agregar nueva variable
        echo "$key=$value" >> "$ENV_FILE"
        echo "  ✓ Agregado: $key"
    fi
}

# Configurar todas las variables
update_env_var "MAIL_MAILER" "smtp"
update_env_var "MAIL_HOST" "smtp.gmail.com"
update_env_var "MAIL_PORT" "587"
update_env_var "MAIL_USERNAME" "nachobonilla6@gmail.com"
update_env_var "MAIL_PASSWORD" "$APP_PASSWORD"
update_env_var "MAIL_ENCRYPTION" "tls"
update_env_var "MAIL_FROM_ADDRESS" "nachobonilla6@gmail.com"
update_env_var "MAIL_FROM_NAME" "\"WALEÉ\""

echo ""
echo "🧹 Limpiando caché de Laravel..."
cd "$PROJECT_PATH"
php artisan config:clear
php artisan cache:clear

echo ""
echo "✅ Configuración completada!"
echo ""
echo "📋 Resumen de configuración:"
echo "   MAIL_MAILER=smtp"
echo "   MAIL_HOST=smtp.gmail.com"
echo "   MAIL_PORT=587"
echo "   MAIL_USERNAME=nachobonilla6@gmail.com"
echo "   MAIL_PASSWORD=*** (configurada)"
echo "   MAIL_ENCRYPTION=tls"
echo "   MAIL_FROM_ADDRESS=nachobonilla6@gmail.com"
echo "   MAIL_FROM_NAME=\"WALEÉ\""
echo ""
echo "🚀 Ahora puedes probar enviando una factura o cotización desde el panel."

