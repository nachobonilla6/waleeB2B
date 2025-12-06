#!/bin/bash

# Script para eliminar recursos de n8n en el servidor de Hostinger
# Ejecutar en: /home/u655097049/domains/websolutions.work

echo "🧹 Limpiando recursos de n8n en el servidor..."

# Cambiar al directorio del proyecto
cd /home/u655097049/domains/websolutions.work || exit 1

# Eliminar modelos
echo "Eliminando modelos..."
rm -f app/Models/N8nBot.php
rm -f app/Models/N8nError.php
rm -f app/Models/N8nPost.php

# Eliminar servicio
echo "Eliminando servicio..."
rm -f app/Services/N8nService.php

# Eliminar recursos de Filament
echo "Eliminando recursos de Filament..."
rm -rf app/Filament/Resources/N8nBotResource
rm -rf app/Filament/Resources/N8nErrorResource
rm -rf app/Filament/Resources/N8nPostResource
rm -rf app/Filament/Resources/VelaSportPostResource

# Eliminar página
echo "Eliminando página N8nWorkflows..."
rm -f app/Filament/Pages/N8nWorkflows.php
rm -f resources/views/filament/pages/n8n-workflows.blade.php

# Eliminar vistas
echo "Eliminando vistas..."
rm -rf resources/views/filament/resources/n8n-post-resource
rm -rf resources/views/filament/resources/vela-sport-post-resource

# Eliminar migraciones
echo "Eliminando migraciones..."
rm -f database/migrations/*_create_n8n_*.php
rm -f database/migrations/*_add_*_to_n8n_*.php

# Eliminar seeder
echo "Eliminando seeder..."
rm -f database/seeders/N8nBotSeeder.php

# Regenerar autoload
echo "Regenerando autoload de Composer..."
composer dump-autoload --optimize

# Eliminar tablas de la base de datos
echo "Eliminando tablas de n8n de la base de datos..."
php artisan migrate

# Limpiar caché de Laravel
echo "Limpiando caché de Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan filament:cache-components

echo "✅ Limpieza completada!"
echo ""
echo "⚠️  NOTA: Asegúrate de que los cambios en los archivos de configuración"
echo "   (config/services.php, routes/web.php, etc.) también estén actualizados"
echo "   en el servidor. Puedes hacerlo con: git pull origin main"

