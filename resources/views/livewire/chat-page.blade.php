<div class="h-full flex flex-col bg-white dark:bg-gray-800">
    <!-- Chat Header -->
    <div class="bg-primary-600 px-6 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center">
            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">W</div>
            <div class="ml-3">
                <h1 class="text-white font-semibold text-lg">WALEE</h1>
                <div class="text-indigo-100 text-sm">websolutions.work</div>
                <div class="flex items-center mt-1">
                    <span class="h-2 w-2 rounded-full bg-green-400 mr-2"></span>
                    <span class="text-xs text-indigo-100">En línea</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="flex-1 overflow-y-auto p-4 bg-white dark:bg-gray-800 space-y-4 min-h-0" id="messages-container">
        @foreach($messages as $index => $message)
            @if($message['type'] === 'assistant')
                <div class="flex items-start" wire:key="message-{{ $index }}">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-medium">
                        W
                    </div>
                    <div class="ml-3 max-w-xs lg:max-w-md">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 inline-block">
                            <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words">{{ $message['content'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            websolutions.work - {{ $message['timestamp']->format('H:i') }}
                        </p>
                    </div>
                </div>
            @else
                <div class="flex items-start justify-end" wire:key="message-{{ $index }}">
                    <div class="max-w-xs lg:max-w-md text-right">
                        <div class="bg-indigo-100 dark:bg-indigo-900 rounded-lg p-3">
                            <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words">{{ $message['content'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Tú - {{ $message['timestamp']->format('H:i') }}
                        </p>
                    </div>
                    <div class="ml-3 flex-shrink-0 h-8 w-8 rounded-full bg-indigo-600 dark:bg-indigo-500 flex items-center justify-center text-white font-medium">
                        {{ \Illuminate\Support\Str::substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            @endif
        @endforeach

        @if($isLoading)
            <div class="flex items-start" wire:key="loading">
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-medium">
                    W
                </div>
                <div class="ml-3 max-w-xs lg:max-w-md">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 inline-block">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Message Input -->
    <div class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 flex-shrink-0">
        <form wire:submit.prevent="sendMessage" class="flex items-end space-x-2">
            <div class="flex-1 relative">
                <textarea 
                    wire:model="newMessage"
                    x-data="{ resize() { $el.style.height = '48px'; $el.style.height = $el.scrollHeight + 'px'; } }"
                    x-init="resize(); $watch('$wire.newMessage', () => setTimeout(resize, 10))"
                    @input="resize()"
                    rows="1"
                    placeholder="Escribe tu mensaje..."
                    class="w-full rounded-2xl border-0 py-3 px-4 pr-12 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 resize-none transition-all duration-200 min-h-[48px] max-h-32 text-sm leading-relaxed overflow-y-auto"
                    style="scrollbar-width: thin;"
                    @keydown.enter.prevent="if(!event.shiftKey) $wire.sendMessage()"
                ></textarea>
            </div>
            <button 
                type="submit" 
                :disabled="$isLoading || !trim($newMessage)"
                class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white shadow-lg shadow-primary-500/50 hover:shadow-xl hover:shadow-primary-500/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transform hover:scale-105 active:scale-95"
                title="Enviar mensaje"
            >
                @if($isLoading)
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                @endif
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', ({ el, component }) => {
            // Auto-scroll al final cuando hay nuevos mensajes
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    });
</script>

