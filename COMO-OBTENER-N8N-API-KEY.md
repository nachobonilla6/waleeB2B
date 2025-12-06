# Cómo Obtener la API Key de n8n

## 📋 Pasos para Obtener tu N8N_API_KEY

### Paso 1: Acceder a n8n
1. Abre tu navegador y ve a: **https://n8n.srv1137974.hstgr.cloud**
2. Inicia sesión con tus credenciales

### Paso 2: Ir a Configuración
1. Haz clic en el **menú de usuario** (esquina superior derecha) - icono de perfil o tres puntos
2. Selecciona **"Settings"** o **"Configuración"**

### Paso 3: Crear API Key
1. En el menú lateral, busca la sección **"API"** o **"n8n API"**
2. Haz clic en **"Create API Key"** o **"Crear clave de API"**
3. Completa el formulario:
   - **Nombre**: Dale un nombre descriptivo (ej: "WebSolutions API")
   - **Duración**: Elige cuánto tiempo será válida:
     - 7 días
     - 30 días
     - 2 meses
     - Sin expiración (no recomendado por seguridad)
   - **Permisos (Scope)**: Selecciona los permisos necesarios:
     - `workflow:read` - Para leer workflows
     - `workflow:write` - Para editar workflows
     - `workflow:execute` - Para ejecutar workflows
     - O selecciona "All" si necesitas todos los permisos

### Paso 4: Copiar la API Key
1. Una vez creada, **copia la clave inmediatamente**
2. ⚠️ **IMPORTANTE**: n8n solo muestra la clave una vez. Si la pierdes, tendrás que crear una nueva.

### Paso 5: Agregar al .env
Agrega la clave a tu archivo `.env`:

```env
N8N_URL=https://n8n.srv1137974.hstgr.cloud
N8N_API_KEY=tu_clave_copiada_aqui
```

## 🔍 Ubicación Alternativa

Si no encuentras la opción en Settings, también puedes:

1. **Buscar en el menú**: Algunas versiones de n8n tienen la opción en:
   - Settings → API
   - Settings → Security → API Keys
   - User Menu → API Keys

2. **URL directa**: Intenta acceder directamente a:
   - `https://n8n.srv1137974.hstgr.cloud/settings/api`

## ⚠️ Notas de Seguridad

1. **Nunca compartas tu API Key** públicamente
2. **No la subas a Git** - asegúrate de que esté en `.gitignore`
3. **Rota las claves periódicamente** si es posible
4. **Usa permisos mínimos necesarios** - solo los que realmente necesitas

## 🧪 Verificar que Funciona

Después de agregar la API key, puedes verificar que funciona:

1. Ve a la página de Automatizaciones n8n en Filament
2. Si ves los workflows, la API key está funcionando correctamente
3. Si ves un error, verifica:
   - Que la API key esté correctamente copiada (sin espacios)
   - Que los permisos sean suficientes
   - Que la URL de n8n sea correcta

## 📝 Ejemplo de API Key

Una API key de n8n típicamente se ve así:
```
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzNWNhODY2Ny0wYmNhLTQwYjAtOWFhYS04ZTBhZDA0ODE1ZWMiLCJpc3MiOiJuOG4iLCJhdWQiOiJwdWJsaWMtYXBpIiwiaWF0IjoxNzY0OTIyNzk2fQ.IBMfPU0yuKMNOdx0lDUqnJ6W67fpOPsYTIOjEUF679g
```

Es un token JWT largo que comienza con `eyJ...`

