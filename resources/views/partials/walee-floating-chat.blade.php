<!-- Chat Flotante Walee -->
<div id="walee-floating-chat" class="fixed bottom-6 right-24 z-50" x-data="{ open: false }">
    <!-- Botón flotante -->
    <button
        @click="open = !open"
        class="bg-walee-500 hover:bg-walee-600 text-white rounded-full p-4 shadow-lg transition-all duration-300 hover:scale-110 flex items-center justify-center w-16 h-16 dark:bg-walee-600 dark:hover:bg-walee-700"
        :class="{ 'rotate-180': open }"
    >
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Ventana de chat -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-24 right-24 w-96 h-[600px] bg-white dark:bg-gray-800 rounded-lg shadow-2xl flex flex-col border border-gray-200 dark:border-gray-700 overflow-hidden"
        x-cloak
        style="display: none;"
    >
        <!-- Header del chat -->
        <div class="bg-walee-500 dark:bg-walee-600 text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border-2 border-white/30">
                    <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" 
                         alt="Walee" 
                         class="w-full h-full object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full bg-walee-400 flex items-center justify-center text-2xl font-bold" style="display: none;">
                        W
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold">Walee Chat</h3>
                    <p class="text-xs text-white/80">Asistente virtual</p>
                </div>
            </div>
            <button
                @click="open = false"
                class="text-white hover:text-gray-200 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Área de mensajes -->
        <div id="walee-chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900">
            <!-- Mensaje de bienvenida (se oculta cuando hay mensajes) -->
            <div id="walee-welcome-message" class="text-center text-gray-500 dark:text-gray-400 mt-8">
                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-sm">Hola, soy Walee</p>
                <p class="text-xs mt-1">¿En qué puedo ayudarte hoy?</p>
            </div>
        </div>

        <!-- Input de mensaje -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-800">
            <form id="walee-chat-form" class="flex gap-2">
                <input
                    type="text"
                    id="walee-chat-input"
                    placeholder="Escribe un mensaje..."
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-walee-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    autocomplete="off"
                />
                <button
                    type="submit"
                    class="bg-walee-500 hover:bg-walee-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center dark:bg-walee-600 dark:hover:bg-walee-700"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('walee-chat-form');
    const chatInput = document.getElementById('walee-chat-input');
    const chatMessages = document.getElementById('walee-chat-messages');
    const welcomeMessage = document.getElementById('walee-welcome-message');

    if (!chatForm || !chatInput || !chatMessages) return;

    let historyLoaded = false;
    let messagesLoaded = false;

    // Función para agregar mensaje al chat
    function addMessage(text, sender = 'user', timestamp = null, skipScroll = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'}`;
        
        const messageContent = document.createElement('div');
        messageContent.className = `max-w-[80%] rounded-lg p-3 ${
            sender === 'user' 
                ? 'bg-walee-500 dark:bg-walee-600 text-white' 
                : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'
        }`;
        
        const messageText = document.createElement('p');
        messageText.className = 'text-sm whitespace-pre-wrap';
        messageText.textContent = text;
        
        const messageTime = document.createElement('p');
        messageTime.className = 'text-xs mt-1 opacity-70';
        if (timestamp) {
            const date = new Date(timestamp);
            messageTime.textContent = date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        } else {
            messageTime.textContent = new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        }
        
        messageContent.appendChild(messageText);
        messageContent.appendChild(messageTime);
        messageDiv.appendChild(messageContent);
        
        // Ocultar mensaje de bienvenida si existe
        if (welcomeMessage) {
            welcomeMessage.style.display = 'none';
        }
        
        chatMessages.appendChild(messageDiv);
        if (!skipScroll) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    // Cargar historial del chat
    async function loadChatHistory() {
        if (historyLoaded) return;
        historyLoaded = true;
        
        try {
            const response = await fetch('/walee-chat/history', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.messages && data.messages.length > 0) {
                    // Limpiar mensajes existentes (excepto el de bienvenida si no hay mensajes)
                    const existingMessages = chatMessages.querySelectorAll('.flex.justify-end, .flex.justify-start');
                    existingMessages.forEach(msg => msg.remove());
                    
                    // Cargar todos los mensajes del historial
                    data.messages.forEach(msg => {
                        const sender = msg.type === 'user' ? 'user' : 'bot';
                        addMessage(msg.message, sender, msg.created_at, true);
                    });
                    
                    // Scroll al final después de cargar todos los mensajes
                    setTimeout(() => {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }, 100);
                    
                    messagesLoaded = true;
                }
            }
        } catch (error) {
            console.error('Error cargando historial:', error);
            historyLoaded = false; // Permitir reintentar si falla
        }
    }

    // Cargar historial inmediatamente al cargar la página
    loadChatHistory();

    // También cargar cuando se abre el chat (por si acaso no se cargó antes)
    const chatButton = document.querySelector('#walee-floating-chat button');
    if (chatButton) {
        chatButton.addEventListener('click', function() {
            setTimeout(() => {
                if (!messagesLoaded) {
                    loadChatHistory();
                }
            }, 200);
        });
    }

    // Enviar mensaje
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (!message) return;

        // Agregar mensaje del usuario
        addMessage(message, 'user');
        chatInput.value = '';

        // Mostrar indicador de escritura
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'flex justify-start';
        typingIndicator.id = 'typing-indicator';
        typingIndicator.innerHTML = `
            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-3">
                <div class="flex gap-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        `;
        chatMessages.appendChild(typingIndicator);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        try {
            // Enviar mensaje al servidor (usando la ruta de chat existente)
            const response = await fetch('/walee-chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            // Remover indicador de escritura
            typingIndicator.remove();

            // Agregar respuesta
            if (data.response) {
                addMessage(data.response, 'bot');
            } else {
                addMessage('Lo siento, no pude procesar tu mensaje. Por favor, intenta de nuevo.', 'bot');
            }
        } catch (error) {
            console.error('Error:', error);
            typingIndicator.remove();
            addMessage('Error al enviar el mensaje. Por favor, intenta de nuevo.', 'bot');
        }
    });
});
</script>

