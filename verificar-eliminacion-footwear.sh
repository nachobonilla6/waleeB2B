#!/bin/bash

# Script para verificar que Footwear fue eliminado correctamente

echo "🔍 Verificando eliminación de Footwear..."
echo ""

cd /home/u655097049/domains/websolutions.work || exit 1

# Verificar archivos
echo "1. Verificando archivos:"
if [ -f "app/Filament/Pages/FootwearStorePage.php" ]; then
    echo "   ❌ ERROR: FootwearStorePage.php todavía existe"
    ls -la app/Filament/Pages/FootwearStorePage.php
else
    echo "   ✅ FootwearStorePage.php eliminado correctamente"
fi

if [ -f "resources/views/filament/pages/footwear-store-page.blade.php" ]; then
    echo "   ❌ ERROR: Vista todavía existe"
    ls -la resources/views/filament/pages/footwear-store-page.blade.php
else
    echo "   ✅ Vista eliminada correctamente"
fi

echo ""
echo "2. Buscando referencias en el código:"
grep -r "FootwearStorePage" app/ routes/ config/ --exclude-dir=vendor 2>/dev/null | head -5
if [ $? -eq 0 ]; then
    echo "   ⚠️  Se encontraron referencias (revisar arriba)"
else
    echo "   ✅ No se encontraron referencias"
fi

echo ""
echo "3. Listando páginas actuales de Filament:"
ls -1 app/Filament/Pages/*.php 2>/dev/null | sed 's|.*/||' | sed 's|\.php||' | while read page; do
    echo "   - $page"
done

echo ""
echo "✅ Verificación completada!"
echo ""
echo "📝 PRÓXIMOS PASOS:"
echo "   1. Limpia la caché de tu navegador (Ctrl+Shift+Delete)"
echo "   2. Recarga la página con Ctrl+F5"
echo "   3. Prueba acceder a: https://websolutions.work/admin/footwear-store-page"
echo "   4. Debería dar error 404 o redirigir"

