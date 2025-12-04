<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Ticket de Soporte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Nuevo Ticket de Soporte</h1>
            
            <div id="message" class="hidden p-4 mb-6 rounded"></div>
            
            <form id="ticketForm" class="space-y-6">
                @csrf
                
                <div class="space-y-4">
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Título del ticket -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título del ticket</label>
                        <input type="text" name="title" id="title" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción del problema</label>
                        <textarea name="description" id="description" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Imagen (opcional)</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100">
                    </div>

                    <!-- Botón de envío -->
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Enviar Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('ticketForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Mostrar estado de carga
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Enviando...';
            
            axios.post('https://n8n.srv1137974.hstgr.cloud/webhook-test/1400bf64-ed20-45c1-bc87-30c3f40fec37', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function (response) {
                messageDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                messageDiv.textContent = '¡Ticket enviado correctamente! Nos pondremos en contacto contigo pronto.';
                messageDiv.classList.remove('hidden');
                document.getElementById('ticketForm').reset();
                
                // Scroll al mensaje
                messageDiv.scrollIntoView({ behavior: 'smooth' });
            })
            .catch(function (error) {
                let errorMessage = 'Ocurrió un error al enviar el ticket. Por favor, inténtalo de nuevo.';
                
                if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                }
                
                messageDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
                messageDiv.textContent = errorMessage;
                messageDiv.classList.remove('hidden');
                
                // Scroll al mensaje
                messageDiv.scrollIntoView({ behavior: 'smooth' });
            })
            .finally(function() {
                // Restaurar el botón
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar Ticket';
            });
        });
    </script>
</body>
</html>