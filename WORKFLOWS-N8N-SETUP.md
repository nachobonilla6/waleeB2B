# Sistema de Tracking de Workflows n8n → Laravel

Este documento explica el sistema completo de tracking de workflows de n8n en Laravel con Filament.

## 📋 Componentes Creados

### 1. Base de Datos
- **Tabla**: `workflow_runs`
- **Migración**: `database/migrations/2025_12_12_001843_create_workflow_runs_table.php`
- **Modelo**: `app/Models/WorkflowRun.php`

### 2. API Endpoint
- **Ruta**: `POST /api/n8n/progress`
- **Controller**: `app/Http/Controllers/N8nProgressController.php`
- **Propósito**: Recibir actualizaciones de progreso desde n8n

### 3. Página de Filament
- **Página**: `app/Filament/Pages/WorkflowsPage.php`
- **Vista**: `resources/views/filament/pages/workflows-page.blade.php`
- **URL**: `/admin/workflows`
- **Propósito**: Mostrar y gestionar workflows en ejecución

## 🔄 Flujo Completo

```
Usuario (Filament)
   ↓
Laravel crea job_id (UUID)
   ↓
Laravel llama a n8n (1 vez) → POST al webhook de n8n
   ↓
n8n ejecuta workflow
   ↓
n8n POST progreso a Laravel (varias veces) → /api/n8n/progress
   ↓
Laravel guarda estado en workflow_runs
   ↓
Filament muestra progreso (auto-refresh cada 3s)
```

## 🚀 Cómo Usar

### Paso 1: Configurar n8n

En tu workflow de n8n, necesitas:

1. **Recibir el job_id y progress_url** desde Laravel
2. **Reportar progreso** durante la ejecución
3. **Enviar resultado final** al completar

#### Ejemplo de workflow n8n:

```javascript
// 1. Nodo Webhook (recibe inicio desde Laravel)
// Recibe: { job_id, progress_url, ...otros datos }

// 2. Durante el proceso, reportar progreso
// HTTP Request → POST a {{ $json.progress_url }}
{
  "job_id": "{{ $json.job_id }}",
  "status": "running",
  "step": "Procesando datos...",
  "progress": 25
}

// 3. Al completar
{
  "job_id": "{{ $json.job_id }}",
  "status": "completed",
  "step": "Completado",
  "progress": 100,
  "result": { /* datos del resultado */ }
}

// 4. Si falla
{
  "job_id": "{{ $json.job_id }}",
  "status": "failed",
  "step": "Error",
  "progress": 0,
  "error_message": "Descripción del error"
}
```

### Paso 2: Iniciar Workflow desde Filament

1. Ve a **Workflows** en el menú de Filament
2. Haz clic en **"Iniciar Workflow"**
3. Completa el formulario:
   - **Nombre del Workflow**: Nombre descriptivo
   - **URL del Webhook de n8n**: URL del webhook que iniciará el workflow
   - **Datos Adicionales (JSON)**: Datos opcionales a enviar
4. Haz clic en **"Iniciar"**

### Paso 3: Ver Progreso

La página de Workflows muestra:
- **ID del Trabajo**: UUID único
- **Workflow**: Nombre del workflow
- **Paso Actual**: Paso en ejecución
- **Progreso**: Porcentaje (0-100%)
- **Barra de Progreso**: Visual
- **Estado**: Badge con color
- **Timestamps**: Inicio, fin, creación

La tabla se actualiza automáticamente cada 3 segundos.

## 📡 API Endpoint: `/api/n8n/progress`

### Request Body

```json
{
  "job_id": "uuid-del-trabajo",
  "status": "running|completed|failed|pending",
  "step": "Descripción del paso actual",
  "progress": 50,
  "result": { /* resultado opcional */ },
  "data": { /* datos adicionales opcionales */ },
  "workflow_name": "Nombre del workflow",
  "error_message": "Mensaje de error si falla"
}
```

### Response

```json
{
  "success": true,
  "message": "Progreso actualizado correctamente"
}
```

## 🎨 Estados del Workflow

- **pending**: Pendiente (amarillo)
- **running**: Ejecutando (azul)
- **completed**: Completado (verde)
- **failed**: Fallido (rojo)

## 📝 Campos de la Tabla

- `job_id`: UUID único del trabajo
- `status`: Estado actual
- `step`: Paso actual en ejecución
- `progress`: Porcentaje (0-100)
- `result`: Resultado final (JSON)
- `data`: Datos adicionales (JSON)
- `workflow_name`: Nombre del workflow
- `error_message`: Mensaje de error si falla
- `started_at`: Fecha/hora de inicio
- `completed_at`: Fecha/hora de finalización

## 🔍 Ver Resultado

Haz clic en el botón **"Ver Resultado"** en cualquier workflow completado para ver:
- Resultado del workflow
- Datos adicionales
- Todo formateado en JSON legible

## ⚙️ Configuración

En `.env` puedes agregar:

```env
N8N_START_WEBHOOK=https://n8n.srv1137974.hstgr.cloud/webhook/...
```

Aunque también puedes especificar la URL directamente al iniciar un workflow.

## 🐛 Debugging

Los logs se guardan en `storage/logs/laravel.log`:
- Actualizaciones de progreso
- Errores de validación
- Errores al iniciar workflows

## ✅ Checklist de Implementación

- [x] Migración de base de datos
- [x] Modelo WorkflowRun
- [x] Controller para recibir progreso
- [x] Ruta API `/api/n8n/progress`
- [x] Página de Filament con tabla
- [x] Auto-refresh cada 3 segundos
- [x] Acción para iniciar workflows
- [x] Vista de resultados
- [x] Barra de progreso visual
- [x] Filtros por estado
- [x] Búsqueda por job_id

## 🚨 Importante

1. **El webhook de n8n debe recibir**:
   - `job_id`: UUID del trabajo
   - `progress_url`: URL para reportar progreso (`https://websolutions.work/api/n8n/progress`)

2. **n8n debe reportar progreso** durante la ejecución usando el `progress_url` recibido.

3. **La tabla se actualiza automáticamente** cada 3 segundos, no necesitas refrescar manualmente.
