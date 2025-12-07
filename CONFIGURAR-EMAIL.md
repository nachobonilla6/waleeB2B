# Configuración de Email en Hostinger

Para que los emails se envíen correctamente desde `nachobonilla6@gmail.com`, necesitas configurar el archivo `.env` en el servidor.

## Pasos:

1. **Conecta por SSH al servidor de Hostinger**

2. **Edita el archivo `.env`**:
   ```bash
   cd /home/u655097049/domains/websolutions.work
   nano .env
   ```

3. **Agrega o actualiza estas líneas**:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=nachobonilla6@gmail.com
   MAIL_PASSWORD=TU_CONTRASEÑA_DE_APLICACION_AQUI
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=nachobonilla6@gmail.com
   MAIL_FROM_NAME="WALEÉ"
   ```

4. **Para obtener la contraseña de aplicación de Gmail**:
   - Ve a tu cuenta de Google: https://myaccount.google.com/
   - Seguridad → Verificación en 2 pasos (debe estar activada)
   - Contraseñas de aplicaciones → Generar nueva contraseña
   - Usa esa contraseña (16 caracteres) en `MAIL_PASSWORD`

5. **Limpia la caché de configuración**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

6. **Prueba enviando una factura o cotización desde el panel**

## Nota:
- NO uses tu contraseña normal de Gmail
- Debes usar una "Contraseña de aplicación" generada específicamente
- Si no tienes verificación en 2 pasos activada, actívala primero

