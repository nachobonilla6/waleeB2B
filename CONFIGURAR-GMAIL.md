# Configuración de Gmail para Envío de Emails

## Requisitos

Para enviar emails de cotizaciones, necesitas configurar Gmail en tu aplicación Laravel.

## Pasos para Configurar Gmail

### 1. Crear una App Password en Gmail

1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. Ve a **Seguridad** → **Verificación en 2 pasos** (debe estar activada)
3. Busca **Contraseñas de aplicaciones** o **App passwords**
4. Selecciona **Correo** y **Otro (nombre personalizado)**
5. Escribe "Web Solutions Laravel" o el nombre que prefieras
6. Haz clic en **Generar**
7. **Copia la contraseña de 16 caracteres** que aparece (la necesitarás)

### 2. Configurar Variables de Entorno

Agrega estas variables a tu archivo `.env` en el servidor:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="Web Solutions"
```

**Importante:**
- `MAIL_USERNAME`: Tu dirección de Gmail completa (ej: juan@gmail.com)
- `MAIL_PASSWORD`: La App Password de 16 caracteres que generaste (sin espacios)
- `MAIL_FROM_ADDRESS`: Debe ser el mismo email que `MAIL_USERNAME`
- `MAIL_FROM_NAME`: El nombre que aparecerá como remitente

### 3. Ejemplo de Configuración

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=websolutionscrnow@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=websolutionscrnow@gmail.com
MAIL_FROM_NAME="Web Solutions"
```

### 4. Después de Configurar

1. Guarda el archivo `.env`
2. Ejecuta en el servidor:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
3. Prueba enviando una cotización

## Notas Importantes

- ⚠️ **NO uses tu contraseña normal de Gmail**, usa una App Password
- ✅ La verificación en 2 pasos debe estar activada
- ✅ La App Password es de 16 caracteres (puede tener espacios, quítalos al ponerla en .env)
- ✅ El email se enviará automáticamente cuando hagas clic en "Enviar Correo Electrónico"

## Solución de Problemas

Si los emails no se envían:
1. Verifica que la verificación en 2 pasos esté activada
2. Verifica que la App Password sea correcta
3. Verifica que `MAIL_USERNAME` y `MAIL_FROM_ADDRESS` sean el mismo email
4. Revisa los logs: `storage/logs/laravel.log`

