# 🔍 Diagnóstico del Webhook de WhatsApp

## Problema: No se está guardando la información

### Pasos para diagnosticar:

#### 1. Verificar que las tablas existan

Ejecuta el comando de verificación:
```bash
php artisan whatsapp:check-tables
```

O verifica manualmente:
```bash
php artisan migrate:status
```

Si las tablas no existen, ejecuta:
```bash
php artisan migrate
```

#### 2. Verificar los logs

Revisa los logs de Laravel para ver qué está llegando al webhook:
```bash
tail -f storage/logs/laravel.log | grep -i whatsapp
```

O revisa el archivo completo:
```bash
tail -n 100 storage/logs/laravel.log
```

#### 3. Probar el webhook manualmente

Puedes probar el webhook con curl:

```bash
curl -X POST https://tu-dominio.com/api/whatsapp/webhook/1c5f2da5-0d1a-4d87-a9da-bb1544748868 \
  -H "Content-Type: application/json" \
  -d '{
    "phone_number": "+50612345678",
    "contact_name": "Test User",
    "content": "Mensaje de prueba",
    "direction": "incoming",
    "message_id": "test_123"
  }'
```

O usar el endpoint de prueba:
```bash
curl -X POST https://tu-dominio.com/api/whatsapp/webhook-test \
  -H "Content-Type: application/json" \
  -d '{
    "phone_number": "+50612345678",
    "content": "Mensaje de prueba"
  }'
```

#### 4. Verificar la configuración en n8n

Asegúrate de que en n8n:

1. **URL del webhook**: `https://tu-dominio.com/api/whatsapp/webhook/1c5f2da5-0d1a-4d87-a9da-bb1544748868`
2. **Método**: POST
3. **Content-Type**: application/json
4. **Datos enviados** deben incluir al menos:
   - `phone_number` o `from` o `to` (número de teléfono)
   - `content` o `text` o `message` (contenido del mensaje)

#### 5. Formatos de datos soportados

El webhook acepta múltiples formatos:

**Formato 1 - Mensaje único:**
```json
{
  "phone_number": "+50612345678",
  "contact_name": "Juan Pérez",
  "content": "Hola, ¿cómo estás?",
  "direction": "incoming",
  "message_id": "unique_id_123"
}
```

**Formato 2 - Array de mensajes:**
```json
[
  {
    "phone_number": "+50612345678",
    "content": "Mensaje 1"
  },
  {
    "phone_number": "+50687654321",
    "content": "Mensaje 2"
  }
]
```

**Formato 3 - Con campo data:**
```json
{
  "data": [
    {
      "phone_number": "+50612345678",
      "content": "Mensaje"
    }
  ]
}
```

**Formato 4 - Estructura n8n:**
```json
[
  {
    "json": {
      "phone_number": "+50612345678",
      "content": "Mensaje"
    }
  }
]
```

#### 6. Verificar en la base de datos

Puedes verificar directamente en la base de datos:

```sql
-- Ver conversaciones
SELECT * FROM whatsapp_conversations ORDER BY created_at DESC LIMIT 10;

-- Ver mensajes
SELECT * FROM whatsapp_messages ORDER BY created_at DESC LIMIT 10;

-- Contar registros
SELECT 
  (SELECT COUNT(*) FROM whatsapp_conversations) as conversaciones,
  (SELECT COUNT(*) FROM whatsapp_messages) as mensajes;
```

#### 7. Problemas comunes

**Problema**: Las tablas no existen
- **Solución**: Ejecuta `php artisan migrate`

**Problema**: Error de permisos en base de datos
- **Solución**: Verifica que el usuario de la BD tenga permisos de INSERT/UPDATE

**Problema**: El formato de datos no coincide
- **Solución**: Revisa los logs para ver qué formato está llegando y ajusta n8n

**Problema**: El número de teléfono está vacío
- **Solución**: Asegúrate de que n8n envíe `phone_number`, `from`, `to` o `number`

**Problema**: Error de validación
- **Solución**: Revisa los logs para ver el error específico

#### 8. Logging mejorado

El webhook ahora tiene logging detallado. Cada vez que llega un webhook, se registra:

- Headers completos
- Body raw (sin parsear)
- Body parseado
- Cada paso del procesamiento
- Errores específicos

Revisa `storage/logs/laravel.log` para ver todos los detalles.

#### 9. Endpoints disponibles

- **Webhook principal**: `POST /api/whatsapp/webhook/{id}`
- **Webhook de prueba**: `POST /api/whatsapp/webhook-test`
- **Obtener conversaciones**: `GET /api/whatsapp/conversations` (requiere auth)
- **Obtener mensajes**: `GET /api/whatsapp/conversations/{id}/messages` (requiere auth)
- **Enviar mensaje**: `POST /api/whatsapp/conversations/{id}/send` (requiere auth)

#### 10. Contacto para soporte

Si después de seguir estos pasos el problema persiste:

1. Revisa los logs completos: `tail -n 200 storage/logs/laravel.log`
2. Ejecuta el comando de verificación: `php artisan whatsapp:check-tables`
3. Prueba el webhook manualmente con curl
4. Verifica que las tablas existan en la base de datos

