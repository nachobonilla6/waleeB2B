#!/bin/bash

# Script AGRESIVO para eliminar la página de Footwear del servidor
# Ejecutar en: /home/u655097049/domains/websolutions.work

echo "🔥 ELIMINACIÓN FORZADA de página de Footwear..."

cd /home/u655097049/domains/websolutions.work || exit 1

# 1. Verificar y eliminar archivos
echo "1. Eliminando archivos..."
rm -f app/Filament/Pages/FootwearStorePage.php
rm -f resources/views/filament/pages/footwear-store-page.blade.php

# Verificar que se eliminaron
if [ -f "app/Filament/Pages/FootwearStorePage.php" ]; then
    echo "⚠️  ERROR: El archivo todavía existe. Eliminando con fuerza..."
    rm -rf app/Filament/Pages/FootwearStorePage.php
fi

if [ -f "resources/views/filament/pages/footwear-store-page.blade.php" ]; then
    echo "⚠️  ERROR: La vista todavía existe. Eliminando con fuerza..."
    rm -rf resources/views/filament/pages/footwear-store-page.blade.php
fi

# 2. Buscar y eliminar cualquier referencia
echo "2. Buscando referencias..."
find . -type f -name "*.php" -exec grep -l "FootwearStorePage" {} \; 2>/dev/null | grep -v vendor | grep -v node_modules | while read file; do
    echo "   Encontrado en: $file"
    # No eliminamos estos archivos, solo los reportamos
done

# 3. Limpiar TODO el caché
echo "3. Limpiando caché completamente..."
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*
rm -rf storage/framework/sessions/*

# 4. Comandos de Artisan
echo "4. Ejecutando comandos de Artisan..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan optimize:clear 2>/dev/null || true
php artisan filament:cache-components 2>/dev/null || true

# 5. Regenerar autoload
echo "5. Regenerando autoload..."
composer dump-autoload --optimize --no-interaction 2>/dev/null || true

# 6. Verificación final
echo ""
echo "6. Verificación final:"
if [ ! -f "app/Filament/Pages/FootwearStorePage.php" ]; then
    echo "   ✅ FootwearStorePage.php eliminado"
else
    echo "   ❌ ERROR: FootwearStorePage.php todavía existe"
    ls -la app/Filament/Pages/FootwearStorePage.php
fi

if [ ! -f "resources/views/filament/pages/footwear-store-page.blade.php" ]; then
    echo "   ✅ Vista eliminada"
else
    echo "   ❌ ERROR: Vista todavía existe"
    ls -la resources/views/filament/pages/footwear-store-page.blade.php
fi

echo ""
echo "✅ Proceso completado!"
echo ""
echo "⚠️  IMPORTANTE:"
echo "   1. Limpia la caché de tu navegador (Ctrl+Shift+Delete)"
echo "   2. Recarga la página con Ctrl+F5"
echo "   3. Si usas OPcache, reinicia PHP-FPM: sudo systemctl restart php8.2-fpm"

