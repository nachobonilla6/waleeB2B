#!/bin/bash

# Script para actualizar la página de automatizaciones n8n en el servidor
# Ejecutar en: /home/u655097049/domains/websolutions.work

echo "🔄 Actualizando página de Automatizaciones n8n..."

cd /home/u655097049/domains/websolutions.work || exit 1

# Verificar que los archivos existen
echo "1. Verificando archivos..."
if [ ! -f "app/Services/N8nService.php" ]; then
    echo "   ⚠️  ERROR: N8nService.php no existe"
    echo "   Por favor, sube el archivo primero"
    exit 1
fi

if [ ! -f "app/Filament/Pages/N8nAutomatizaciones.php" ]; then
    echo "   ⚠️  ERROR: N8nAutomatizaciones.php no existe"
    exit 1
fi

if [ ! -f "resources/views/filament/pages/n8n-automatizaciones.blade.php" ]; then
    echo "   ⚠️  ERROR: Vista no existe"
    exit 1
fi

echo "   ✅ Archivos encontrados"

# Agregar variables al .env si no existen
echo "2. Verificando variables de entorno..."
if ! grep -q "^N8N_URL" .env 2>/dev/null; then
    echo "" >> .env
    echo "# n8n Configuration" >> .env
    echo "N8N_URL=https://n8n.srv1137974.hstgr.cloud" >> .env
    echo "N8N_API_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNWNhODY2Ny0wYmNhLTQwYjAtOWFhYS04ZTBhZDA0ODE1ZWMiLCJpc3MiOiJuOG4iLCJhdWQiOiJwdWJsaWMtYXBpIiwiaWF0IjoxNzY0OTkyMjk4fQ.HLP8p4yzzk81Bt5W5ppgi8Em8qy1QECNbSbdrhivqvk" >> .env
    echo "   ✅ Variables agregadas al .env"
else
    echo "   ✅ Variables ya existen"
fi

# Regenerar autoload
echo "3. Regenerando autoload..."
composer dump-autoload --optimize --no-interaction 2>/dev/null || {
    echo "   ⚠️  Error al regenerar autoload, continuando..."
}

# Limpiar caché
echo "4. Limpiando caché..."
php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan filament:cache-components 2>/dev/null || true

# Verificar sintaxis
echo "5. Verificando sintaxis..."
php -l app/Services/N8nService.php 2>&1 | grep -q "No syntax errors" && echo "   ✅ N8nService.php - OK" || echo "   ❌ Error en N8nService.php"
php -l app/Filament/Pages/N8nAutomatizaciones.php 2>&1 | grep -q "No syntax errors" && echo "   ✅ N8nAutomatizaciones.php - OK" || echo "   ❌ Error en N8nAutomatizaciones.php"

echo ""
echo "✅ Actualización completada!"
echo ""
echo "📝 Próximos pasos:"
echo "   1. Limpia la caché de tu navegador (Ctrl+Shift+Delete)"
echo "   2. Recarga la página: https://websolutions.work/admin/n8n-automatizaciones"
echo "   3. Deberías ver los workflows en formato de filas con opciones para editar, activar y ver nodos"

