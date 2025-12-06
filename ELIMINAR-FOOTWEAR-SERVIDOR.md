# Eliminar Página de Footwear del Servidor

La página de Footwear todavía existe en el servidor de producción: https://websolutions.work/admin/footwear-store-page

## 🚀 Solución Rápida

### Opción 1: Script Automático (Recomendado)

1. **Conectarse al servidor vía SSH:**
   ```bash
   ssh u655097049@tu-servidor.hostinger.com
   ```

2. **Navegar al directorio:**
   ```bash
   cd /home/u655097049/domains/websolutions.work
   ```

3. **Subir y ejecutar el script:**
   ```bash
   # Sube el archivo eliminar-footwear-server.sh al servidor
   chmod +x eliminar-footwear-server.sh
   ./eliminar-footwear-server.sh
   ```

### Opción 2: Comandos Manuales

Ejecuta estos comandos en el servidor:

```bash
cd /home/u655097049/domains/websolutions.work

# 1. Eliminar archivos
rm -f app/Filament/Pages/FootwearStorePage.php
rm -f resources/views/filament/pages/footwear-store-page.blade.php

# 2. Limpiar TODO el caché
rm -rf bootstrap/cache/*
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan filament:cache-components

# 3. Regenerar autoload
composer dump-autoload --optimize

# 4. Verificar que se eliminaron
ls -la app/Filament/Pages/FootwearStorePage.php
# Debería decir: No such file or directory
```

### Opción 3: Usando Git (Si los cambios están en el repo)

```bash
cd /home/u655097049/domains/websolutions.work
git pull origin main
composer dump-autoload --optimize
php artisan optimize:clear
php artisan filament:cache-components
```

## ✅ Verificación

Después de ejecutar los comandos, verifica:

1. **Verificar que los archivos no existen:**
   ```bash
   find app/Filament/Pages -name "*Footwear*"
   find resources/views/filament/pages -name "*footwear*"
   ```
   No debería mostrar ningún resultado.

2. **Limpiar caché del navegador:**
   - Presiona `Ctrl + Shift + Delete`
   - Selecciona "Caché"
   - Limpia y recarga con `Ctrl + F5`

3. **Verificar la URL:**
   - Intenta acceder a: https://websolutions.work/admin/footwear-store-page
   - Debería dar un error 404 o redirigir

## ⚠️ Si Aún Aparece

Si después de todo esto todavía aparece:

1. **Reiniciar PHP-FPM (si tienes acceso):**
   ```bash
   sudo systemctl restart php8.2-fpm
   ```

2. **Verificar permisos:**
   ```bash
   ls -la app/Filament/Pages/FootwearStorePage.php
   ```
   Si el archivo existe, elimínalo manualmente.

3. **Verificar caché de OPcache:**
   El servidor puede tener OPcache habilitado. Reinicia PHP-FPM o espera unos minutos.

