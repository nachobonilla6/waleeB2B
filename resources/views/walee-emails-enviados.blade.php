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
    @php
        // Contadores
        $totalEmails = \App\Models\PropuestaPersonalizada::count();
        $emailsEsteMes = \App\Models\PropuestaPersonalizada::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $emailsHoy = \App\Models\PropuestaPersonalizada::whereDate('created_at', today())->count();
    @endphp

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
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Emails Enviados
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Historial de propuestas y emails enviados</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <button onclick="openNewEmailModal()" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Nuevo</span>
                    </button>
                    <button onclick="window.open('https://mail.google.com', '_blank')" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span class="hidden sm:inline">Ir a Gmail</span>
                    </button>
                </div>
            </header>
            
            <!-- Search Bar -->
            <div class="mb-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                    <form method="GET" action="{{ route('walee.emails.enviados') }}" class="flex gap-2">
                        <div class="relative flex-1">
                            <input 
                                type="text" 
                                name="search"
                                value="{{ $searchQuery ?? '' }}"
                                placeholder="Buscar por asunto, email, cliente o contenido..."
                                class="w-full px-3 py-2 pl-9 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                            >
                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Buscar</span>
                        </button>
                        @if($searchQuery ?? '')
                            <a href="{{ route('walee.emails.enviados') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
            
            <!-- Email List -->
            <div class="space-y-2 sm:space-y-3 md:space-y-4 animate-fade-in-up">
                @forelse($emails as $index => $email)
                    <div class="email-card bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 hover:border-blue-400 dark:hover:border-blue-500/30 transition-all cursor-pointer shadow-sm dark:shadow-none" style="animation-delay: {{ $index * 0.05 }}s" onclick="openEmailModalFromSent({{ $email->id }}, '{{ addslashes($email->email ?? '') }}', '{{ addslashes($email->cliente_nombre ?? '') }}', '{{ addslashes($email->subject ?? '') }}', '{{ addslashes($email->body ?? '') }}', '{{ addslashes($email->ai_prompt ?? '') }}', {{ $email->cliente_id ?? 'null' }})">
                        <div class="flex items-start gap-2 sm:gap-3 md:gap-4">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-blue-100 dark:bg-blue-500/20 flex-shrink-0 flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h3 class="font-semibold text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate flex-1 min-w-0">{{ $email->subject }}</h3>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 flex-shrink-0 whitespace-nowrap">{{ $email->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-2 mb-1 sm:mb-2 flex-wrap">
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">
                                        <span class="text-blue-600 dark:text-blue-400">{{ $email->email }}</span>
                                        @if($email->cliente_nombre)
                                            <span class="text-slate-500 dark:text-slate-500"> · {{ $email->cliente_nombre }}</span>
                                        @endif
                                    </p>
                                    @if($email->tipo === 'extractor')
                                        <span class="inline-block px-1.5 py-0.5 text-[10px] font-medium rounded-full border bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border-cyan-500/30 whitespace-nowrap">
                                            Enviado con alpha
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500 line-clamp-2">{{ Str::limit(strip_tags($email->body), 100) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 sm:py-16">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white mb-2">No hay emails enviados</h3>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-500 mb-3 sm:mb-4">Aún no has enviado ninguna propuesta personalizada</p>
                        <a href="{{ route('walee.emails.crear') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 py-1.5 sm:px-4 sm:py-2 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg sm:rounded-xl transition-all text-xs sm:text-sm">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear primer email
                        </a>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($emails->hasPages())
                <div class="mt-4 sm:mt-6 md:mt-8 flex flex-wrap justify-center gap-2">
                    @if($emails->onFirstPage())
                        <span class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-lg sm:rounded-xl cursor-not-allowed text-xs sm:text-sm">Anterior</span>
                    @else
                        <a href="{{ $emails->previousPageUrl() }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-lg sm:rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none text-xs sm:text-sm">Anterior</a>
                    @endif
                    
                    <span class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-lg sm:rounded-xl border border-slate-200 dark:border-slate-700 text-xs sm:text-sm">
                        Página {{ $emails->currentPage() }} de {{ $emails->lastPage() }}
                    </span>
                    
                    @if($emails->hasMorePages())
                        <a href="{{ $emails->nextPageUrl() }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-lg sm:rounded-xl transition-colors border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-none text-xs sm:text-sm">Siguiente</a>
                    @else
                        <span class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-lg sm:rounded-xl cursor-not-allowed text-xs sm:text-sm">Siguiente</span>
                    @endif
                </div>
            @endif
            
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
            
            if (!templateId || !emailTemplates) {
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
                }
                return;
            }
            
            const template = emailTemplates.find(t => t.id == templateId);
            if (!template) {
                if (aiGenerateContainer) {
                    aiGenerateContainer.style.display = 'block';
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
            
            let templatesOptions = '<option value="">Seleccionar template (opcional)</option>';
            if (emailTemplates && emailTemplates.length > 0) {
                emailTemplates.forEach(template => {
                    templatesOptions += `<option value="${template.id}">${template.nombre}</option>`;
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
                        <select id="email_template_select" onchange="loadEmailTemplate(this.value)"
                            class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                            ${templatesOptions}
                        </select>
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
                <div class="text-left space-y-2 sm:space-y-3 md:space-y-4 ${isMobile ? 'text-xs' : 'text-sm'}">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg sm:rounded-xl p-2.5 sm:p-3 md:p-4 border border-slate-200 dark:border-slate-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 md:gap-4">
                            <div>
                                <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Para</h4>
                                <p class="text-xs sm:text-sm text-slate-900 dark:text-white break-words">${email.email}</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Cliente</h4>
                                <p class="text-xs sm:text-sm text-slate-900 dark:text-white">${email.cliente_nombre || 'N/A'}</p>
                            </div>
                            <div class="col-span-1 sm:col-span-2">
                                <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Enviado</h4>
                                <p class="text-xs sm:text-sm text-slate-900 dark:text-white">${new Date(email.created_at).toLocaleString('es-ES', { 
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
                        <div class="bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20 rounded-lg sm:rounded-xl p-2.5 sm:p-3 md:p-4">
                            <h4 class="text-xs font-medium text-violet-600 dark:text-violet-400 mb-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                                Prompt de AI
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300">${email.ai_prompt}</p>
                        </div>
                    ` : ''}
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg sm:rounded-xl p-2.5 sm:p-3 md:p-4 border border-slate-200 dark:border-slate-700">
                        <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Mensaje</h4>
                        ${email.tipo === 'extractor' || email.cliente_estado === 'extractor' ? 
                            `<div class="text-slate-900 dark:text-white text-xs sm:text-sm prose prose-sm dark:prose-invert max-w-none">${email.body}</div>` : 
                            `<div class="text-slate-900 dark:text-white whitespace-pre-wrap text-xs sm:text-sm">${email.body}</div>`
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
            
            @media (max-width: 640px) {
                .swal2-popup {
                    width: 95% !important;
                    margin: 0.5rem !important;
                    padding: 1rem !important;
                }
                .swal2-title {
                    font-size: 1.125rem !important;
                    margin-bottom: 0.75rem !important;
                }
                .swal2-html-container {
                    margin: 0.5rem 0 !important;
                    font-size: 0.875rem !important;
                }
                .swal2-confirm {
                    font-size: 0.875rem !important;
                    padding: 0.5rem 1rem !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @include('partials.walee-support-button')
</body>
</html>

