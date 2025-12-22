<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Chat</title>
    <meta name="description" content="Chat con Walee">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        walee: {
                            50: '#FBF7EE',
                            100: '#F5ECD6',
                            200: '#EBD9AD',
                            300: '#E0C684',
                            400: '#D59F3B',
                            500: '#C78F2E',
                            600: '#A67524',
                            700: '#7F5A1C',
                            800: '#594013',
                            900: '#33250B',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(213, 159, 59, 0.3); }
            50% { box-shadow: 0 0 40px rgba(213, 159, 59, 0.5); }
        }
        
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-4px); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        
        .typing-dot {
            animation: typing 1.2s infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
        
        .message-user {
            background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%);
        }
        
        .message-assistant {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .dark .message-assistant {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(100, 116, 139, 0.2);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 h-screen overflow-hidden">
    @php
        $chatMessages = \App\Models\ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();
    @endphp

    <div class="h-screen flex flex-col relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Header -->
        <header class="relative flex-shrink-0 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-800 px-4 py-3 safe-area-inset-top">
            <div class="max-w-7xl mx-auto">
                @php $pageTitle = 'Chat'; @endphp
                @include('partials.walee-navbar')
                
                <div class="flex items-center justify-center gap-2 mt-2">
                    <!-- Voice Toggle -->
                    <button id="voice-toggle" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-walee-500/50 transition-all shadow-sm dark:shadow-none" data-enabled="true">
                        <svg id="voice-icon" class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        </svg>
                        <span class="text-xs text-slate-600 dark:text-slate-400 hidden sm:inline">Voz</span>
                    </button>
                    
                    <!-- Clear Chat -->
                    <button id="clear-chat" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-red-500/50 hover:bg-red-50 dark:hover:bg-red-500/10 flex items-center justify-center transition-all shadow-sm dark:shadow-none" title="Limpiar chat">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>
        
        <!-- Messages Container -->
        <main id="messages-container" class="relative flex-1 overflow-y-auto px-4 py-6">
            <div class="max-w-3xl mx-auto space-y-4" id="messages-list">
                @if($chatMessages->count() === 0)
                    <!-- Welcome Message -->
                    <div class="text-center py-12 animate-fade-in-up">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-walee-400 to-walee-600 p-1" style="animation: pulse-glow 3s infinite;">
                            <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="w-full h-full rounded-full object-cover">
                        </div>
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">¡Hola! Soy Walee 👋</h2>
                        <p class="text-slate-600 dark:text-slate-400 max-w-md mx-auto">
                            Tu asistente virtual de Web Solutions. Puedo ayudarte con información sobre clientes, facturas, emails y más.
                        </p>
                        
                        <!-- Quick Actions -->
                        <div class="flex flex-wrap justify-center gap-2 mt-6">
                            <button onclick="sendQuickMessage('¿Cuántos clientes tenemos este mes?')" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-300 hover:border-walee-500/50 hover:text-walee-600 dark:hover:text-walee-400 transition-all shadow-sm dark:shadow-none">
                                📊 Clientes del mes
                            </button>
                            <button onclick="sendQuickMessage('¿Cuál es el total de ingresos?')" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-300 hover:border-walee-500/50 hover:text-walee-600 dark:hover:text-walee-400 transition-all shadow-sm dark:shadow-none">
                                💰 Ingresos totales
                            </button>
                            <button onclick="sendQuickMessage('Dame un resumen del día')" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-300 hover:border-walee-500/50 hover:text-walee-600 dark:hover:text-walee-400 transition-all shadow-sm dark:shadow-none">
                                📋 Resumen del día
                            </button>
                        </div>
                    </div>
                @else
                    @foreach($chatMessages as $index => $msg)
                        @if($msg->type === 'user')
                            <div class="flex justify-end animate-fade-in-up" style="animation-delay: {{ $index * 0.05 }}s;">
                                <div class="max-w-[85%] sm:max-w-[70%]">
                                    <div class="message-user rounded-2xl rounded-br-md px-4 py-3 text-white shadow-lg">
                                        <p class="text-sm leading-relaxed">{{ $msg->message }}</p>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 text-right mt-1 mr-1">{{ $msg->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start animate-fade-in-up" style="animation-delay: {{ $index * 0.05 }}s;">
                                <div class="flex gap-3 max-w-[85%] sm:max-w-[70%]">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-walee-400 to-walee-600 p-0.5 flex-shrink-0">
                                        <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="w-full h-full rounded-full object-cover">
                                    </div>
                                    <div>
                                        <div class="message-assistant rounded-2xl rounded-bl-md px-4 py-3 shadow-lg bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                                            <p class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">{{ $msg->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </main>
        
        <!-- Input Area -->
        <footer class="relative flex-shrink-0 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 px-4 py-4 safe-area-inset-bottom">
            <div class="max-w-3xl mx-auto">
                <form id="chat-form" class="flex items-end gap-3">
                    <div class="flex-1 relative">
                        <textarea
                            id="chat-input"
                            rows="1"
                            placeholder="Escribe un mensaje..."
                            class="w-full px-4 py-3 pr-12 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none shadow-sm dark:shadow-none"
                            style="min-height: 48px; max-height: 120px;"
                        ></textarea>
                    </div>
                    <button
                        id="chat-send"
                        type="submit"
                        class="w-12 h-12 rounded-xl bg-walee-500 hover:bg-walee-400 text-white flex items-center justify-center transition-all disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
                <p class="text-xs text-slate-600 dark:text-slate-500 text-center mt-2">
                    Walee puede cometer errores. Verifica la información importante.
                </p>
            </div>
        </footer>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('chat-form');
            const textarea = document.getElementById('chat-input');
            const sendBtn = document.getElementById('chat-send');
            const container = document.getElementById('messages-container');
            const messagesList = document.getElementById('messages-list');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const voiceToggle = document.getElementById('voice-toggle');
            const voiceIcon = document.getElementById('voice-icon');
            const clearBtn = document.getElementById('clear-chat');
            
            let voiceEnabled = true;
            let isProcessing = false;
            
            // Auto-resize textarea
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
            
            // Enter to send (Shift+Enter for new line)
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });
            
            // Scroll to bottom
            function scrollToBottom() {
                container.scrollTop = container.scrollHeight;
            }
            scrollToBottom();
            
            // Voice toggle
            voiceToggle.addEventListener('click', () => {
                voiceEnabled = !voiceEnabled;
                voiceToggle.dataset.enabled = voiceEnabled;
                
                if (voiceEnabled) {
                    voiceIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>';
                    voiceIcon.classList.add('text-walee-600', 'dark:text-walee-400');
                    voiceIcon.classList.remove('text-slate-500', 'dark:text-slate-500');
                } else {
                    voiceIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>';
                    voiceIcon.classList.remove('text-walee-600', 'dark:text-walee-400');
                    voiceIcon.classList.add('text-slate-500', 'dark:text-slate-500');
                }
            });
            
            // Clear chat
            clearBtn.addEventListener('click', async () => {
                if (!confirm('¿Eliminar todo el historial de chat?')) return;
                
                try {
                    await fetch('/walee-chat/clear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                    });
                    location.reload();
                } catch (e) {
                    console.error(e);
                }
            });
            
            // Add message to UI
            function addMessage(content, type, time = null) {
                const timeStr = time || new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                
                // Remove welcome message if exists
                const welcome = messagesList.querySelector('.text-center.py-12');
                if (welcome) welcome.remove();
                
                const wrapper = document.createElement('div');
                wrapper.className = `flex ${type === 'user' ? 'justify-end' : 'justify-start'} animate-fade-in-up`;
                
                if (type === 'user') {
                    wrapper.innerHTML = `
                        <div class="max-w-[85%] sm:max-w-[70%]">
                            <div class="message-user rounded-2xl rounded-br-md px-4 py-3 text-white shadow-lg">
                                <p class="text-sm leading-relaxed">${escapeHtml(content)}</p>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 text-right mt-1 mr-1">${timeStr}</p>
                        </div>
                    `;
                } else {
                    wrapper.innerHTML = `
                        <div class="flex gap-3 max-w-[85%] sm:max-w-[70%]">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-walee-400 to-walee-600 p-0.5 flex-shrink-0">
                                <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="w-full h-full rounded-full object-cover">
                            </div>
                            <div>
                                <div class="message-assistant rounded-2xl rounded-bl-md px-4 py-3 shadow-lg bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                                    <p class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">${escapeHtml(content)}</p>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-1">${timeStr}</p>
                            </div>
                        </div>
                    `;
                }
                
                messagesList.appendChild(wrapper);
                scrollToBottom();
            }
            
            // Add typing indicator
            function showTyping() {
                const wrapper = document.createElement('div');
                wrapper.id = 'typing-indicator';
                wrapper.className = 'flex justify-start animate-fade-in-up';
                wrapper.innerHTML = `
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-walee-400 to-walee-600 p-0.5 flex-shrink-0">
                            <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div class="message-assistant rounded-2xl px-4 py-3 flex items-center gap-1 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                            <span class="w-2 h-2 bg-slate-500 dark:bg-slate-400 rounded-full typing-dot"></span>
                            <span class="w-2 h-2 bg-slate-500 dark:bg-slate-400 rounded-full typing-dot"></span>
                            <span class="w-2 h-2 bg-slate-500 dark:bg-slate-400 rounded-full typing-dot"></span>
                        </div>
                    </div>
                `;
                messagesList.appendChild(wrapper);
                scrollToBottom();
            }
            
            function hideTyping() {
                const indicator = document.getElementById('typing-indicator');
                if (indicator) indicator.remove();
            }
            
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
            
            // Send message to webhook
            async function sendToWebhook(message) {
                const resp = await fetch('https://n8n.srv1137974.hstgr.cloud/webhook/444688a4-305e-4d97-b667-5f52c2c3bda9', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        message,
                        user: '{{ auth()->user()->name }}',
                        email: '{{ auth()->user()->email }}',
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
                } catch (_) {}
                
                return (assistantText || 'Lo siento, no recibí respuesta.').trim();
            }
            
            // Finalize and save
            async function finalizeMessage(userMessage, assistantMessage) {
                try {
                    console.log('Saving conversation...', { userMessage, assistantMessage, voiceEnabled });
                    
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
                            skip_actions: true,
                        }),
                        credentials: 'same-origin',
                    });
                    
                    if (resp.ok) {
                        const data = await resp.json();
                        console.log('Conversation saved successfully:', data);
                        return data;
                    } else {
                        const errorText = await resp.text();
                        console.error('Error saving conversation:', resp.status, errorText);
                    }
                } catch (err) {
                    console.error('Failed to save conversation:', err);
                }
                return null;
            }
            
            // Play audio
            function playAudio(url) {
                if (!url) return;
                const audio = new Audio(url);
                audio.preload = 'auto';
                audio.play().catch(err => console.log('Audio play error:', err));
            }
            
            // Quick message
            window.sendQuickMessage = function(msg) {
                textarea.value = msg;
                form.dispatchEvent(new Event('submit'));
            };
            
            // Form submit
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const message = textarea.value.trim();
                if (!message || isProcessing) return;
                
                isProcessing = true;
                sendBtn.disabled = true;
                textarea.disabled = true;
                
                // Add user message
                addMessage(message, 'user');
                textarea.value = '';
                textarea.style.height = 'auto';
                
                // Show typing
                showTyping();
                
                try {
                    const assistantText = await sendToWebhook(message);
                    hideTyping();
                    addMessage(assistantText, 'assistant');
                    
                    // Save to database and get audio
                    const result = await finalizeMessage(message, assistantText);
                    
                    // Play audio if enabled and available
                    if (voiceEnabled && result?.audio_url) {
                        playAudio(result.audio_url);
                    }
                } catch (err) {
                    console.error(err);
                    hideTyping();
                    addMessage('Lo siento, hubo un problema al procesar tu mensaje. Por favor, intenta de nuevo.', 'assistant');
                } finally {
                    isProcessing = false;
                    sendBtn.disabled = false;
                    textarea.disabled = false;
                    textarea.focus();
                }
            });
        });
    </script>
    @include('partials.walee-support-button')
</body>
</html>

