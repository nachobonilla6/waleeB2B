# Ejemplo de Formulario para Cotización

Este documento muestra cómo crear un formulario para solicitar cotizaciones de sitios web y automatizaciones.

## 📝 Formulario HTML/Blade

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Cotización - WebSolutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Solicitar Cotización
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Completa el formulario y te contactaremos pronto
                </p>
            </div>
            
            <form id="cotizacionForm" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">
                            Nombre completo *
                        </label>
                        <input 
                            id="nombre" 
                            name="nombre" 
                            type="text" 
                            required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Juan Pérez"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Correo electrónico *
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="juan@example.com"
                        >
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700">
                            Teléfono
                        </label>
                        <input 
                            id="telefono" 
                            name="telefono" 
                            type="tel" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="+506 8888-8888"
                        >
                    </div>

                    <div>
                        <label for="tipo_servicio" class="block text-sm font-medium text-gray-700">
                            Tipo de servicio *
                        </label>
                        <select 
                            id="tipo_servicio" 
                            name="tipo_servicio" 
                            required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Selecciona un servicio</option>
                            <option value="sitio_web">Sitio Web</option>
                            <option value="automatizacion">Automatización</option>
                        </select>
                    </div>

                    <div>
                        <label for="mensaje" class="block text-sm font-medium text-gray-700">
                            Mensaje (opcional)
                        </label>
                        <textarea 
                            id="mensaje" 
                            name="mensaje" 
                            rows="4" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Cuéntanos sobre tu proyecto..."
                        ></textarea>
                    </div>
                </div>

                <div>
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="submitText">Enviar Solicitud</span>
                        <span id="loadingSpinner" class="hidden ml-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>

                <div id="mensajeExito" class="hidden p-4 bg-green-50 border border-green-200 rounded-md">
                    <p class="text-sm text-green-800">
                        ✅ ¡Solicitud enviada correctamente! Te contactaremos pronto.
                    </p>
                </div>

                <div id="mensajeError" class="hidden p-4 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-sm text-red-800" id="errorTexto"></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('cotizacionForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const mensajeExito = document.getElementById('mensajeExito');
            const mensajeError = document.getElementById('mensajeError');
            const errorTexto = document.getElementById('errorTexto');
            
            // Ocultar mensajes anteriores
            mensajeExito.classList.add('hidden');
            mensajeError.classList.add('hidden');
            
            // Deshabilitar botón y mostrar loading
            submitBtn.disabled = true;
            submitText.textContent = 'Enviando...';
            loadingSpinner.classList.remove('hidden');
            
            // Recopilar datos del formulario
            const formData = {
                nombre: document.getElementById('nombre').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                tipo_servicio: document.getElementById('tipo_servicio').value,
                mensaje: document.getElementById('mensaje').value,
            };
            
            try {
                const response = await fetch('/api/cotizacion/iniciar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Mostrar mensaje de éxito
                    mensajeExito.classList.remove('hidden');
                    
                    // Limpiar formulario
                    document.getElementById('cotizacionForm').reset();
                    
                    // Opcional: Redirigir o mostrar job_id
                    console.log('Job ID:', data.job_id);
                } else {
                    // Mostrar mensaje de error
                    errorTexto.textContent = data.message || 'Error al enviar la solicitud. Por favor, intenta nuevamente.';
                    mensajeError.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                errorTexto.textContent = 'Error de conexión. Por favor, verifica tu conexión a internet.';
                mensajeError.classList.remove('hidden');
            } finally {
                // Rehabilitar botón
                submitBtn.disabled = false;
                submitText.textContent = 'Enviar Solicitud';
                loadingSpinner.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
```

## 🔧 Integración con Laravel Blade

Si estás usando Laravel Blade, puedes crear una vista así:

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold text-center mb-6">Solicitar Cotización</h2>
        
        <form id="cotizacionForm" method="POST" action="{{ route('api.cotizacion.iniciar') }}">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre completo *
                    </label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        value="{{ old('nombre') }}"
                    >
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico *
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono
                    </label>
                    <input 
                        type="tel" 
                        id="telefono" 
                        name="telefono"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        value="{{ old('telefono') }}"
                    >
                </div>

                <div>
                    <label for="tipo_servicio" class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de servicio *
                    </label>
                    <select 
                        id="tipo_servicio" 
                        name="tipo_servicio" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="">Selecciona un servicio</option>
                        <option value="sitio_web" {{ old('tipo_servicio') === 'sitio_web' ? 'selected' : '' }}>
                            Sitio Web
                        </option>
                        <option value="automatizacion" {{ old('tipo_servicio') === 'automatizacion' ? 'selected' : '' }}>
                            Automatización
                        </option>
                    </select>
                    @error('tipo_servicio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mensaje" class="block text-sm font-medium text-gray-700 mb-1">
                        Mensaje (opcional)
                    </label>
                    <textarea 
                        id="mensaje" 
                        name="mensaje" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('mensaje') }}</textarea>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full mt-6 py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
            >
                Enviar Solicitud
            </button>
        </form>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Si quieres usar AJAX en lugar de submit normal
    document.getElementById('cotizacionForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await fetch('{{ route("api.cotizacion.iniciar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('¡Solicitud enviada correctamente!');
                this.reset();
            } else {
                alert('Error: ' + (result.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de conexión. Por favor, intenta nuevamente.');
        }
    });
</script>
@endpush
@endsection
```

## 📡 Uso de la API

### Iniciar cotización

```javascript
POST /api/cotizacion/iniciar
Content-Type: application/json

{
  "nombre": "Juan Pérez",
  "email": "juan@example.com",
  "telefono": "+506 8888-8888",
  "tipo_servicio": "sitio_web",
  "mensaje": "Necesito un sitio web para mi negocio"
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Solicitud de cotización enviada correctamente",
  "job_id": "550e8400-e29b-41d4-a716-446655440000",
  "data": { ... }
}
```

### Consultar estado

```javascript
GET /api/cotizacion/estado/{job_id}
```

**Respuesta:**
```json
{
  "success": true,
  "data": {
    "job_id": "550e8400-e29b-41d4-a716-446655440000",
    "status": "completed",
    "step": "Proceso completado",
    "progress": 100,
    "result": {
      "cliente": { ... },
      "evento_calendario_id": "event_123",
      "opciones_enviadas": 3
    }
  }
}
```

## ⚙️ Configuración Requerida

Agrega en tu archivo `.env`:

```env
N8N_COTIZACION_WEBHOOK_URL=https://n8n.srv1137974.hstgr.cloud/webhook/cotizacion-servicios
```

## ✅ Checklist

- [ ] Workflow de n8n creado y activado
- [ ] Google Sheets configurado con lista de sitios
- [ ] Credenciales de Google configuradas en n8n
- [ ] Variable `N8N_COTIZACION_WEBHOOK_URL` en `.env`
- [ ] Formulario creado y probado
- [ ] Prueba end-to-end del flujo completo


