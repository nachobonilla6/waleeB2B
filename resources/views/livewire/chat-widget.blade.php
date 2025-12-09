<div>
    <!-- Botón flotante del chat -->
    <button
        wire:click="toggleChat"
        class="fixed bottom-6 right-6 z-50 h-14 w-14 rounded-full bg-primary-600 hover:bg-primary-700 text-white shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group"
        style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);"
    >
        @if($isOpen)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        @endif
        
        <!-- Indicador de notificación (opcional) -->
        @if(!$isOpen && count($messages) > 1)
            <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 rounded-full flex items-center justify-center text-xs text-white">
                {{ count($messages) - 1 }}
            </span>
        @endif
    </button>

    <!-- Ventana del chat -->
    @if($isOpen)
        <div 
            x-data="{ isOpen: @entangle('isOpen') }"
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-24 right-6 z-40 w-96 h-[600px] bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden"
            style="box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);"
        >
            <!-- Header del chat -->
            <div class="bg-primary-600 px-4 py-3 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold">
                        W
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-sm">WALEE</h3>
                        <div class="flex items-center space-x-1">
                            <span class="h-2 w-2 rounded-full bg-green-400"></span>
                            <span class="text-xs text-white/80">En línea</span>
                        </div>
                    </div>
                </div>
                <button 
                    wire:click="toggleChat"
                    class="text-white hover:text-white/80 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Área de mensajes -->
            <div 
                id="chat-messages"
                class="flex-1 overflow-y-auto p-4 space-y-4 bg-white dark:bg-gray-800"
                style="scrollbar-width: thin; scrollbar-color: rgba(156, 163, 175, 0.5) transparent;"
            >
                @foreach($messages as $index => $message)
                    <div class="flex items-start {{ $message['type'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        @if($message['type'] === 'assistant')
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-600 dark:text-primary-300 font-medium text-sm mr-2">
                                W
                            </div>
                        @endif
                        
                        <div class="max-w-[75%]">
                            <div class="rounded-lg px-4 py-2 {{ $message['type'] === 'user' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">
                                <p class="text-sm leading-relaxed">{{ $message['content'] }}</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 {{ $message['type'] === 'user' ? 'text-right' : 'text-left' }}">
                                {{ $message['timestamp']->format('H:i') }}
                            </p>
                        </div>

                        @if($message['type'] === 'user')
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-600 dark:bg-primary-500 flex items-center justify-center text-white font-medium text-sm ml-2">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Input del chat -->
            <div class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3 flex-shrink-0">
                <form wire:submit.prevent="sendMessage" class="flex items-end space-x-2">
                    <div class="flex-1 relative">
                        <textarea
                            wire:model="newMessage"
                            rows="1"
                            placeholder="Escribe tu mensaje..."
                            class="w-full rounded-lg border-0 py-2 px-3 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 resize-none text-sm"
                            style="min-height: 40px; max-height: 120px;"
                            x-data="{ 
                                resize() { 
                                    this.$el.style.height = '40px';
                                    this.$el.style.height = this.$el.scrollHeight + 'px';
                                }
                            }"
                            x-on:input="resize()"
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-primary-600 hover:bg-primary-700 text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors flex-shrink-0"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <script>
        const scrollToBottom = () => {
            setTimeout(() => {
                const messagesContainer = document.getElementById('chat-messages');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }, 100);
        };

        // Auto-scroll cuando se agregan nuevos mensajes
        document.addEventListener('livewire:updated', () => {
            scrollToBottom();
        });

        // Scroll inicial cuando se abre el chat
        Livewire.on('chat-opened', () => {
            scrollToBottom();
        });
    </script>
</div>
