# Comandos para Subir Automatizaciones n8n a GitHub

## 📋 Archivos Nuevos/Modificados

Los siguientes archivos están listos para commit:

1. ✅ `app/Services/N8nService.php` - Nuevo servicio
2. ✅ `app/Filament/Pages/N8nAutomatizaciones.php` - Nueva página
3. ✅ `resources/views/filament/pages/n8n-automatizaciones.blade.php` - Nueva vista
4. ✅ `config/services.php` - Configuración actualizada

## 🚀 Comandos para Ejecutar

### 1. Agregar archivos al staging
```bash
cd websolutions-laravel

git add app/Services/N8nService.php
git add app/Filament/Pages/N8nAutomatizaciones.php
git add resources/views/filament/pages/n8n-automatizaciones.blade.php
git add config/services.php
```

### 2. Hacer commit
```bash
git commit -m "feat: Agregar página de automatizaciones n8n con diseño en filas

- Nueva página N8nAutomatizaciones con diseño en filas
- Servicio N8nService para interactuar con API de n8n
- Vista personalizada con opciones para editar, activar y ver nodos
- Búsqueda y filtros en tiempo real
- Configuración de n8n en services.php"
```

### 3. Push a GitHub
```bash
git push origin main
```

## ⚠️ IMPORTANTE: Variables de Entorno

**NO subas el archivo `.env` a GitHub**. Las variables de entorno deben agregarse directamente en el servidor de Hostinger.

### En el servidor de Hostinger, después del pull:

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
php artisan filament:cache-components
```

## ✅ Verificación

Después del push y pull en el servidor:

1. Verifica que los archivos existen:
   ```bash
   ls -la app/Services/N8nService.php
   ls -la app/Filament/Pages/N8nAutomatizaciones.php
   ```

2. Accede a la página:
   - https://websolutions.work/admin/n8n-automatizaciones
   - Deberías ver los workflows en formato de filas

