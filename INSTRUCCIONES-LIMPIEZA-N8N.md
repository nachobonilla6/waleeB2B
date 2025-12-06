# Instrucciones para Eliminar Recursos de n8n en Hostinger

## ✅ Cambios Realizados Localmente

Todos los recursos de n8n han sido eliminados del código local:
- ✅ Modelos (N8nBot, N8nError, N8nPost)
- ✅ Servicios (N8nService)
- ✅ Recursos de Filament
- ✅ Páginas y vistas
- ✅ Migraciones y seeders
- ✅ Referencias en configuración y rutas

## 🚀 Pasos para Limpiar en el Servidor de Hostinger

### Opción 1: Usando el Script Automático (Recomendado)

1. **Conectarse al servidor de Hostinger vía SSH:**
   ```bash
   ssh u655097049@your-server.hostinger.com
   ```

2. **Navegar al directorio del proyecto:**
   ```bash
   cd /home/u655097049/domains/websolutions.work
   ```

3. **Subir el script de limpieza:**
   - Sube el archivo `cleanup-n8n-server.sh` al servidor
   - O copia su contenido y créalo en el servidor

4. **Ejecutar el script:**
   ```bash
   chmod +x cleanup-n8n-server.sh
   ./cleanup-n8n-server.sh
   ```

### Opción 2: Usando Git Pull (Más Seguro)

Si los cambios ya están en el repositorio Git:

1. **Conectarse al servidor vía SSH:**
   ```bash
   ssh u655097049@your-server.hostinger.com
   ```

2. **Navegar al directorio del proyecto:**
   ```bash
   cd /home/u655097049/domains/websolutions.work
   ```

3. **Hacer pull de los cambios:**
   ```bash
   git pull origin main
   ```

4. **Regenerar autoload y limpiar caché:**
   ```bash
   composer dump-autoload --optimize
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

### Opción 3: Eliminación Manual

Si prefieres hacerlo manualmente, ejecuta estos comandos en el servidor:

```bash
cd /home/u655097049/domains/websolutions.work

# Eliminar modelos
rm -f app/Models/N8nBot.php
rm -f app/Models/N8nError.php
rm -f app/Models/N8nPost.php

# Eliminar servicio
rm -f app/Services/N8nService.php

# Eliminar recursos de Filament
rm -rf app/Filament/Resources/N8nBotResource
rm -rf app/Filament/Resources/N8nErrorResource
rm -rf app/Filament/Resources/N8nPostResource
rm -rf app/Filament/Resources/VelaSportPostResource

# Eliminar página
rm -f app/Filament/Pages/N8nWorkflows.php
rm -f resources/views/filament/pages/n8n-workflows.blade.php

# Eliminar vistas
rm -rf resources/views/filament/resources/n8n-post-resource
rm -rf resources/views/filament/resources/vela-sport-post-resource

# Eliminar migraciones
rm -f database/migrations/*_create_n8n_*.php
rm -f database/migrations/*_add_*_to_n8n_*.php

# Eliminar seeder
rm -f database/seeders/N8nBotSeeder.php

# Regenerar autoload
composer dump-autoload --optimize

# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## ⚠️ Notas Importantes

1. **Backup:** Antes de ejecutar cualquier comando, asegúrate de tener un backup del servidor.

2. **Base de Datos:** Las tablas de n8n en la base de datos NO se eliminan automáticamente. Si quieres eliminarlas, ejecuta:
   ```bash
   php artisan migrate:rollback --step=X
   ```
   (Donde X es el número de migraciones de n8n que quieres revertir)

3. **Webhooks:** Los webhooks de n8n en otros archivos (como DeployButton, ClientResource, etc.) siguen funcionando pero apuntan a URLs de n8n. Si quieres eliminarlos también, necesitarás modificar esos archivos manualmente.

4. **Verificación:** Después de la limpieza, verifica que la aplicación funcione correctamente:
   ```bash
   php artisan route:list | grep n8n
   ```
   No debería mostrar ninguna ruta relacionada con n8n.

## 📝 Archivos Modificados (No Eliminados)

Los siguientes archivos fueron modificados pero no eliminados (contienen referencias a webhooks que podrían seguir siendo útiles):

- `app/Livewire/DeployButton.php` - Contiene webhook de deploy
- `app/Filament/Resources/ClientResource.php` - Contiene webhooks para envío de propuestas
- `app/Filament/Resources/SitioResource/Pages/EditSitio.php` - Contiene webhook para actualización de sitios
- `app/Filament/Pages/SiteScraper.php` - Webhook deshabilitado
- `app/Filament/Pages/BotConfiguracion.php` - Configuración actualizada

Si quieres eliminar también estas referencias, puedes hacerlo manualmente o pedir que se eliminen.

