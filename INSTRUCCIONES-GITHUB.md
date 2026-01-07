# 🚀 Instrucciones para Subir a GitHub y Hostinger

## ✅ Archivos Listos para Commit

Los siguientes archivos están preparados:

1. ✅ `app/Services/N8nService.php` (modificado)
2. ✅ `app/Filament/Pages/N8nAutomatizaciones.php` (nuevo)
3. ✅ `resources/views/filament/pages/n8n-automatizaciones.blade.php` (nuevo)
4. ✅ `config/services.php` (modificado)
5. ✅ Archivos antiguos de n8n eliminados (D)

## 📝 Comandos para Ejecutar

### 1. Verificar que .env NO se suba
```bash
cd websolutions-laravel
git status .env
# Si aparece como "modified", NO lo agregues al commit
```

### 2. Hacer commit de los cambios
```bash
git commit -m "feat: Nueva página de automatizaciones n8n con diseño en filas

- Nueva página N8nAutomatizaciones con diseño en filas
- Servicio N8nService actualizado para interactuar con API de n8n
- Vista personalizada con opciones para editar, activar y ver nodos
- Búsqueda y filtros en tiempo real
- Configuración de n8n en services.php
- Eliminados recursos antiguos de n8n"
```

### 3. Push a GitHub
```bash
git push origin main
```

## 🔧 En el Servidor de Hostinger (después del pull)

Una vez que Hostinger haga el pull automático o manualmente:

```bash
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com

# Si es pull manual:
git pull origin main

# Agregar variables al .env (si no existen)
if ! grep -q "^N8N_URL" .env; then
    echo "" >> .env
    echo "# n8n Configuration" >> .env
    echo "N8N_URL=https://n8n.srv1137974.hstgr.cloud" >> .env
    echo "N8N_API_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNWNhODY2Ny0wYmNhLTQwYjAtOWFhYS04ZTBhZDA0ODE1ZWMiLCJpc3MiOiJuOG4iLCJhdWQiOiJwdWJsaWMtYXBpIiwiaWF0IjoxNzY0OTkyMjk4fQ.HLP8p4yzzk81Bt5W5ppgi8Em8qy1QECNbSbdrhivqvk" >> .env
fi

# Regenerar y limpiar caché
composer dump-autoload --optimize
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan filament:cache-components

# Limpiar OPcache si está disponible
php -r "if (function_exists('opcache_reset')) { opcache_reset(); }"
```

## ✅ Verificación Final

1. **En el servidor**, verifica que los archivos existen:
   ```bash
   ls -la app/Services/N8nService.php
   ls -la app/Filament/Pages/N8nAutomatizaciones.php
   ls -la resources/views/filament/pages/n8n-automatizaciones.blade.php
   ```

2. **En el navegador**:
   - Limpia la caché (Ctrl+Shift+Delete)
   - Recarga con Ctrl+F5
   - Ve a: https://websolutions.work/admin/n8n-automatizaciones
   - Deberías ver los workflows en formato de filas con botones para editar, activar y ver nodos

## 🎨 Características del Nuevo Diseño

- ✅ Workflows en formato de filas (tarjetas)
- ✅ Barra de búsqueda en tiempo real
- ✅ Filtro por estado (Activos/Inactivos/Todos)
- ✅ Botones de acción: Editar, Activar/Desactivar, Ejecutar, Ver Nodos
- ✅ Información expandible de nodos
- ✅ Indicadores visuales de estado (Activo/Inactivo)
- ✅ Contador de resultados

