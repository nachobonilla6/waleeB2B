# Comandos para Hostinger

## Pasos para actualizar el proyecto en Hostinger:

```bash
# 1. Ir al directorio del proyecto
cd public_html

# 2. Actualizar código desde git
git pull origin main

# 3. Instalar dependencias (incluye mPDF)
composer install --no-dev --optimize-autoloader

# 4. Limpiar caché de Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 5. Regenerar autoload
composer dump-autoload
```

## Notas importantes:

- Asegúrate de estar en el directorio correcto del proyecto
- Si tienes problemas con permisos, puede que necesites ajustar los permisos de `storage/` y `bootstrap/cache/`
- El flag `--no-dev` evita instalar dependencias de desarrollo en producción
- El flag `--optimize-autoloader` optimiza el autoloader para mejor rendimiento

## Verificar que mPDF está instalado:

```bash
composer show mpdf/mpdf
```

Si aparece la información del paquete, está instalado correctamente.

