#!/bin/bash

# Script para actualizar el widget de ingresos en el servidor de Hostinger
# Ejecutar en: /home/u655097049/domains/websolutions.work

echo "📊 Actualizando Widget de Ingresos..."

cd /home/u655097049/domains/websolutions.work || exit 1

# Verificar que los archivos existen
echo "1. Verificando archivos..."
if [ ! -f "app/Filament/Widgets/IngresosStatsWidget.php" ]; then
    echo "   ⚠️  ERROR: IngresosStatsWidget.php no existe"
    echo "   Por favor, sube el archivo primero"
    exit 1
fi

if [ ! -f "app/Filament/Pages/Dashboard.php" ]; then
    echo "   ⚠️  ERROR: Dashboard.php no existe"
    exit 1
fi

echo "   ✅ Archivos encontrados"

# Regenerar autoload
echo "2. Regenerando autoload..."
composer dump-autoload --optimize --no-interaction 2>/dev/null || {
    echo "   ⚠️  Error al regenerar autoload, continuando..."
}

# Limpiar caché
echo "3. Limpiando caché..."
php artisan optimize:clear 2>/dev/null || true
php artisan filament:cache-components 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true

# Verificar sintaxis
echo "4. Verificando sintaxis..."
php -l app/Filament/Widgets/IngresosStatsWidget.php 2>&1 | grep -q "No syntax errors" && echo "   ✅ IngresosStatsWidget.php - OK" || echo "   ❌ Error de sintaxis en IngresosStatsWidget.php"

php -l app/Filament/Pages/Dashboard.php 2>&1 | grep -q "No syntax errors" && echo "   ✅ Dashboard.php - OK" || echo "   ❌ Error de sintaxis en Dashboard.php"

echo ""
echo "✅ Actualización completada!"
echo ""
echo "📝 Próximos pasos:"
echo "   1. Limpia la caché de tu navegador (Ctrl+Shift+Delete)"
echo "   2. Recarga el Dashboard: https://websolutions.work/admin"
echo "   3. Deberías ver el nuevo widget de 'Ingresos Stats'"

