<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WALEE - Chat</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

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
        
        #messages-container {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="antialiased h-screen overflow-hidden bg-gray-50 text-gray-900 dark:bg-slate-900 dark:text-slate-100">
    @php
        $chatMessages = \App\Models\ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        $lastAssistant = \App\Models\ChatMessage::where('user_id', auth()->id())
            ->where('type', 'assistant')
            ->orderBy('created_at', 'desc')
            ->first();
        $chatHistory = \App\Models\ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get(['type', 'message']);
        $defaultAvatar = 'https://scontent.fsyq2-1.fna.fbcdn.net/v/t39.30808-6/435683598_122110261394258631_6405474837326534704_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=zgjvYarZ9xgQ7kNvwEswbG1&_nc_oc=AdmWm-pIFz310EeZ09ooC9EseFnorsGaoX-I-s3_7InzU9AF7y1ktWzwE18dMah-YZQ&_nc_zt=23&_nc_ht=scontent.fsyq2-1.fna&_nc_gid=XUnr5aeZ51zDI4Jgejtwlw&oh=00_Afk_foorn0DkQzlRE4BKg1Ft8Jqm5SMBBr90TiKvgeT-CQ&oe=693D7C04';
    @endphp
    <div class="h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex-shrink-0 text-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold" style="color: #D59F3B;">wesolutions.work</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-300 hover:text-white transition-colors">
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Chat Content -->
        <main class="flex-1 min-h-0 overflow-hidden">
            <div class="h-full flex flex-col bg-white">
                <!-- Chat Header -->
                <div class="px-6 py-3 flex items-center justify-between flex-shrink-0 border-b border-gray-800" style="background-color: #D59F3B;">
                    <div class="flex items-center">
                        <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="h-10 w-10 rounded-full object-cover border-2 border-white/30">
                        <div class="ml-3">
                            <h1 class="text-white font-semibold text-lg">Walee</h1>
                            <div class="flex items-center mt-1">
                                <span class="h-2 w-2 rounded-full bg-green-400 mr-2"></span>
                                <span class="text-xs text-indigo-100">En línea</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-white/80 hidden sm:inline">Voz</span>
                        <button 
                            id="voice-toggle"
                            type="button"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#D59F3B]"
                            data-enabled="true"
                            style="background-color: #fff;"
                            aria-checked="true"
                            role="switch"
                        >
                            <span 
                                class="inline-block h-4 w-4 transform rounded-full bg-[#D59F3B] transition-transform translate-x-6"
                            ></span>
                        </button>
                    </div>
                </div>

                <!-- Search Bar Input (Top) -->
                <div class="px-4 py-4 border-b border-gray-800 bg-gray-900 flex-shrink-0">
                    <form id="chat-form" class="w-full">
                        <div class="relative">
                            <textarea
                                id="chat-input"
                                rows="3"
                                placeholder="Escribe tu mensaje aquí..."
                                class="w-full px-4 py-4 pr-12 rounded-lg border border-gray-700 bg-gray-800 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-[#D59F3B] focus:border-transparent transition-all duration-200 min-h-[96px]"
                                style="resize: vertical;"
                            ></textarea>
                            <button 
                                id="chat-send"
                                type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 rounded-lg text-white transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                style="background-color: #D59F3B;"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Messages (Bottom) -->
                <div 
                    class="flex-1 overflow-y-auto p-4 bg-gray-900 space-y-4 min-h-0" 
                    id="messages-container"
                >
                    @if($lastAssistant)
                        @php $last = $lastAssistant; @endphp
                        <div class="animate-fade-in" style="animation: fadeIn 0.3s ease-in;">
                            <div class="bg-gray-800 text-gray-100 rounded-lg p-4 shadow-sm border border-gray-700 inline-block">
                                <div class="text-sm break-words leading-relaxed">{!! nl2br(e($last->message)) !!}</div>
                                <p class="text-xs text-gray-400 mt-2">
                                    {{ $last->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('chat-form');
            const textarea = document.getElementById('chat-input');
            const sendBtn = document.getElementById('chat-send');
            const container = document.getElementById('messages-container');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const voiceToggle = document.getElementById('voice-toggle');
            let voiceEnabled = true;
            let history = @json($chatHistory->map(function($m){ return ['role' => $m->type === 'user' ? 'user' : 'assistant', 'content' => $m->message]; }));

            function showAssistantMessage(content, audioUrl = null) {
                if (!container) return;
                container.innerHTML = '';
                const wrapper = document.createElement('div');
                wrapper.className = 'animate-fade-in';
                wrapper.style.animation = 'fadeIn 0.3s ease-in';
                wrapper.innerHTML = `
                    <div class="bg-gray-800 text-gray-100 rounded-lg p-4 shadow-sm border border-gray-700 inline-block">
                        <div class="text-sm break-words leading-relaxed assistant-text">${content}</div>
                    </div>`;
                container.appendChild(wrapper);

                if (audioUrl) {
                    const audio = new Audio(audioUrl);
                    audio.preload = 'auto';
                    audio.play().catch(() => {});
                }
            }

            function scrollToTop() {
                if (container) container.scrollTop = 0;
            }

            async function sendToWebhook(message) {
                const resp = await fetch('https://n8n.srv1137974.hstgr.cloud/webhook/444688a4-305e-4d97-b667-5f52c2c3bda9', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        message,
                        user: '{{ auth()->user()->name }}',
                        email: '{{ auth()->user()->email }}',
                        conversation_history: history,
                    }),
                });
                if (!resp.ok) throw new Error('Error al llamar al webhook');
                let text = await resp.text();
                let assistantText = text;
                try {
                    const json = JSON.parse(text);
                    if (Array.isArray(json) && json[0]?.output) {
                        assistantText = json[0].output;
                    } else if (json.output) {
                        assistantText = json.output;
                    } else if (json.message) {
                        assistantText = json.message;
                    } else if (json.response) {
                        assistantText = json.response;
                    } else if (json.text) {
                        assistantText = json.text;
                    }
                } catch (_) {
                    // keep text as is
                }
                if (!assistantText || assistantText === 'undefined') {
                    assistantText = 'Lo siento, no recibí respuesta del servicio.';
                }
                return assistantText.trim();
            }

            async function finalizeMessage(userMessage, assistantMessage) {
                const resp = await fetch('{{ route('chat.finalize') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        user_message: userMessage,
                        assistant_message: assistantMessage,
                        voice_enabled: voiceEnabled,
                        skip_actions: true, // n8n maneja agenda/email
                    }),
                    credentials: 'same-origin',
                });

                if (!resp.ok) throw new Error('Error al guardar conversación');
                return resp.json();
            }

            if (voiceToggle) {
                voiceToggle.addEventListener('click', () => {
                    voiceEnabled = !voiceEnabled;
                    voiceToggle.setAttribute('data-enabled', voiceEnabled ? 'true' : 'false');
                    voiceToggle.setAttribute('aria-checked', voiceEnabled ? 'true' : 'false');
                    voiceToggle.style.backgroundColor = voiceEnabled ? '#fff' : '#d1d5db';
                    const knob = voiceToggle.querySelector('span');
                    if (knob) {
                        knob.classList.toggle('translate-x-6', voiceEnabled);
                        knob.classList.toggle('translate-x-1', !voiceEnabled);
                    }
                });
            }

            if (form && textarea && sendBtn && container) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const message = textarea.value.trim();
                    if (!message || sendBtn.disabled) return;

                    sendBtn.disabled = true;
                    textarea.disabled = true;
                    try {
                        const assistantText = await sendToWebhook(message);
                        const result = await finalizeMessage(message, assistantText || '');
                        const audioUrl = result?.audio_url || null;
                        showAssistantMessage(assistantText || 'Lo siento, no recibí respuesta.', audioUrl);
                        // Actualizar historial en frontend (mantener máx 20)
                        history.push({ role: 'user', content: message });
                        history.push({ role: 'assistant', content: assistantText || '' });
                        if (history.length > 20) {
                            history = history.slice(history.length - 20);
                        }
                    } catch (err) {
                        console.error(err);
                        showAssistantMessage('Lo siento, hubo un problema al procesar tu mensaje.');
                    } finally {
                        textarea.value = '';
                        textarea.disabled = false;
                        sendBtn.disabled = false;
                        scrollToTop();
                    }
                });
            }
        });
    </script>
</body>
</html>
