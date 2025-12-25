# Configuración de Etiqueta SUPPORT en n8n para Tickets Resueltos

## 📋 Problema
Cuando se envía un ticket resuelto al cliente, el email debe tener la etiqueta "SUPPORT" en Gmail.

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

## 📚 Referencias

- [n8n Gmail Node Documentation](https://docs.n8n.io/integrations/builtin/app-nodes/n8n-nodes-base.gmail/)
- [Gmail API Labels](https://developers.google.com/gmail/api/guides/labels)

