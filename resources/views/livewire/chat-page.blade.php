@php
    use Illuminate\Support\Facades\Storage;
    
    // Función helper para convertir URLs en enlaces estéticos
    function formatMessageWithLinks($text) {
        // Primero escapar el texto para seguridad
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        
        // Convertir saltos de línea a <br>
        $text = nl2br($text);
        
        // Patrón para detectar URLs (más completo)
        $urlPattern = '/(https?:\/\/[^\s<>"\'{}|\\^`\[\]]+|www\.[^\s<>"\'{}|\\^`\[\]]+)/i';
        
        // Reemplazar URLs con enlaces estéticos
        $formatted = preg_replace_callback($urlPattern, function($matches) {
            $url = trim($matches[0]);
            
            // Agregar http:// si no tiene protocolo
            if (!preg_match('/^https?:\/\//i', $url)) {
                $url = 'https://' . $url;
            }
            
            // Detectar si es Google Calendar
            $isGoogleCalendar = str_contains($url, 'calendar.google.com') || str_contains($url, 'google.com/calendar');
            $icon = $isGoogleCalendar ? '📅' : '🔗';
            
            // Obtener dominio para mostrar
            $parsedUrl = parse_url($url);
            $domain = $parsedUrl['host'] ?? 'Enlace';
            $displayText = $isGoogleCalendar ? 'Ver en Google Calendar' : (str_replace('www.', '', $domain));
            
            return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 mt-1.5 text-xs font-medium text-primary-700 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-all duration-200 group">
                <span>' . $icon . '</span>
                <span class="group-hover:underline">' . htmlspecialchars($displayText, ENT_QUOTES, 'UTF-8') . '</span>
                <svg class="w-3 h-3 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>';
        }, $text);
        
        return $formatted;
    }
@endphp

<div class="h-full flex flex-col bg-white dark:bg-gray-800">
    <!-- Chat Header -->
    <div class="px-6 py-3 flex items-center justify-between flex-shrink-0" style="background-color: #D59F3B;">
        <div class="flex items-center">
            <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="WALEE" class="h-10 w-10 rounded-full object-cover border-2 border-white/20">
            <div class="ml-3">
                <h1 class="text-white font-semibold text-lg">WALEE</h1>
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
                <div class="flex items-start animate-fade-in" wire:key="message-{{ $index }}" style="animation: fadeIn 0.3s ease-in;">
                    <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="WALEE" class="flex-shrink-0 h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                    <div class="ml-3 max-w-xs lg:max-w-md">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 inline-block">
                            <div class="text-sm text-gray-800 dark:text-gray-200 break-words">
                                {!! formatMessageWithLinks($message['content']) !!}
                            </div>
                        </div>
                        @if(isset($message['audio_url']) && $message['audio_url'])
                            @php
                                // Asegurar que la URL sea absoluta
                                $audioUrl = $message['audio_url'];
                                if (!str_starts_with($audioUrl, 'http')) {
                                    // Si empieza con /storage, usar asset directamente
                                    if (str_starts_with($audioUrl, '/storage')) {
                                        $audioUrl = asset($audioUrl);
                                    } else {
                                        // Si es una ruta relativa, agregar /storage/
                                        $audioUrl = asset('storage/' . ltrim($audioUrl, '/'));
                                    }
                                }
                            @endphp
                            <div class="mt-2 bg-gray-50 dark:bg-gray-800 rounded-lg p-2">
                                <audio 
                                    controls 
                                    controlsList="nodownload"
                                    class="w-full h-10" 
                                    preload="metadata"
                                    id="audio-{{ $index }}"
                                    data-audio-url="{{ $audioUrl }}"
                                    wire:key="audio-{{ $index }}"
                                    onloadedmetadata="console.log('Audio cargado:', this.duration)"
                                    onerror="console.error('Error cargando audio:', this.error, 'URL:', '{{ $audioUrl }}')"
                                >
                                    <source src="{{ $audioUrl }}" type="audio/mpeg">
                                    <source src="{{ $audioUrl }}" type="audio/mp3">
                                    Tu navegador no soporta el elemento de audio.
                                    <a href="{{ $audioUrl }}" download>Descargar audio</a>
                                </audio>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <a href="{{ $audioUrl }}" target="_blank" class="underline">Ver URL del audio</a>
                                </p>
                            </div>
                        @endif
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            WALEE - {{ $message['timestamp']->format('H:i') }}
                        </p>
                    </div>
                </div>
            @else
                <div class="flex items-start justify-end animate-fade-in" wire:key="message-{{ $index }}" style="animation: fadeIn 0.3s ease-in;">
                    <div class="max-w-xs lg:max-w-md text-right">
                        <div class="bg-indigo-100 dark:bg-indigo-900 rounded-lg p-3">
                            <div class="text-sm text-gray-800 dark:text-gray-200 break-words">
                                {!! formatMessageWithLinks($message['content']) !!}
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Tú - {{ $message['timestamp']->format('H:i') }}
                        </p>
                    </div>
                    @php
                        $user = auth()->user();
                        $avatarUrl = $user->avatar ? Storage::url($user->avatar) : 'https://scontent.fsyq2-1.fna.fbcdn.net/v/t39.30808-6/435683598_122110261394258631_6405474837326534704_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=zgjvYarZ9xgQ7kNvwEswbG1&_nc_oc=AdmWm-pIFz310EeZ09ooC9EseFnorsGaoX-I-s3_7InzU9AF7y1ktWzwE18dMah-YZQ&_nc_zt=23&_nc_ht=scontent.fsyq2-1.fna&_nc_gid=XUnr5aeZ51zDI4Jgejtwlw&oh=00_Afk_foorn0DkQzlRE4BKg1Ft8Jqm5SMBBr90TiKvgeT-CQ&oe=693D7C04';
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="ml-3 flex-shrink-0 h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                </div>
            @endif
        @endforeach

        @if($isLoading)
            <div class="flex items-start animate-fade-in" wire:key="loading" style="animation: fadeIn 0.3s ease-in;">
                <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="WALEE" class="flex-shrink-0 h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-600">
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
                    x-data="{ 
                        resize() { 
                            $el.style.height = '48px'; 
                            $el.style.height = $el.scrollHeight + 'px'; 
                        },
                        handleEnter(e) {
                            if (e.key === 'Enter' && !e.shiftKey) {
                                e.preventDefault();
                                if (!$wire.isLoading && $wire.newMessage.trim()) {
                                    $wire.sendMessage();
                                }
                            }
                        }
                    }"
                    x-init="resize(); $watch('$wire.newMessage', () => setTimeout(resize, 10))"
                    @input="resize()"
                    @keydown="handleEnter($event)"
                    rows="1"
                    placeholder="Escribe tu mensaje... (Enter para enviar, Shift+Enter para nueva línea)"
                    class="w-full rounded-2xl border-0 py-3 px-4 pr-12 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 resize-none transition-all duration-200 min-h-[48px] max-h-32 text-sm leading-relaxed overflow-y-auto"
                    style="scrollbar-width: thin;"
                ></textarea>
            </div>
            <button 
                type="submit" 
                :disabled="$isLoading || !trim($newMessage)"
                class="inline-flex items-center justify-center h-16 w-16 rounded-lg text-white shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transform hover:scale-105 active:scale-95"
                style="background: linear-gradient(135deg, #D59F3B 0%, #C08A2E 100%); box-shadow: 0 10px 25px rgba(213, 159, 59, 0.4);"
                onmouseover="this.style.boxShadow='0 15px 35px rgba(213, 159, 59, 0.5)'"
                onmouseout="this.style.boxShadow='0 10px 25px rgba(213, 159, 59, 0.4)'"
                title="Enviar mensaje (Enter)"
            >
                @if($isLoading)
                    <svg class="animate-spin h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                @endif
            </button>
        </form>
    </div>
    
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        /* Smooth scroll */
        #messages-container {
            scroll-behavior: smooth;
        }
    </style>

    <script>
        document.addEventListener('livewire:init', () => {
            let lastAudioUrl = null;
            let playedAudios = new Set();
            
            function scrollToBottom(smooth = true) {
                const container = document.getElementById('messages-container');
                if (container) {
                    if (smooth) {
                        container.scrollTo({
                            top: container.scrollHeight,
                            behavior: 'smooth'
                        });
                    } else {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }
            
            function playAudio(audioElement) {
                if (!audioElement) return;
                
                const audioUrl = audioElement.getAttribute('data-audio-url') || audioElement.querySelector('source')?.src;
                
                if (!audioUrl || playedAudios.has(audioUrl)) {
                    return;
                }
                
                // Verificar que el audio esté cargado
                if (audioElement.readyState >= 2) {
                    playedAudios.add(audioUrl);
                    const playPromise = audioElement.play();
                    
                    if (playPromise !== undefined) {
                        playPromise.then(() => {
                            console.log('Audio reproduciéndose automáticamente:', audioUrl);
                        }).catch(error => {
                            console.log('Autoplay bloqueado. Usa los controles para reproducir.', error);
                        });
                    }
                } else {
                    // Esperar a que el audio se cargue
                    audioElement.addEventListener('loadedmetadata', () => {
                        playedAudios.add(audioUrl);
                        audioElement.play().catch(e => {
                            console.log('Autoplay bloqueado después de cargar:', e);
                        });
                    }, { once: true });
                    
                    audioElement.addEventListener('error', (e) => {
                        console.error('Error cargando audio:', audioUrl, audioElement.error);
                    }, { once: true });
                    
                    // Forzar carga
                    audioElement.load();
                }
            }
            
            // Scroll al final cuando el componente se monta inicialmente
            Livewire.hook('mounted', () => {
                setTimeout(() => {
                    scrollToBottom(false); // Scroll inmediato al cargar
                }, 200);
            });
            
            // Escuchar evento cuando hay un nuevo mensaje con audio
            Livewire.on('new-audio-message', (event) => {
                const audioUrl = event[0]?.audioUrl || event.audioUrl;
                if (audioUrl && !playedAudios.has(audioUrl)) {
                    lastAudioUrl = audioUrl;
                    
                    // Esperar a que el DOM se actualice
                    setTimeout(() => {
                        const container = document.getElementById('messages-container');
                        if (container) {
                            const audios = container.querySelectorAll('audio');
                            const lastAudio = audios[audios.length - 1];
                            playAudio(lastAudio);
                        }
                    }, 1000);
                }
            });
            
            Livewire.hook('morph.updated', ({ el, component }) => {
                // Auto-scroll al final cuando hay nuevos mensajes
                scrollToBottom(true);
                
                // Intentar reproducir el último audio si es nuevo
                setTimeout(() => {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        const audios = container.querySelectorAll('audio');
                        if (audios.length > 0) {
                            const lastAudio = audios[audios.length - 1];
                            playAudio(lastAudio);
                        }
                    }
                }, 800);
            });
        });
    </script>
</div>

