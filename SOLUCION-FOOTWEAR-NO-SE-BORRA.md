# Solución: La página de Footwear no se borra

## 🔥 Script de Eliminación Forzada

He creado el script `forzar-eliminar-footwear.sh` que hace una eliminación más agresiva.

### Ejecutar en el servidor:

```bash
cd /home/u655097049/domains/websolutions.work
chmod +x forzar-eliminar-footwear.sh
./forzar-eliminar-footwear.sh
```

## 🔍 Verificación Manual

Si el script no funciona, verifica manualmente:

### 1. Verificar que los archivos no existen:
```bash
ls -la app/Filament/Pages/FootwearStorePage.php
ls -la resources/views/filament/pages/footwear-store-page.blade.php
```

Ambos deberían dar: `No such file or directory`

### 2. Si los archivos EXISTEN, elimínalos manualmente:
```bash
rm -f app/Filament/Pages/FootwearStorePage.php
rm -f resources/views/filament/pages/footwear-store-page.blade.php
```

### 3. Buscar referencias en el código:
```bash
grep -r "FootwearStorePage" app/ routes/ config/ --exclude-dir=vendor
```

No debería mostrar ningún resultado.

### 4. Limpiar caché de OPcache (si está habilitado):

Si el servidor tiene OPcache, puede estar sirviendo código en caché:

```bash
# Opción 1: Reiniciar PHP-FPM (si tienes acceso root)
sudo systemctl restart php8.2-fpm

# Opción 2: Crear un archivo temporal para limpiar OPcache
php -r "opcache_reset();"
```

### 5. Limpiar TODO el caché:
```bash
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*
php artisan optimize:clear
php artisan filament:cache-components
composer dump-autoload --optimize
```

## 🚨 Si AÚN NO FUNCIONA

### Opción A: Deshabilitar temporalmente el descubrimiento automático

Edita `app/Providers/Filament/AdminPanelProvider.php` y comenta temporalmente `discoverPages`:

```php
// ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
```

Y registra solo las páginas que quieres manualmente en el array `->pages([...])`.

### Opción B: Crear un archivo .gitignore o .cursorignore

Asegúrate de que el archivo no se vuelva a subir por error.

### Opción C: Verificar permisos

```bash
ls -la app/Filament/Pages/
```

Si ves que el archivo existe pero no puedes eliminarlo, verifica permisos:
```bash
chmod 755 app/Filament/Pages/
rm -f app/Filament/Pages/FootwearStorePage.php
```

## ✅ Verificación Final

Después de todo, verifica:

1. **Archivos eliminados:**
   ```bash
   find app/Filament/Pages -name "*Footwear*"
   find resources/views/filament/pages -name "*footwear*"
   ```
   No debería mostrar nada.

2. **Caché del navegador:**
   - Limpia completamente (Ctrl+Shift+Delete)
   - Recarga con Ctrl+F5
   - Prueba en modo incógnito

3. **URL:**
   - Intenta acceder a: https://websolutions.work/admin/footwear-store-page
   - Debería dar error 404

