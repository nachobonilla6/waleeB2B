# 📱 Configuración del Webhook de WhatsApp en n8n

## URL del Webhook

**URL Principal:**
```
https://websolutions.work/api/whatsapp/webhook/1c5f2da5-0d1a-4d87-a9da-bb1544748868
```

**URL de Prueba (sin ID):**
```
https://websolutions.work/api/whatsapp/webhook-test
```

**URL de Test Simple (solo logging):**
```
https://websolutions.work/api/whatsapp/test
```

## Configuración en n8n

### Paso 1: Configurar el nodo Webhook

1. Agrega un nodo **Webhook** en n8n
2. Configura:
   - **HTTP Method**: `POST`
   - **Path**: `/api/whatsapp/webhook/1c5f2da5-0d1a-4d87-a9da-bb1544748868`
   - **Response Mode**: `Last Node`
   - **Response Data**: `All Entries`

### Paso 2: Configurar el nodo HTTP Request (si usas HTTP Request en lugar de Webhook)

Si estás usando un nodo **HTTP Request** para enviar datos:

1. **Method**: `POST`
2. **URL**: `https://websolutions.work/api/whatsapp/webhook/1c5f2da5-0d1a-4d87-a9da-bb1544748868`
3. **Headers**:
   - `Content-Type: application/json`
4. **Body**:
   - **Body Content Type**: `JSON`
   - **JSON**: Ver formatos abajo

## Formatos de Datos Soportados

### Formato Recomendado (Mensaje Único)

```json
{
  "phone_number": "+50612345678",
  "contact_name": "Juan Pérez",
  "content": "Hola, ¿cómo estás?",
  "direction": "incoming",
  "message_id": "unique_id_123",
  "timestamp": 1706457600
}
```

### Formato con Array (Múltiples Mensajes)

```json
[
  {
    "phone_number": "+50612345678",
    "content": "Mensaje 1",
    "direction": "incoming"
  },
  {
    "phone_number": "+50687654321",
    "content": "Mensaje 2",
    "direction": "incoming"
  }
]
```

### Formato n8n Estándar (con campo json)

```json
[
  {
    "json": {
      "phone_number": "+50612345678",
      "content": "Mensaje",
      "direction": "incoming"
    }
  }
]
```

## Campos Requeridos y Opcionales

### Campos Requeridos:
- **phone_number** (o `from`, `to`, `number`) - Número de teléfono

### Campos Opcionales:
- **content** (o `text`, `message`, `body`) - Contenido del mensaje
- **contact_name** (o `name`) - Nombre del contacto
- **direction** - `incoming` o `outgoing` (por defecto: `incoming`)
- **message_id** - ID único del mensaje (previene duplicados)
- **timestamp** - Timestamp del mensaje
- **message_type** - Tipo de mensaje (`text`, `image`, `video`, etc.)
- **media_url** - URL del archivo multimedia
- **media_type** - Tipo de media
- **media_name** - Nombre del archivo

## Ejemplo de Configuración en n8n

### Si usas WhatsApp Business API:

```javascript
// En el nodo Code o Function de n8n
return [{
  json: {
    phone_number: $input.item.json.from || $input.item.json.to,
    contact_name: $input.item.json.contact?.name || null,
    content: $input.item.json.body || $input.item.json.text || '',
    direction: $input.item.json.type === 'sent' ? 'outgoing' : 'incoming',
    message_id: $input.item.json.id,
    timestamp: $input.item.json.timestamp,
    message_type: $input.item.json.type || 'text'
  }
}];
```

### Si usas otro servicio de WhatsApp:

Ajusta los campos según la estructura de datos que recibes.

## Verificación

### 1. Probar el Endpoint de Test

Usa este endpoint primero para verificar que n8n puede llegar al servidor:

```bash
curl -X POST https://websolutions.work/api/whatsapp/test \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'
```

Deberías recibir una respuesta JSON con `success: true`.

### 2. Verificar los Logs

Después de enviar un webhook, revisa los logs:

```bash
tail -f storage/logs/laravel.log | grep -i whatsapp
```

O en el servidor:
```bash
tail -f /ruta/a/storage/logs/laravel.log | grep -i whatsapp
```

### 3. Verificar en la Base de Datos

```sql
-- Ver conversaciones recientes
SELECT * FROM whatsapp_conversations ORDER BY created_at DESC LIMIT 10;

-- Ver mensajes recientes
SELECT * FROM whatsapp_messages ORDER BY created_at DESC LIMIT 10;
```

## Troubleshooting

### Problema: No se guardan los datos

1. **Verifica que las tablas existan:**
   ```bash
   php artisan whatsapp:check-tables
   ```

2. **Verifica los logs:**
   ```bash
   tail -n 100 storage/logs/laravel.log
   ```

3. **Prueba el endpoint de test:**
   ```bash
   curl -X POST https://websolutions.work/api/whatsapp/test \
     -H "Content-Type: application/json" \
     -d '{"phone_number": "+50612345678", "content": "Test"}'
   ```

### Problema: Error 404

- Verifica que la URL sea correcta
- Verifica que el servidor esté accesible
- Verifica que las rutas estén cargadas: `php artisan route:list | grep whatsapp`

### Problema: Error 500

- Revisa los logs para ver el error específico
- Verifica que las tablas existan
- Verifica permisos de la base de datos

### Problema: Datos no se procesan

- Verifica que el formato de datos coincida con los ejemplos
- Revisa los logs para ver qué datos están llegando
- Asegúrate de que el campo `phone_number` esté presente

## Notas Importantes

1. El webhook **NO requiere autenticación** (es público)
2. Si necesitas seguridad, puedes agregar validación por token en el futuro
3. Los mensajes duplicados se previenen usando el campo `message_id`
4. El sistema detecta automáticamente si un mensaje es entrante o saliente
5. Los logs incluyen toda la información recibida para debugging

## Soporte

Si después de seguir estos pasos el problema persiste:

1. Revisa los logs completos
2. Ejecuta `php artisan whatsapp:check-tables`
3. Prueba el endpoint de test
4. Verifica la configuración en n8n

