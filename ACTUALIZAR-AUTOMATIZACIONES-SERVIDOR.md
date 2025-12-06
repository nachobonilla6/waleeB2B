# Actualizar Página de Automatizaciones n8n en el Servidor

## 📋 Archivos a Subir

Necesitas subir estos archivos al servidor de Hostinger:

1. **Nuevo servicio:**
   - `app/Services/N8nService.php`

2. **Nueva página:**
   - `app/Filament/Pages/N8nAutomatizaciones.php`

3. **Nueva vista:**
   - `resources/views/filament/pages/n8n-automatizaciones.blade.php`

4. **Configuración actualizada:**
   - `config/services.php` (ya tiene la configuración de n8n)

5. **Variables de entorno:**
   - Agregar al `.env` del servidor:
     ```env
     N8N_URL=https://n8n.srv1137974.hstgr.cloud
     N8N_API_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNWNhODY2Ny0wYmNhLTQwYjAtOWFhYS04ZTBhZDA0ODE1ZWMiLCJpc3MiOiJuOG4iLCJhdWQiOiJwdWJsaWMtYXBpIiwiaWF0IjoxNzY0OTkyMjk4fQ.HLP8p4yzzk81Bt5W5ppgi8Em8qy1QECNbSbdrhivqvk
     ```

## 🚀 Pasos para Actualizar en el Servidor

### Opción 1: Usando Git

```bash
cd /home/u655097049/domains/websolutions.work
git pull origin main
composer dump-autoload --optimize
php artisan config:clear
php artisan view:clear
php artisan filament:cache-components
```

### Opción 2: Subir Archivos Manualmente

1. **Sube los archivos vía FTP/SFTP o File Manager**

2. **Agrega las variables al .env del servidor:**
   ```bash
   cd /home/u655097049/domains/websolutions.work
   echo "" >> .env
   echo "# n8n Configuration" >> .env
   echo "N8N_URL=https://n8n.srv1137974.hstgr.cloud" >> .env
   echo "N8N_API_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNWNhODY2Ny0wYmNhLTQwYjAtOWFhYS04ZTBhZDA0ODE1ZWMiLCJpc3MiOiJuOG4iLCJhdWQiOiJwdWJsaWMtYXBpIiwiaWF0IjoxNzY0OTkyMjk4fQ.HLP8p4yzzk81Bt5W5ppgi8Em8qy1QECNbSbdrhivqvk" >> .env
   ```

3. **Ejecuta estos comandos:**
   ```bash
   composer dump-autoload --optimize
   php artisan config:clear
   php artisan view:clear
   php artisan filament:cache-components
   ```

## ✅ Verificación

Después de actualizar:

1. **Verifica que los archivos existen:**
   ```bash
   ls -la app/Services/N8nService.php
   ls -la app/Filament/Pages/N8nAutomatizaciones.php
   ls -la resources/views/filament/pages/n8n-automatizaciones.blade.php
   ```

2. **Verifica las variables de entorno:**
   ```bash
   grep N8N .env
   ```

3. **Accede a la página:**
   - Ve a: https://websolutions.work/admin/n8n-automatizaciones
   - Deberías ver los workflows en formato de filas con todas las opciones

## 🎨 Características del Nuevo Diseño

- ✅ **Diseño en filas**: Cada workflow en su propia tarjeta
- ✅ **Búsqueda en tiempo real**: Filtra workflows por nombre
- ✅ **Filtro por estado**: Activos/Inactivos
- ✅ **Acciones por workflow**:
  - Editar (abre en n8n)
  - Activar/Desactivar
  - Ejecutar
  - Ver Nodos (expandible)
- ✅ **Información detallada**: Nodos, fecha de actualización, ID
- ✅ **Diseño responsive**: Se adapta a diferentes tamaños de pantalla

