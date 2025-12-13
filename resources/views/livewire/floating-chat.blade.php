<div>
    <!-- Icono flotante de chat -->
    <button
        wire:click="toggleChat"
        class="fixed bottom-6 right-6 z-50 bg-primary-600 hover:bg-primary-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 hover:scale-110 flex items-center justify-center w-14 h-14"
        x-data="{ show: true }"
        x-show="show"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
    </button>

    <!-- Ventana de chat -->
    <div
        x-data="{ open: @entangle('isOpen') }"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-24 right-6 z-50 w-96 h-[500px] bg-white dark:bg-gray-800 rounded-lg shadow-2xl flex flex-col border border-gray-200 dark:border-gray-700"
        style="display: none;"
        x-cloak
    >
        <!-- Header del chat -->
        <div class="bg-primary-600 text-white p-4 rounded-t-lg flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="font-semibold">Chat de Soporte</h3>
            </div>
            <button
                wire:click="toggleChat"
                class="text-white hover:text-gray-200 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Área de mensajes -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
            @if(count($messages) === 0)
                <div class="text-center text-gray-500 dark:text-gray-400 mt-8">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-sm">No hay mensajes aún</p>
                    <p class="text-xs mt-1">Escribe un mensaje para comenzar</p>
                </div>
            @else
                @foreach($messages as $message)
                    <div class="flex {{ $message['sender'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-lg p-3 {{ $message['sender'] === 'user' ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                            <p class="text-sm">{{ $message['text'] }}</p>
                            <p class="text-xs mt-1 opacity-70">{{ $message['timestamp'] }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Input de mensaje -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <form wire:submit.prevent="sendMessage" class="flex gap-2">
                <input
                    type="text"
                    wire:model="newMessage"
                    placeholder="Escribe un mensaje..."
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                />
                <button
                    type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                // Scroll al final cuando hay nuevos mensajes
                const messagesContainer = document.getElementById('chat-messages');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            });
        });
    </script>
</div>
