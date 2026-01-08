#!/bin/bash

# Script para reparar problemas de Composer en el servidor
# Uso: Copia este script al servidor y ejecútalo

echo "🔧 Reparando problemas de Composer..."

# Directorio del proyecto
PROJECT_DIR="/home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com"

cd "$PROJECT_DIR" || exit 1

echo "📂 Directorio: $(pwd)"

# Paso 1: Limpiar vendor
echo ""
echo "🧹 Limpiando vendor..."
rm -rf vendor
echo "✅ Vendor eliminado"

# Paso 2: Limpiar caché de Composer
echo ""
echo "🧹 Limpiando caché de Composer..."
composer clear-cache
echo "✅ Caché limpiada"

# Paso 3: Reinstalar dependencias
echo ""
echo "📦 Reinstalando dependencias (esto puede tardar)..."
composer install --no-dev --no-scripts
if [ $? -eq 0 ]; then
    echo "✅ Dependencias instaladas"
else
    echo "❌ Error al instalar dependencias"
    exit 1
fi

# Paso 4: Regenerar autoload
echo ""
echo "🔄 Regenerando autoload..."
composer dump-autoload
if [ $? -eq 0 ]; then
    echo "✅ Autoload regenerado"
else
    echo "❌ Error al regenerar autoload"
    exit 1
fi

# Paso 5: Optimizar autoload
echo ""
echo "⚡ Optimizando autoload..."
composer dump-autoload --optimize
echo "✅ Autoload optimizado"

# Paso 6: Verificar .env
echo ""
echo "🔍 Verificando archivo .env..."
if [ ! -f .env ]; then
    echo "⚠️  .env no existe, creando desde .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        php artisan key:generate
        echo "✅ .env creado"
    else
        echo "❌ .env.example no existe"
    fi
else
    echo "✅ .env existe"
fi

# Paso 7: Limpiar caché de Laravel
echo ""
echo "🧹 Limpiando caché de Laravel..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan optimize:clear 2>/dev/null || true
echo "✅ Caché de Laravel limpiada"

# Paso 8: Verificar permisos
echo ""
echo "🔐 Verificando permisos..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
echo "✅ Permisos verificados"

# Paso 9: Verificar instalación
echo ""
echo "🔍 Verificando instalación..."
if [ -d "vendor" ] && [ -f "vendor/autoload.php" ]; then
    echo "✅ Vendor instalado correctamente"
    
    # Verificar DomPDF
    if [ -d "vendor/barryvdh/laravel-dompdf" ]; then
        echo "✅ DomPDF instalado"
    else
        echo "⚠️  DomPDF no encontrado, intentando instalar..."
        composer require barryvdh/laravel-dompdf --no-dev
    fi
else
    echo "❌ Vendor no instalado correctamente"
    exit 1
fi

echo ""
echo "✅ Proceso completado!"
echo ""
echo "📝 Próximos pasos:"
echo "   1. Verifica los logs: tail -n 50 storage/logs/laravel.log"
echo "   2. Verifica que el sitio funciona en el navegador"
echo "   3. Si hay errores, revisa: php artisan --version"

