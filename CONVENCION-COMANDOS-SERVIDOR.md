# 📝 Convención para Comandos del Servidor

## ⚠️ Importante

**Todos los comandos para el servidor asumen que ya estás en el directorio:**
```bash
/home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com
```

## 🎯 ¿Por qué?

Para simplificar las instrucciones y evitar repetir el comando `cd` en cada paso, se asume que ya estás en el directorio correcto del proyecto.

## 📋 Ejemplo

**Antes:**
```bash
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com
git pull origin main
php artisan cache:clear
```

**Ahora (simplificado):**
```bash
git pull origin main
php artisan cache:clear
```

## 🔍 Verificar tu ubicación

Si no estás seguro de en qué directorio estás, ejecuta:
```bash
pwd
```

Si necesitas navegar al directorio del proyecto:
```bash
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com
```

## ✅ Comandos comunes (sin cd)

```bash
# Git
git pull origin main
git status

# Composer
composer install --no-dev --optimize-autoloader
composer dump-autoload --optimize

# Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

