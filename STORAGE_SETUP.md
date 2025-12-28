# Configuración de Storage para Acceso Público

## Problema: Error 403 Forbidden en imágenes

Si las imágenes en `/storage/publicaciones/` dan error 403, necesitas configurar el acceso público.

## Solución:

### 1. Crear el enlace simbólico

En el servidor, ejecuta:

```bash
php artisan storage:link
```

O manualmente:

```bash
cd /ruta/al/proyecto
ln -sfn ../storage/app/public public/storage
```

### 2. Verificar permisos

```bash
chmod -R 755 storage/app/public
chown -R www-data:www-data storage/app/public  # Ajusta según tu servidor
```

### 3. Verificar que el enlace existe

```bash
ls -la public/storage
```

Debería mostrar algo como:
```
lrwxrwxrwx ... storage -> ../storage/app/public
```

### 4. Verificar acceso

La URL `https://websolutions.work/storage/publicaciones/archivo.jpg` debería ser accesible.

## Nota importante:

El enlace simbólico `public/storage` debe existir en el servidor para que las imágenes sean accesibles públicamente.

