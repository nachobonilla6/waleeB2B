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
                    <a href="{{ route('walee.emails.crear') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
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
                    <div class="email-card bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 hover:border-blue-400 dark:hover:border-blue-500/30 transition-all cursor-pointer shadow-sm dark:shadow-none" style="animation-delay: {{ $index * 0.05 }}s" onclick="showEmailDetail({{ $email->id }})">
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
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1 sm:mb-2 truncate">
                                    <span class="text-blue-600 dark:text-blue-400">{{ $email->email }}</span>
                                    @if($email->cliente_nombre)
                                        <span class="text-slate-500 dark:text-slate-500"> · {{ $email->cliente_nombre }}</span>
                                    @endif
                                </p>
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
                        <div class="text-slate-900 dark:text-white whitespace-pre-wrap text-xs sm:text-sm">${email.body}</div>
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

