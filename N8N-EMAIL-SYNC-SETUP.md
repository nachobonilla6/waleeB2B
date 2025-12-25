# Configuración de Sincronización de Emails con n8n

Esta guía explica cómo configurar un workflow en n8n para sincronizar emails desde Gmail (o cualquier otro proveedor) a tu aplicación Laravel.

## 📋 Requisitos Previos

1. Tener acceso a n8n
2. Tener una cuenta de Gmail configurada en n8n (o el proveedor de email que uses)
3. Conocer la URL de tu aplicación Laravel

## 🔧 Paso 1: Crear el Workflow en n8n

1. Ve a tu instancia de n8n
2. Crea un nuevo workflow
3. Nómbralo: "Sincronizar Emails Recibidos"

## 📥 Paso 2: Configurar el Webhook Trigger

1. **Agrega un nodo "Webhook"** como primer nodo
2. **Configuración del Webhook:**
   - **HTTP Method**: `POST`
   - **Path**: `/email-sync` (o el que prefieras)
   - **Response Mode**: "Respond When Last Node Finishes"
   - **Options** → **Response Code**: `200`

3. **Activa el workflow** para obtener la URL del webhook
4. **Copia la URL completa** del webhook (ejemplo: `https://n8n.srv1137974.hstgr.cloud/webhook/email-sync`)

## 📧 Paso 3: Configurar Gmail (o tu proveedor de email)

1. **Agrega un nodo "Gmail"** después del Webhook
2. **Operación**: "Get All Messages" o "Search Messages"
3. **Configuración:**
   - **Credential**: Conecta tu cuenta de Gmail
   - **Query**: `is:unread` (para obtener solo no leídos) o déjalo vacío para todos
   - **Max Results**: `50` (ajusta según necesites)
   - **Format**: `full` o `metadata`

## 🔄 Paso 4: Procesar los Emails

1. **Agrega un nodo "Code"** o "Function" para transformar los datos
2. **Código de ejemplo** (JavaScript):

```javascript
// Procesar los emails recibidos de Gmail
const emails = [];

for (const item of $input.all()) {
  const message = item.json;
  
  // Extraer información del email
  const emailData = {
    message_id: message.id || message.messageId,
    from_email: message.from?.email || message.from || '',
    from_name: message.from?.name || message.from?.split('<')[0]?.trim() || null,
    subject: message.subject || 'Sin asunto',
    body: message.textPlain || message.text || '',
    body_html: message.textHtml || null,
    attachments: message.attachments?.map(att => ({
      filename: att.filename,
      mimeType: att.mimeType,
      size: att.size,
      attachmentId: att.attachmentId
    })) || [],
    received_at: message.date || new Date().toISOString()
  };
  
  emails.push(emailData);
}

return emails.map(email => ({ json: email }));
```

## 📤 Paso 5: Enviar Emails a Laravel

1. **Agrega un nodo "HTTP Request"**
2. **Configuración:**
   - **Method**: `POST`
   - **URL**: `https://websolutions.work/api/emails/recibidos`
   - **Authentication**: None (o Basic si tienes autenticación)
   - **Send Body**: `Yes`
   - **Body Content Type**: `JSON`
   - **JSON Body**: 
   ```json
   {{ $json }}
   ```

3. **Opcional - Si quieres enviar múltiples emails:**
   - Usa el modo "Split In Batches" antes del HTTP Request
   - O envía todo el array directamente (el endpoint lo soporta)

## ✅ Paso 6: Respuesta Final

1. **Agrega otro nodo "HTTP Request"** o "Respond to Webhook" para confirmar
2. **O simplemente deja que el webhook responda automáticamente**

## 🔐 Paso 7: Configurar la URL en Laravel

1. Agrega la URL del webhook en tu archivo `.env`:

```env
N8N_EMAIL_SYNC_WEBHOOK_URL=https://n8n.srv1137974.hstgr.cloud/webhook/email-sync
```

2. O usa la URL por defecto si no la configuras

## 📊 Estructura del Workflow Completo

```
[Webhook] 
    ↓
[Gmail - Get Messages]
    ↓
[Code - Transform Data]
    ↓
[HTTP Request - Send to Laravel]
    ↓
[Respond to Webhook]
```

## 🎯 Formato de Datos Esperado

El endpoint `/api/emails/recibidos` espera recibir datos en cualquiera de estos formatos:

### Opción 1: Array de emails
```json
[
  {
    "message_id": "12345",
    "from_email": "sender@example.com",
    "from_name": "Nombre del Remitente",
    "subject": "Asunto del email",
    "body": "Cuerpo del email en texto plano",
    "body_html": "<p>Cuerpo del email en HTML</p>",
    "attachments": [],
    "received_at": "2025-01-15T10:30:00Z"
  }
]
```

### Opción 2: Objeto con campo 'emails'
```json
{
  "emails": [
    {
      "message_id": "12345",
      "from_email": "sender@example.com",
      ...
    }
  ]
}
```

### Opción 3: Un solo email
```json
{
  "message_id": "12345",
  "from_email": "sender@example.com",
  ...
}
```

## 🔄 Alternativa: Sincronización Automática

Si quieres que se sincronice automáticamente cada X tiempo:

1. **Reemplaza el nodo "Webhook"** con un nodo **"Schedule Trigger"**
2. **Configura la frecuencia**: Cada hora, cada 30 minutos, etc.
3. El resto del workflow permanece igual

## 🐛 Troubleshooting

### El webhook no responde
- Verifica que el workflow esté activado
- Revisa los logs de n8n
- Verifica que la URL del webhook sea correcta

### Los emails no se guardan
- Revisa los logs de Laravel: `storage/logs/laravel.log`
- Verifica que el formato de datos sea correcto
- Asegúrate de que el endpoint `/api/emails/recibidos` esté accesible

### Error de autenticación con Gmail
- Verifica las credenciales de Gmail en n8n
- Asegúrate de tener los permisos necesarios
- Puede que necesites re-autenticar

## 📝 Notas Importantes

1. **Deduplicación**: El endpoint ya verifica si un email existe por `message_id`, así que puedes ejecutar la sincronización múltiples veces sin duplicar.

2. **Rendimiento**: Si tienes muchos emails, considera procesarlos en lotes.

3. **Seguridad**: El endpoint `/api/emails/recibidos` es público. Considera agregar autenticación si es necesario.

4. **Frecuencia**: No sincronices muy frecuentemente para evitar sobrecargar el sistema.

## 🚀 Ejemplo de Workflow Completo

```
┌─────────────┐
│   Webhook   │ ← Recibe petición de sincronización
└──────┬──────┘
       │
       ↓
┌─────────────┐
│    Gmail    │ ← Obtiene emails no leídos
│ Get Messages│
└──────┬──────┘
       │
       ↓
┌─────────────┐
│    Code     │ ← Transforma datos al formato esperado
│  Transform  │
└──────┬──────┘
       │
       ↓
┌─────────────┐
│ HTTP Request│ ← Envía a Laravel
│  to Laravel │
└──────┬──────┘
       │
       ↓
┌─────────────┐
│   Respond   │ ← Confirma sincronización
└─────────────┘
```

¡Listo! Con esta configuración, cuando hagas clic en "Sincronizar" en la página de emails recibidos, se ejecutará el workflow y traerá los nuevos emails desde Gmail.

