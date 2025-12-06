# Actualizar Widget de Ingresos en Hostinger

## 📋 Archivos Nuevos/Cambios

Se han creado/modificado estos archivos:

1. **Nuevo:** `app/Filament/Widgets/IngresosStatsWidget.php`
2. **Modificado:** `app/Filament/Pages/Dashboard.php`

## 🚀 Opción 1: Usando Git (Recomendado)

Si los cambios están en tu repositorio Git:

```bash
# Conectarse al servidor
ssh u655097049@tu-servidor.hostinger.com

# Ir al directorio del proyecto
cd /home/u655097049/domains/websolutions.work

# Hacer pull de los cambios
git pull origin main

# Regenerar autoload
composer dump-autoload --optimize

# Limpiar caché
php artisan optimize:clear
php artisan filament:cache-components
```

## 📤 Opción 2: Subir Archivos Manualmente

Si no usas Git, sube los archivos manualmente:

### Paso 1: Subir archivos vía FTP/SFTP o File Manager

Sube estos archivos al servidor:

1. **Subir el nuevo widget:**
   - Archivo local: `app/Filament/Widgets/IngresosStatsWidget.php`
   - Destino en servidor: `/home/u655097049/domains/websolutions.work/app/Filament/Widgets/IngresosStatsWidget.php`

2. **Actualizar el Dashboard:**
   - Archivo local: `app/Filament/Pages/Dashboard.php`
   - Destino en servidor: `/home/u655097049/domains/websolutions.work/app/Filament/Pages/Dashboard.php`

### Paso 2: Ejecutar comandos en el servidor

Después de subir los archivos, conecta vía SSH y ejecuta:

```bash
cd /home/u655097049/domains/websolutions.work

# Regenerar autoload de Composer
composer dump-autoload --optimize

# Limpiar caché
php artisan optimize:clear
php artisan filament:cache-components
php artisan view:clear
php artisan config:clear
```

## ✅ Verificación

Después de actualizar, verifica:

1. **Verificar que los archivos existen:**
   ```bash
   ls -la app/Filament/Widgets/IngresosStatsWidget.php
   ls -la app/Filament/Pages/Dashboard.php
   ```

2. **Verificar que no hay errores:**
   ```bash
   php artisan about
   ```

3. **Acceder al Dashboard:**
   - Ve a: https://websolutions.work/admin
   - Deberías ver el nuevo widget de "Ingresos Stats" con las estadísticas de facturas pagadas

## 🔧 Si hay problemas

### Error: Clase no encontrada

Si ves un error de que la clase no se encuentra:

```bash
composer dump-autoload --optimize
php artisan optimize:clear
```

### El widget no aparece

Si el widget no aparece en el Dashboard:

```bash
php artisan filament:cache-components
php artisan view:clear
php artisan config:clear
```

Luego limpia la caché del navegador (Ctrl+Shift+Delete) y recarga.

### Error de sintaxis

Verifica que los archivos se subieron correctamente:

```bash
php -l app/Filament/Widgets/IngresosStatsWidget.php
php -l app/Filament/Pages/Dashboard.php
```

No debería mostrar errores.

