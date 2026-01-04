<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Crear Cotización</title>
    <meta name="description" content="Crear nueva cotización">
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
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $clientes = \App\Models\Cliente::orderBy('nombre_empresa')->get();
        $ultimaCotizacion = \App\Models\Cotizacion::orderBy('id', 'desc')->first();
        $siguienteNumero = 'COT-' . date('Ymd') . '-' . rand(100, 999);
        if ($ultimaCotizacion && preg_match('/COT-(\d{8})-(\d+)/', $ultimaCotizacion->numero_cotizacion, $matches)) {
            $fechaUltima = $matches[1];
            $numeroUltima = intval($matches[2]);
            if ($fechaUltima === date('Ymd')) {
                $siguienteNumero = 'COT-' . date('Ymd') . '-' . str_pad($numeroUltima + 1, 3, '0', STR_PAD_LEFT);
            }
        }
        
        // Validar cliente_id desde URL
        $clienteIdFromUrl = request()->get('cliente_id');
        $clienteSeleccionado = null;
        $errorCliente = null;
        
        if ($clienteIdFromUrl !== null) {
            $clienteIdFromUrl = trim($clienteIdFromUrl);
            
            if ($clienteIdFromUrl === '') {
                $errorCliente = 'El ID del cliente no puede estar vacío. Por favor, seleccione un cliente válido.';
            } elseif (!is_numeric($clienteIdFromUrl)) {
                $errorCliente = 'El ID del cliente debe ser un número válido.';
            } else {
                $clienteIdInt = intval($clienteIdFromUrl);
                $clienteSeleccionado = \App\Models\Cliente::where('id', $clienteIdInt)->first();
                
                if (!$clienteSeleccionado) {
                    $clientEnProceso = \App\Models\Client::find($clienteIdInt);
                    if ($clientEnProceso) {
                        $clienteSeleccionado = \App\Models\Cliente::where('correo', $clientEnProceso->email)->first();
                        
                        if (!$clienteSeleccionado && $clientEnProceso->name) {
                            $clienteSeleccionado = \App\Models\Cliente::where('nombre_empresa', $clientEnProceso->name)->first();
                        }
                        
                        if (!$clienteSeleccionado) {
                            $clienteSeleccionado = \App\Models\Cliente::create([
                                'nombre_empresa' => $clientEnProceso->name,
                                'correo' => $clientEnProceso->email ?: '',
                                'telefono' => $clientEnProceso->telefono_1 ?? '',
                                'ciudad' => $clientEnProceso->ciudad ?? '',
                            ]);
                        }
                    }
                }
                
                if (!$clienteSeleccionado) {
                    $errorCliente = 'El cliente especificado (ID: ' . $clienteIdInt . ') no existe en la base de datos. Por favor, seleccione un cliente válido.';
                }
            }
        }
        
        // Cargar cotizaciones del cliente si está seleccionado
        $cotizacionesCliente = collect();
        $clientInfo = null;
        if ($clienteSeleccionado) {
            $cotizacionesCliente = \App\Models\Cotizacion::where('cliente_id', $clienteSeleccionado->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($cotizacionesCliente->isEmpty() && $clienteSeleccionado->correo) {
                $cotizacionesCliente = \App\Models\Cotizacion::where('correo', $clienteSeleccionado->correo)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            
            $clientInfo = $clienteSeleccionado;
            
            if (!$clientInfo && isset($clienteIdFromUrl) && is_numeric($clienteIdFromUrl)) {
                $clientEnProceso = \App\Models\Client::find(intval($clienteIdFromUrl));
                if ($clientEnProceso) {
                    $clientInfo = $clientEnProceso;
                    if ($clientEnProceso->email) {
                        $cotizacionesCliente = \App\Models\Cotizacion::where('correo', $clientEnProceso->email)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    }
                }
            }
        }
        
        $totalCotizaciones = $cotizacionesCliente->count();
        $cotizacionesEnviadas = $cotizacionesCliente->whereNotNull('enviada_at')->count();
        $cotizacionesPendientes = $cotizacionesCliente->whereNull('enviada_at')->count();
        $totalMonto = $cotizacionesCliente->sum('monto');
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-4 sm:px-6 lg:px-8">
            @php $pageTitle = 'Crear Cotización'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Notifications -->
            <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
            
            <!-- Error de Cliente desde URL -->
            @if($errorCliente)
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl animate-fade-in-up">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">Error en el cliente</p>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">{{ $errorCliente }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if($clienteSeleccionado && $clientInfo)
            <!-- Información del Cliente y Cotizaciones -->
            <div class="mb-6 animate-fade-in-up">
                <!-- Cliente Info Card -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none mb-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white truncate">
                                    {{ $clientInfo->nombre_empresa ?? $clientInfo->name ?? 'Cliente' }}
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">
                                    {{ $clientInfo->correo ?? $clientInfo->email ?? '' }}
                                </p>
                            </div>
                        </div>
                        <button onclick="abrirModalCotizacion()" class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Crear Cotización</span>
                        </button>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $totalCotizaciones }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Total</div>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $cotizacionesEnviadas }}</div>
                            <div class="text-xs text-emerald-600/80 dark:text-emerald-400/70">Enviadas</div>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $cotizacionesPendientes }}</div>
                            <div class="text-xs text-amber-600/80 dark:text-amber-400/70">Pendientes</div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-500/10 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-blue-600 dark:text-blue-400">₡{{ number_format($totalMonto, 2) }}</div>
                            <div class="text-xs text-blue-600/80 dark:text-blue-400/70">Total</div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de Cotizaciones -->
                @if($cotizacionesCliente->count() > 0)
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Cotizaciones del Cliente
                    </h3>
                    
                    <!-- Search -->
                    <div class="mb-4">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchCotizacionesInput" placeholder="Buscar por número o descripción..." class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                    </div>
                    
                    <div id="cotizacionesListContainer" class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($cotizacionesCliente as $cotizacion)
                        <div class="cotizacion-item bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 sm:p-4 hover:border-blue-400 dark:hover:border-blue-500/30 transition-all" 
                             data-search="{{ strtolower($cotizacion->numero_cotizacion ?? '') }} {{ strtolower($cotizacion->descripcion ?? '') }}">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="text-xs font-mono text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-400/10 px-2 py-0.5 rounded">
                                            #{{ $cotizacion->numero_cotizacion }}
                                        </span>
                                        @if($cotizacion->enviada_at)
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-400/10 px-2 py-0.5 rounded flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="hidden sm:inline">Enviada </span>{{ \Carbon\Carbon::parse($cotizacion->enviada_at)->format('d/m/Y') }}
                                        </span>
                                        @else
                                        <span class="text-xs text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-400/10 px-2 py-0.5 rounded">No enviada</span>
                                        @endif
                                        @if($cotizacion->estado === 'aceptada')
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-400/10 px-2 py-0.5 rounded">Aceptada</span>
                                        @elseif($cotizacion->estado === 'rechazada')
                                        <span class="text-xs text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-400/10 px-2 py-0.5 rounded">Rechazada</span>
                                        @else
                                        <span class="text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-400/10 px-2 py-0.5 rounded">Pendiente</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mb-2 line-clamp-2">
                                        {{ $cotizacion->descripcion ?? 'Sin descripción' }}
                                    </p>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 text-xs text-slate-500 dark:text-slate-400">
                                        <span>{{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}</span>
                                        <span class="font-semibold text-slate-900 dark:text-white text-sm">₡{{ number_format($cotizacion->monto, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2 sm:ml-3 sm:flex-nowrap">
                                    <button onclick="verCotizacionModal({{ $cotizacion->id }})" class="flex-1 sm:flex-none px-2.5 sm:px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-1 sm:gap-1.5 min-w-[70px] sm:min-w-[80px]">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden xs:inline">Ver</span>
                                    </button>
                                    <button onclick="verPDFCotizacion({{ $cotizacion->id }})" class="flex-1 sm:flex-none px-2.5 sm:px-3 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-1 sm:gap-1.5 min-w-[70px] sm:min-w-[80px]">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="hidden xs:inline">PDF</span>
                                    </button>
                                    <button onclick="enviarCotizacionEmail({{ $cotizacion->id }}, '{{ $cotizacion->correo }}', {{ $cotizacion->enviada_at ? 'true' : 'false' }})" class="flex-1 sm:flex-none px-2.5 sm:px-3 py-2 {{ $cotizacion->enviada_at ? 'bg-blue-600 hover:bg-blue-500' : 'bg-emerald-600 hover:bg-emerald-500' }} text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-1 sm:gap-1.5 min-w-[80px] sm:min-w-[100px]">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="hidden xs:inline">{{ $cotizacion->enviada_at ? 'Reenviar' : 'Enviar' }}</span>
                                    </button>
                                    <button onclick="eliminarCotizacion({{ $cotizacion->id }}, '{{ $cotizacion->numero_cotizacion }}')" class="flex-1 sm:flex-none px-2.5 sm:px-3 py-2 bg-red-700 hover:bg-red-800 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-1 sm:gap-1.5 min-w-[70px] sm:min-w-[80px]">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="hidden xs:inline">Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-6 shadow-sm dark:shadow-none text-center">
                    <svg class="w-12 h-12 text-slate-400 dark:text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-slate-600 dark:text-slate-400 mb-4">No hay cotizaciones para este cliente</p>
                    <button onclick="abrirModalCotizacion()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-all">
                        Crear Primera Cotización
                    </button>
                </div>
                @endif
            </div>
            @else
            <!-- Botón para abrir modal cuando no hay cliente seleccionado -->
            <div class="flex justify-center items-center min-h-[60vh]">
                <button onclick="abrirModalCotizacion()" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Crear Nueva Cotización</span>
                </button>
            </div>
            @endif
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Detectar modo oscuro
        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }
        
        // Búsqueda de cotizaciones
        document.getElementById('searchCotizacionesInput')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.cotizacion-item');
            
            items.forEach(item => {
                const searchText = item.getAttribute('data-search') || '';
                if (searchText.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // ========== SISTEMA DE MODAL POR FASES ==========
        let cotizacionData = {
            cliente_id: '',
            correo: '',
            numero_cotizacion: '{{ $siguienteNumero }}',
            fecha: '{{ date("Y-m-d") }}',
            idioma: 'es',
            tipo_servicio: '',
            plan: '',
            monto: 0,
            vigencia: '15',
            descripcion: '',
            estado: 'pendiente'
        };
        
        let currentPhase = 1;
        const totalPhases = 5;
        
        // Abrir modal de cotización
        function abrirModalCotizacion() {
            currentPhase = 1;
            @if($clienteSeleccionado)
            cotizacionData.cliente_id = '{{ $clienteSeleccionado->id }}';
            cotizacionData.correo = '{{ $clienteSeleccionado->correo }}';
            @endif
            mostrarFase1();
        }
        
        // FASE 1: Cliente y Correo
        function mostrarFase1() {
            const clientesOptions = `@foreach($clientes as $cliente)<option value="{{ $cliente->id }}" data-email="{{ $cliente->correo }}" ${cotizacionData.cliente_id == '{{ $cliente->id }}' ? 'selected' : ''}>{{ $cliente->nombre_empresa }}</option>@endforeach`;
            
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-blue-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
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
                        <input type="email" id="modal_correo" value="${cotizacionData.correo}" placeholder="correo@ejemplo.com" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
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
                confirmButtonColor: '#2563eb',
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
                    
                    cotizacionData.cliente_id = clienteId;
                    cotizacionData.correo = correo;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    currentPhase = 2;
                    mostrarFase2();
                }
            });
        }
        
        // FASE 2: Información Básica
        function mostrarFase2() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-blue-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de Cotización <span class="text-red-500">*</span></label>
                            <input type="text" id="modal_numero_cotizacion" value="${cotizacionData.numero_cotizacion}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha <span class="text-red-500">*</span></label>
                            <input type="date" id="modal_fecha" value="${cotizacionData.fecha}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Idioma</label>
                            <select id="modal_idioma" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                                <option value="es" ${cotizacionData.idioma === 'es' ? 'selected' : ''}>Español</option>
                                <option value="en" ${cotizacionData.idioma === 'en' ? 'selected' : ''}>English</option>
                                <option value="fr" ${cotizacionData.idioma === 'fr' ? 'selected' : ''}>Français</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Vigencia (días)</label>
                            <input type="number" id="modal_vigencia" value="${cotizacionData.vigencia}" min="1" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 2: Información Básica',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#2563eb',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    const numeroCotizacion = document.getElementById('modal_numero_cotizacion').value.trim();
                    const fecha = document.getElementById('modal_fecha').value;
                    
                    if (!numeroCotizacion) {
                        Swal.showValidationMessage('El número de cotización es requerido');
                        return false;
                    }
                    if (!fecha) {
                        Swal.showValidationMessage('La fecha es requerida');
                        return false;
                    }
                    
                    cotizacionData.numero_cotizacion = numeroCotizacion;
                    cotizacionData.fecha = fecha;
                    cotizacionData.idioma = document.getElementById('modal_idioma').value;
                    cotizacionData.vigencia = document.getElementById('modal_vigencia').value || '15';
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
        
        // FASE 3: Servicio y Plan
        function mostrarFase3() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-blue-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de Servicio <span class="text-red-500">*</span></label>
                        <input type="text" id="modal_tipo_servicio" value="${cotizacionData.tipo_servicio}" placeholder="Ej: Sitio Web, Automatización..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Plan <span class="text-red-500">*</span></label>
                        <input type="text" id="modal_plan" value="${cotizacionData.plan}" placeholder="Ej: Básico, Premium, Enterprise..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 3: Servicio y Plan',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#2563eb',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    const tipoServicio = document.getElementById('modal_tipo_servicio').value.trim();
                    const plan = document.getElementById('modal_plan').value.trim();
                    
                    if (!tipoServicio) {
                        Swal.showValidationMessage('El tipo de servicio es requerido');
                        return false;
                    }
                    if (!plan) {
                        Swal.showValidationMessage('El plan es requerido');
                        return false;
                    }
                    
                    cotizacionData.tipo_servicio = tipoServicio;
                    cotizacionData.plan = plan;
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
        
        // FASE 4: Monto y Descripción
        function mostrarFase4() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-blue-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Monto <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" id="modal_monto" value="${cotizacionData.monto}" min="0" placeholder="0.00" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción</label>
                        <textarea id="modal_descripcion" rows="4" placeholder="Descripción detallada de la cotización..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">${cotizacionData.descripcion}</textarea>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 4: Monto y Descripción',
                html: html,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#2563eb',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                preConfirm: () => {
                    const monto = parseFloat(document.getElementById('modal_monto').value) || 0;
                    
                    if (monto <= 0) {
                        Swal.showValidationMessage('El monto debe ser mayor a 0');
                        return false;
                    }
                    
                    cotizacionData.monto = monto;
                    cotizacionData.descripcion = document.getElementById('modal_descripcion').value;
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
        
        // FASE 5: Resumen y Confirmación
        function mostrarFase5() {
            const html = `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full">
                            <div class="h-1 bg-blue-600 rounded-full" style="width: ${(currentPhase/totalPhases)*100}%"></div>
                        </div>
                        <span class="text-xs text-slate-600 dark:text-slate-400">Fase ${currentPhase}/${totalPhases}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Número:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">${cotizacionData.numero_cotizacion}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Fecha:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">${new Date(cotizacionData.fecha).toLocaleDateString('es-ES')}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Tipo de Servicio:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">${cotizacionData.tipo_servicio}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Plan:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">${cotizacionData.plan}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-600 dark:text-slate-400">Vigencia:</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">${cotizacionData.vigencia} días</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-slate-300 dark:border-slate-600">
                            <span class="text-base font-semibold text-slate-900 dark:text-white">Monto Total:</span>
                            <span class="text-base font-bold text-blue-600 dark:text-blue-400">₡${parseFloat(cotizacionData.monto).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Fase 5: Resumen y Confirmación',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Crear Cotización',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#2563eb',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            }).then((result) => {
                if (result.isConfirmed) {
                    guardarCotizacion();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 4;
                    mostrarFase4();
                }
            });
        }
        
        // Guardar cotización
        async function guardarCotizacion() {
            try {
                Swal.fire({
                    title: 'Creando cotización...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch('{{ route("walee.cotizaciones.guardar") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(cotizacionData),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cotización creada',
                        text: 'La cotización ha sido creada correctamente',
                        confirmButtonColor: '#2563eb',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al crear cotización',
                        confirmButtonColor: '#2563eb',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#2563eb',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Ver cotización en modal
        async function verCotizacionModal(cotizacionId) {
            try {
                const response = await fetch(`/walee-cotizaciones/${cotizacionId}/json`);
                const data = await response.json();
                
                if (!data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la cotización',
                        confirmButtonColor: '#2563eb',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    });
                    return;
                }
                
                const cotizacion = data.cotizacion;
                const cliente = data.cliente;
                
                const html = `
                    <div class="text-left space-y-3 max-h-[70vh] overflow-y-auto">
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-3">Información General</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Número:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">${cotizacion.numero_cotizacion}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Fecha:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">${new Date(cotizacion.fecha).toLocaleDateString('es-ES')}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Cliente:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">${cliente?.nombre_empresa || 'N/A'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Correo:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">${cotizacion.correo}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-3">Detalles del Servicio</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Tipo de Servicio:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">${cotizacion.tipo_servicio || 'N/A'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Plan:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">${cotizacion.plan || 'N/A'}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600 dark:text-slate-400">Vigencia:</span>
                                    <span class="font-semibold text-slate-900 dark:text-white">${cotizacion.vigencia || '15'} días</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-slate-300 dark:border-slate-600">
                                    <span class="text-base font-semibold text-slate-900 dark:text-white">Monto:</span>
                                    <span class="text-base font-bold text-blue-600 dark:text-blue-400">₡${parseFloat(cotizacion.monto || 0).toLocaleString()}</span>
                                </div>
                            </div>
                        </div>
                        ${cotizacion.descripcion ? `
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Descripción</h3>
                            <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">${cotizacion.descripcion}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                Swal.fire({
                    title: 'Detalles de la Cotización',
                    html: html,
                    width: '700px',
                    showConfirmButton: false,
                    showCloseButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: true,
                    backdrop: true,
                    customClass: {
                        popup: 'z-[9999] relative',
                        container: 'z-[9999]',
                    },
                    didOpen: () => {
                        const popup = document.querySelector('.swal2-popup');
                        if (popup) {
                            popup.style.zIndex = '9999';
                            
                            const closeButton = document.createElement('button');
                            closeButton.innerHTML = `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            `;
                            closeButton.className = 'absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg z-[10000] transition-all hover:scale-110 cursor-pointer';
                            closeButton.style.zIndex = '10000';
                            closeButton.onclick = () => Swal.close();
                            popup.appendChild(closeButton);
                        }
                    },
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar la cotización: ' + error.message,
                    confirmButtonColor: '#2563eb',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Ver PDF de cotización
        function verPDFCotizacion(cotizacionId) {
            const pdfUrl = `/walee-cotizaciones/${cotizacionId}/pdf`;
            
            const isMobile = window.innerWidth < 640;
            if (isMobile) {
                window.location.href = pdfUrl;
                return;
            }
            
            Swal.fire({
                title: '',
                html: `
                    <div style="width: 100%; height: calc(100vh - 120px); max-height: 800px; overflow: hidden;">
                        <iframe src="${pdfUrl}#toolbar=0&navpanes=0&scrollbar=0" style="width: 100%; height: 100%; border: none;"></iframe>
                    </div>
                `,
                width: '95%',
                maxWidth: '900px',
                padding: '0',
                showConfirmButton: false,
                showCloseButton: false,
                allowOutsideClick: true,
                allowEscapeKey: true,
                customClass: {
                    popup: 'z-[9999] relative p-0',
                    container: 'z-[9999]',
                    htmlContainer: 'p-0 m-0',
                },
                didOpen: () => {
                    const popup = document.querySelector('.swal2-popup');
                    if (popup) {
                        popup.style.zIndex = '9999';
                        popup.style.position = 'relative';
                        popup.style.padding = '0';
                        popup.style.maxHeight = 'calc(100vh - 40px)';
                        
                        const closeButton = document.createElement('button');
                        closeButton.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        `;
                        closeButton.className = 'absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg z-[10000] transition-all hover:scale-110 cursor-pointer';
                        closeButton.style.zIndex = '10000';
                        closeButton.onclick = () => Swal.close();
                        popup.appendChild(closeButton);
                    }
                },
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
        }
        
        // Eliminar cotización
        async function eliminarCotizacion(cotizacionId, numeroCotizacion) {
            const result = await Swal.fire({
                title: '¿Eliminar cotización?',
                text: `¿Está seguro de que desea eliminar la cotización ${numeroCotizacion}? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) return;
            
            try {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch(`/walee-cotizaciones/${cotizacionId}/eliminar`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cotización eliminada',
                        text: 'La cotización ha sido eliminada correctamente',
                        confirmButtonColor: '#2563eb',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar la cotización',
                        confirmButtonColor: '#2563eb',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#2563eb',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Enviar cotización por email
        async function enviarCotizacionEmail(cotizacionId, correo, yaEnviada) {
            const result = await Swal.fire({
                title: yaEnviada ? 'Reenviar Cotización' : 'Enviar Cotización',
                text: `¿Desea ${yaEnviada ? 'reenviar' : 'enviar'} la cotización a ${correo}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: yaEnviada ? 'Reenviar' : 'Enviar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#2563eb',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) return;
            
            try {
                Swal.fire({
                    title: 'Enviando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch(`/walee-cotizaciones/${cotizacionId}/enviar-email`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ correo: correo }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cotización enviada',
                        text: data.message || 'La cotización ha sido enviada correctamente',
                        confirmButtonColor: '#2563eb',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al enviar la cotización',
                        confirmButtonColor: '#2563eb',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#2563eb',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

