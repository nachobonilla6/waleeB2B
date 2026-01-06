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
                    <a href="{{ route('walee.facturas') }}" class="px-3 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
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
                    <button onclick="abrirModalCrearFactura()" class="px-3 py-1.5 bg-gradient-to-r from-violet-500 to-violet-600 hover:from-violet-600 hover:to-violet-700 text-white font-medium rounded-lg transition-all flex items-center gap-1.5 text-xs shadow-sm hover:shadow flex-shrink-0">
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
                            <button onclick="abrirModalEditarFactura({{ $factura->id }}, {{ $factura->cliente_id ?? 'null' }}, '{{ $factura->correo ?? '' }}')" class="p-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-all shadow-sm hover:shadow" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
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
        const isDarkMode = document.documentElement.classList.contains('dark');
        
        // Enviar factura
        async function enviarFactura(id) {
            const result = await Swal.fire({
                title: '¿Enviar factura?',
                text: '¿Deseas enviar esta factura al cliente?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b'
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const response = await fetch(`/walee-facturas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Factura enviada',
                        text: 'La factura ha sido enviada correctamente',
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
                        text: data.message || 'Error al enviar factura',
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
        
        // Reenviar factura
        async function reenviarFactura(id) {
            const result = await Swal.fire({
                title: '¿Reenviar factura?',
                text: '¿Deseas reenviar esta factura al cliente?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Reenviar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b'
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const response = await fetch(`/walee-facturas/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Factura reenviada',
                        text: 'La factura ha sido reenviada correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false,
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al reenviar factura',
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
        
        // Generar opciones de clientes para el select
        const clientesOptions = clientes.map(cliente => 
            `<option value="${cliente.id}">${cliente.nombre_empresa}</option>`
        ).join('');
        
        // Abrir modal para crear factura (Fase 1)
        function abrirModalCrearFactura() {
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
                        <select id="modal_cliente_id" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                            <option value="">Seleccionar cliente...</option>
                            ${clientesOptions}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Correo <span class="text-red-500">*</span></label>
                        <input type="email" id="modal_correo" value="" placeholder="correo@ejemplo.com" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
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
                    const clienteSelect = document.getElementById('modal_cliente_id');
                    const correoInput = document.getElementById('modal_correo');
                    
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
                    const clienteId = document.getElementById('modal_cliente_id').value;
                    const correo = document.getElementById('modal_correo').value;
                    
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
                    // Redirigir a la página de crear con el cliente seleccionado
                    window.location.href = `{{ route('walee.facturas.crear') }}?cliente_id=${result.value.clienteId}`;
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
