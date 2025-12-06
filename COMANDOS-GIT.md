# 🚀 Comandos para Subir a GitHub

## ✅ Todo está listo para commit

### Archivos preparados:
- ✅ `app/Filament/Pages/N8nAutomatizaciones.php` (nuevo)
- ✅ `app/Services/N8nService.php` (modificado)
- ✅ `resources/views/filament/pages/n8n-automatizaciones.blade.php` (nuevo)
- ✅ `config/services.php` (modificado)
- ✅ `.gitignore` (actualizado para excluir .env)
- ✅ Archivos antiguos de n8n eliminados

## 📝 Ejecuta estos comandos:

```bash
cd websolutions-laravel

# 1. Hacer commit
git commit -m "feat: Nueva página de automatizaciones n8n con diseño en filas

- Nueva página N8nAutomatizaciones con diseño en filas
- Servicio N8nService actualizado para interactuar con API de n8n
- Vista personalizada con opciones para editar, activar y ver nodos
- Búsqueda y filtros en tiempo real
- Configuración de n8n en services.php
- Actualizado .gitignore para excluir .env
- Eliminados recursos antiguos de n8n"

# 2. Push a GitHub
git push origin main
```

## 🔧 Después del push, en Hostinger:

```bash
cd /home/u655097049/domains/websolutions.work
git pull origin main

# Agregar variables al .env (si no existen)
if ! grep -q "^N8N_URL" .env; then
    echo "" >> .env
    echo "# n8n Configuration" >> .env
    echo "N8N_URL=https://n8n.srv1137974.hstgr.cloud" >> .env
    echo "N8N_API_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNWNhODY2Ny0wYmNhLTQwYjAtOWFhYS04ZTBhZDA0ODE1ZWMiLCJpc3MiOiJuOG4iLCJhdWQiOiJwdWJsaWMtYXBpIiwiaWF0IjoxNzY0OTkyMjk4fQ.HLP8p4yzzk81Bt5W5ppgi8Em8qy1QECNbSbdrhivqvk" >> .env
fi

# Regenerar y limpiar
composer dump-autoload --optimize
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan filament:cache-components
php -r "if (function_exists('opcache_reset')) { opcache_reset(); }"
```

## ✅ Verificación

1. Limpia caché del navegador (Ctrl+Shift+Delete)
2. Recarga con Ctrl+F5
3. Ve a: https://websolutions.work/admin/n8n-automatizaciones
4. Deberías ver los workflows en formato de filas

