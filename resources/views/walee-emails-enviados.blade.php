<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Emails Enviados</title>
    <meta name="description" content="Walee - Historial de Emails Enviados">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .email-card {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(213, 159, 59, 0.3);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(213, 159, 59, 0.5);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Emails Enviados'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-3 sm:mb-4">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Emails Enviados
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('walee.emails.dashboard') }}" class="px-2 sm:px-3 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs" title="Dashboard">
                        <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                    <a href="{{ route('walee.bot.alpha') }}" class="px-2 sm:px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm hover:shadow" title="Alpha">
                        <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="hidden sm:inline">Alpha</span>
                    </a>
                    @if(!request()->has('cliente_id'))
                    <button onclick="openNewEmailModal()" class="px-2 sm:px-3 py-1.5 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm hover:shadow" title="Nuevo Email">
                        <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Nuevo</span>
                    </button>
                    @endif
                    <button onclick="window.open('https://mail.google.com', '_blank')" class="px-2 sm:px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm hover:shadow" title="Gmail">
                        <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span class="hidden sm:inline">Gmail</span>
                    </button>
                </div>
            </header>
            
            <!-- Search Bar -->
            <div class="mb-3">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                    <div class="flex gap-2 items-center">
                        <form method="GET" action="{{ route('walee.emails.enviados') }}" class="flex gap-2 flex-1">
                            <div class="relative flex-1">
                                <input 
                                    type="text" 
                                    name="search"
                                    value="{{ $searchQuery ?? '' }}"
                                    placeholder="Buscar..."
                                    class="w-full px-3 py-2 pl-9 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                                >
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <button type="submit" class="w-10 h-10 sm:w-auto sm:px-3 sm:py-2 sm:h-auto bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-all flex items-center justify-center gap-1.5 text-xs shadow-sm" title="Buscar">
                                <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden sm:inline">Buscar</span>
                            </button>
                            @if($searchQuery ?? '')
                                <a href="{{ route('walee.emails.enviados') }}" class="w-10 h-10 sm:w-auto sm:px-3 sm:py-2 sm:h-auto bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center justify-center gap-1.5 text-xs" title="Limpiar">
                                    <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="hidden sm:inline">Limpiar</span>
                                </a>
                            @endif
                        </form>
                        @if(!request()->has('cliente_id'))
                        <button onclick="openNewEmailModal()" class="w-10 h-10 sm:w-auto sm:px-3 sm:py-2 sm:h-auto bg-gradient-to-r from-violet-500 to-violet-600 hover:from-violet-600 hover:to-violet-700 text-white font-medium rounded-lg transition-all flex items-center justify-center gap-1.5 text-xs shadow-sm hover:shadow" title="Crear Email">
                            <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">Crear Email</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Email List -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                        Emails Enviados
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400">
                            ({{ $emails->total() }})
                        </span>
                    </h2>
                </div>
                
                <div class="space-y-2">
                @forelse($emails as $email)
                    <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500/30 hover:bg-blue-50/50 dark:hover:bg-blue-500/10 transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex-shrink-0 flex items-center justify-center border border-blue-500/30">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="font-medium text-sm text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $email->subject }}</p>
                                @if($email->tipo === 'extractor')
                                    <span class="px-1.5 py-0.5 text-[10px] font-medium rounded border bg-cyan-100 dark:bg-cyan-900/30 text-cyan-800 dark:text-cyan-300 border-cyan-300 dark:border-cyan-700 flex-shrink-0">
                                        Enviado con alpha
                                    </span>
                                @elseif($email->tipo === 'propuesta_personalizada' || $email->cliente_estado === 'propuesta_personalizada_enviada')
                                    <span class="px-1.5 py-0.5 text-[10px] font-medium rounded border bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border-orange-300 dark:border-orange-700 flex-shrink-0">
                                        Enviado manual
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ $email->email }}</p>
                            @if($email->cliente_nombre)
                                <p class="text-xs text-slate-500 dark:text-slate-500 truncate">{{ $email->cliente_nombre }}</p>
                            @endif
                            <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">{{ $email->created_at->diffForHumans() }}</p>
                        </div>
                        <button onclick="showEmailDetail({{ $email->id }})" class="p-1.5 rounded-md bg-walee-500/20 hover:bg-walee-500/30 text-walee-600 dark:text-walee-400 border border-walee-500/30 hover:border-walee-400/50 transition-all flex-shrink-0" title="Ver email">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron emails</p>
                    </div>
                @endforelse
                </div>
                
                <!-- Pagination -->
                @if($emails->hasPages())
                    <div class="mt-4 flex justify-center gap-2">
                        @if($emails->onFirstPage())
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Anterior</span>
                        @else
                            <a href="{{ $emails->previousPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Anterior</a>
                        @endif
                        
                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-md border border-slate-200 dark:border-slate-700 text-xs">
                            Página {{ $emails->currentPage() }} de {{ $emails->lastPage() }}
                        </span>
                        
                        @if($emails->hasMorePages())
                            <a href="{{ $emails->nextPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Siguiente</a>
                        @else
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Siguiente</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8 mt-4 sm:mt-6 md:mt-8">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    
    <script>
        // Email data
        const emailsData = @json($emails->items());
        
        // Templates de email disponibles
        const emailTemplates = @json($templates ?? []);
        
        // Clientes en proceso disponibles
        const clientesEnProceso = @json($clientesEnProceso ?? []);
        
        // Variables globales para el flujo de fases del modal de email
        let emailModalData = {
            clienteId: null,
            clienteEmail: '',
            clienteName: '',
            clienteWebsite: '',
            email: '',
            aiPrompt: '',
            subject: '',
            body: '',
            attachments: null
        };
        
        function openNewEmailModal() {
            // Resetear datos para un nuevo email
            emailModalData.clienteId = null;
            emailModalData.clienteEmail = '';
            emailModalData.clienteName = '';
            emailModalData.clienteWebsite = '';
            emailModalData.email = '';
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            emailModalData.attachments = null;
            
            // Abrir desde la fase 1
            showEmailPhase1();
        }
        
        function openEmailModalFromSent(emailId, email, clienteNombre, subject, body, aiPrompt, clienteId) {
            const emailData = emailsData.find(e => e.id === emailId);
            if (!emailData) return;
            
            // Configurar datos del email enviado
            emailModalData.clienteId = clienteId || emailData.cliente_id || null;
            emailModalData.clienteEmail = email || emailData.email || '';
            emailModalData.clienteName = clienteNombre || emailData.cliente_nombre || '';
            emailModalData.clienteWebsite = '';
            
            // Cargar datos del email enviado
            emailModalData.email = email || emailData.email || '';
            emailModalData.aiPrompt = aiPrompt || emailData.ai_prompt || '';
            emailModalData.subject = subject || emailData.subject || '';
            emailModalData.body = body || emailData.body || '';
            emailModalData.attachments = null;
            
            // Ir directamente a la fase 2 con los datos cargados
            showEmailPhase2();
        }
        
        function loadEmailTemplate(templateId) {
            const aiGenerateContainer = document.getElementById('ai_generate_container');
            const tipoDisplay = document.getElementById('template_tipo_display');
            const tipoBadgeInline = document.getElementById('template_tipo_badge_inline');
            const tipoBadgeValue = document.getElementById('template_tipo_badge_value');
            
            if (!templateId || !emailTemplates) {
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                if (tipoDisplay) {
                    tipoDisplay.style.display = 'none';
                }
                if (tipoBadgeInline) {
                    tipoBadgeInline.style.display = 'none';
                }
                return;
            }
            
            const template = emailTemplates.find(t => t.id == templateId);
            if (!template) {
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                if (tipoDisplay) {
                    tipoDisplay.style.display = 'none';
                }
                if (tipoBadgeInline) {
                    tipoBadgeInline.style.display = 'none';
                }
                return;
            }
            
            emailModalData.aiPrompt = template.ai_prompt || '';
            emailModalData.subject = template.asunto || '';
            emailModalData.body = template.contenido || '';
            
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = emailModalData.aiPrompt;
            }
            
            const subjectField = document.getElementById('email_subject');
            const bodyField = document.getElementById('email_body');
            if (subjectField) {
                subjectField.value = emailModalData.subject;
            }
            if (bodyField) {
                bodyField.value = emailModalData.body;
            }
            
            // Mostrar el tipo del template como badge
            setTimeout(() => {
                const tipoValue = document.getElementById('template_tipo_value');
                
                // Función para obtener colores del badge según el tipo
                const getTipoColors = (tipo) => {
                    const tipoColors = {
                        'business': {
                            class: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border border-blue-300 dark:border-blue-500/30',
                            style: 'background-color: rgb(219 234 254); color: rgb(29 78 216); border: 1px solid rgb(147 197 253);'
                        },
                        'agricultura': {
                            class: 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-300 border border-green-300 dark:border-green-500/30',
                            style: 'background-color: rgb(220 252 231); color: rgb(21 128 61); border: 1px solid rgb(134 239 172);'
                        },
                        'b2b': {
                            class: 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-300 dark:border-purple-500/30',
                            style: 'background-color: rgb(243 232 255); color: rgb(126 34 206); border: 1px solid rgb(196 181 253);'
                        },
                        'b2c': {
                            class: 'bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-300 border border-orange-300 dark:border-orange-500/30',
                            style: 'background-color: rgb(255 237 213); color: rgb(194 65 12); border: 1px solid rgb(254 215 170);'
                        }
                    };
                    const defaultColors = {
                        class: 'bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300 border border-violet-300 dark:border-violet-500/30',
                        style: 'background-color: rgb(237 233 254); color: rgb(109 40 217); border: 1px solid rgb(196 181 253);'
                    };
                    return tipoColors[tipo] || defaultColors;
                };
                
                if (template.tipo) {
                    const tipoText = template.tipo.charAt(0).toUpperCase() + template.tipo.slice(1);
                    const tipoColors = getTipoColors(template.tipo);
                    
                    // Badge debajo del select
                    if (tipoDisplay && tipoValue) {
                        tipoValue.textContent = tipoText;
                        tipoValue.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ' + tipoColors.class;
                        tipoValue.style.cssText = tipoColors.style + ' display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;';
                        tipoDisplay.style.display = 'block';
                        tipoDisplay.style.visibility = 'visible';
                    }
                    
                    // Badge inline en el select
                    if (tipoBadgeInline && tipoBadgeValue) {
                        tipoBadgeValue.textContent = tipoText;
                        tipoBadgeValue.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold ' + tipoColors.class;
                        tipoBadgeValue.style.cssText = tipoColors.style + ' display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.625rem; font-weight: 600;';
                        tipoBadgeInline.style.display = 'block';
                        tipoBadgeInline.style.visibility = 'visible';
                    }
                } else {
                    if (tipoDisplay) {
                        tipoDisplay.style.display = 'none';
                    }
                    if (tipoBadgeInline) {
                        tipoBadgeInline.style.display = 'none';
                    }
                }
            }, 100);
            
            if (aiGenerateContainer) {
                aiGenerateContainer.style.display = 'none';
            }
            
            const showAiBtn = document.getElementById('show_ai_btn');
            if (showAiBtn) {
                showAiBtn.style.display = 'block';
            }
        }
        
        function showAIGenerateButton() {
            const aiGenerateContainer = document.getElementById('ai_generate_container');
            if (aiGenerateContainer) {
                aiGenerateContainer.style.display = 'block';
            }
            
            const showAiBtn = document.getElementById('show_ai_btn');
            if (showAiBtn) {
                showAiBtn.style.display = 'none';
            }
            
            const templateSelect = document.getElementById('email_template_select');
            if (templateSelect) {
                templateSelect.value = '';
            }
            
            // Ocultar badges de tipo
            const tipoDisplay = document.getElementById('template_tipo_display');
            const tipoBadgeInline = document.getElementById('template_tipo_badge_inline');
            if (tipoDisplay) {
                tipoDisplay.style.display = 'none';
            }
            if (tipoBadgeInline) {
                tipoBadgeInline.style.display = 'none';
            }
            
            emailModalData.aiPrompt = '';
            emailModalData.subject = '';
            emailModalData.body = '';
            
            const aiPromptField = document.getElementById('ai_prompt');
            if (aiPromptField) {
                aiPromptField.value = '';
            }
        }
        
        function selectClienteProceso(clienteId) {
            if (!clienteId) {
                return;
            }
            
            const select = document.getElementById('cliente_proceso_select');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption) {
                const email = selectedOption.getAttribute('data-email') || '';
                const name = selectedOption.getAttribute('data-name') || '';
                
                // Actualizar datos del modal
                emailModalData.clienteId = clienteId;
                emailModalData.clienteEmail = email;
                emailModalData.clienteName = name;
                emailModalData.email = email;
                
                // Actualizar el campo de email
                const emailField = document.getElementById('email_destinatario');
                if (emailField) {
                    emailField.value = email;
                }
            }
        }
        
        function showEmailPhase1() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px'; // Ancho en vistas grandes
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            // Generar opciones de templates - ORDENAR: PRIMERO LOS QUE TIENEN TIPO
            let templatesOptions = '<option value="">Seleccionar template (opcional)</option>';
            if (emailTemplates && emailTemplates.length > 0) {
                // Ordenar: primero los que tienen tipo, luego los que no tienen
                const sortedTemplates = [...emailTemplates].sort((a, b) => {
                    const aHasTipo = a.tipo && a.tipo.trim() !== '';
                    const bHasTipo = b.tipo && b.tipo.trim() !== '';
                    if (aHasTipo && !bHasTipo) return -1; // a primero
                    if (!aHasTipo && bHasTipo) return 1;  // b primero
                    return 0; // mantener orden original si ambos tienen o no tienen tipo
                });
                
                sortedTemplates.forEach(template => {
                    // Mostrar el template siempre, con o sin tipo
                    const tipoLabel = template.tipo ? ` [${template.tipo.charAt(0).toUpperCase() + template.tipo.slice(1)}]` : '';
                    templatesOptions += `<option value="${template.id}" data-tipo="${template.tipo || ''}">${template.nombre}${tipoLabel}</option>`;
                });
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    ${emailTemplates && emailTemplates.length > 0 ? `
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">Template guardado (opcional)</label>
                            <button type="button" onclick="showAIGenerateButton()" id="show_ai_btn" style="display: none;"
                                class="text-xs text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 underline">
                                Usar AI en su lugar
                            </button>
                        </div>
                        <div class="relative">
                            <select id="email_template_select" onchange="loadEmailTemplate(this.value)"
                                class="w-full px-3 py-2 pr-20 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none appearance-none">
                                ${templatesOptions}
                            </select>
                            <div id="template_tipo_badge_inline" class="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none z-10" style="display: none;">
                                <span id="template_tipo_badge_value" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold"></span>
                            </div>
                        </div>
                        <div id="template_tipo_display" class="mt-2" style="display: none;">
                            <span id="template_tipo_value" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"></span>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${clientesEnProceso && clientesEnProceso.length > 0 ? `
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Seleccionar cliente en proceso (opcional)</label>
                        <select id="cliente_proceso_select" onchange="selectClienteProceso(this.value)"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                            <option value="">Seleccionar cliente...</option>
                            ${clientesEnProceso.map(cliente => 
                                `<option value="${cliente.id}" data-email="${cliente.email || ''}" data-name="${cliente.name || ''}">${cliente.name || 'Sin nombre'} - ${cliente.email || 'Sin email'}</option>`
                            ).join('')}
                        </select>
                    </div>
                    ` : ''}
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Email destinatario <span class="text-red-500">*</span></label>
                        <input type="email" id="email_destinatario" value="${emailModalData.email}" required
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Instrucciones para AI (opcional)</label>
                        <textarea id="ai_prompt" rows="5" placeholder="Ej: Genera un email profesional..."
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none resize-none">${emailModalData.aiPrompt}</textarea>
                        <div id="ai_generate_container">
                            <button type="button" onclick="generateEmailWithAI()" id="generateEmailBtn"
                                class="mt-2 w-full px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                                <span>Generar con AI</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 1</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const email = document.getElementById('email_destinatario').value;
                    if (!email) {
                        Swal.showValidationMessage('El email destinatario es requerido');
                        return false;
                    }
                    emailModalData.email = email;
                    const aiPromptField = document.getElementById('ai_prompt');
                    if (aiPromptField) {
                        emailModalData.aiPrompt = aiPromptField.value;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showEmailPhase2();
                }
            });
        }
        
        function showEmailPhase2() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px'; // Ancho en vistas grandes
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Asunto <span class="text-red-500">*</span></label>
                        <input type="text" id="email_subject" value="${emailModalData.subject}" required placeholder="Asunto del email"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Mensaje <span class="text-red-500">*</span></label>
                        <textarea id="email_body" rows="10" required placeholder="Escribe o genera el contenido del email..."
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none resize-none">${emailModalData.body}</textarea>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 2</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const subject = document.getElementById('email_subject').value;
                    const body = document.getElementById('email_body').value;
                    if (!subject || !body) {
                        Swal.showValidationMessage('Por favor, completa el asunto y el mensaje');
                        return false;
                    }
                    emailModalData.subject = subject;
                    emailModalData.body = body;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showEmailPhase3();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    showEmailPhase1();
                }
            });
        }
        
        function showEmailPhase3() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px'; // Ancho en vistas grandes
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <div class="space-y-3 text-left">
                    <div class="flex items-center justify-center gap-1 mb-3">
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                        <div class="w-2 h-2 rounded-full bg-violet-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-2">Adjuntar archivos (opcional)</label>
                        <input type="file" id="email_attachments" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.webp"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        <p class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mt-1">PDF o imágenes (máx. 10MB por archivo)</p>
                        <div id="email_files_list" class="mt-2 space-y-1.5"></div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 21.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg><span>Crear Email - Paso 3</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Enviar Email',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    const fileInput = document.getElementById('email_attachments');
                    const filesList = document.getElementById('email_files_list');
                    if (fileInput) {
                        fileInput.addEventListener('change', function(e) {
                            if (filesList) {
                                filesList.innerHTML = '';
                                Array.from(e.target.files).forEach((file, index) => {
                                    const fileItem = document.createElement('div');
                                    fileItem.className = `flex items-center justify-between p-1.5 rounded ${isDarkMode ? 'bg-slate-700' : 'bg-slate-100'}`;
                                    fileItem.innerHTML = `
                                        <span class="text-xs ${isDarkMode ? 'text-slate-300' : 'text-slate-700'}">${file.name}</span>
                                        <span class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-500'}">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                                    `;
                                    filesList.appendChild(fileItem);
                                });
                            }
                        });
                    }
                },
                preConfirm: async () => {
                    const attachments = document.getElementById('email_attachments');
                    emailModalData.attachments = attachments && attachments.files && attachments.files.length > 0 ? attachments.files : null;
                    
                    const formData = new FormData();
                    formData.append('cliente_id', emailModalData.clienteId || '');
                    formData.append('email', emailModalData.email);
                    formData.append('subject', emailModalData.subject);
                    formData.append('body', emailModalData.body);
                    formData.append('ai_prompt', emailModalData.aiPrompt || '');
                    formData.append('from_bot_alpha', 'true'); // Marcar como enviado-manual
                    
                    if (emailModalData.attachments) {
                        Array.from(emailModalData.attachments).forEach((file, index) => {
                            formData.append(`archivos[${index}]`, file);
                        });
                    }
                    
                    try {
                        Swal.fire({
                            title: 'Enviando...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b'
                        });
                        
                        const response = await fetch('{{ route("walee.emails.enviar") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Email enviado!',
                                text: data.message || 'El email se ha enviado correctamente',
                                confirmButtonColor: '#8b5cf6',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al enviar el email',
                                confirmButtonColor: '#ef4444',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: error.message,
                            confirmButtonColor: '#ef4444',
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b'
                        });
                    }
                    
                    return false;
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    showEmailPhase2();
                }
            });
        }
        
        async function generateEmailWithAI() {
            const generateBtn = document.getElementById('generateEmailBtn');
            const aiPrompt = document.getElementById('ai_prompt').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const clienteId = emailModalData.clienteId;
            const clienteName = emailModalData.clienteName;
            const clienteWebsite = emailModalData.clienteWebsite;
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Generando...</span>
            `;
            
            try {
                const response = await fetch('{{ route("walee.emails.generar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        cliente_id: clienteId,
                        ai_prompt: aiPrompt,
                        client_name: clienteName,
                        client_website: clienteWebsite,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    emailModalData.subject = data.subject;
                    emailModalData.body = data.body;
                    Swal.fire({
                        icon: 'success',
                        title: 'Email generado',
                        text: 'El contenido ha sido generado con AI',
                        confirmButtonColor: '#8b5cf6',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        Swal.close();
                        showEmailPhase2();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al generar email',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: error.message,
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                generateBtn.disabled = false;
                generateBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                    <span>Generar con AI</span>
                `;
            }
        }
        
        async function showEmailDetail(emailId) {
            const email = emailsData.find(e => e.id === emailId);
            if (!email) return;
            
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px'; // Ancho en vistas grandes
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <div class="text-left space-y-2 sm:space-y-3 md:space-y-4 ${isMobile ? 'text-xs w-full' : 'text-sm'}">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg sm:rounded-xl ${isMobile ? 'p-2 w-full' : 'p-2.5 sm:p-3 md:p-4'} border border-slate-200 dark:border-slate-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 md:gap-4 w-full">
                            <div class="w-full">
                                <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Para</h4>
                                <p class="text-xs sm:text-sm text-slate-900 dark:text-white break-words w-full">${email.email}</p>
                            </div>
                            <div class="w-full">
                                <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Cliente</h4>
                                <p class="text-xs sm:text-sm text-slate-900 dark:text-white w-full">${email.cliente_nombre || 'N/A'}</p>
                            </div>
                            <div class="col-span-1 sm:col-span-2 w-full">
                                <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Enviado</h4>
                                <p class="text-xs sm:text-sm text-slate-900 dark:text-white w-full">${new Date(email.created_at).toLocaleString('es-ES', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                            </div>
                        </div>
                    </div>
                    ${email.ai_prompt ? `
                        <div class="bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20 rounded-lg sm:rounded-xl ${isMobile ? 'p-2 w-full' : 'p-2.5 sm:p-3 md:p-4'}">
                            <h4 class="text-xs font-medium text-violet-600 dark:text-violet-400 mb-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                                Prompt de AI
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 w-full break-words">${email.ai_prompt}</p>
                        </div>
                    ` : ''}
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg sm:rounded-xl ${isMobile ? 'p-2 w-full' : 'p-2.5 sm:p-3 md:p-4'} border border-slate-200 dark:border-slate-700">
                        <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Mensaje</h4>
                        ${email.tipo === 'extractor' || email.cliente_estado === 'extractor' ? 
                            `<div class="text-slate-900 dark:text-white text-xs sm:text-sm prose prose-sm dark:prose-invert max-w-none w-full break-words overflow-x-auto email-html-content">${email.body}</div>` : 
                            `<div class="text-slate-900 dark:text-white whitespace-pre-wrap text-xs sm:text-sm w-full break-words">${email.body}</div>`
                        }
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: email.subject || 'Email',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#D59F3B',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                }
            });
        }
        
        // Estilos para SweetAlert dark/light mode
        const style = document.createElement('style');
        style.textContent = `
            .swal2-container {
                z-index: 99999 !important;
            }
            .swal2-popup {
                z-index: 99999 !important;
            }
            .swal2-backdrop-show {
                z-index: 99998 !important;
            }
            .dark-swal {
                background: #1e293b !important;
                color: #e2e8f0 !important;
            }
            .light-swal {
                background: #ffffff !important;
                color: #1e293b !important;
            }
            .dark-swal-title {
                color: #f1f5f9 !important;
            }
            .light-swal-title {
                color: #0f172a !important;
            }
            .dark-swal-html {
                color: #cbd5e1 !important;
            }
            .light-swal-html {
                color: #334155 !important;
            }
            @media (min-width: 1024px) {
                .swal2-popup {
                    max-height: 90vh !important;
                    overflow-y: auto !important;
                }
                .swal2-html-container {
                    max-height: calc(90vh - 150px) !important;
                    overflow-y: auto !important;
                }
            }
            
            /* Estilos para contenido HTML en emails */
            .email-html-content {
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                word-break: break-word !important;
                max-width: 100% !important;
            }
            .email-html-content img {
                max-width: 100% !important;
                height: auto !important;
                display: block !important;
            }
            .email-html-content table {
                width: 100% !important;
                max-width: 100% !important;
                border-collapse: collapse !important;
                table-layout: auto !important;
            }
            .email-html-content table td,
            .email-html-content table th {
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
            .email-html-content pre,
            .email-html-content code {
                white-space: pre-wrap !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                max-width: 100% !important;
            }
            .email-html-content iframe,
            .email-html-content video,
            .email-html-content embed,
            .email-html-content object {
                max-width: 100% !important;
                height: auto !important;
            }
            .email-html-content * {
                max-width: 100% !important;
            }
            
            @media (max-width: 640px) {
                .swal2-popup {
                    width: 95% !important;
                    max-width: 95% !important;
                    margin: 0.5rem !important;
                    padding: 0.75rem !important;
                }
                .swal2-title {
                    font-size: 1.125rem !important;
                    margin-bottom: 0.75rem !important;
                    padding: 0 0.5rem !important;
                }
                .swal2-html-container {
                    margin: 0.5rem 0 !important;
                    padding: 0 !important;
                    font-size: 0.875rem !important;
                    width: 100% !important;
                    max-width: 100% !important;
                }
                .swal2-html-container > div {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                .swal2-confirm {
                    font-size: 0.875rem !important;
                    padding: 0.5rem 1rem !important;
                }
                .email-html-content {
                    word-wrap: break-word !important;
                    overflow-wrap: break-word !important;
                    word-break: break-word !important;
                }
                .email-html-content img {
                    max-width: 100% !important;
                    height: auto !important;
                }
                .email-html-content table {
                    width: 100% !important;
                    max-width: 100% !important;
                    display: block !important;
                    overflow-x: auto !important;
                }
                .email-html-content pre,
                .email-html-content code {
                    white-space: pre-wrap !important;
                    word-wrap: break-word !important;
                    max-width: 100% !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @include('partials.walee-support-button')
</body>
</html>

