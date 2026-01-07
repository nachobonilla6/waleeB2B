<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Facturas</title>
    <meta name="description" content="Lista de Facturas">
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
            @php $pageTitle = 'Facturas'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-3 sm:mb-4">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Facturas
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('walee.dashboard') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 hover:from-slate-300 hover:to-slate-400 dark:hover:from-slate-600 dark:hover:to-slate-700 text-slate-900 dark:text-white font-semibold rounded-xl sm:rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                </div>
            </header>
            
            <!-- Search Bar -->
            <div class="mb-3 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm flex items-center gap-2">
                    <form method="GET" action="{{ route('walee.facturas.lista') }}" class="flex flex-1 gap-2">
                        <div class="relative flex-1">
                            <input 
                                type="text" 
                                name="search"
                                value="{{ $searchQuery ?? '' }}"
                                placeholder="Buscar por número, cliente, concepto o email..."
                                class="w-full px-3 py-2 pl-9 rounded-lg bg-slate-100 dark:bg-slate-900/80 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm"
                            >
                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm hover:shadow">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Buscar</span>
                        </button>
                        @if($searchQuery ?? '')
                            <a href="{{ route('walee.facturas.lista') }}" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Limpiar</span>
                            </a>
                        @endif
                    </form>
                    <button onclick="if(typeof abrirModalCrearFactura === 'function') { abrirModalCrearFactura(); } else { console.error('abrirModalCrearFactura no está definida'); alert('Error: El modal no está disponible. Por favor, recarga la página.'); }" class="px-3 py-1.5 bg-gradient-to-r from-violet-500 to-violet-600 hover:from-violet-600 hover:to-violet-700 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm hover:shadow flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Crear Factura</span>
                    </button>
                </div>
            </div>
            
            <!-- Facturas List -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                        Facturas
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400">
                            ({{ $facturas->total() }})
                        </span>
                    </h2>
                </div>
                
                <div class="space-y-2">
                @forelse($facturas as $factura)
                    <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500/30 hover:bg-blue-50/50 dark:hover:bg-blue-500/10 transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex-shrink-0 flex items-center justify-center border border-blue-500/30">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="font-medium text-sm text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    #{{ $factura->numero_factura }}
                                </p>
                                @php
                                    $estadoClass = '';
                                    switch ($factura->estado) {
                                        case 'pagada':
                                            $estadoClass = 'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border-emerald-500/30';
                                            break;
                                        case 'pendiente':
                                            $estadoClass = 'bg-amber-500/20 text-amber-600 dark:text-amber-400 border-amber-500/30';
                                            break;
                                        case 'vencida':
                                            $estadoClass = 'bg-red-500/20 text-red-600 dark:text-red-400 border-red-500/30';
                                            break;
                                        default:
                                            $estadoClass = 'bg-slate-500/20 text-slate-600 dark:text-slate-400 border-slate-500/30';
                                            break;
                                    }
                                @endphp
                                <span class="inline-block px-1.5 py-0.5 text-[10px] font-medium rounded-full border {{ $estadoClass }} whitespace-nowrap">
                                    {{ ucfirst($factura->estado) }}
                                </span>
                                @if($factura->enviada_at)
                                    <span class="inline-block px-1.5 py-0.5 text-[10px] font-medium rounded-full border bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border-cyan-500/30 whitespace-nowrap">
                                        Enviada
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 truncate">
                                {{ $factura->cliente?->nombre_empresa ?? 'Sin cliente' }}
                            </p>
                            @if($factura->concepto)
                                <p class="text-xs text-slate-500 dark:text-slate-500 truncate">{{ Str::limit($factura->concepto, 50) }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-0.5">
                                <p class="text-xs text-slate-500 dark:text-slate-500">₡{{ number_format($factura->total, 0, ',', '.') }}</p>
                                <span class="text-xs text-slate-400 dark:text-slate-600">·</span>
                                <p class="text-xs text-slate-500 dark:text-slate-500">{{ $factura->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            @if(!$factura->enviada_at)
                                <button onclick="enviarFactura({{ $factura->id }})" class="p-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-all shadow-sm hover:shadow" title="Enviar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            @else
                                <button onclick="reenviarFactura({{ $factura->id }})" class="p-2 rounded-lg bg-slate-500 hover:bg-slate-600 text-white transition-all shadow-sm hover:shadow" title="Reenviar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            @endif
                            <a href="{{ route('walee.facturas.crear', ['factura_id' => $factura->id]) }}" class="p-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-all shadow-sm hover:shadow" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button onclick="verPDFFactura({{ $factura->id }}, '{{ $factura->numero_factura }}')" class="p-2 rounded-lg bg-walee-500 hover:bg-walee-600 text-white transition-all shadow-sm hover:shadow" title="Ver PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button onclick="eliminarFactura({{ $factura->id }}, '{{ $factura->numero_factura }}')" class="p-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-all shadow-sm hover:shadow" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No se encontraron facturas</p>
                    </div>
                @endforelse
                </div>
                
                <!-- Pagination -->
                @if($facturas->hasPages())
                    <div class="mt-4 flex justify-center gap-2">
                        @if($facturas->onFirstPage())
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Anterior</span>
                        @else
                            <a href="{{ $facturas->previousPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Anterior</a>
                        @endif
                        
                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 rounded-md border border-slate-200 dark:border-slate-700 text-xs">
                            Página {{ $facturas->currentPage() }} de {{ $facturas->lastPage() }}
                        </span>
                        
                        @if($facturas->hasMorePages())
                            <a href="{{ $facturas->nextPageUrl() }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white rounded-md transition-colors border border-slate-200 dark:border-slate-700 text-xs">Siguiente</a>
                        @else
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-md cursor-not-allowed text-xs">Siguiente</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- World Map with Clocks -->
            @include('partials.walee-world-map-clocks')
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8 mt-4 sm:mt-6 md:mt-8">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Enviar factura
        async function enviarFactura(id) {
            let selectedLanguage = 'en';
            
            const { value: language } = await Swal.fire({
                title: 'Enviar factura',
                html: `
                    <div class="text-left space-y-3">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">Selecciona el idioma del correo:</p>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" class="lang-option group border border-slate-300 dark:border-slate-600 rounded-xl px-2 py-3 flex flex-col items-center justify-center gap-1 bg-slate-50 dark:bg-slate-800 data-[active=true]:border-emerald-500 data-[active=true]:bg-emerald-50/80 dark:data-[active=true]:bg-emerald-500/10"
                                data-lang="en" data-active="true">
                                <span class="text-2xl">🇺🇸</span>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">English</span>
                            </button>
                            <button type="button" class="lang-option group border border-slate-300 dark:border-slate-600 rounded-xl px-2 py-3 flex flex-col items-center justify-center gap-1 bg-slate-50 dark:bg-slate-800 data-[active=true]:border-emerald-500 data-[active=true]:bg-emerald-50/80 dark:data-[active=true]:bg-emerald-500/10"
                                data-lang="es">
                                <span class="text-2xl">🇪🇸</span>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">Español</span>
                            </button>
                            <button type="button" class="lang-option group border border-slate-300 dark:border-slate-600 rounded-xl px-2 py-3 flex flex-col items-center justify-center gap-1 bg-slate-50 dark:bg-slate-800 data-[active=true]:border-emerald-500 data-[active=true]:bg-emerald-50/80 dark:data-[active=true]:bg-emerald-500/10"
                                data-lang="fr">
                                <span class="text-2xl">🇫🇷</span>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">Français</span>
                            </button>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                didOpen: () => {
                    const options = document.querySelectorAll('.lang-option');
                    options.forEach(btn => {
                        btn.addEventListener('click', () => {
                            options.forEach(b => b.setAttribute('data-active', 'false'));
                            btn.setAttribute('data-active', 'true');
                            selectedLanguage = btn.getAttribute('data-lang') || 'en';
                        });
                    });
                },
                preConfirm: () => selectedLanguage || 'en',
            });
            
            if (!language) return;
            
            try {
                const response = await fetch(`/walee-facturas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ language }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: language === 'es' ? 'Factura enviada' : language === 'fr' ? 'Facture envoyée' : 'Invoice sent',
                        text: language === 'es' ? 'La factura ha sido enviada correctamente' : language === 'fr' ? 'La facture a été envoyée correctement' : 'The invoice has been sent successfully',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b'
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al enviar factura',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b'
                });
            }
        }
        
        // Reenviar factura
        async function reenviarFactura(id) {
            let selectedLanguage = 'en';
            
            const { value: language } = await Swal.fire({
                title: 'Reenviar factura',
                html: `
                    <div class="text-left space-y-3">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">Selecciona el idioma del correo:</p>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" class="lang-option group border border-slate-300 dark:border-slate-600 rounded-xl px-2 py-3 flex flex-col items-center justify-center gap-1 bg-slate-50 dark:bg-slate-800 data-[active=true]:border-emerald-500 data-[active=true]:bg-emerald-50/80 dark:data-[active=true]:bg-emerald-500/10"
                                data-lang="en" data-active="true">
                                <span class="text-2xl">🇺🇸</span>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">English</span>
                            </button>
                            <button type="button" class="lang-option group border border-slate-300 dark:border-slate-600 rounded-xl px-2 py-3 flex flex-col items-center justify-center gap-1 bg-slate-50 dark:bg-slate-800 data-[active=true]:border-emerald-500 data-[active=true]:bg-emerald-50/80 dark:data-[active=true]:bg-emerald-500/10"
                                data-lang="es">
                                <span class="text-2xl">🇪🇸</span>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">Español</span>
                            </button>
                            <button type="button" class="lang-option group border border-slate-300 dark:border-slate-600 rounded-xl px-2 py-3 flex flex-col items-center justify-center gap-1 bg-slate-50 dark:bg-slate-800 data-[active=true]:border-emerald-500 data-[active=true]:bg-emerald-50/80 dark:data-[active=true]:bg-emerald-500/10"
                                data-lang="fr">
                                <span class="text-2xl">🇫🇷</span>
                                <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">Français</span>
                            </button>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Resend',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                didOpen: () => {
                    const options = document.querySelectorAll('.lang-option');
                    options.forEach(btn => {
                        btn.addEventListener('click', () => {
                            options.forEach(b => b.setAttribute('data-active', 'false'));
                            btn.setAttribute('data-active', 'true');
                            selectedLanguage = btn.getAttribute('data-lang') || 'en';
                        });
                    });
                },
                preConfirm: () => selectedLanguage || 'en',
            });
            
            if (!language) return;
            
            try {
                const response = await fetch(`/walee-facturas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ language }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: language === 'es' ? 'Factura reenviada' : language === 'fr' ? 'Facture renvoyée' : 'Invoice resent',
                        text: language === 'es' ? 'La factura ha sido reenviada correctamente' : language === 'fr' ? 'La facture a été renvoyée correctement' : 'The invoice has been resent successfully',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al reenviar factura',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b'
                });
            }
        }
        
        // Eliminar factura
        async function eliminarFactura(id, numeroFactura) {
            const result = await Swal.fire({
                title: '¿Eliminar factura?',
                html: `¿Estás seguro de que deseas eliminar la factura <strong>#${numeroFactura}</strong>?<br><br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                reverseButtons: true
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const response = await fetch(`/walee-facturas/${id}/eliminar`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Factura eliminada',
                        text: 'La factura ha sido eliminada correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b'
                    });
                    setTimeout(() => location.reload(), 2000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar factura',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b'
                });
            }
        }
        
        // Ver PDF de factura en nueva ventana
        function verPDFFactura(facturaId, numeroFactura) {
            const pdfUrl = `/walee-facturas/${facturaId}/pdf`;
            window.open(pdfUrl, '_blank');
        }
        
        // Datos para modal
        const clientes = @json($clientes ?? []);
        const siguienteNumero = {{ str_pad($siguienteNumero, 4, "0", STR_PAD_LEFT) }};
        const tasaCambio = {{ $tasaCambio ?? 520 }};
        
        // Generar opciones de clientes para el select
        const clientesOptions = clientes.map(cliente => 
            `<option value="${cliente.id}">${cliente.nombre_empresa}</option>`
        ).join('');
        
        // Sistema de modales por fases para crear factura
        let facturaData = {
            factura_id: null,
            cliente_id: '',
            correo: '',
            numero_factura: '{{ str_pad($siguienteNumero, 4, "0", STR_PAD_LEFT) }}',
            serie: 'A',
            fecha_emision: '{{ date("Y-m-d") }}',
            fecha_vencimiento: '{{ date("Y-m-d", strtotime("+30 days")) }}',
            estado: 'pendiente',
            items: [],
            subtotal: 0,
            descuento_antes_impuestos: 0,
            iva: 0,
            descuento_despues_impuestos: 0,
            total: 0,
            monto_pagado: 0,
            saldo_pendiente: 0,
            metodo_pago: '',
            concepto_pago: '',
            concepto: '',
            numero_orden: '',
            pagos: [],
            notas: '',
            archivos: null
        };
        
        let currentPhase = 1;
        const totalPhases = 9;
        
        // Detectar modo oscuro
        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }
        
        // Calcular totales en modal
        function calcularTotalesModal() {
            if (facturaData.items.length === 0) return;
            
            const subtotal = facturaData.items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
            const descuentoAntes = facturaData.descuento_antes_impuestos || 0;
            const subtotalConDescuento = subtotal - descuentoAntes;
            const iva = subtotalConDescuento * 0.13;
            const descuentoDespues = facturaData.descuento_despues_impuestos || 0;
            const total = subtotalConDescuento + iva - descuentoDespues;
            const montoPagado = facturaData.monto_pagado || 0;
            const saldoPendiente = total - montoPagado;
            
            facturaData.subtotal = subtotal;
            facturaData.iva = iva;
            facturaData.total = total;
            facturaData.saldo_pendiente = saldoPendiente;
        }
        
        // Abrir modal para crear factura (Fase 1)
        function abrirModalCrearFactura() {
            try {
                // Resetear datos de factura
                facturaData = {
                    factura_id: null,
                    cliente_id: '',
                    correo: '',
                    numero_factura: '{{ str_pad($siguienteNumero, 4, "0", STR_PAD_LEFT) }}',
                    serie: 'A',
                    fecha_emision: '{{ date("Y-m-d") }}',
                    fecha_vencimiento: '{{ date("Y-m-d", strtotime("+30 days")) }}',
                    estado: 'pendiente',
                    items: [],
                    subtotal: 0,
                    descuento_antes_impuestos: 0,
                    iva: 0,
                    descuento_despues_impuestos: 0,
                    total: 0,
                    monto_pagado: 0,
                    saldo_pendiente: 0,
                    metodo_pago: '',
                    concepto_pago: '',
                    concepto: '',
                    numero_orden: '',
                    pagos: [],
                    notas: '',
                    archivos: null
                };
                currentPhase = 1;
                mostrarFase1();
            } catch (error) {
                console.error('Error al abrir modal:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo abrir el modal. Por favor, recarga la página.',
                    confirmButtonColor: '#ef4444',
                });
            }
        }
        
        // FASE 1: Cliente y Correo
        function mostrarFase1() {
            const clientesOptionsHtml = clientes.map(cliente => 
                `<option value="${cliente.id}" data-email="${cliente.correo || ''}">${cliente.nombre_empresa}</option>`
            ).join('');
            
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente <span class="text-red-500">*</span></label>
                        <select id="modal_cliente_id" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                            <option value="">Seleccionar cliente...</option>
                            ${clientesOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo <span class="text-red-500">*</span></label>
                        <input type="email" id="modal_correo" value="${facturaData.correo}" placeholder="correo@ejemplo.com" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 1: Información del Cliente',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                didOpen: () => {
                    const select = document.getElementById('modal_cliente_id');
                    const emailInput = document.getElementById('modal_correo');
                    
                    select.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const email = selectedOption.dataset.email || '';
                        emailInput.value = email;
                    });
                },
                preConfirm: () => {
                    const clienteId = document.getElementById('modal_cliente_id').value.trim();
                    const correo = document.getElementById('modal_correo').value.trim();
                    
                    if (!clienteId) {
                        Swal.showValidationMessage('Debe seleccionar un cliente');
                        return false;
                    }
                    if (!correo || !correo.includes('@')) {
                        Swal.showValidationMessage('Debe ingresar un correo válido');
                        return false;
                    }
                    
                    facturaData.cliente_id = clienteId;
                    facturaData.correo = correo;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 2;
                    mostrarFase2();
                }
            });
        }
        
        // FASE 2: Información Básica de Factura
        function mostrarFase2() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de Factura <span class="text-red-500">*</span></label>
                            <input type="text" id="modal_numero_factura" value="${facturaData.numero_factura}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Serie</label>
                            <input type="text" id="modal_serie" value="${facturaData.serie}" placeholder="A, B, C..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Emisión <span class="text-red-500">*</span></label>
                            <input type="date" id="modal_fecha_emision" value="${facturaData.fecha_emision}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Vencimiento</label>
                            <input type="date" id="modal_fecha_vencimiento" value="${facturaData.fecha_vencimiento}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                        <select id="modal_estado" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                            <option value="pendiente" ${facturaData.estado === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                            <option value="pagada" ${facturaData.estado === 'pagada' ? 'selected' : ''}>Pagada</option>
                            <option value="vencida" ${facturaData.estado === 'vencida' ? 'selected' : ''}>Vencida</option>
                            <option value="cancelada" ${facturaData.estado === 'cancelada' ? 'selected' : ''}>Cancelada</option>
                        </select>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 2: Información Básica de Factura',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    const numeroFactura = document.getElementById('modal_numero_factura').value.trim();
                    const fechaEmision = document.getElementById('modal_fecha_emision').value;
                    
                    if (!numeroFactura) {
                        Swal.showValidationMessage('El número de factura es requerido');
                        return false;
                    }
                    if (!fechaEmision) {
                        Swal.showValidationMessage('La fecha de emisión es requerida');
                        return false;
                    }
                    
                    facturaData.numero_factura = numeroFactura;
                    facturaData.serie = document.getElementById('modal_serie').value || 'A';
                    facturaData.fecha_emision = fechaEmision;
                    facturaData.fecha_vencimiento = document.getElementById('modal_fecha_vencimiento').value || facturaData.fecha_vencimiento;
                    facturaData.estado = document.getElementById('modal_estado').value;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 3;
                    mostrarFase3();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 1;
                    mostrarFase1();
                }
            });
        }
        
        // FASE 3: Items de Factura
        function mostrarFase3() {
            let itemsHtml = '';
            if (facturaData.items.length === 0) {
                itemsHtml = '<p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">No hay items agregados</p>';
            } else {
                itemsHtml = facturaData.items.map((item, index) => `
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-lg mb-2">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="text-sm font-medium">${item.descripcion || 'Sin descripción'}</p>
                                <p class="text-xs text-slate-500">Cant: ${item.cantidad} x $${parseFloat(item.precio_unitario).toLocaleString()} = $${parseFloat(item.subtotal).toLocaleString()}</p>
                            </div>
                            <button onclick="eliminarItemModal(${index})" class="text-red-500 hover:text-red-700 text-xs">Eliminar</button>
                        </div>
                    </div>
                `).join('');
            }
            
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div id="modal_items_list" class="max-h-60 overflow-y-auto mb-3">
                        ${itemsHtml}
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-2">
                            <input type="text" id="modal_item_descripcion" placeholder="Descripción" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <input type="number" id="modal_item_cantidad" placeholder="Cantidad" value="1" min="1" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <input type="number" id="modal_item_precio" step="0.01" placeholder="Precio" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                    <button onclick="agregarItemModal()" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm">Agregar Item</button>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 3: Items de Factura',
                html: html,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                didOpen: () => {
                    window.eliminarItemModal = function(index) {
                        facturaData.items.splice(index, 1);
                        calcularTotalesModal();
                        mostrarFase3();
                    };
                    window.agregarItemModal = function() {
                        const descripcion = document.getElementById('modal_item_descripcion').value.trim();
                        const cantidad = parseFloat(document.getElementById('modal_item_cantidad').value) || 1;
                        const precio = parseFloat(document.getElementById('modal_item_precio').value) || 0;
                        
                        if (!descripcion) {
                            Swal.showValidationMessage('La descripción es requerida');
                            return;
                        }
                        if (precio <= 0) {
                            Swal.showValidationMessage('El precio debe ser mayor a 0');
                            return;
                        }
                        
                        facturaData.items.push({
                            descripcion: descripcion,
                            cantidad: cantidad,
                            precio_unitario: precio,
                            subtotal: cantidad * precio
                        });
                        
                        document.getElementById('modal_item_descripcion').value = '';
                        document.getElementById('modal_item_cantidad').value = '1';
                        document.getElementById('modal_item_precio').value = '';
                        
                        calcularTotalesModal();
                        mostrarFase3();
                    };
                },
                preConfirm: () => {
                    if (facturaData.items.length === 0) {
                        Swal.showValidationMessage('Debe agregar al menos un item');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 4;
                    mostrarFase4();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 2;
                    mostrarFase2();
                }
            });
        }
        
        // FASE 4: Descuentos e Impuestos
        function mostrarFase4() {
            calcularTotalesModal();
            
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg mb-3">
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Subtotal</label>
                                <p class="text-lg font-bold text-slate-900 dark:text-white" id="modalSubtotalUSD">$${facturaData.subtotal.toFixed(2)}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500" id="modalSubtotalCRC">₡0</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">IVA (13%)</label>
                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400" id="modalIvaUSD">$${facturaData.iva.toFixed(2)}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500" id="modalIvaCRC">₡0</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Total</label>
                            <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400" id="modalTotalUSD">$${facturaData.total.toFixed(2)}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-500" id="modalTotalCRC">₡0</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descuento Antes Impuestos</label>
                            <input type="number" step="0.01" id="modal_descuento_antes" value="${facturaData.descuento_antes_impuestos}" oninput="calcularTotalesModalFase4()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descuento Después Impuestos</label>
                            <input type="number" step="0.01" id="modal_descuento_despues" value="${facturaData.descuento_despues_impuestos}" oninput="calcularTotalesModalFase4()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Monto Pagado</label>
                            <input type="number" step="0.01" id="modal_monto_pagado" value="${facturaData.monto_pagado}" oninput="calcularTotalesModalFase4()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" id="modal_total" value="${facturaData.total.toFixed(2)}" readonly class="w-full px-3 py-2 bg-emerald-50 dark:bg-emerald-500/10 border-2 border-emerald-500 rounded-lg text-sm font-bold text-emerald-700 dark:text-emerald-400">
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 4: Descuentos e Impuestos',
                html: html,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                didOpen: () => {
                    window.calcularTotalesModalFase4 = function() {
                        const subtotal = facturaData.items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
                        const descuentoAntes = parseFloat(document.getElementById('modal_descuento_antes')?.value) || 0;
                        const subtotalConDescuento = subtotal - descuentoAntes;
                        const iva = subtotalConDescuento * 0.13;
                        const descuentoDespues = parseFloat(document.getElementById('modal_descuento_despues')?.value) || 0;
                        const total = subtotalConDescuento + iva - descuentoDespues;
                        const montoPagado = parseFloat(document.getElementById('modal_monto_pagado')?.value) || 0;
                        const saldoPendiente = total - montoPagado;
                        
                        facturaData.subtotal = subtotal;
                        facturaData.descuento_antes_impuestos = descuentoAntes;
                        facturaData.iva = iva;
                        facturaData.descuento_despues_impuestos = descuentoDespues;
                        facturaData.total = total;
                        facturaData.monto_pagado = montoPagado;
                        facturaData.saldo_pendiente = saldoPendiente;
                        
                        // Actualizar valores en dólares
                        if (document.getElementById('modalSubtotalUSD')) {
                            document.getElementById('modalSubtotalUSD').textContent = `$${subtotal.toFixed(2)}`;
                        }
                        if (document.getElementById('modalIvaUSD')) {
                            document.getElementById('modalIvaUSD').textContent = `$${iva.toFixed(2)}`;
                        }
                        if (document.getElementById('modalTotalUSD')) {
                            document.getElementById('modalTotalUSD').textContent = `$${total.toFixed(2)}`;
                        }
                        if (document.getElementById('modal_total')) {
                            document.getElementById('modal_total').value = total.toFixed(2);
                        }
                        
                        // Actualizar valores en colones
                        if (document.getElementById('modalSubtotalCRC')) {
                            document.getElementById('modalSubtotalCRC').textContent = `₡${(subtotal * tasaCambio).toLocaleString('es-CR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                        }
                        if (document.getElementById('modalIvaCRC')) {
                            document.getElementById('modalIvaCRC').textContent = `₡${(iva * tasaCambio).toLocaleString('es-CR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                        }
                        if (document.getElementById('modalTotalCRC')) {
                            document.getElementById('modalTotalCRC').textContent = `₡${(total * tasaCambio).toLocaleString('es-CR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                        }
                    };
                    // Calcular inicialmente
                    setTimeout(() => {
                        if (window.calcularTotalesModalFase4) {
                            window.calcularTotalesModalFase4();
                        }
                    }, 100);
                },
                preConfirm: () => {
                    if (window.calcularTotalesModalFase4) {
                        window.calcularTotalesModalFase4();
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 5;
                    mostrarFase5();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 3;
                    mostrarFase3();
                }
            });
        }
        
        // FASE 5: Método de Pago
        function mostrarFase5() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Método de Pago</label>
                            <select id="modal_metodo_pago" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                                <option value="">Sin especificar</option>
                                <option value="transferencia" ${facturaData.metodo_pago === 'transferencia' ? 'selected' : ''}>Transferencia Bancaria</option>
                                <option value="efectivo" ${facturaData.metodo_pago === 'efectivo' ? 'selected' : ''}>Efectivo</option>
                                <option value="tarjeta" ${facturaData.metodo_pago === 'tarjeta' ? 'selected' : ''}>Tarjeta de Crédito/Débito</option>
                                <option value="sinpe" ${facturaData.metodo_pago === 'sinpe' ? 'selected' : ''}>SINPE Móvil</option>
                                <option value="paypal" ${facturaData.metodo_pago === 'paypal' ? 'selected' : ''}>PayPal</option>
                                <option value="otro" ${facturaData.metodo_pago === 'otro' ? 'selected' : ''}>Otro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Concepto de Pago</label>
                            <input type="text" id="modal_concepto_pago" value="${facturaData.concepto_pago}" placeholder="Ej: Pago inicial..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 5: Método de Pago',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    facturaData.metodo_pago = document.getElementById('modal_metodo_pago').value || 'sin_especificar';
                    facturaData.concepto_pago = document.getElementById('modal_concepto_pago').value;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 6;
                    mostrarFase6();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 4;
                    mostrarFase4();
                }
            });
        }
        
        // FASE 6: Concepto General
        function mostrarFase6() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Concepto General <span class="text-red-500">*</span></label>
                        <textarea id="modal_concepto" rows="4" placeholder="Descripción general de la factura..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">${facturaData.concepto}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de Orden</label>
                        <input type="text" id="modal_numero_orden" value="${facturaData.numero_orden}" placeholder="Ej: 1_191125 cliente" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 6: Concepto y Referencias',
                html: html,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    const concepto = document.getElementById('modal_concepto').value.trim();
                    if (!concepto) {
                        Swal.showValidationMessage('El concepto general es requerido');
                        return false;
                    }
                    facturaData.concepto = concepto;
                    facturaData.numero_orden = document.getElementById('modal_numero_orden').value;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 7;
                    mostrarFase7();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 5;
                    mostrarFase5();
                }
            });
        }
        
        // FASE 7: Pagos Recibidos
        function mostrarFase7() {
            let pagosHtml = '';
            if (facturaData.pagos.length === 0) {
                pagosHtml = '<p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">No hay pagos agregados</p>';
            } else {
                pagosHtml = facturaData.pagos.map((pago, index) => `
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-lg mb-2">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-sm font-medium">${pago.descripcion || 'Sin descripción'}</p>
                                <p class="text-xs text-slate-500">${pago.fecha} - $${parseFloat(pago.importe).toLocaleString()} (${pago.metodo_pago || 'Sin método'})</p>
                            </div>
                            <button onclick="eliminarPagoModal(${index})" class="text-red-500 hover:text-red-700 text-xs">Eliminar</button>
                        </div>
                    </div>
                `).join('');
            }
            
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div id="modal_pagos_list" class="max-h-60 overflow-y-auto mb-3">
                        ${pagosHtml}
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-2">
                            <input type="text" id="modal_pago_descripcion" placeholder="Descripción" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <input type="date" id="modal_pago_fecha" value="${new Date().toISOString().split('T')[0]}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <input type="number" id="modal_pago_importe" step="0.01" placeholder="Importe" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                    <div>
                        <select id="modal_pago_metodo" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                            <option value="">Método de pago...</option>
                            <option value="sinpe">SINPE</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <button onclick="agregarPagoModal()" class="w-full px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg text-sm">Agregar Pago</button>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 7: Pagos Recibidos',
                html: html,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                didOpen: () => {
                    window.eliminarPagoModal = function(index) {
                        facturaData.pagos.splice(index, 1);
                        mostrarFase7();
                    };
                    window.agregarPagoModal = function() {
                        const descripcion = document.getElementById('modal_pago_descripcion').value.trim();
                        const fecha = document.getElementById('modal_pago_fecha').value;
                        const importe = parseFloat(document.getElementById('modal_pago_importe').value) || 0;
                        const metodo = document.getElementById('modal_pago_metodo').value;
                        
                        if (!descripcion || importe <= 0) {
                            Swal.showValidationMessage('Complete la descripción y el importe');
                            return;
                        }
                        
                        facturaData.pagos.push({
                            descripcion: descripcion,
                            fecha: fecha,
                            importe: importe,
                            metodo_pago: metodo
                        });
                        
                        document.getElementById('modal_pago_descripcion').value = '';
                        document.getElementById('modal_pago_importe').value = '';
                        document.getElementById('modal_pago_metodo').value = '';
                        
                        mostrarFase7();
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 8;
                    mostrarFase8();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 6;
                    mostrarFase6();
                }
            });
        }
        
        // FASE 8: Notas y Archivos
        function mostrarFase8() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Notas Adicionales</label>
                        <textarea id="modal_notas" rows="3" placeholder="Notas adicionales para la factura..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">${facturaData.notas}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Archivos Adjuntos (Opcional)</label>
                        <input type="file" id="modal_archivos" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 8: Notas y Archivos',
                html: html,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#7c3aed',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    facturaData.notas = document.getElementById('modal_notas').value;
                    const archivosInput = document.getElementById('modal_archivos');
                    if (archivosInput.files.length > 0) {
                        facturaData.archivos = archivosInput.files;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 9;
                    mostrarFase9();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 7;
                    mostrarFase7();
                }
            });
        }
        
        // FASE 9: Resumen Final y Confirmación
        function mostrarFase9() {
            calcularTotalesModal();
            const clienteNombre = clientes.find(c => c.id == facturaData.cliente_id)?.nombre_empresa || 'N/A';
            
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-500/10 border-2 border-emerald-500 rounded-lg p-4">
                        <h3 class="text-base font-bold text-emerald-700 dark:text-emerald-400 mb-3">Resumen de la Factura</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Cliente:</span>
                                <span class="font-medium text-slate-900 dark:text-white">${clienteNombre}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Número de Factura:</span>
                                <span class="font-medium text-slate-900 dark:text-white">${facturaData.numero_factura}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Fecha de Emisión:</span>
                                <span class="font-medium text-slate-900 dark:text-white">${facturaData.fecha_emision}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Items:</span>
                                <span class="font-medium text-slate-900 dark:text-white">${facturaData.items.length}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Subtotal:</span>
                                <span class="font-medium text-slate-900 dark:text-white">$${facturaData.subtotal.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">IVA (13%):</span>
                                <span class="font-medium text-slate-900 dark:text-white">$${facturaData.iva.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between border-t border-emerald-300 dark:border-emerald-500/30 pt-2 mt-2">
                                <span class="text-base font-bold text-slate-900 dark:text-white">Total:</span>
                                <span class="text-lg font-bold text-emerald-700 dark:text-emerald-400">$${facturaData.total.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Monto Pagado:</span>
                                <span class="font-medium text-slate-900 dark:text-white">$${facturaData.monto_pagado.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Saldo Pendiente:</span>
                                <span class="font-medium text-red-600 dark:text-red-400">$${facturaData.saldo_pendiente.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Pagos Registrados:</span>
                                <span class="font-medium text-slate-900 dark:text-white">${facturaData.pagos.length}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-lg p-3">
                        <p class="text-xs text-blue-700 dark:text-blue-300"><strong>Concepto:</strong> ${facturaData.concepto || 'Sin concepto'}</p>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 9: Resumen Final',
                html: html,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Crear Factura',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#10b981',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    // Validar que haya items
                    if (!facturaData.items || facturaData.items.length === 0) {
                        Swal.showValidationMessage('Debe agregar al menos un item');
                        return false;
                    }
                    
                    // Validar que haya concepto
                    if (!facturaData.concepto || facturaData.concepto.trim() === '') {
                        Swal.showValidationMessage('El concepto general es requerido');
                        return false;
                    }
                    
                    // Recalcular totales antes de enviar
                    calcularTotalesModal();
                    return true;
                }
            }).then(async (result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 8;
                    mostrarFase8();
                    return;
                }
                
                if (!result.isConfirmed) {
                    return;
                }
                
                // Enviar factura
                Swal.fire({
                    title: 'Creando factura...',
                    allowOutsideClick: false,
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('cliente_id', facturaData.cliente_id);
                formData.append('correo', facturaData.correo);
                formData.append('numero_factura', facturaData.numero_factura);
                formData.append('serie', facturaData.serie);
                formData.append('fecha_emision', facturaData.fecha_emision);
                formData.append('fecha_vencimiento', facturaData.fecha_vencimiento);
                formData.append('estado', facturaData.estado);
                formData.append('concepto', facturaData.concepto);
                formData.append('concepto_pago', facturaData.concepto_pago);
                formData.append('subtotal', facturaData.subtotal);
                formData.append('total', facturaData.total);
                formData.append('monto_pagado', facturaData.monto_pagado);
                formData.append('metodo_pago', facturaData.metodo_pago || 'sin_especificar');
                formData.append('descuento_antes_impuestos', facturaData.descuento_antes_impuestos);
                formData.append('descuento_despues_impuestos', facturaData.descuento_despues_impuestos);
                formData.append('numero_orden', facturaData.numero_orden);
                formData.append('notas', facturaData.notas);
                
                if (facturaData.archivos) {
                    Array.from(facturaData.archivos).forEach(file => {
                        formData.append('archivos[]', file);
                    });
                }
                
                facturaData.items.forEach((item, index) => {
                    formData.append(`items[${index}][descripcion]`, item.descripcion);
                    formData.append(`items[${index}][cantidad]`, item.cantidad);
                    formData.append(`items[${index}][precio_unitario]`, item.precio_unitario);
                    formData.append(`items[${index}][subtotal]`, item.subtotal);
                    formData.append(`items[${index}][orden]`, index);
                });
                
                facturaData.pagos.forEach((pago, index) => {
                    formData.append(`pagos[${index}][descripcion]`, pago.descripcion);
                    formData.append(`pagos[${index}][fecha]`, pago.fecha);
                    formData.append(`pagos[${index}][importe]`, pago.importe);
                    formData.append(`pagos[${index}][metodo_pago]`, pago.metodo_pago || '');
                    formData.append(`pagos[${index}][notas]`, pago.notas || '');
                });
                
                try {
                    const response = await fetch('{{ route("walee.facturas.guardar") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData,
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Factura Creada!',
                            text: 'La factura ha sido creada correctamente',
                            confirmButtonColor: '#10b981',
                            background: isDarkMode() ? '#1e293b' : '#ffffff',
                            color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                        }).then(() => {
                            if (data.client_id) {
                                window.location.href = `/walee-facturas/cliente/${data.client_id}`;
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error al crear factura',
                            confirmButtonColor: '#ef4444',
                            background: isDarkMode() ? '#1e293b' : '#ffffff',
                            color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: error.message,
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    });
                }
            });
        }
        
        // Abrir modal para editar factura (Fase 1 con datos precargados)
        async function abrirModalEditarFactura(facturaId, clienteId, correo) {
            // Cargar datos de la factura si es necesario
            let facturaData = {
                cliente_id: clienteId,
                correo: correo || ''
            };
            
            // Si no tenemos el correo, intentar obtenerlo del cliente
            if (!facturaData.correo && facturaData.cliente_id) {
                const cliente = clientes.find(c => c.id == facturaData.cliente_id);
                if (cliente) {
                    facturaData.correo = cliente.correo || '';
                }
            }
            
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-violet-600 rounded-full" style="width: 11%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase 1/9</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente <span class="text-red-500">*</span></label>
                        <select id="modal_cliente_id_edit" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                            <option value="">Seleccionar cliente...</option>
                            ${clientesOptions}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo <span class="text-red-500">*</span></label>
                        <input type="email" id="modal_correo_edit" value="${facturaData.correo}" placeholder="correo@ejemplo.com" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 1: Información del Cliente',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                didOpen: () => {
                    const clienteSelect = document.getElementById('modal_cliente_id_edit');
                    const correoInput = document.getElementById('modal_correo_edit');
                    
                    // Pre-seleccionar cliente si existe
                    if (clienteSelect && facturaData.cliente_id) {
                        clienteSelect.value = facturaData.cliente_id;
                    }
                    
                    // Auto-completar correo cuando se selecciona cliente
                    if (clienteSelect) {
                        clienteSelect.addEventListener('change', function() {
                            const clienteId = this.value;
                            const cliente = clientes.find(c => c.id == clienteId);
                            if (cliente && correoInput) {
                                correoInput.value = cliente.correo || '';
                            }
                        });
                    }
                },
                preConfirm: () => {
                    const clienteId = document.getElementById('modal_cliente_id_edit').value;
                    const correo = document.getElementById('modal_correo_edit').value;
                    
                    if (!clienteId) {
                        Swal.showValidationMessage('Por favor selecciona un cliente');
                        return false;
                    }
                    
                    if (!correo || !correo.includes('@')) {
                        Swal.showValidationMessage('Por favor ingresa un correo válido');
                        return false;
                    }
                    
                    return { clienteId, correo };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a la página de crear/editar con el factura_id y cliente_id
                    window.location.href = `{{ route('walee.facturas.crear') }}?factura_id=${facturaId}&cliente_id=${result.value.clienteId}`;
                }
            });
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>
