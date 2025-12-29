# Resumen: Workflow de Cotización de Sitios Web y Automatizaciones

## 📋 Archivos Creados

### 1. Documentación
- **`WORKFLOW-COTIZACION-SITIOS-AUTOMATIZACIONES.md`**: Guía completa de configuración del workflow en n8n
- **`EJEMPLO-FORMULARIO-COTIZACION.md`**: Ejemplos de formularios HTML y Blade para solicitar cotizaciones
- **`workflow-cotizacion-sitios-automatizaciones.json`**: Archivo JSON del workflow para importar directamente en n8n

### 2. Código Laravel
- **`app/Http/Controllers/CotizacionWorkflowController.php`**: Controlador para iniciar y consultar el estado de cotizaciones
- **`routes/api.php`**: Rutas API agregadas para cotización
- **`config/services.php`**: Configuración del webhook de cotización agregada

## 🔄 Flujo Completo

```
1. Cliente llena formulario
   ↓
2. Laravel recibe datos y crea job_id
   ↓
3. Laravel envía datos a n8n (webhook)
   ↓
4. n8n lee Google Sheets (lista de sitios)
   ↓
5. n8n filtra servicios según tipo
   ↓
6. n8n crea evento en Google Calendar
   ↓
7. n8n envía email de confirmación
   ↓
8. n8n reporta progreso a Laravel
   ↓
9. Cliente recibe confirmación
```

## 🚀 Pasos para Configurar

### Paso 1: Configurar Google Sheets

1. Crea una Google Sheet con esta estructura:

| ID | Nombre | Descripción | Precio | Categoría | Disponible |
|----|--------|-------------|--------|-----------|------------|
| 1 | Sitio Web Básico | Sitio web de hasta 5 páginas | $500 | Sitio Web | Sí |
| 2 | Sitio Web Avanzado | Sitio web con CMS | $1200 | Sitio Web | Sí |
| 3 | Automatización Email | Flujo de emails | $300 | Automatización | Sí |

2. Comparte la hoja con la cuenta de Google que uses en n8n
3. Copia el **Spreadsheet ID** de la URL

### Paso 2: Configurar n8n

1. **Importar el workflow:**
   - Ve a n8n
   - Clic en "Import from File"
   - Selecciona `workflow-cotizacion-sitios-automatizaciones.json`
   - O crea el workflow manualmente siguiendo `WORKFLOW-COTIZACION-SITIOS-AUTOMATIZACIONES.md`

2. **Configurar credenciales:**
   - Google Sheets: Conecta tu cuenta de Google
   - Google Calendar: Conecta tu cuenta de Google
   - Gmail: Conecta tu cuenta de Gmail

3. **Configurar nodos:**
   - **Leer Google Sheets**: Agrega el Spreadsheet ID y nombre de la hoja
   - **Crear Evento Calendar**: Configura el calendario y horarios
   - **Enviar Email**: Configura el remitente

4. **Activar el workflow** y copiar la URL del webhook

### Paso 3: Configurar Laravel

1. **Agregar variable de entorno:**
   ```env
   N8N_COTIZACION_WEBHOOK_URL=https://n8n.srv1137974.hstgr.cloud/webhook/cotizacion-servicios
   ```

2. **Limpiar caché:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Paso 4: Crear Formulario

Usa los ejemplos en `EJEMPLO-FORMULARIO-COTIZACION.md` para crear tu formulario.

## 📡 Endpoints API

### Iniciar Cotización
```
POST /api/cotizacion/iniciar
```

**Body:**
```json
{
  "nombre": "Juan Pérez",
  "email": "juan@example.com",
  "telefono": "+506 8888-8888",
  "tipo_servicio": "sitio_web",
  "mensaje": "Necesito un sitio web"
}
```

### Consultar Estado
```
GET /api/cotizacion/estado/{job_id}
```

## 🔍 Ver Progreso

Puedes ver el progreso de las cotizaciones en:
- **Filament**: `/admin/workflows` (si usas la página de workflows)
- **API**: `/api/cotizacion/estado/{job_id}`

## ✅ Checklist de Configuración

### n8n
- [ ] Workflow importado o creado
- [ ] Credenciales de Google Sheets configuradas
- [ ] Credenciales de Google Calendar configuradas
- [ ] Credenciales de Gmail configuradas
- [ ] Spreadsheet ID configurado en nodo Google Sheets
- [ ] Workflow activado
- [ ] URL del webhook copiada

### Laravel
- [ ] Variable `N8N_COTIZACION_WEBHOOK_URL` en `.env`
- [ ] Caché limpiada
- [ ] Rutas API funcionando
- [ ] Controlador sin errores

### Google Sheets
- [ ] Hoja creada con estructura correcta
- [ ] Hoja compartida con cuenta de Google de n8n
- [ ] Primera fila tiene encabezados
- [ ] Datos de ejemplo agregados

### Pruebas
- [ ] Formulario creado y funcionando
- [ ] Prueba de envío exitosa
- [ ] Google Sheets se lee correctamente
- [ ] Evento en Google Calendar se crea
- [ ] Email de confirmación se envía
- [ ] Progreso se reporta a Laravel

## 🐛 Troubleshooting

### Error: "URL del webhook no configurada"
- Verifica que `N8N_COTIZACION_WEBHOOK_URL` esté en `.env`
- Ejecuta `php artisan config:clear`

### Error: "No se pueden leer las filas de Google Sheets"
- Verifica que la hoja esté compartida
- Verifica el Spreadsheet ID
- Verifica que la primera fila tenga encabezados

### Error: "No se puede crear evento en Google Calendar"
- Verifica credenciales de Google Calendar
- Verifica permisos del calendario
- Verifica formato de fechas

### Los emails no se envían
- Verifica credenciales de Gmail
- Revisa carpeta de spam
- Verifica formato del email

## 📚 Documentación Relacionada

- `WORKFLOW-COTIZACION-SITIOS-AUTOMATIZACIONES.md`: Configuración detallada del workflow
- `EJEMPLO-FORMULARIO-COTIZACION.md`: Ejemplos de formularios
- `WORKFLOWS-N8N-SETUP.md`: Sistema general de workflows
- `GOOGLE_CALENDAR_SETUP.md`: Configuración de Google Calendar

## 🎯 Próximos Pasos

1. Personalizar los servicios en Google Sheets según tus necesidades
2. Ajustar los horarios de las citas en Google Calendar
3. Personalizar el email de confirmación
4. Agregar más campos al formulario si es necesario
5. Integrar con tu sistema de CRM si lo tienes

## 💡 Mejoras Futuras

- Agregar selección de fecha/hora por parte del cliente
- Integrar con sistema de pagos
- Agregar notificaciones push
- Dashboard para ver todas las cotizaciones
- Exportar cotizaciones a PDF
- Integración con WhatsApp para confirmaciones


