# 🚀 Comandos para el Nuevo Dominio

## Dominio Actual
**ghostwhite-parrot-934435.hostingersite.com**

## 📂 Directorio del Proyecto en el Servidor
```bash
/home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com
```

## 🔧 Comandos Rápidos

### 1. Navegar al directorio del proyecto
```bash
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com
```

### 2. Verificar si es un repositorio Git
```bash
git status
```

### 3. Si NO es un repositorio Git, inicializarlo:
```bash
# Inicializar git
git init

# Agregar el remote
git remote add origin https://github.com/nachobonilla6/waleeB2B.git

# Verificar
git remote -v

# Hacer pull
git pull origin main
```

### 4. Si ya es un repositorio Git, hacer pull:
```bash
git pull origin main
```

### 5. Después del pull, actualizar dependencias y limpiar caché:
```bash
# Instalar/actualizar dependencias
composer install --no-dev --optimize-autoloader

# Limpiar caché de Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Regenerar autoload
composer dump-autoload --optimize

# Limpiar OPcache (si está disponible)
php -r "if (function_exists('opcache_reset')) { opcache_reset(); }"
```

## 🔍 Verificar el Directorio Correcto

Si no estás seguro del directorio exacto, ejecuta:

```bash
# Ver todos los dominios disponibles
ls -la ~/domains/

# O buscar por el nombre del dominio
ls -la ~/domains/ | grep ghostwhite
```

## ⚠️ Nota Importante

Si el directorio tiene un nombre ligeramente diferente (por ejemplo, con guiones o números diferentes), ajusta el comando según el nombre real del directorio que veas con `ls -la ~/domains/`.

## 📝 Script de Verificación

Puedes usar el script `verificar-git-servidor.sh` que ya está actualizado con el nuevo dominio. Cópialo al servidor y ejecútalo:

```bash
chmod +x verificar-git-servidor.sh
./verificar-git-servidor.sh
```

