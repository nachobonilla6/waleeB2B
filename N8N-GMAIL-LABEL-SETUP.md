# Configuración de Etiqueta SUPPORT y Carpeta Separada en Gmail

## 📋 Problema
Cuando se envía un ticket resuelto al cliente, el email debe:
1. Tener la etiqueta "SUPPORT" en Gmail
2. Llegar a una carpeta separada (etiqueta) en Gmail

## 🔧 Solución en Laravel
Laravel ya está enviando los siguientes campos en el webhook:
- `email_label`: "SUPPORT"
- `gmail_label`: "SUPPORT"
- `label`: "SUPPORT"
- `labels`: ["SUPPORT"]
- `labelIds`: ["SUPPORT"]
- `gmail_labels`: ["SUPPORT"]
- `addLabel`: "SUPPORT"

## ⚙️ Configuración en n8n

### Opción 1: Usar el campo `labels` (Recomendado)

1. **En el nodo de Gmail (Send Email)**:
   - Ve a la sección **"Additional Fields"** o **"Options"**
   - Busca el campo **"Labels"** o **"Label IDs"**
   - Usa la expresión: `{{ $json.labels }}` o `{{ $json.labels[0] }}`

### Opción 2: Usar un nodo Code para transformar

1. **Agrega un nodo "Code"** antes del nodo Gmail:
   ```javascript
   const items = $input.all();
   return items.map(item => {
     return {
       json: {
         ...item.json,
         // Asegurar que labels esté en el formato correcto
         labels: item.json.labels || [item.json.label || 'SUPPORT'],
         labelIds: item.json.labelIds || [item.json.label || 'SUPPORT']
       }
     };
   });
   ```

2. **En el nodo Gmail**, usa:
   - **Labels**: `{{ $json.labels }}`
   - O **Label IDs**: `{{ $json.labelIds }}`

### Opción 3: Usar directamente el campo `label`

1. **En el nodo Gmail**:
   - **Labels**: `{{ $json.label }}`
   - O crea un array: `{{ [$json.label] }}`

## 📝 Nota Importante sobre Gmail Labels

Gmail puede requerir el **ID de la etiqueta** en lugar del nombre. Si la etiqueta "SUPPORT" ya existe en Gmail:

1. **Obtén el ID de la etiqueta**:
   - Ve a Gmail → Configuración → Etiquetas
   - Busca "SUPPORT" y copia su ID (si está disponible)
   - O usa la API de Gmail para obtener el ID

2. **Si la etiqueta no existe**, n8n puede crearla automáticamente o puedes:
   - Crearla manualmente en Gmail primero
   - O usar el nombre directamente si n8n lo soporta

## 🔍 Verificar que funciona

1. **Ejecuta el workflow manualmente** con datos de prueba
2. **Revisa los logs de n8n** para ver qué datos recibe
3. **Verifica en Gmail** que el email enviado tenga la etiqueta "SUPPORT"

## 🐛 Troubleshooting

### La etiqueta no se aplica
- Verifica que el campo `labels` o `labelIds` esté correctamente mapeado en el nodo Gmail
- Asegúrate de que la etiqueta "SUPPORT" exista en Gmail
- Revisa los logs de n8n para ver qué datos recibe el nodo Gmail

### Error al enviar el email
- Verifica que las credenciales de Gmail estén correctas
- Asegúrate de tener permisos para crear/aplicar etiquetas
- Verifica que el formato del campo `labels` sea correcto (array o string según lo que requiera n8n)

## 📁 Configurar Carpeta Separada en Gmail

Para que los emails lleguen automáticamente a una carpeta separada en Gmail, sigue estos pasos:

### Paso 1: Crear la Etiqueta "SUPPORT" en Gmail

1. Ve a **Gmail** → **Configuración** (⚙️) → **Ver todas las configuraciones**
2. Ve a la pestaña **"Etiquetas"**
3. Haz clic en **"Crear nueva etiqueta"**
4. Nómbrala: **"SUPPORT"**
5. Opcionalmente, puedes crear una etiqueta anidada como "SUPPORT/Tickets Resueltos"
6. Haz clic en **"Crear"**

### Paso 2: Crear un Filtro en Gmail (IMPORTANTE - Para que NO caiga en Primary)

1. En Gmail, haz clic en el **icono de búsqueda avanzada** (el ícono de filtro al lado de la barra de búsqueda)
2. O ve a **Configuración** → **Filtros y direcciones bloqueadas** → **"Crear un nuevo filtro"**

3. **Configura el filtro** con una de estas opciones:

   **Opción A: Por asunto (Recomendado)**
   - En **"Tiene las palabras"**, escribe: `[SUPPORT]`
   - O en **"Asunto"**, escribe: `[SUPPORT]`

   **Opción B: Por remitente**
   - En **"De"**, escribe: `websolutionscrnow@gmail.com`
   - Y en **"Tiene las palabras"**, escribe: `Ticket Resuelto`

   **Opción C: Combinado (Más preciso)**
   - **"De"**: `websolutionscrnow@gmail.com`
   - **"Asunto"**: `[SUPPORT]`

4. Haz clic en **"Crear filtro"**

5. **Marca las siguientes opciones (CRÍTICO para que no caiga en Primary)**:
   - ✅ **"Aplicar la etiqueta"** → Selecciona **"SUPPORT"**
   - ✅ **"Archivar también"** ← **ESTO ES CRÍTICO**: Esto hace que el email NO aparezca en Primary
   - ✅ **"Marcar como importante"** (opcional)
   - ✅ **"Nunca enviarlo a Spam"** (opcional)

6. Haz clic en **"Crear filtro"**

**⚠️ IMPORTANTE**: La opción **"Archivar también"** es esencial porque:
- Los emails archivados NO aparecen en la pestaña "Primary"
- Solo aparecerán cuando hagas clic en la etiqueta "SUPPORT"
- Esto es exactamente lo que necesitas para que no caigan en Primary

### Paso 3: Verificar que Funciona

1. **Marca un ticket como "resuelto"** en la aplicación
2. **Revisa tu Gmail**:
   - El email debe tener la etiqueta "SUPPORT"
   - Si configuraste "Archivar también", no aparecerá en la bandeja de entrada
   - Puedes verlo haciendo clic en la etiqueta "SUPPORT" en el menú lateral de Gmail

### Paso 4: Ver la Carpeta/Etiqueta en Gmail

1. En el menú lateral izquierdo de Gmail, busca **"SUPPORT"**
2. Si no la ves, haz clic en **"Más"** para expandir las etiquetas
3. Haz clic en **"SUPPORT"** para ver todos los emails con esa etiqueta

## 🔄 Configuración en n8n para que NO caiga en Primary

Para que los emails NO caigan en la pestaña "Primary" y vayan directamente a "Support", configura n8n así:

### Opción 1: Usar el campo `archive` (Recomendado)

1. **En el nodo Gmail (Send Email)** de n8n:
   - Ve a **"Additional Fields"** o **"Options"**
   - Busca el campo **"Archive"** o **"Skip Inbox"**
   - Actívalo o usa: `{{ $json.archive }}` (que será `true`)
   - **Labels**: `{{ $json.labels }}` o `{{ $json.label }}`

2. Esto hará que el email:
   - Se envíe directamente archivado
   - NO aparezca en Primary
   - Solo sea visible en la etiqueta "SUPPORT"

### Opción 2: Usar un nodo Code para configurar

1. **Agrega un nodo "Code"** antes del nodo Gmail:
   ```javascript
   const items = $input.all();
   return items.map(item => {
     return {
       json: {
         ...item.json,
         // Archivar el email (no aparecerá en Primary)
         archive: true,
         skipInbox: true,
         // Aplicar etiqueta
         labels: item.json.labels || [item.json.label || 'SUPPORT'],
         labelIds: item.json.labelIds || [item.json.label || 'SUPPORT']
       }
     };
   });
   ```

2. **En el nodo Gmail**, configura:
   - **Archive**: `{{ $json.archive }}`
   - **Labels**: `{{ $json.labels }}`

### Opción 3: Combinar con Filtro de Gmail (Más Confiable)

La mejor solución es **combinar ambas**:
1. **Configura n8n** para aplicar la etiqueta (como en Opción 1 o 2)
2. **Crea el filtro en Gmail** con "Archivar también" (como en Paso 2)

Esto garantiza que el email:
- ✅ Se archive automáticamente
- ✅ NO aparezca en Primary
- ✅ Solo sea visible en la etiqueta "SUPPORT"

## 📝 Notas Importantes

- **Los emails enviados desde n8n** aparecerán en "Enviados" con la etiqueta aplicada
- **Los emails recibidos** (respuestas de clientes) NO tendrán automáticamente la etiqueta a menos que:
  - Configure un filtro adicional para emails que contengan "[SUPPORT]" en el asunto
  - O uses un sistema de seguimiento de hilos de conversación

- **Para organizar mejor**, puedes crear sub-etiquetas:
  - `SUPPORT/Tickets Resueltos`
  - `SUPPORT/Respuestas Clientes`
  - etc.

## 🎯 Resumen de Configuración

1. ✅ **Laravel** envía el webhook con `[SUPPORT]` en el asunto y campos de etiqueta
2. ✅ **n8n** aplica la etiqueta "SUPPORT" al email (si está configurado)
3. ✅ **Gmail** filtra automáticamente los emails con `[SUPPORT]` y los mueve a la carpeta/etiqueta "SUPPORT"

## 📚 Referencias

- [n8n Gmail Node Documentation](https://docs.n8n.io/integrations/builtin/app-nodes/n8n-nodes-base.gmail/)
- [Gmail API Labels](https://developers.google.com/gmail/api/guides/labels)
- [Cómo crear filtros en Gmail](https://support.google.com/mail/answer/6579)

