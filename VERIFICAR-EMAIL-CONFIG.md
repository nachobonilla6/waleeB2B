# Verificar Configuración de Email en el Servidor

## Cómo verificar si ya está configurada:

1. **Conecta por SSH al servidor de Hostinger**

2. **Revisa el archivo `.env`**:
   ```bash
   cd /home/u655097049/domains/websolutions.work
   cat .env | grep MAIL
   ```

3. **Si ves algo como esto, ya está configurado**:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=nachobonilla6@gmail.com
   MAIL_PASSWORD=xxxx xxxx xxxx xxxx
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=nachobonilla6@gmail.com
   MAIL_FROM_NAME="WALEÉ"
   ```

## Si NO está configurado o necesitas crear una nueva contraseña:

### Opción 1: Si ya tienes una contraseña de aplicación
- Solo necesitas agregarla al `.env` del servidor en `MAIL_PASSWORD`

### Opción 2: Si necesitas crear una nueva contraseña de aplicación

1. Ve a: https://myaccount.google.com/apppasswords
2. O ve a: https://myaccount.google.com/ → Seguridad → Verificación en 2 pasos → Contraseñas de aplicaciones
3. Selecciona:
   - **Aplicación**: Correo
   - **Dispositivo**: Otro (nombre personalizado) → "WALEÉ Laravel"
4. Haz clic en **Generar**
5. **Copia la contraseña de 16 caracteres** (ejemplo: `abcd efgh ijkl mnop`)
6. Agrégala al `.env` del servidor (sin espacios):
   ```env
   MAIL_PASSWORD=abcdefghijklmnop
   ```

## Después de configurar:

```bash
php artisan config:clear
php artisan cache:clear
```

## Probar el envío:

Intenta crear una factura o cotización y usar el botón "Guardar y Enviar por Email". Si hay un error, aparecerá en la notificación.

