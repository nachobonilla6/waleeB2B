# Webhook n8n → Filament Notifications

Este documento explica cómo recibir datos de n8n y convertirlos en notificaciones en Filament.

## 📋 URL del Webhook

```
POST https://websolutions.work/api/n8n-webhook
```

## 🔧 Configuración en n8n

### Opción 1: Enviar un solo elemento

En tu workflow de n8n, después de procesar los datos, agrega un nodo **HTTP Request**:

1. **Method**: `POST`
2. **URL**: `https://websolutions.work/api/n8n-webhook`
3. **Body Content Type**: `JSON`
4. **JSON Body**: 
```json
{
  "titulo": "Nuevo cliente agregado",
  "mensaje": "Se ha agregado un nuevo cliente: Juan Pérez",
  "tipo": "success"
}
```

### Opción 2: Enviar múltiples elementos (array)

Si tienes múltiples elementos en n8n, puedes enviarlos como array:

```json
[
  {
    "titulo": "Cliente 1",
    "mensaje": "Mensaje del cliente 1",
    "tipo": "info"
  },
  {
    "titulo": "Cliente 2",
    "mensaje": "Mensaje del cliente 2",
    "tipo": "success"
  }
]
```

### Opción 3: Usar estructura n8n estándar

n8n puede enviar los datos con la estructura estándar:

```json
[
  {
    "json": {
      "titulo": "Título de la notificación",
      "mensaje": "Mensaje de la notificación",
      "tipo": "warning"
    }
  }
]
```

## 📝 Campos Soportados

El webhook busca automáticamente los siguientes campos (en orden de prioridad):

### Título
- `titulo`, `Titulo`
- `title`, `Title`
- `nombre`, `Nombre`
- `name`, `Name`
- Si no encuentra ninguno, usa: "Notificación de n8n" o "Notificación X" (si hay múltiples)

### Mensaje/Cuerpo
- `mensaje`, `Mensaje`
- `message`, `Message`
- `texto`, `Texto`
- `text`
- `descripcion`, `Descripcion`
- `description`
- Si no encuentra ninguno, construye el body con los demás campos disponibles

### Tipo/Status
- `tipo`, `Tipo`, `type`, `status`, `estado`
- Valores soportados:
  - `success`, `exito`, `éxito`, `ok` → Notificación verde (éxito)
  - `warning`, `advertencia`, `alerta` → Notificación amarilla (advertencia)
  - `danger`, `error`, `fallo` → Notificación roja (error)
  - Cualquier otro valor → Notificación azul (info) - por defecto

### Icono (opcional)
- `icono`, `Icono`, `icon`
- Puedes usar cualquier icono de Heroicons (ej: `heroicon-o-bell`, `heroicon-o-check-circle`)
- Si no se especifica, se asigna automáticamente según el tipo

## 🎨 Ejemplos de Uso

### Ejemplo 1: Notificación simple
```json
{
  "titulo": "Nuevo pedido",
  "mensaje": "Se ha recibido un nuevo pedido #1234",
  "tipo": "success"
}
```

### Ejemplo 2: Notificación con campos personalizados
```json
{
  "titulo": "Error en proceso",
  "mensaje": "El proceso de sincronización falló",
  "tipo": "danger",
  "icono": "heroicon-o-x-circle"
}
```

### Ejemplo 3: Múltiples notificaciones
```json
[
  {
    "titulo": "Cliente agregado",
    "mensaje": "Cliente: Juan Pérez",
    "tipo": "success"
  },
  {
    "titulo": "Factura generada",
    "mensaje": "Factura #001 creada",
    "tipo": "info"
  }
]
```

### Ejemplo 4: Usando campos en inglés
```json
{
  "title": "New Order",
  "message": "Order #1234 has been received",
  "type": "success"
}
```

## 🔍 Debugging

El webhook registra todas las peticiones en los logs de Laravel. Puedes verlos con:

```bash
tail -f storage/logs/laravel.log
```

O desde Filament, revisa los logs del sistema.

## ✅ Respuesta del Webhook

### Éxito
```json
{
  "success": true,
  "message": "Notificaciones recibidas y enviadas a Filament",
  "elements_processed": 2,
  "notifications_sent": 4,
  "timestamp": "2024-01-15 10:30:00"
}
```

### Error
```json
{
  "success": false,
  "message": "Error procesando webhook: [mensaje de error]",
  "timestamp": "2024-01-15 10:30:00"
}
```

## 📱 Ver las Notificaciones en Filament

Las notificaciones aparecerán:
1. **Como toast** en la esquina superior derecha (durante 10 segundos)
2. **En la campana de notificaciones** (icono de campana en la barra superior)
3. Se envían a **todos los usuarios** del sistema

## 🔐 Seguridad

Actualmente el webhook es público. Si necesitas seguridad adicional, puedes:

1. Agregar autenticación por token en el header
2. Validar IPs permitidas
3. Agregar rate limiting

Para agregar autenticación, modifica la ruta en `routes/api.php` para validar un token:

```php
Route::post('/n8n-webhook', function (\Illuminate\Http\Request $request) {
    // Validar token
    if ($request->header('X-API-Key') !== env('N8N_WEBHOOK_TOKEN')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    // ... resto del código
});
```

Y en n8n, agrega el header:
- **Name**: `X-API-Key`
- **Value**: `tu_token_secreto`
