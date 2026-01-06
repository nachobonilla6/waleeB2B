# Sistema de Órdenes Programadas - Bot Alpha

## Arquitectura

```
Webhook (crea/actualiza órdenes)
        ↓
Base de datos (ordenes_programadas)
        ↓
Schedule Trigger n8n (cada 30 min)
        ↓
Procesar órdenes pendientes
```

## Estructura de la Base de Datos

### Tabla: `ordenes_programadas`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único |
| `tipo` | enum | `extraccion_clientes` o `emails_automaticos` |
| `activo` | boolean | Si la orden está activa |
| `recurrencia_horas` | decimal(5,2) | Recurrencia en horas (0.5, 1, 2, etc.) |
| `last_run` | timestamp | Última vez que se ejecutó |
| `configuracion` | json | Configuración adicional |
| `user_id` | bigint | Usuario que creó la orden |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Fecha de actualización |

## Endpoints API

### 1. Crear/Actualizar Orden Programada

**POST** `/api/ordenes-programadas`

**Payload:**
```json
{
  "tipo": "extraccion_clientes",
  "activo": true,
  "recurrencia_horas": 0.5,
  "configuracion": {
    "webhook_url": "https://example.com/webhook",
    "filtros": {}
  },
  "user_id": 1
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Orden creada correctamente",
  "data": {
    "id": 1,
    "tipo": "extraccion_clientes",
    "activo": true,
    "recurrencia_horas": "0.50",
    "last_run": null,
    "configuracion": {...},
    "user_id": 1,
    "created_at": "2026-01-06T00:00:00.000000Z",
    "updated_at": "2026-01-06T00:00:00.000000Z"
  }
}
```

**Nota:** Si ya existe una orden del mismo tipo para el usuario, se actualiza en lugar de crear una nueva.

### 2. Obtener Órdenes Pendientes

**GET** `/api/ordenes-programadas/pendientes`

**Parámetros opcionales:**
- `minutos` (default: 30) - Intervalo mínimo desde last_run
- `tipo` (opcional) - Filtrar por tipo

**Ejemplo:**
```
GET /api/ordenes-programadas/pendientes?minutos=30&tipo=extraccion_clientes
```

**Respuesta:**
```json
{
  "success": true,
  "count": 2,
  "data": [
    {
      "id": 1,
      "tipo": "extraccion_clientes",
      "activo": true,
      "recurrencia_horas": "0.50",
      "last_run": null,
      ...
    }
  ]
}
```

### 3. Marcar Orden como Ejecutada

**POST** `/api/ordenes-programadas/{id}/ejecutar`

**Respuesta:**
```json
{
  "success": true,
  "message": "Orden marcada como ejecutada",
  "data": {
    "id": 1,
    "last_run": "2026-01-06T12:30:00.000000Z",
    ...
  }
}
```

## Configuración en n8n

### Paso 1: Webhook (crear/actualizar órdenes)

**Nodo:** HTTP Request

**Método:** POST

**URL:** `https://tu-dominio.com/api/ordenes-programadas`

**Body:**
```json
{
  "tipo": "{{ $json.tipo }}",
  "activo": {{ $json.activo }},
  "recurrencia_horas": {{ $json.recurrencia_horas }},
  "user_id": {{ $json.user_id }}
}
```

### Paso 2: Schedule Trigger

**Nodo:** Schedule Trigger

**Configuración:**
- Mode: `Every X`
- Value: `30`
- Unit: `minutes`

### Paso 3: Leer Órdenes Pendientes

**Nodo:** HTTP Request

**Método:** GET

**URL:** `https://tu-dominio.com/api/ordenes-programadas/pendientes?minutos=30`

### Paso 4: Procesar Cada Orden

**Nodo:** Split In Batches (opcional) o Loop

Para cada orden pendiente:
1. Ejecutar la acción correspondiente (extracción o envío de emails)
2. Marcar como ejecutada: `POST /api/ordenes-programadas/{id}/ejecutar`

### Ejemplo de Workflow n8n

```
Schedule Trigger (30 min)
    ↓
HTTP Request (GET /api/ordenes-programadas/pendientes)
    ↓
Split In Batches
    ↓
IF tipo === 'extraccion_clientes'
    ↓
    Ejecutar extracción
    ↓
ELSE IF tipo === 'emails_automaticos'
    ↓
    Enviar emails
    ↓
HTTP Request (POST /api/ordenes-programadas/{id}/ejecutar)
```

## SQL para Consultas Manuales

### Obtener órdenes pendientes (para n8n)

```sql
SELECT *
FROM ordenes_programadas
WHERE activo = 1
AND (
  last_run IS NULL
  OR last_run <= NOW() - INTERVAL 30 MINUTE
);
```

### Obtener órdenes por tipo

```sql
SELECT *
FROM ordenes_programadas
WHERE tipo = 'extraccion_clientes'
AND activo = 1;
```

### Crear orden manualmente

```sql
INSERT INTO ordenes_programadas 
(tipo, activo, recurrencia_horas, last_run, user_id, created_at, updated_at)
VALUES 
('extraccion_clientes', 1, 0.5, NULL, 1, NOW(), NOW());
```

### Actualizar last_run después de ejecutar

```sql
UPDATE ordenes_programadas
SET last_run = NOW()
WHERE id = 1;
```

## Tipos de Recurrencia

- `0.5` - Cada media hora
- `1` - Cada una hora
- `2` - Cada 2 horas
- `4` - Cada 4 horas
- `6` - Cada 6 horas
- `8` - Cada 8 horas
- `12` - Cada 12 horas
- `24` - Cada 24 horas
- `48` - Cada 48 horas
- `76` - Cada 76 horas

## Tipos de Orden

- `extraccion_clientes` - Extracción automática de clientes
- `emails_automaticos` - Envío automático de emails

## Notas Importantes

1. **Una orden por tipo y usuario:** El sistema actualiza automáticamente si ya existe una orden del mismo tipo para el usuario.

2. **Schedule Trigger:** Configurar en n8n para ejecutarse cada 30 minutos (o según la recurrencia mínima).

3. **last_run:** Se actualiza automáticamente cuando se marca la orden como ejecutada.

4. **activo:** Las órdenes inactivas no se procesan, incluso si están pendientes.

5. **Validación:** El webhook valida que `recurrencia_horas` sea mínimo 0.5.

