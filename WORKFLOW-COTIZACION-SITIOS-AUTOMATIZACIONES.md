# Workflow n8n: Cotización de Sitios Web y Automatizaciones

Este documento explica cómo configurar un workflow en n8n para:
- Ofrecer sitios web y automatizaciones
- Usar Google Sheets para mantener lista de sitios disponibles
- Agendar citas con Google Calendar para hablar del proyecto
- Recopilar datos básicos del cliente

## 📋 Estructura del Workflow

```
1. Webhook Trigger (recibe datos del formulario)
   ↓
2. Extraer datos básicos (nombre, email, teléfono, tipo de servicio)
   ↓
3. Leer Google Sheets (obtener lista de sitios disponibles)
   ↓
4. Formatear respuesta con opciones de sitios
   ↓
5. Crear evento en Google Calendar (agendar cita)
   ↓
6. Enviar confirmación por email
   ↓
7. Reportar progreso a Laravel
```

## 🔧 Configuración Paso a Paso

### Paso 1: Crear el Webhook Trigger

1. **Agrega un nodo "Webhook"** como primer nodo
2. **Configuración:**
   - **HTTP Method**: `POST`
   - **Path**: `/cotizacion-servicios` (o el que prefieras)
   - **Response Mode**: "Respond When Last Node Finishes"
   - **Options** → **Response Code**: `200`
3. **Activa el workflow** para obtener la URL del webhook
4. **Copia la URL completa** (ejemplo: `https://n8n.srv1137974.hstgr.cloud/webhook/cotizacion-servicios`)

### Paso 2: Configurar Google Sheets

#### 2.1 Crear la Hoja de Cálculo

Crea una Google Sheet con la siguiente estructura:

| ID | Nombre | Descripción | Precio | Categoría | Disponible |
|----|--------|-------------|--------|-----------|------------|
| 1 | Sitio Web Básico | Sitio web de hasta 5 páginas | $500 | Sitio Web | Sí |
| 2 | Sitio Web Avanzado | Sitio web con CMS y panel admin | $1200 | Sitio Web | Sí |
| 3 | E-commerce Básico | Tienda online con hasta 50 productos | $2000 | E-commerce | Sí |
| 4 | Automatización Email | Flujo de emails automatizados | $300 | Automatización | Sí |
| 5 | Automatización CRM | Integración con CRM y seguimiento | $800 | Automatización | Sí |

**Comparte la hoja** con el email de la cuenta de servicio de Google que uses en n8n.

#### 2.2 Configurar Nodo Google Sheets

1. **Agrega un nodo "Google Sheets"**
2. **Operación**: "Read Rows"
3. **Configuración:**
   - **Credential**: Conecta tu cuenta de Google
   - **Spreadsheet ID**: ID de tu Google Sheet (de la URL)
   - **Sheet Name**: Nombre de la hoja (ej: "Sitios")
   - **Range**: `A2:F100` (ajusta según necesites)
   - **Options** → **Use First Row as Headers**: `true`

### Paso 3: Filtrar y Formatear Datos

#### 3.1 Filtrar por Disponibilidad y Categoría

Agrega un nodo **"Filter"** o **"Code"** para filtrar:

```javascript
// Filtrar sitios disponibles según el tipo de servicio solicitado
const tipoServicio = $input.first().json.tipo_servicio; // "sitio_web" o "automatizacion"
const categoria = tipoServicio === "sitio_web" ? "Sitio Web" : "Automatización";

const items = $input.all()
  .filter(item => {
    const row = item.json;
    return row.Disponible === "Sí" && 
           (row.Categoría === categoria || row.Categoría === "Sitio Web" || row.Categoría === "E-commerce");
  })
  .map(item => ({
    id: item.json.ID,
    nombre: item.json.Nombre,
    descripcion: item.json.Descripción,
    precio: item.json.Precio,
    categoria: item.json.Categoría
  }));

return items.map(item => ({ json: item }));
```

### Paso 4: Agregar Datos del Cliente

Agrega un nodo **"Set"** o **"Code"** para combinar datos:

```javascript
const datosCliente = $('Webhook').first().json;
const opcionesServicios = $input.all().map(item => item.json);

return [{
  json: {
    cliente: {
      nombre: datosCliente.nombre,
      email: datosCliente.email,
      telefono: datosCliente.telefono,
      tipo_servicio: datosCliente.tipo_servicio,
      mensaje: datosCliente.mensaje || ""
    },
    opciones_servicios: opcionesServicios,
    job_id: datosCliente.job_id,
    progress_url: datosCliente.progress_url
  }
}];
```

### Paso 5: Reportar Progreso a Laravel

Agrega un nodo **"HTTP Request"** para reportar progreso:

1. **Method**: `POST`
2. **URL**: `{{ $json.progress_url }}` (recibido desde Laravel)
3. **Body Content Type**: `JSON`
4. **JSON Body**:
```json
{
  "job_id": "{{ $json.job_id }}",
  "status": "running",
  "step": "Opciones de servicios obtenidas",
  "progress": 50
}
```

### Paso 6: Crear Evento en Google Calendar

#### 6.1 Configurar Nodo Google Calendar

1. **Agrega un nodo "Google Calendar"**
2. **Operación**: "Create Event"
3. **Configuración:**
   - **Credential**: Conecta tu cuenta de Google
   - **Calendar ID**: ID de tu calendario (o "primary")
   - **Summary**: `Consulta: {{ $json.cliente.nombre }} - {{ $json.cliente.tipo_servicio }}`
   - **Description**: 
     ```
     Cliente: {{ $json.cliente.nombre }}
     Email: {{ $json.cliente.email }}
     Teléfono: {{ $json.cliente.telefono }}
     Tipo de servicio: {{ $json.cliente.tipo_servicio }}
     
     Mensaje: {{ $json.cliente.mensaje }}
     
     Opciones disponibles:
     {{ $json.opciones_servicios }}
     ```
   - **Start**: `{{ $now.plus({days: 1}).toISO() }}` (mañana a las 9 AM)
   - **End**: `{{ $now.plus({days: 1}).set({hour: 10}).toISO() }}` (1 hora después)
   - **Location**: `Reunión virtual` (o tu ubicación)
   - **Attendees**: `{{ $json.cliente.email }}`

#### 6.2 Obtener Horarios Disponibles (Opcional)

Si quieres que el cliente elija el horario, puedes usar un nodo **"Google Calendar"** con operación "Get Free/Busy" antes de crear el evento.

### Paso 7: Enviar Email de Confirmación

Agrega un nodo **"Gmail"** o **"Email Send"**:

1. **Operación**: "Send Email"
2. **To**: `{{ $json.cliente.email }}`
3. **Subject**: `Confirmación de cita - WebSolutions`
4. **Body** (HTML):
```html
<h2>¡Hola {{ $json.cliente.nombre }}!</h2>
<p>Hemos recibido tu solicitud de cotización para <strong>{{ $json.cliente.tipo_servicio }}</strong>.</p>

<h3>Opciones disponibles:</h3>
<ul>
{{ $json.opciones_servicios.map(opcion => `
  <li>
    <strong>${opcion.nombre}</strong><br>
    ${opcion.descripcion}<br>
    <em>Precio: ${opcion.precio}</em>
  </li>
`).join('') }}
</ul>

<p>Hemos agendado una cita para hablar sobre tu proyecto. Recibirás una invitación de Google Calendar.</p>

<p>Si necesitas cambiar la fecha o hora, por favor contáctanos.</p>

<p>Saludos,<br>Equipo WebSolutions</p>
```

### Paso 8: Reportar Completado

Agrega otro nodo **"HTTP Request"** para reportar finalización:

1. **Method**: `POST`
2. **URL**: `{{ $json.progress_url }}`
3. **Body Content Type**: `JSON`
4. **JSON Body**:
```json
{
  "job_id": "{{ $json.job_id }}",
  "status": "completed",
  "step": "Proceso completado",
  "progress": 100,
  "result": {
    "cliente": "{{ $json.cliente }}",
    "evento_calendario_id": "{{ $('Google Calendar').first().json.id }}",
    "opciones_enviadas": {{ $json.opciones_servicios.length }}
  }
}
```

## 📝 Datos que Recibe el Webhook

El webhook espera recibir los siguientes datos:

```json
{
  "nombre": "Juan Pérez",
  "email": "juan@example.com",
  "telefono": "+506 8888-8888",
  "tipo_servicio": "sitio_web",
  "mensaje": "Necesito un sitio web para mi negocio",
  "job_id": "uuid-del-trabajo",
  "progress_url": "https://websolutions.work/api/n8n/progress"
}
```

**Campos:**
- `nombre` (requerido): Nombre del cliente
- `email` (requerido): Email del cliente
- `telefono` (opcional): Teléfono de contacto
- `tipo_servicio` (requerido): `"sitio_web"` o `"automatizacion"`
- `mensaje` (opcional): Mensaje adicional del cliente
- `job_id` (requerido): UUID del trabajo (desde Laravel)
- `progress_url` (requerido): URL para reportar progreso (desde Laravel)

## 🔗 Integración con Laravel

### Desde Laravel, iniciar el workflow:

```php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

$jobId = Str::uuid();

$response = Http::timeout(120)->post('https://n8n.srv1137974.hstgr.cloud/webhook/cotizacion-servicios', [
    'job_id' => $jobId,
    'progress_url' => url('/api/n8n/progress'),
    'nombre' => $request->nombre,
    'email' => $request->email,
    'telefono' => $request->telefono,
    'tipo_servicio' => $request->tipo_servicio, // "sitio_web" o "automatizacion"
    'mensaje' => $request->mensaje,
]);
```

## 📊 Estructura de Google Sheets

### Hoja: "Sitios"

| ID | Nombre | Descripción | Precio | Categoría | Disponible |
|----|--------|-------------|--------|-----------|------------|
| 1 | Sitio Web Básico | Sitio web de hasta 5 páginas con diseño responsive | $500 | Sitio Web | Sí |
| 2 | Sitio Web Avanzado | Sitio web con CMS, panel admin y blog | $1200 | Sitio Web | Sí |
| 3 | E-commerce Básico | Tienda online con hasta 50 productos | $2000 | E-commerce | Sí |
| 4 | E-commerce Avanzado | Tienda online con múltiples métodos de pago | $3500 | E-commerce | Sí |
| 5 | Automatización Email | Flujo de emails automatizados con n8n | $300 | Automatización | Sí |
| 6 | Automatización CRM | Integración con CRM y seguimiento automático | $800 | Automatización | Sí |
| 7 | Automatización Completa | Sistema completo de automatizaciones | $1500 | Automatización | Sí |

**Notas:**
- La primera fila debe contener los encabezados
- La columna "Disponible" debe ser "Sí" o "No"
- La columna "Categoría" debe coincidir con los filtros del workflow

## 🎨 Personalización

### Cambiar horario de la cita

En el nodo Google Calendar, modifica:
- **Start**: `{{ $now.plus({days: 2}).set({hour: 14, minute: 0}).toISO() }}` (pasado mañana a las 2 PM)
- **End**: `{{ $now.plus({days: 2}).set({hour: 15, minute: 0}).toISO() }}` (1 hora después)

### Agregar más campos al formulario

1. Agrega el campo en el webhook
2. Úsalo en los nodos siguientes con `{{ $json.nombre_del_campo }}`

### Filtrar por precio

Puedes agregar un filtro adicional en el nodo Code:

```javascript
const precioMaximo = $('Webhook').first().json.precio_maximo || 9999;

const items = $input.all()
  .filter(item => {
    const precio = parseFloat(item.json.Precio.replace('$', '').replace(',', ''));
    return precio <= precioMaximo;
  });
```

## ✅ Checklist de Configuración

- [ ] Webhook creado y activado
- [ ] Google Sheets creado y compartido
- [ ] Credenciales de Google configuradas en n8n
- [ ] Nodo Google Sheets configurado correctamente
- [ ] Filtros de servicios funcionando
- [ ] Google Calendar configurado
- [ ] Email de confirmación configurado
- [ ] Reporte de progreso a Laravel funcionando
- [ ] Workflow probado end-to-end

## 🐛 Troubleshooting

### Error: "No se pueden leer las filas de Google Sheets"
- Verifica que la hoja esté compartida con la cuenta de servicio
- Verifica el Spreadsheet ID y el nombre de la hoja
- Asegúrate de que la primera fila tenga encabezados

### Error: "No se puede crear evento en Google Calendar"
- Verifica las credenciales de Google Calendar
- Asegúrate de que el calendario tenga permisos de escritura
- Verifica el formato de las fechas (deben ser ISO 8601)

### Los emails no se envían
- Verifica las credenciales de Gmail/Email
- Revisa la carpeta de spam
- Verifica que el formato del email sea correcto

## 📱 Ejemplo de Respuesta Final

El workflow retornará:

```json
{
  "success": true,
  "cliente": {
    "nombre": "Juan Pérez",
    "email": "juan@example.com",
    "telefono": "+506 8888-8888"
  },
  "opciones_servicios": [
    {
      "id": "1",
      "nombre": "Sitio Web Básico",
      "descripcion": "Sitio web de hasta 5 páginas",
      "precio": "$500",
      "categoria": "Sitio Web"
    }
  ],
  "evento_calendario": {
    "id": "event_id_123",
    "link": "https://calendar.google.com/event?eid=..."
  }
}
```


