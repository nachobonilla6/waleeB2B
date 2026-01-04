<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Crear Factura</title>
    <meta name="description" content="Crear nueva factura">
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
        $ultimaFactura = \App\Models\Factura::orderBy('id', 'desc')->first();
        $siguienteNumero = $ultimaFactura ? intval($ultimaFactura->numero_factura) + 1 : 1;
        
        // Validar cliente_id desde URL
        $clienteIdFromUrl = request()->get('cliente_id');
        $clienteSeleccionado = null;
        $errorCliente = null;
        
        if ($clienteIdFromUrl !== null) {
            // Limpiar espacios en blanco
            $clienteIdFromUrl = trim($clienteIdFromUrl);
            
            // Validar que no esté vacío después de trim
            if ($clienteIdFromUrl === '') {
                $errorCliente = 'El ID del cliente no puede estar vacío. Por favor, seleccione un cliente válido.';
            } elseif (!is_numeric($clienteIdFromUrl)) {
                $errorCliente = 'El ID del cliente debe ser un número válido.';
            } else {
                // Buscar el cliente - puede venir de clientes o clientes_en_proceso
                $clienteIdInt = intval($clienteIdFromUrl);
                
                // Primero buscar en la tabla clientes (modelo Cliente)
                $clienteSeleccionado = \App\Models\Cliente::where('id', $clienteIdInt)->first();
                
                // Si no se encuentra, buscar en clientes_en_proceso (modelo Client)
                if (!$clienteSeleccionado) {
                    $clientEnProceso = \App\Models\Client::find($clienteIdInt);
                    if ($clientEnProceso) {
                        // Si existe en clientes_en_proceso, buscar o crear el Cliente correspondiente
                        $clienteSeleccionado = \App\Models\Cliente::where('correo', $clientEnProceso->email)->first();
                        
                        if (!$clienteSeleccionado && $clientEnProceso->name) {
                            // Intentar buscar por nombre
                            $clienteSeleccionado = \App\Models\Cliente::where('nombre_empresa', $clientEnProceso->name)->first();
                        }
                        
                        // Si aún no existe, crear uno nuevo basado en el Client
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
        
        // Cargar facturas del cliente si está seleccionado
        $facturasCliente = collect();
        $clientInfo = null;
        $clienteIdParaPerfil = null;
        if ($clienteSeleccionado) {
            // Obtener información del cliente para mostrar
            $clientInfo = $clienteSeleccionado;
            
            // Buscar el Client correspondiente para obtener el ID del perfil
            // La ruta walee.cliente.detalle espera el ID de Client, no de Cliente
            $clientEnProceso = null;
            if ($clienteSeleccionado->correo) {
                $clientEnProceso = \App\Models\Client::where('email', $clienteSeleccionado->correo)->first();
            }
            if (!$clientEnProceso && $clienteSeleccionado->nombre_empresa) {
                $clientEnProceso = \App\Models\Client::where('name', $clienteSeleccionado->nombre_empresa)->first();
            }
            
            if ($clientEnProceso) {
                $clienteIdParaPerfil = $clientEnProceso->id;
            }
            
            // Buscar facturas SOLO por cliente_id (no por correo para evitar facturas de otros clientes)
            $facturasCliente = \App\Models\Factura::where('cliente_id', $clienteSeleccionado->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif (isset($clienteIdFromUrl) && is_numeric($clienteIdFromUrl)) {
            // Si el cliente_id viene de Client pero no se encontró en Cliente
            $clientEnProceso = \App\Models\Client::find(intval($clienteIdFromUrl));
            if ($clientEnProceso) {
                $clientInfo = $clientEnProceso;
                $clienteIdParaPerfil = $clientEnProceso->id; // ID del Client para el perfil
                
                // Buscar el Cliente correspondiente por email o nombre
                $clienteCorrespondiente = \App\Models\Cliente::where('correo', $clientEnProceso->email)->first();
                if (!$clienteCorrespondiente && $clientEnProceso->name) {
                    $clienteCorrespondiente = \App\Models\Cliente::where('nombre_empresa', $clientEnProceso->name)->first();
                }
                
                // Si encontramos un Cliente correspondiente, buscar facturas por su ID
                if ($clienteCorrespondiente) {
                    $facturasCliente = \App\Models\Factura::where('cliente_id', $clienteCorrespondiente->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                } else {
                    // Si no hay Cliente correspondiente, no mostrar facturas (evitar mostrar facturas de otros)
                    $facturasCliente = collect();
                }
            }
        }
        
        // Obtener URL de la foto del cliente
        $fotoUrl = null;
        if ($clientInfo) {
            // Si tenemos un Cliente, buscar el Client correspondiente para obtener la foto
            $clientParaFoto = null;
            if ($clientInfo instanceof \App\Models\Cliente) {
                // Buscar Client por email o nombre
                if ($clientInfo->correo) {
                    $clientParaFoto = \App\Models\Client::where('email', $clientInfo->correo)->first();
                }
                if (!$clientParaFoto && $clientInfo->nombre_empresa) {
                    $clientParaFoto = \App\Models\Client::where('name', $clientInfo->nombre_empresa)->first();
                }
            } else {
                // Si ya es Client, usarlo directamente
                $clientParaFoto = $clientInfo;
            }
            
            // Obtener la foto del Client
            if ($clientParaFoto) {
                $fotoPath = $clientParaFoto->foto ?? null;
                if ($fotoPath) {
                    if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                        $fotoUrl = $fotoPath;
                    } else {
                        $filename = basename($fotoPath);
                        $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                    }
                }
            }
        }
        
        $totalFacturas = $facturasCliente->count();
        $facturasEnviadas = $facturasCliente->whereNotNull('enviada_at')->count();
        $facturasPendientes = $facturasCliente->whereNull('enviada_at')->count();
        $totalMonto = $facturasCliente->sum('total');
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-4 sm:px-6 lg:px-8">
            @php $pageTitle = 'Crear Factura'; @endphp
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
            <!-- Información del Cliente y Facturas -->
            <div class="mb-6 animate-fade-in-up">
                <!-- Cliente Info Card -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none mb-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <a href="{{ $clienteIdParaPerfil ? route('walee.cliente.detalle', $clienteIdParaPerfil) : '#' }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $clientInfo->nombre_empresa ?? $clientInfo->name ?? 'Cliente' }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $clientInfo->nombre_empresa ?? $clientInfo->name ?? 'Cliente' }}" class="w-full h-full object-cover opacity-80">
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white truncate">
                                    {{ $clientInfo->nombre_empresa ?? $clientInfo->name ?? 'Cliente' }}
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">
                                    {{ $clientInfo->correo ?? $clientInfo->email ?? '' }}
                                </p>
                            </div>
                        </a>
                        <button onclick="abrirModalFactura()" class="w-full sm:w-auto px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Crear Factura</span>
                        </button>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-4 gap-3">
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $totalFacturas }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Total</div>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $facturasEnviadas }}</div>
                            <div class="text-xs text-emerald-600/80 dark:text-emerald-400/70">Enviadas</div>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-500/10 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $facturasPendientes }}</div>
                            <div class="text-xs text-amber-600/80 dark:text-amber-400/70">Pendientes</div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-500/10 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-blue-600 dark:text-blue-400">₡{{ number_format($totalMonto, 2) }}</div>
                            <div class="text-xs text-blue-600/80 dark:text-blue-400/70">Total</div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de Facturas -->
                @if($facturasCliente->count() > 0)
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Facturas del Cliente
                    </h3>
                    
                    <!-- Search -->
                    <div class="mb-4">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchFacturasInput" placeholder="Buscar por número o concepto..." class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                        </div>
                    </div>
                    
                    <div id="facturasListContainer" class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($facturasCliente as $factura)
                        <div class="factura-item bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 sm:p-4 hover:border-violet-400 dark:hover:border-violet-500/30 transition-all" 
                             data-search="{{ strtolower($factura->numero_factura ?? '') }} {{ strtolower($factura->concepto ?? '') }}">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="text-xs font-mono text-violet-600 dark:text-violet-400 bg-violet-100 dark:bg-violet-400/10 px-2 py-0.5 rounded">
                                            #{{ $factura->numero_factura }}
                                        </span>
                                        @if($factura->enviada_at)
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-400/10 px-2 py-0.5 rounded flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="hidden sm:inline">Enviada </span>{{ \Carbon\Carbon::parse($factura->enviada_at)->format('d/m/Y') }}
                                        </span>
                                        @else
                                        <span class="text-xs text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-400/10 px-2 py-0.5 rounded">No enviada</span>
                                        @endif
                                        @if($factura->estado === 'pagada')
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-400/10 px-2 py-0.5 rounded">Pagada</span>
                                        @elseif($factura->estado === 'vencida')
                                        <span class="text-xs text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-400/10 px-2 py-0.5 rounded">Vencida</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 mb-2 line-clamp-2">
                                        {{ $factura->concepto }}
                                    </p>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 text-xs text-slate-500 dark:text-slate-400">
                                        <span>{{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}</span>
                                        <span class="font-semibold text-slate-900 dark:text-white text-sm">₡{{ number_format($factura->total, 2) }}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-4 gap-1.5 sm:flex sm:flex-nowrap sm:gap-2 sm:ml-3">
                                    <button onclick="eliminarFactura({{ $factura->id }}, '{{ $factura->numero_factura }}')" class="px-2 sm:px-2.5 sm:px-3 py-2 bg-red-700 hover:bg-red-800 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="hidden xs:inline">Eliminar</span>
                                    </button>
                                    <button onclick="verFacturaModal({{ $factura->id }})" class="px-2 sm:px-2.5 sm:px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden xs:inline">Ver</span>
                                    </button>
                                    <button onclick="verPDFFactura({{ $factura->id }})" class="px-2 sm:px-2.5 sm:px-3 py-2 bg-red-600 hover:bg-red-500 text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="hidden xs:inline">PDF</span>
                                    </button>
                                    <button onclick="enviarFacturaEmail({{ $factura->id }}, '{{ $factura->correo }}', {{ $factura->enviada_at ? 'true' : 'false' }})" class="px-2 sm:px-2.5 sm:px-3 py-2 {{ $factura->enviada_at ? 'bg-blue-600 hover:bg-blue-500' : 'bg-emerald-600 hover:bg-emerald-500' }} text-white text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-0.5 sm:gap-1 sm:gap-1.5">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="hidden xs:inline">{{ $factura->enviada_at ? 'Reenviar' : 'Enviar' }}</span>
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
                    <p class="text-slate-600 dark:text-slate-400 mb-4">No hay facturas para este cliente</p>
                    <button onclick="abrirModalFactura()" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg transition-all">
                        Crear Primera Factura
                    </button>
                </div>
                @endif
            </div>
            @else
            <!-- Botón para abrir modal cuando no hay cliente seleccionado -->
            <div class="flex justify-center items-center min-h-[60vh]">
                <button onclick="abrirModalFactura()" class="px-8 py-4 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Crear Nueva Factura</span>
                </button>
            </div>
            @endif
            
            <!-- Form oculto para mantener estructura -->
            <form id="facturaForm" class="hidden" enctype="multipart/form-data">
                @csrf
                
                <!-- Cliente Section -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Cliente
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Cliente <span class="text-red-500 dark:text-red-400">*</span></label>
                            <select id="cliente_id" name="cliente_id" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all @if($errorCliente) border-red-500 @endif">
                                <option value="">Seleccionar cliente...</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" data-email="{{ $cliente->correo }}" @if($clienteSeleccionado && $clienteSeleccionado->id == $cliente->id) selected @endif>{{ $cliente->nombre_empresa }}</option>
                                @endforeach
                            </select>
                            @if($errorCliente)
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $errorCliente }}</p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Correo <span class="text-red-500 dark:text-red-400">*</span></label>
                            <input type="email" id="correo" name="correo" required placeholder="correo@ejemplo.com" value="{{ $clienteSeleccionado ? $clienteSeleccionado->correo : '' }}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                        </div>
                    </div>
                </div>
                
                <!-- Factura Info -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Información de Factura
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Número de Factura <span class="text-red-500 dark:text-red-400">*</span></label>
                            <input type="text" id="numero_factura" name="numero_factura" required value="{{ str_pad($siguienteNumero, 4, '0', STR_PAD_LEFT) }}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Serie</label>
                            <input type="text" id="serie" name="serie" value="A" placeholder="A, B, C..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Fecha de Emisión <span class="text-red-500 dark:text-red-400">*</span></label>
                            <input type="date" id="fecha_emision" name="fecha_emision" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Fecha de Vencimiento</label>
                            <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Estado</label>
                            <select id="estado" name="estado" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                                <option value="pendiente">Pendiente</option>
                                <option value="pagada">Pagada</option>
                                <option value="vencida">Vencida</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Información del Cliente (Cálculo Automático) -->
                <div id="clienteInfoCard" class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl p-4 shadow-sm dark:shadow-none hidden">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Resumen del Cliente
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Total Facturado</p>
                            <p class="text-base font-bold text-slate-900 dark:text-white" id="infoTotalFacturado">₡0</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Total Pagado</p>
                            <p class="text-base font-bold text-emerald-600 dark:text-emerald-400" id="infoTotalPagado">₡0</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Saldo Pendiente</p>
                            <p class="text-base font-bold text-red-600 dark:text-red-400" id="infoSaldoPendiente">₡0</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Facturas</p>
                            <p class="text-base font-bold text-slate-900 dark:text-white" id="infoFacturasCount">0</p>
                        </div>
                    </div>
                </div>
                
                <!-- Paquetes Predefinidos -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Paquetes Predefinidos
                    </h2>
                    <div class="mb-3">
                        <select id="paqueteSelect" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all">
                            <option value="">Seleccionar paquete...</option>
                        </select>
                    </div>
                    <button type="button" onclick="agregarPaquete()" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-500 text-white rounded-lg transition-all text-xs">
                        Agregar Paquete
                    </button>
                </div>
                
                <!-- Items de Factura -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Items de Factura <span class="text-red-500 dark:text-red-400 text-sm font-normal">*</span>
                    </h2>
                    
                    <div id="itemsContainer" class="space-y-2 mb-3">
                        <!-- Items se agregarán dinámicamente aquí -->
                    </div>
                    
                    <button type="button" onclick="agregarItem()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-all text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Item
                    </button>
                </div>
                
                <!-- Resumen y Totales -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Resumen y Totales
                    </h2>
                    
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Número de Orden</label>
                                <input type="text" id="numero_orden" name="numero_orden" placeholder="Ej: 1_191125 cliente" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Subtotal</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">₡</span>
                                    <input type="number" step="0.01" id="subtotal" name="subtotal" value="0" readonly class="w-full pl-7 pr-3 py-2 bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Descuento Antes Impuestos</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">₡</span>
                                    <input type="number" step="0.01" id="descuento_antes_impuestos" name="descuento_antes_impuestos" value="0" oninput="calcularTotales()" class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">IVA (13%)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">₡</span>
                                    <input type="number" step="0.01" id="iva" name="iva" value="0" readonly class="w-full pl-7 pr-3 py-2 bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Descuento Después Impuestos</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">₡</span>
                                    <input type="number" step="0.01" id="descuento_despues_impuestos" name="descuento_despues_impuestos" value="0" oninput="calcularTotales()" class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Total <span class="text-red-500 dark:text-red-400">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">₡</span>
                                    <input type="number" step="0.01" id="total" name="total" required value="0" readonly class="w-full pl-7 pr-3 py-2 bg-emerald-50 dark:bg-emerald-500/10 border-2 border-emerald-500 dark:border-emerald-500 rounded-lg text-sm text-emerald-700 dark:text-emerald-400 font-bold">
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Monto Pagado</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">₡</span>
                                    <input type="number" step="0.01" id="monto_pagado" name="monto_pagado" value="0" oninput="calcularSaldo()" class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Saldo Pendiente</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">₡</span>
                                    <input type="number" step="0.01" id="saldo_pendiente" name="saldo_pendiente" value="0" readonly class="w-full pl-7 pr-3 py-2 bg-red-50 dark:bg-red-500/10 border border-red-300 dark:border-red-500/30 rounded-lg text-sm text-red-700 dark:text-red-400 font-semibold">
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Método de Pago</label>
                                <select id="metodo_pago" name="metodo_pago" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                                    <option value="">Sin especificar</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                    <option value="sinpe">SINPE Móvil</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Concepto de Pago</label>
                                <input type="text" id="concepto_pago" name="concepto_pago" placeholder="Ej: Pago inicial, Pago final..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Concepto General <span class="text-red-500 dark:text-red-400">*</span></label>
                            <textarea id="concepto" name="concepto" rows="2" placeholder="Descripción general de la factura (se generará automáticamente si se deja vacío)..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-none"></textarea>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Si se deja vacío, se generará automáticamente basado en los items</p>
                        </div>
                    </div>
                </div>
                
                <!-- Pagos Recibidos -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pagos Recibidos
                    </h2>
                    
                    <div id="pagosContainer" class="space-y-2 mb-3">
                        <!-- Pagos se agregarán dinámicamente aquí -->
                    </div>
                    
                    <button type="button" onclick="agregarPago()" class="px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white rounded-lg transition-all text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Pago
                    </button>
                </div>
                
                <!-- Notas -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Notas Adicionales
                    </h2>
                    
                    <textarea id="notas" name="notas" rows="2" placeholder="Notas adicionales para la factura..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition-all resize-none"></textarea>
                </div>
                
                <!-- Archivos Adjuntos Section -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 shadow-sm dark:shadow-none">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        Archivos Adjuntos (Opcional)
                    </h2>
                    
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Seleccionar Archivos</label>
                        <input type="file" id="archivos" name="archivos[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all @error('archivos') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, ZIP, RAR</p>
                        @error('archivos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('archivos.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        
                        <!-- Lista de archivos seleccionados -->
                        <div id="archivos-lista" class="mt-3 space-y-2 hidden">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Archivos seleccionados:</p>
                            <div id="archivos-nombres" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="space-y-2">
                    <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                        <span class="text-red-500 dark:text-red-400">*</span>
                        <span>Campos obligatorios</span>
                    </p>
                    <div class="flex gap-3">
                        <button type="button" onclick="mostrarVistaPrevia()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-all flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>Vista Previa</span>
                        </button>
                        <button type="submit" id="submitBtn" class="flex-1 px-4 py-2.5 bg-violet-600 hover:bg-violet-500 text-white font-medium rounded-lg transition-all flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Crear Factura</span>
                        </button>
                    </div>
                </div>
            </form>
            
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
        let items = [];
        let paquetes = [];
        let pagos = [];
        let itemCounter = 0;
        let pagoCounter = 0;
        
        // Cargar paquetes al iniciar
        async function cargarPaquetes() {
            try {
                const response = await fetch('{{ route("walee.facturas.paquetes") }}');
                const data = await response.json();
                if (data.success) {
                    paquetes = data.paquetes;
                    const select = document.getElementById('paqueteSelect');
                    select.innerHTML = '<option value="">Seleccionar paquete...</option>';
                    paquetes.forEach(paquete => {
                        const option = document.createElement('option');
                        option.value = paquete.id;
                        option.textContent = `${paquete.nombre} - ₡${parseFloat(paquete.precio).toLocaleString()}`;
                        option.dataset.paquete = JSON.stringify(paquete);
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error cargando paquetes:', error);
            }
        }
        
        // Cargar información del cliente
        async function cargarInfoCliente(clienteId) {
            if (!clienteId) {
                document.getElementById('clienteInfoCard').classList.add('hidden');
                return;
            }
            
            try {
                const response = await fetch(`/walee-facturas/cliente/${clienteId}/info`);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('infoTotalFacturado').textContent = `₡${parseFloat(data.resumen.total_facturado).toLocaleString()}`;
                    document.getElementById('infoTotalPagado').textContent = `₡${parseFloat(data.resumen.total_pagado).toLocaleString()}`;
                    document.getElementById('infoSaldoPendiente').textContent = `₡${parseFloat(data.resumen.saldo_pendiente).toLocaleString()}`;
                    document.getElementById('infoFacturasCount').textContent = data.resumen.facturas_count;
                    document.getElementById('clienteInfoCard').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error cargando info del cliente:', error);
            }
        }
        
        // Auto-fill correo when cliente changes
        document.getElementById('cliente_id').addEventListener('change', function() {
            const clienteId = this.value.trim();
            
            // Validar que no esté vacío
            if (clienteId === '') {
                document.getElementById('correo').value = '';
                document.getElementById('clienteInfoCard').classList.add('hidden');
                return;
            }
            
            // Validar que sea numérico
            if (!/^\d+$/.test(clienteId)) {
                showNotification('Error', 'El ID del cliente debe ser un número válido', 'error');
                this.value = '';
                document.getElementById('correo').value = '';
                document.getElementById('clienteInfoCard').classList.add('hidden');
                return;
            }
            
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.dataset.email;
            if (email) {
                document.getElementById('correo').value = email;
            }
            cargarInfoCliente(clienteId);
        });
        
        // Cargar cliente si viene pre-seleccionado desde URL
        @if($clienteSeleccionado)
        document.addEventListener('DOMContentLoaded', function() {
            const clienteId = '{{ $clienteSeleccionado->id }}';
            cargarInfoCliente(clienteId);
        });
        @endif
        
        // Agregar paquete
        function agregarPaquete() {
            const select = document.getElementById('paqueteSelect');
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption.value) return;
            
            const paquete = JSON.parse(selectedOption.dataset.paquete);
            agregarItem(paquete.nombre, paquete.precio, 1, paquete.descripcion);
            select.value = '';
        }
        
        // Agregar item
        function agregarItem(descripcion = '', precio = 0, cantidad = 1, notas = '') {
            const itemId = itemCounter++;
            const item = {
                id: itemId,
                descripcion: descripcion,
                cantidad: cantidad,
                precio_unitario: precio,
                subtotal: cantidad * precio
            };
            items.push(item);
            renderizarItems();
            calcularTotales();
        }
        
        // Eliminar item
        function eliminarItem(itemId) {
            items = items.filter(item => item.id !== itemId);
            renderizarItems();
            calcularTotales();
        }
        
        // Renderizar items
        function renderizarItems() {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = '';
            
            items.forEach((item, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg p-3';
                itemDiv.innerHTML = `
                    <div class="grid grid-cols-12 gap-2 items-end">
                        <div class="col-span-12 md:col-span-5">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Descripción</label>
                            <input type="text" value="${item.descripcion}" oninput="actualizarItem(${item.id}, 'descripcion', this.value)" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Cantidad</label>
                            <input type="number" value="${item.cantidad}" min="1" oninput="actualizarItem(${item.id}, 'cantidad', parseFloat(this.value) || 1)" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Precio Unit.</label>
                            <input type="number" step="0.01" value="${item.precio_unitario}" oninput="actualizarItem(${item.id}, 'precio_unitario', parseFloat(this.value) || 0)" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Subtotal</label>
                            <input type="number" step="0.01" value="${item.subtotal.toFixed(2)}" readonly class="w-full px-2.5 py-1.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs font-semibold">
                        </div>
                        <div class="col-span-12 md:col-span-1">
                            <button type="button" onclick="eliminarItem(${item.id})" class="w-full px-2.5 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all text-xs">
                                <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(itemDiv);
            });
        }
        
        // Actualizar item
        function actualizarItem(itemId, campo, valor) {
            const item = items.find(i => i.id === itemId);
            if (item) {
                item[campo] = valor;
                if (campo === 'cantidad' || campo === 'precio_unitario') {
                    item.subtotal = (item.cantidad || 1) * (item.precio_unitario || 0);
                }
                renderizarItems();
                calcularTotales();
            }
        }
        
        // Agregar pago
        function agregarPago() {
            const pagoId = pagoCounter++;
            const pago = {
                id: pagoId,
                descripcion: '',
                fecha: new Date().toISOString().split('T')[0],
                importe: 0,
                metodo_pago: ''
            };
            pagos.push(pago);
            renderizarPagos();
        }
        
        // Eliminar pago
        function eliminarPago(pagoId) {
            pagos = pagos.filter(p => p.id !== pagoId);
            renderizarPagos();
            calcularSaldo();
        }
        
        // Renderizar pagos
        function renderizarPagos() {
            const container = document.getElementById('pagosContainer');
            container.innerHTML = '';
            
            pagos.forEach((pago) => {
                const pagoDiv = document.createElement('div');
                pagoDiv.className = 'bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg p-3';
                pagoDiv.innerHTML = `
                    <div class="grid grid-cols-12 gap-2 items-end">
                        <div class="col-span-12 md:col-span-4">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Descripción</label>
                            <input type="text" value="${pago.descripcion}" oninput="actualizarPago(${pago.id}, 'descripcion', this.value)" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Fecha</label>
                            <input type="date" value="${pago.fecha}" oninput="actualizarPago(${pago.id}, 'fecha', this.value)" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Importe</label>
                            <input type="number" step="0.01" value="${pago.importe}" oninput="actualizarPago(${pago.id}, 'importe', parseFloat(this.value) || 0)" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="col-span-10 md:col-span-3">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Método</label>
                            <select onchange="actualizarPago(${pago.id}, 'metodo_pago', this.value)" class="w-full px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white text-xs">
                                <option value="">Seleccionar...</option>
                                <option value="sinpe" ${pago.metodo_pago === 'sinpe' ? 'selected' : ''}>SINPE</option>
                                <option value="transferencia" ${pago.metodo_pago === 'transferencia' ? 'selected' : ''}>Transferencia</option>
                                <option value="efectivo" ${pago.metodo_pago === 'efectivo' ? 'selected' : ''}>Efectivo</option>
                                <option value="tarjeta" ${pago.metodo_pago === 'tarjeta' ? 'selected' : ''}>Tarjeta</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <button type="button" onclick="eliminarPago(${pago.id})" class="w-full px-2.5 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all text-xs">
                                <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(pagoDiv);
            });
        }
        
        // Actualizar pago
        function actualizarPago(pagoId, campo, valor) {
            const pago = pagos.find(p => p.id === pagoId);
            if (pago) {
                pago[campo] = valor;
                if (campo === 'importe') {
                    calcularSaldo();
                }
            }
        }
        
        // Calcular totales
        function calcularTotales() {
            const subtotal = items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
            const descuentoAntes = parseFloat(document.getElementById('descuento_antes_impuestos').value) || 0;
            const subtotalConDescuento = subtotal - descuentoAntes;
            const iva = subtotalConDescuento * 0.13;
            const descuentoDespues = parseFloat(document.getElementById('descuento_despues_impuestos').value) || 0;
            const total = subtotalConDescuento + iva - descuentoDespues;
            
            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('iva').value = iva.toFixed(2);
            document.getElementById('total').value = total.toFixed(2);
            
            calcularSaldo();
        }
        
        // Calcular saldo pendiente
        function calcularSaldo() {
            const total = parseFloat(document.getElementById('total').value) || 0;
            const montoPagadoInput = parseFloat(document.getElementById('monto_pagado').value) || 0;
            const totalPagos = pagos.reduce((sum, pago) => sum + (parseFloat(pago.importe) || 0), 0);
            const montoPagado = montoPagadoInput + totalPagos;
            const saldoPendiente = total - montoPagado;
            document.getElementById('saldo_pendiente').value = saldoPendiente.toFixed(2);
        }
        
        // Vista previa
        async function mostrarVistaPrevia() {
            if (items.length === 0) {
                showNotification('Error', 'Debe agregar al menos un item', 'error');
                return;
            }
            
            const formData = new FormData(document.getElementById('facturaForm'));
            const data = Object.fromEntries(formData.entries());
            data.items_json = JSON.stringify(items);
            data.pagos = JSON.stringify(pagos.filter(p => p.descripcion && p.importe > 0));
            
            // Abrir modal de vista previa
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-auto">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Vista Previa de Factura</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <iframe id="previewFrame" name="previewFrame" style="width: 100%; height: 600px; border: none;"></iframe>
                    </div>
                    <div class="p-6 border-t border-slate-200 dark:border-slate-700 flex gap-3 justify-end">
                        <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg">Cerrar</button>
                        <button onclick="generarPDF()" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg">Descargar PDF</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Enviar datos a la ruta de previsualización
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("walee.facturas.preview") }}';
            form.target = 'previewFrame';
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
            Object.keys(data).forEach(key => {
                form.innerHTML += `<input type="hidden" name="${key}" value="${data[key]}">`;
            });
            document.body.appendChild(form);
            form.submit();
            setTimeout(() => form.remove(), 1000);
        }
        
        // Generar PDF
        function generarPDF() {
            const formData = new FormData(document.getElementById('facturaForm'));
            const data = Object.fromEntries(formData.entries());
            data.items_json = JSON.stringify(items);
            data.pagos = JSON.stringify(pagos.filter(p => p.descripcion && p.importe > 0));
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("walee.facturas.generar-pdf") }}';
            form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
            Object.keys(data).forEach(key => {
                form.innerHTML += `<input type="hidden" name="${key}" value="${data[key]}">`;
            });
            document.body.appendChild(form);
            form.submit();
            setTimeout(() => form.remove(), 1000);
        }
        
        // Form submission
        document.getElementById('facturaForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validar cliente
            const clienteId = document.getElementById('cliente_id').value.trim();
            if (!clienteId || clienteId === '') {
                showNotification('Error', 'Debe seleccionar un cliente', 'error');
                document.getElementById('cliente_id').focus();
                return;
            }
            
            if (!/^\d+$/.test(clienteId)) {
                showNotification('Error', 'El ID del cliente debe ser un número válido', 'error');
                document.getElementById('cliente_id').focus();
                return;
            }
            
            if (items.length === 0) {
                showNotification('Error', 'Debe agregar al menos un item', 'error');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(this);
            
            // Asegurar que cliente_id esté limpio (sin espacios)
            formData.set('cliente_id', clienteId);
            
            // Agregar items al formData
            items.forEach((item, index) => {
                formData.append(`items[${index}][descripcion]`, item.descripcion);
                formData.append(`items[${index}][cantidad]`, item.cantidad);
                formData.append(`items[${index}][precio_unitario]`, item.precio_unitario);
                formData.append(`items[${index}][subtotal]`, item.subtotal);
                formData.append(`items[${index}][orden]`, index);
            });
            
            // Agregar pagos al formData
            pagos.forEach((pago, index) => {
                if (pago.descripcion && pago.importe > 0) {
                    formData.append(`pagos[${index}][descripcion]`, pago.descripcion);
                    formData.append(`pagos[${index}][fecha]`, pago.fecha);
                    formData.append(`pagos[${index}][importe]`, pago.importe);
                    formData.append(`pagos[${index}][metodo_pago]`, pago.metodo_pago || '');
                }
            });
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Creando...</span>
            `;
            
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
                    showNotification('Factura creada', 'La factura ha sido creada correctamente', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("walee.facturas") }}';
                    }, 1500);
                } else {
                    showNotification('Error', data.message || 'Error al crear factura', 'error');
                }
            } catch (error) {
                showNotification('Error', 'Error de conexión: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Crear Factura</span>
                `;
            }
        });
        
        function showNotification(title, body, type = 'info') {
            const container = document.getElementById('notifications');
            const id = 'notif-' + Date.now();
            
            const bgClass = {
                'success': 'bg-emerald-600',
                'error': 'bg-red-600',
                'info': 'bg-blue-600',
            }[type] || 'bg-slate-600';
            
            const notification = document.createElement('div');
            notification.id = id;
            notification.className = `${bgClass} text-white px-4 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300`;
            notification.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="font-medium text-sm">${title}</p>
                        <p class="text-xs opacity-90 mt-0.5">${body}</p>
                    </div>
                    <button onclick="document.getElementById('${id}').remove()" class="text-white/70 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            setTimeout(() => notification.classList.remove('translate-x-full'), 10);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        // Mostrar lista de archivos seleccionados
        const archivosInput = document.getElementById('archivos');
        const archivosLista = document.getElementById('archivos-lista');
        const archivosNombres = document.getElementById('archivos-nombres');
        
        if (archivosInput) {
            archivosInput.addEventListener('change', function() {
                const files = Array.from(this.files);
                
                if (files.length > 0) {
                    archivosNombres.innerHTML = '';
                    files.forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center gap-2 p-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 text-xs';
                        fileItem.innerHTML = `
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="flex-1 text-slate-700 dark:text-slate-300">${file.name}</span>
                            <span class="text-slate-500 dark:text-slate-400">${(file.size / 1024).toFixed(2)} KB</span>
                        `;
                        archivosNombres.appendChild(fileItem);
                    });
                    archivosLista.classList.remove('hidden');
                } else {
                    archivosLista.classList.add('hidden');
                }
            });
        }
        
        // ========== SISTEMA DE MODAL POR FASES ==========
        let facturaData = {
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
        
        // Abrir modal de factura
        function abrirModalFactura() {
            currentPhase = 1;
            // Inicializar datos si hay cliente desde URL
            @if($clienteSeleccionado)
            facturaData.cliente_id = '{{ $clienteSeleccionado->id }}';
            facturaData.correo = '{{ $clienteSeleccionado->correo }}';
            @endif
            mostrarFase1();
        }
        
        // FASE 1: Cliente y Correo
        function mostrarFase1() {
            const clientesOptions = `@foreach($clientes as $cliente)<option value="{{ $cliente->id }}" data-email="{{ $cliente->correo }}" ${facturaData.cliente_id == '{{ $cliente->id }}' ? 'selected' : ''}>{{ $cliente->nombre_empresa }}</option>@endforeach`;
            
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
                            ${clientesOptions}
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
                                <p class="text-xs text-slate-500">Cant: ${item.cantidad} x ₡${parseFloat(item.precio_unitario).toLocaleString()} = ₡${parseFloat(item.subtotal).toLocaleString()}</p>
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
                                <p class="text-lg font-bold text-slate-900 dark:text-white">₡${facturaData.subtotal.toFixed(2)}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">IVA (13%)</label>
                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">₡${facturaData.iva.toFixed(2)}</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descuento Antes Impuestos</label>
                            <input type="number" step="0.01" id="modal_descuento_antes" value="${facturaData.descuento_antes_impuestos}" oninput="calcularTotalesModal()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Descuento aplicado antes de calcular el IVA</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descuento Después Impuestos</label>
                            <input type="number" step="0.01" id="modal_descuento_despues" value="${facturaData.descuento_despues_impuestos}" oninput="calcularTotalesModal()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Descuento aplicado después del IVA</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Monto Pagado</label>
                            <input type="number" step="0.01" id="modal_monto_pagado" value="${facturaData.monto_pagado}" oninput="calcularTotalesModal()" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
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
                    window.calcularTotalesModal = function() {
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
                        
                        if (document.getElementById('modal_total')) {
                            document.getElementById('modal_total').value = total.toFixed(2);
                        }
                    };
                },
                preConfirm: () => {
                    calcularTotalesModal();
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
        
        // FASE 5: Método de Pago y Concepto de Pago
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
                            <input type="text" id="modal_concepto_pago" value="${facturaData.concepto_pago}" placeholder="Ej: Pago inicial, Pago final..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
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
                    facturaData.metodo_pago = document.getElementById('modal_metodo_pago').value;
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
        
        // FASE 6: Concepto General y Número de Orden
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
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Describe los servicios o productos facturados</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Número de Orden</label>
                        <input type="text" id="modal_numero_orden" value="${facturaData.numero_orden}" placeholder="Ej: 1_191125 cliente" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Número de orden o referencia opcional</p>
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
                                <p class="text-xs text-slate-500">${pago.fecha} - ₡${parseFloat(pago.importe).toLocaleString()} (${pago.metodo_pago || 'Sin método'})</p>
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
                        <p class="mt-1 text-xs text-slate-500">Formatos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, ZIP, RAR</p>
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
            const clienteNombre = document.querySelector(`#modal_cliente_id option[value="${facturaData.cliente_id}"]`)?.text || 'N/A';
            
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
                                <span class="font-medium text-slate-900 dark:text-white">₡${facturaData.subtotal.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">IVA (13%):</span>
                                <span class="font-medium text-slate-900 dark:text-white">₡${facturaData.iva.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between border-t border-emerald-300 dark:border-emerald-500/30 pt-2 mt-2">
                                <span class="text-base font-bold text-slate-900 dark:text-white">Total:</span>
                                <span class="text-lg font-bold text-emerald-700 dark:text-emerald-400">₡${facturaData.total.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Monto Pagado:</span>
                                <span class="font-medium text-slate-900 dark:text-white">₡${facturaData.monto_pagado.toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 dark:text-slate-400">Saldo Pendiente:</span>
                                <span class="font-medium text-red-600 dark:text-red-400">₡${facturaData.saldo_pendiente.toFixed(2)}</span>
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
                preConfirm: async () => {
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
                    formData.append('metodo_pago', facturaData.metodo_pago);
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
                        if (pago.descripcion && pago.importe > 0) {
                            formData.append(`pagos[${index}][descripcion]`, pago.descripcion);
                            formData.append(`pagos[${index}][fecha]`, pago.fecha);
                            formData.append(`pagos[${index}][importe]`, pago.importe);
                            formData.append(`pagos[${index}][metodo_pago]`, pago.metodo_pago || '');
                        }
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
                                window.location.href = '{{ route("walee.facturas") }}';
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
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    currentPhase = 8;
                    mostrarFase8();
                }
            });
        }
        
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
        
        // Búsqueda de facturas
        const searchFacturasInput = document.getElementById('searchFacturasInput');
        if (searchFacturasInput) {
            searchFacturasInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const facturasItems = document.querySelectorAll('.factura-item');
                
                facturasItems.forEach(item => {
                    const searchData = item.getAttribute('data-search') || '';
                    if (searchData.includes(searchTerm) || searchTerm === '') {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
        
        // Ver factura en modal compacto
        async function verFacturaModal(facturaId) {
            try {
                Swal.fire({
                    title: 'Cargando factura...',
                    allowOutsideClick: false,
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch(`/walee-facturas/${facturaId}/json`);
                if (!response.ok) throw new Error('Error al cargar la factura');
                
                const data = await response.json();
                if (!data.success) throw new Error(data.message || 'Error al cargar la factura');
                
                const factura = data.factura;
                
                // Construir HTML de items
                let itemsHtml = '';
                if (factura.items && factura.items.length > 0) {
                    factura.items.forEach(item => {
                        itemsHtml += `
                            <div class="flex justify-between items-start py-2 border-b border-slate-200 dark:border-slate-700 last:border-0">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">${item.descripcion || 'Sin descripción'}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Cantidad: ${item.cantidad} · ₡${item.precio_unitario}</p>
                                    ${item.notas ? `<p class="text-xs text-slate-500 dark:text-slate-400 mt-1">${item.notas}</p>` : ''}
                                </div>
                                <span class="text-sm font-semibold text-slate-900 dark:text-white ml-4">₡${item.total}</span>
                            </div>
                        `;
                    });
                } else {
                    itemsHtml = '<p class="text-sm text-slate-500 dark:text-slate-400 text-center py-2">No hay items en esta factura</p>';
                }
                
                const modalHtml = `
                    <div class="text-left space-y-4 max-h-[70vh] overflow-y-auto">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-violet-500/10 to-violet-600/5 rounded-lg p-4 border border-violet-200 dark:border-violet-500/20">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-violet-600 dark:text-violet-400">Factura ${factura.numero_factura}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Serie ${factura.serie}</p>
                                </div>
                                <div class="text-right">
                                    ${factura.enviada_at ? '<span class="text-xs bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-2 py-1 rounded">Enviada</span>' : '<span class="text-xs bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 px-2 py-1 rounded">Pendiente</span>'}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cliente -->
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Cliente</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">${factura.cliente_nombre}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400">${factura.correo}</p>
                        </div>
                        
                        <!-- Fechas -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Emisión</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">${factura.fecha_emision}</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Vencimiento</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">${factura.fecha_vencimiento}</p>
                            </div>
                        </div>
                        
                        <!-- Concepto -->
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Concepto</p>
                            <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line">${factura.concepto}</p>
                        </div>
                        
                        <!-- Items -->
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Items</p>
                            <div class="space-y-1">
                                ${itemsHtml}
                            </div>
                        </div>
                        
                        <!-- Totales -->
                        <div class="bg-violet-50 dark:bg-violet-500/10 rounded-lg p-3 border border-violet-200 dark:border-violet-500/20">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-400">Subtotal:</span>
                                    <span class="font-medium text-slate-900 dark:text-white">₡${factura.subtotal}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-400">IVA (13%):</span>
                                    <span class="font-medium text-slate-900 dark:text-white">₡${factura.iva}</span>
                                </div>
                                <div class="flex justify-between text-base font-bold border-t border-violet-200 dark:border-violet-500/20 pt-2">
                                    <span class="text-slate-900 dark:text-white">Total:</span>
                                    <span class="text-violet-600 dark:text-violet-400">₡${factura.total}</span>
                                </div>
                                ${factura.monto_pagado ? `
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-400">Pagado:</span>
                                    <span class="font-medium text-emerald-600 dark:text-emerald-400">₡${factura.monto_pagado}</span>
                                </div>
                                ` : ''}
                                ${factura.saldo_pendiente ? `
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-400">Pendiente:</span>
                                    <span class="font-medium text-amber-600 dark:text-amber-400">₡${factura.saldo_pendiente}</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <!-- Estado y Método -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Estado</p>
                                <span class="text-xs font-medium px-2 py-1 rounded ${factura.estado.toLowerCase() === 'pagada' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : factura.estado.toLowerCase() === 'vencida' ? 'bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400' : 'bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400'}">${factura.estado}</span>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Método de Pago</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">${factura.metodo_pago}</p>
                            </div>
                        </div>
                        
                        ${factura.notas ? `
                        <!-- Notas -->
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Notas</p>
                            <p class="text-sm text-slate-700 dark:text-slate-300">${factura.notas}</p>
                        </div>
                        ` : ''}
                        
                        ${factura.enviada_at ? `
                        <!-- Info de Envío -->
                        <div class="bg-emerald-50 dark:bg-emerald-500/10 rounded-lg p-3 border border-emerald-200 dark:border-emerald-500/20">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Factura enviada</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">${factura.enviada_at}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                Swal.fire({
                    title: `Factura ${factura.numero_factura}`,
                    html: modalHtml,
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
                        // Asegurar que el modal esté encima de todo
                        const popup = document.querySelector('.swal2-popup');
                        if (popup) {
                            popup.style.zIndex = '9999';
                            popup.style.position = 'relative';
                            
                            // Crear y agregar botón X en la esquina superior derecha del popup
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
                        const container = document.querySelector('.swal2-container');
                        if (container) {
                            container.style.zIndex = '9999';
                        }
                    },
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
                
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la factura. Por favor, intente nuevamente.',
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Eliminar factura
        async function eliminarFactura(facturaId, numeroFactura) {
            const result = await Swal.fire({
                title: '¿Eliminar factura?',
                text: `¿Está seguro de que desea eliminar la factura ${numeroFactura}? Esta acción no se puede deshacer.`,
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
                
                const response = await fetch(`/walee-facturas/${facturaId}/eliminar`, {
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
                        title: 'Factura eliminada',
                        text: 'La factura ha sido eliminada correctamente',
                        confirmButtonColor: '#7c3aed',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar la factura',
                        confirmButtonColor: '#7c3aed',
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + error.message,
                    confirmButtonColor: '#7c3aed',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Enviar factura por email
        async function enviarFacturaEmail(facturaId, correo, yaEnviada) {
            if (!correo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin correo',
                    text: 'Esta factura no tiene correo electrónico asociado.',
                    confirmButtonColor: '#7c3aed',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
                return;
            }
            
            const confirmText = yaEnviada 
                ? `¿Desea reenviar la factura a ${correo}?` 
                : `¿Desea enviar la factura a ${correo}?`;
            
            const result = await Swal.fire({
                title: yaEnviada ? 'Reenviar Factura' : 'Enviar Factura',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: yaEnviada ? 'Sí, Reenviar' : 'Sí, Enviar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: yaEnviada ? '#2563eb' : '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
            
            if (!result.isConfirmed) return;
            
            try {
                Swal.fire({
                    title: 'Enviando factura...',
                    text: 'Por favor espere',
                    allowOutsideClick: false,
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                const response = await fetch(`/walee-facturas/${facturaId}/enviar-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Factura enviada!',
                        text: data.message || 'La factura ha sido enviada por email correctamente.',
                        confirmButtonColor: '#10b981',
                        timer: 3000,
                        timerProgressBar: true,
                        background: isDarkMode() ? '#1e293b' : '#ffffff',
                        color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                    }).then(() => {
                        // Recargar la página para actualizar el estado
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error al enviar la factura');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo enviar la factura. Por favor, intente nuevamente.',
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode() ? '#1e293b' : '#ffffff',
                    color: isDarkMode() ? '#e2e8f0' : '#1e293b',
                });
            }
        }
        
        // Ver PDF de factura en modal
        function verPDFFactura(facturaId) {
            const pdfUrl = `/walee-facturas/${facturaId}/pdf`;
            
            // En móvil, abrir directamente en la misma pestaña
            const isMobile = window.innerWidth < 640; // sm breakpoint de Tailwind
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
                    // Asegurar que el modal esté encima de todo
                    const popup = document.querySelector('.swal2-popup');
                    if (popup) {
                        popup.style.zIndex = '9999';
                        popup.style.position = 'relative';
                        popup.style.padding = '0';
                        popup.style.maxHeight = 'calc(100vh - 40px)';
                        
                        // Crear y agregar botón X en la esquina superior derecha del popup
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
                    const container = document.querySelector('.swal2-container');
                    if (container) {
                        container.style.zIndex = '9999';
                        container.style.padding = '20px';
                    }
                    const htmlContainer = popup?.querySelector('.swal2-html-container');
                    if (htmlContainer) {
                        htmlContainer.style.padding = '0';
                        htmlContainer.style.margin = '0';
                    }
                },
                background: isDarkMode() ? '#1e293b' : '#ffffff',
                color: isDarkMode() ? '#e2e8f0' : '#1e293b',
            });
        }
        
        // Inicializar
        cargarPaquetes();
    </script>
    @include('partials.walee-support-button')
</body>
</html>

