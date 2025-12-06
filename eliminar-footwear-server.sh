#!/bin/bash

# Script para eliminar la página de Footwear en el servidor de Hostinger
# Ejecutar en: /home/u655097049/domains/websolutions.work

echo "🧹 Eliminando página de Footwear del servidor..."

cd /home/u655097049/domains/websolutions.work || exit 1

# Eliminar página de Filament
echo "Eliminando página FootwearStorePage..."
rm -f app/Filament/Pages/FootwearStorePage.php
rm -f resources/views/filament/pages/footwear-store-page.blade.php

# Regenerar autoload
echo "Regenerando autoload..."
composer dump-autoload --optimize

# Limpiar todo el caché
echo "Limpiando caché..."
rm -rf bootstrap/cache/*
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan filament:cache-components

echo "✅ Página de Footwear eliminada!"

