<div>
    <!-- Botón flotante del chat -->
    <button
        wire:click="toggleChat"
        class="fixed bottom-6 right-6 z-50 h-16 w-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white shadow-2xl hover:shadow-3xl transition-all duration-300 flex items-center justify-center group hover:scale-110 active:scale-95"
        style="box-shadow: 0 8px 24px rgba(34, 197, 94, 0.4);"
    >
        @if($isOpen)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337L5.26 21.5l1.395-3.72C5.512 16.042 5 14.574 5 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
            </svg>
        @endif
        
        <!-- Indicador de notificación (opcional) -->
        @if(!$isOpen && count($messages) > 1)
            <span class="absolute -top-1 -right-1 h-6 w-6 bg-red-500 rounded-full flex items-center justify-center text-xs text-white font-bold shadow-lg animate-pulse ring-2 ring-white dark:ring-gray-800">
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
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="fixed bottom-24 right-6 z-40 w-[520px] h-[680px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 flex flex-col overflow-hidden backdrop-blur-sm"
            style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);"
        >
            <!-- Header del chat -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-5 flex items-center justify-between flex-shrink-0 rounded-t-2xl border-b border-primary-500/20">
                <div class="flex items-center space-x-4">
                    <div class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-xl shadow-lg ring-2 ring-white/30">
                        W
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg tracking-tight">WALEE</h3>
                        <div class="flex items-center space-x-2 mt-0.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-green-400 animate-pulse shadow-sm shadow-green-400/50"></span>
                            <span class="text-xs text-white/95 font-medium tracking-wide">En línea</span>
                        </div>
                    </div>
                </div>
                <button 
                    wire:click="toggleChat"
                    class="text-white hover:text-white hover:bg-white/20 rounded-xl p-2 transition-all duration-200 active:scale-95"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Área de mensajes -->
            <div 
                id="chat-messages"
                class="flex-1 overflow-y-auto p-6 space-y-5 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800"
                style="scrollbar-width: thin; scrollbar-color: rgba(156, 163, 175, 0.3) transparent;"
            >
                @foreach($messages as $index => $message)
                    <div class="flex items-start gap-3 {{ $message['type'] === 'user' ? 'flex-row-reverse' : 'flex-row' }}">
                        @if($message['type'] === 'assistant')
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-sm shadow-sm ring-2 ring-primary-100 dark:ring-primary-900/50">
                                W
                            </div>
                        @endif
                        
                        <div class="flex flex-col {{ $message['type'] === 'user' ? 'items-end' : 'items-start' }} max-w-[75%]">
                            <div class="rounded-2xl px-4 py-3 shadow-sm {{ $message['type'] === 'user' ? 'bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-tr-sm' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200/60 dark:border-gray-700/60 rounded-tl-sm' }}">
                                <p class="text-sm leading-relaxed break-words whitespace-pre-wrap">{{ $message['content'] }}</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 px-1 font-medium">
                                {{ $message['timestamp']->format('H:i') }}
                            </p>
                        </div>

                        @if($message['type'] === 'user')
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-primary-600 to-primary-700 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-primary-100 dark:ring-primary-900/50">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Input del chat -->
            <div class="border-t border-gray-200/60 dark:border-gray-700/60 bg-white dark:bg-gray-900 p-5 flex-shrink-0 rounded-b-2xl">
                <form wire:submit.prevent="sendMessage" class="flex items-end gap-3">
                    <div class="flex-1 relative">
                        <textarea
                            wire:model="newMessage"
                            rows="1"
                            placeholder="Escribe tu mensaje..."
                            class="w-full rounded-2xl border border-gray-300 dark:border-gray-700 py-3.5 px-5 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-offset-gray-900 resize-none text-sm transition-all shadow-sm focus:shadow-md"
                            style="min-height: 52px; max-height: 140px;"
                            x-data="{ 
                                resize() { 
                                    this.$el.style.height = '52px';
                                    this.$el.style.height = Math.min(this.$el.scrollHeight, 140) + 'px';
                                }
                            }"
                            x-on:input="resize()"
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center h-[52px] w-[52px] rounded-2xl bg-gradient-to-br from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-900 transition-all hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex-shrink-0"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <style>
        /* Estilos personalizados para el chat */
        #chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        #chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        
        #chat-messages::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 3px;
        }
        
        #chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.5);
        }
        
        .dark #chat-messages::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
        }
        
        .dark #chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.7);
        }
    </style>

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
