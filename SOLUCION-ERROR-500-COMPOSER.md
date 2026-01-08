# 🔧 Solución: Error HTTP 500 - Problemas con Composer

## Problema
El sitio muestra error HTTP 500 y Composer tiene problemas al instalar dependencias:
- Error: `Failed to open stream: No such file or directory` (deep-copy)
- Error: `Class "Barryvdh\DomPDF\ServiceProvider" not found`

## Causa
Las dependencias de Composer no se instalaron correctamente o el vendor está corrupto.

## Solución

### Paso 1: Limpiar vendor y reinstalar

```bash
# 1. Navegar al directorio del proyecto
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com

# 2. Eliminar vendor y composer.lock (hacer backup primero si es necesario)
rm -rf vendor
rm -f composer.lock

# 3. Reinstalar todas las dependencias
composer install --no-dev --optimize-autoloader

# Si el paso 3 falla, intenta sin optimización primero:
composer install --no-dev

# Luego optimiza:
composer dump-autoload --optimize
```

### Paso 2: Si sigue fallando, reinstalar desde cero

```bash
# 1. Hacer backup del composer.json actual
cp composer.json composer.json.backup

# 2. Eliminar vendor completamente
rm -rf vendor

# 3. Limpiar caché de Composer
composer clear-cache

# 4. Reinstalar sin optimización primero
composer install --no-dev --no-scripts

# 5. Luego ejecutar scripts
composer dump-autoload

# 6. Optimizar
composer dump-autoload --optimize
```

### Paso 3: Verificar permisos

```bash
# Asegurar permisos correctos en storage y bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R u655097049:u655097049 storage bootstrap/cache
```

### Paso 4: Limpiar caché de Laravel

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### Paso 5: Verificar .env

Asegúrate de que el archivo `.env` existe y tiene la configuración correcta:

```bash
# Verificar que .env existe
ls -la .env

# Si no existe, copiar desde .env.example
cp .env.example .env

# Generar nueva key de aplicación
php artisan key:generate
```

## Solución Rápida (Todo en uno)

```bash
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com

# Limpiar todo
rm -rf vendor
composer clear-cache

# Reinstalar
composer install --no-dev

# Optimizar
composer dump-autoload --optimize

# Limpiar Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Verificar permisos
chmod -R 775 storage bootstrap/cache

# Verificar .env
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi
```

## Verificar que funciona

Después de ejecutar los comandos, verifica:

```bash
# Verificar que vendor existe
ls -la vendor/

# Verificar que DomPDF está instalado
composer show barryvdh/laravel-dompdf

# Probar que Laravel funciona
php artisan --version
```

## Si el problema persiste

1. **Verificar logs de Laravel:**
   ```bash
   tail -n 50 storage/logs/laravel.log
   ```

2. **Verificar logs del servidor web:**
   ```bash
   tail -n 50 /home/u655097049/logs/error_log
   ```

3. **Verificar que PHP tiene las extensiones necesarias:**
   ```bash
   php -m | grep -E "pdo|mbstring|xml|openssl|tokenizer|json|curl"
   ```

