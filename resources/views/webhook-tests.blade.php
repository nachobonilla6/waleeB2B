<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Tests - Web Solutions CR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="/" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al inicio
                </a>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">🧪 Webhook Tests</h1>
                <p class="text-gray-600 dark:text-gray-400">Prueba tus webhooks enviando dos palabras personalizadas</p>
            </div>

            <!-- Formulario -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <form id="webhookForm" method="POST" action="{{ route('webhook.tests.send') }}">
                    @csrf
                    
                    <!-- Mensaje de respuesta -->
                    <div id="message" class="hidden mb-6 p-4 rounded-lg"></div>

                    <!-- Campo Palabra 1 -->
                    <div class="mb-6">
                        <label for="palabra1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Palabra 1 <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="palabra1" 
                            name="palabra1" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Ingresa la primera palabra">
                    </div>

                    <!-- Campo Palabra 2 -->
                    <div class="mb-6">
                        <label for="palabra2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Palabra 2 <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="palabra2" 
                            name="palabra2" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Ingresa la segunda palabra">
                    </div>

                    <!-- Campo Webhook URL -->
                    <div class="mb-6">
                        <label for="webhook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            URL del Webhook <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="url" 
                            id="webhook_url" 
                            name="webhook_url" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="https://ejemplo.com/webhook">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Ingresa la URL completa del webhook donde se enviarán los datos
                        </p>
                    </div>

                    <!-- Botón Enviar -->
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span id="submitText">Enviar al Webhook</span>
                            <span id="submitLoading" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Enviando...
                            </span>
                        </button>
                        <button 
                            type="button" 
                            onclick="document.getElementById('webhookForm').reset(); document.getElementById('message').classList.add('hidden');"
                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            Limpiar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">
                    <i class="fas fa-info-circle mr-2"></i> Información
                </h3>
                <p class="text-sm text-blue-800 dark:text-blue-300">
                    Los datos se enviarán al webhook en formato JSON con las siguientes claves: <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">palabra1</code>, <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">palabra2</code> y <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">timestamp</code>.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('webhookForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            const messageDiv = document.getElementById('message');
            
            // Deshabilitar botón y mostrar loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            messageDiv.classList.add('hidden');
            
            // Obtener datos del formulario
            const formData = new FormData(form);
            
            // Enviar petición
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                // Mostrar mensaje
                messageDiv.classList.remove('hidden');
                
                if (data.success) {
                    messageDiv.className = 'bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6';
                    messageDiv.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <div>
                                <strong>¡Éxito!</strong> ${data.message || 'Datos enviados correctamente al webhook.'}
                                ${data.response ? `<br><small class="text-green-600 dark:text-green-400">Respuesta del servidor: ${data.response}</small>` : ''}
                            </div>
                        </div>
                    `;
                } else {
                    messageDiv.className = 'bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6';
                    messageDiv.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <div>
                                <strong>Error:</strong> ${data.message || 'No se pudo enviar al webhook.'}
                                ${data.error ? `<br><small class="text-red-600 dark:text-red-400">${data.error}</small>` : ''}
                            </div>
                        </div>
                    `;
                }
                
                // Scroll al mensaje
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            })
            .catch(error => {
                // Mostrar error
                messageDiv.classList.remove('hidden');
                messageDiv.className = 'bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6';
                messageDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            <strong>Error:</strong> Ocurrió un error al enviar la petición.
                            <br><small class="text-red-600 dark:text-red-400">${error.message}</small>
                        </div>
                    </div>
                `;
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            })
            .finally(() => {
                // Restaurar botón
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
