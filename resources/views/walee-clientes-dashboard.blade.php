<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard de Clientes</title>
    <meta name="description" content="Walee - Dashboard de Clientes">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        .stat-card {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        
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
        
        /* Estilo para backdrop opaco del modal */
        .swal2-backdrop-show {
            background-color: rgba(0, 0, 0, 0.75) !important;
            z-index: 99999 !important;
        }
        
        .swal2-container {
            backdrop-filter: blur(4px);
            z-index: 99999 !important;
        }
        
        .swal2-popup {
            z-index: 99999 !important;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        use App\Models\Client;
        use App\Models\Cliente;
        use App\Models\PublicidadEvento;
        use Carbon\Carbon;
        
        // Estadísticas generales (solo clientes con is_active = true, independiente del estado)
        $totalClientes = Client::where('is_active', true)->count();
        $clientesPending = Client::where('estado', 'pending')->count();
        $clientesPropuestaEnviada = Client::where('estado', 'propuesta_enviada')->count();
        $clientesActivos = Client::where('is_active', true)->count();
        
        // Clientes nuevos (solo con is_active = true)
        $clientesHoy = Client::where('is_active', true)
            ->whereDate('created_at', today())
            ->count();
        $clientesEsteMes = Client::where('is_active', true)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Clientes de esta semana (solo con is_active = true)
        $inicioSemana = now()->startOfWeek();
        $finSemana = now()->endOfWeek();
        $clientesEstaSemana = Client::where('is_active', true)
            ->whereBetween('created_at', [$inicioSemana, $finSemana])
            ->count();
        
        // Clientes de este año (solo con is_active = true)
        $clientesEsteAno = Client::where('is_active', true)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Clientes recientes (últimos 5, solo con is_active = true)
        $clientesRecientes = Client::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Clientes en proceso recientes (últimos 5)
        $clientesEnProcesoRecientes = Client::where('estado', 'pending')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Clientes con publicaciones
        $clientesConPublicaciones = [];
        $clientesMap = []; // Mapa para evitar duplicados usando client_id como clave
        $emailsProcesados = []; // Para evitar procesar el mismo email múltiples veces
        $publicacionesData = PublicidadEvento::select('cliente_id')
            ->selectRaw('COUNT(*) as total_publicaciones')
            ->selectRaw('SUM(CASE WHEN estado = "programado" THEN 1 ELSE 0 END) as programadas')
            ->groupBy('cliente_id')
            ->get();
        
        foreach ($publicacionesData as $pubData) {
            $clientePlaneador = Cliente::find($pubData->cliente_id);
            if ($clientePlaneador) {
                // Normalizar email para comparación
                $emailNormalizado = strtolower(trim($clientePlaneador->correo ?? ''));
                
                // Si ya procesamos este email, saltar para evitar duplicados
                if (!empty($emailNormalizado) && isset($emailsProcesados[$emailNormalizado])) {
                    // Si ya existe este email, agregar las publicaciones al cliente existente
                    $clientIdExistente = $emailsProcesados[$emailNormalizado];
                    if (isset($clientesMap[$clientIdExistente])) {
                        $clientesMap[$clientIdExistente]['total_publicaciones'] += $pubData->total_publicaciones;
                        $clientesMap[$clientIdExistente]['programadas'] += $pubData->programadas;
                    }
                    continue;
                }
                
                // Buscar el cliente correspondiente en Client por email (solo con is_active = true)
                $client = null;
                if (!empty($emailNormalizado)) {
                    $client = Client::where('is_active', true)
                        ->whereRaw('LOWER(TRIM(email)) = ?', [$emailNormalizado])
                        ->first();
                }
                
                // Si no se encuentra por email, buscar por nombre exacto primero
                if (!$client && !empty($clientePlaneador->nombre_empresa)) {
                    $nombreNormalizado = trim($clientePlaneador->nombre_empresa);
                    $client = Client::where('is_active', true)
                        ->where('name', $nombreNormalizado)
                        ->first();
                }
                
                // Si aún no se encuentra, buscar por nombre parcial (último recurso)
                if (!$client && !empty($clientePlaneador->nombre_empresa)) {
                    $client = Client::where('is_active', true)
                        ->where('name', 'like', '%' . $clientePlaneador->nombre_empresa . '%')
                        ->first();
                }
                
                if ($client) {
                    $clientId = $client->id;
                    
                    // Si el cliente ya existe en el mapa, agregar las publicaciones al total existente
                    if (isset($clientesMap[$clientId])) {
                        $clientesMap[$clientId]['total_publicaciones'] += $pubData->total_publicaciones;
                        $clientesMap[$clientId]['programadas'] += $pubData->programadas;
                    } else {
                        // Si es un cliente nuevo, agregarlo al mapa
                        $clientesMap[$clientId] = [
                            'client' => $client,
                            'cliente_planeador' => $clientePlaneador,
                            'total_publicaciones' => $pubData->total_publicaciones,
                            'programadas' => $pubData->programadas,
                        ];
                        
                        // Registrar el email procesado para evitar duplicados
                        if (!empty($emailNormalizado)) {
                            $emailsProcesados[$emailNormalizado] = $clientId;
                        }
                    }
                }
            }
        }
        
        // Convertir el mapa a array y eliminar cualquier duplicado residual
        $clientesConPublicaciones = array_values($clientesMap);
        
        // Verificación adicional: eliminar duplicados por ID de cliente (por si acaso)
        $idsUnicos = [];
        $clientesConPublicacionesUnicos = [];
        foreach ($clientesConPublicaciones as $item) {
            $clientId = $item['client']->id;
            if (!isset($idsUnicos[$clientId])) {
                $idsUnicos[$clientId] = true;
                $clientesConPublicacionesUnicos[] = $item;
            }
        }
        $clientesConPublicaciones = $clientesConPublicacionesUnicos;
        
        // Ordenar por total de publicaciones descendente
        usort($clientesConPublicaciones, function($a, $b) {
            return $b['total_publicaciones'] <=> $a['total_publicaciones'];
        });
        
        // Limitar a los primeros 10
        $clientesConPublicaciones = array_slice($clientesConPublicaciones, 0, 10);
        
        // Datos para gráfico de últimos 15 días (solo activos)
        $ultimos7Dias = [];
        $clientesPorDia = [];
        
        for ($i = 14; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $ultimos7Dias[] = $fecha->format('d/m');
            $clientesPorDia[] = Client::where('is_active', true)
                ->whereDate('created_at', $fecha->format('Y-m-d'))
                ->count();
        }
        
        // Clientes en proceso (pending y received) - Todos los tiempos
        $clientesEnProceso = Client::whereIn('estado', ['pending', 'received'])->count();
        $clientesPending = Client::where('estado', 'pending')->count();
        $clientesReceived = Client::where('estado', 'received')->count();
        // $totalClientes ya está definido arriba con solo activos
        $porcentajeClientes = $totalClientes > 0 ? (($clientesEnProceso / $totalClientes) * 100) : 0;
        
        // Datos para gráfico de barras: Clientes en proceso por día (últimos 7 días)
        $clientesEnProcesoPorDia = [];
        $clientesPendingPorDia = [];
        $clientesReceivedPorDia = [];
        $diasLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $fechaStr = $fecha->format('Y-m-d');
            $diasLabels[] = $fecha->format('d/m');
            
            // Clientes en proceso creados ese día
            $clientesEnProcesoPorDia[] = Client::whereIn('estado', ['pending', 'received'])
                ->whereDate('created_at', $fechaStr)
                ->count();
            
            // Clientes pending creados ese día
            $clientesPendingPorDia[] = Client::where('estado', 'pending')
                ->whereDate('created_at', $fechaStr)
                ->count();
            
            // Clientes received creados ese día
            $clientesReceivedPorDia[] = Client::where('estado', 'received')
                ->whereDate('created_at', $fechaStr)
                ->count();
        }
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Dashboard de Clientes'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Dashboard de Clientes
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Total: {{ number_format($clientesActivos) }} clientes activos</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <a href="{{ route('walee.dashboard') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-walee-500 to-walee-600 hover:from-walee-600 hover:to-walee-700 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg shadow-walee-500/30 hover:shadow-xl hover:shadow-walee-500/40 transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="hidden sm:inline">General</span>
                        <span class="sm:hidden">General</span>
                    </a>
                    <button onclick="openCreateClientModal()" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Agregar Cliente</span>
                        <span class="sm:hidden">Agregar</span>
                    </button>
                    <a href="{{ route('walee.clientes.activos') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <span class="hidden sm:inline">Ver todos los clientes</span>
                        <span class="sm:hidden">Ver todos</span>
                    </a>
                    <a href="{{ route('walee.dashboard') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 hover:from-slate-300 hover:to-slate-400 dark:hover:from-slate-600 dark:hover:to-slate-700 text-slate-900 dark:text-white font-semibold rounded-xl sm:rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Total Activos -->
                <a href="{{ route('walee.clientes.activos') }}" class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Total Activos</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesActivos) }}</p>
                    </div>
                </a>
                
                <!-- Este Mes -->
                <a href="{{ route('walee.clientes.activos') }}?mes={{ now()->month }}&ano={{ now()->year }}" class="stat-card bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Este Mes</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesEsteMes) }}</p>
                    </div>
                </a>
                
                <!-- Esta Semana -->
                <a href="{{ route('walee.clientes.activos') }}?semana=1" class="stat-card bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Esta Semana</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesEstaSemana) }}</p>
                    </div>
                </a>
                
                <!-- Hoy -->
                <a href="{{ route('walee.clientes.activos') }}?hoy=1" class="stat-card bg-gradient-to-br from-walee-50 to-walee-100/50 dark:from-walee-500/10 dark:to-walee-600/5 border border-walee-200 dark:border-walee-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-walee-500/20 dark:bg-walee-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Hoy</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesHoy) }}</p>
                    </div>
                </a>
            </div>
            
            <!-- Charts and Quick Actions -->
            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Chart -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.5s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Clientes Nuevos - Últimos 15 Días</h2>
                    <div class="relative w-full" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <canvas id="clientesChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Clientes con Publicaciones -->
            <div class="mb-4 sm:mb-6 md:mb-8">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.7s">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white">Clientes con Publicaciones</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl sm:text-3xl md:text-4xl font-bold text-violet-600 dark:text-violet-400">{{ count($clientesConPublicaciones) }}</span>
                            <span class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">clientes</span>
                        </div>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($clientesConPublicaciones as $item)
                            @php
                                $fotoPath = $item['client']->foto ?? null;
                                $fotoUrl = null;
                                if ($fotoPath) {
                                    if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                                        $fotoUrl = $fotoPath;
                                    } else {
                                        $filename = basename($fotoPath);
                                        $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                                    }
                                }
                            @endphp
                            <a href="{{ route('walee.cliente.detalle', $item['client']->id) }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-violet-400 dark:hover:border-violet-500/30 hover:bg-violet-50/50 dark:hover:bg-violet-500/10 transition-all cursor-pointer group">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $item['client']->name }}" class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg object-cover border-2 border-violet-500/30 flex-shrink-0 group-hover:scale-110 transition-transform">
                                @else
                                    <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $item['client']->name ?: 'Cliente' }}" class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg object-cover border-2 border-violet-500/30 flex-shrink-0 group-hover:scale-110 transition-transform opacity-80">
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $item['client']->name ?: 'Sin nombre' }}</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $item['client']->email ?: 'Sin email' }}</p>
                                    @if($item['client']->idioma)
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">
                                            @php
                                                $idiomas = [
                                                    'es' => '🇪🇸 Español',
                                                    'en' => '🇬🇧 English',
                                                    'fr' => '🇫🇷 Français',
                                                    'de' => '🇩🇪 Deutsch',
                                                    'it' => '🇮🇹 Italiano',
                                                    'pt' => '🇵🇹 Português'
                                                ];
                                                echo $idiomas[$item['client']->idioma] ?? strtoupper($item['client']->idioma);
                                            @endphp
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                                    <div class="text-right">
                                        <p class="text-xs sm:text-sm font-semibold text-slate-900 dark:text-white">
                                            <span class="text-violet-600 dark:text-violet-400">{{ $item['programadas'] }}</span>
                                            <span class="text-slate-500 dark:text-slate-400">/</span>
                                            <span class="text-slate-700 dark:text-slate-300">{{ $item['total_publicaciones'] }}</span>
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500">Programadas / Total</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-center py-3 sm:py-4">No hay clientes con publicaciones</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <section class="mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Acciones Rápidas
                </h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('walee.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-walee-400/5 dark:hover:bg-walee-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors">Manager</span>
                    </a>
                    
                    <a href="{{ route('walee.clientes.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Clientes</span>
                    </a>
                    
                    <a href="{{ route('walee.facturas') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-violet-400/5 dark:hover:bg-violet-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Facturas</span>
                    </a>
                    
                    <a href="{{ route('walee.emails.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-blue-400/5 dark:hover:bg-blue-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Emails</span>
                    </a>
                    
                    <a href="{{ route('walee.calendario') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Calendario</span>
                    </a>
                    
                    <a href="{{ route('walee') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-walee-400/5 dark:hover:bg-walee-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors">Chat</span>
                    </a>
                    
                    <a href="{{ route('walee.facebook.clientes') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-blue-400/5 dark:hover:bg-blue-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Facebook</span>
                    </a>
                    
                    <a href="{{ route('walee.herramientas') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-walee-400/5 dark:hover:bg-walee-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors">Herramientas</span>
                    </a>
                </div>
            </section>
            
            <!-- World Map with Clocks -->
            @include('partials.walee-world-map-clocks')
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        // Chart configuration - Clientes nuevos
        const ctxClientes = document.getElementById('clientesChart');
        if (ctxClientes) {
            new Chart(ctxClientes, {
                type: 'line',
                data: {
                    labels: @json($ultimos7Dias),
                    datasets: [{
                        label: 'Clientes Nuevos',
                        data: @json($clientesPorDia),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#fff' : '#1e293b',
                                usePointStyle: true,
                                padding: 15
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#94a3b8' : '#64748b',
                                stepSize: 1
                            },
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#94a3b8' : '#64748b'
                            },
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }
        
        // Modal para crear cliente
        function openCreateClientModal() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const html = `
                <form id="createClientForm" class="space-y-3 sm:space-y-3 md:space-y-4 text-left">
                    <div class="grid grid-cols-1 ${isDesktop ? 'md:grid-cols-2' : ''} gap-3 sm:gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Nombre *</label>
                            <input type="text" id="clientName" name="name" required
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Email</label>
                            <input type="email" id="clientEmail" name="email"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Teléfono 1</label>
                            <input type="tel" id="clientTelefono1" name="telefono_1"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Teléfono 2</label>
                            <input type="tel" id="clientTelefono2" name="telefono_2"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'md:grid-cols-2' : ''} gap-3 sm:gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Sitio Web</label>
                            <input type="url" id="clientWebsite" name="website"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Estado</label>
                            <select id="clientEstado" name="estado"
                                    class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="pending">Pendiente</option>
                                <option value="propuesta_enviada">Propuesta Enviada</option>
                                <option value="activo">Activo</option>
                                <option value="accepted">Aceptado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Dirección</label>
                        <textarea id="clientAddress" name="address" rows="2"
                                  class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                </form>
            `;
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '800px';
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            Swal.fire({
                title: 'Agregar Cliente',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                allowOutsideClick: false,
                allowEscapeKey: true,
                backdrop: true,
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    // Hacer el backdrop más opaco
                    const backdrop = document.querySelector('.swal2-backdrop-show');
                    if (backdrop) {
                        backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.75)';
                    }
                    // Focus en el primer campo
                    document.getElementById('clientName')?.focus();
                },
                preConfirm: () => {
                    const form = document.getElementById('createClientForm');
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData);
                    
                    // Validar nombre requerido
                    if (!data.name || data.name.trim() === '') {
                        Swal.showValidationMessage('El nombre es requerido');
                        return false;
                    }
                    
                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    createClient(result.value);
                }
            });
        }
        
        async function createClient(data) {
            try {
                const response = await fetch('{{ route("walee.clientes.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente creado!',
                        text: 'El cliente se ha agregado correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    let errorMessage = 'Error al crear el cliente';
                    if (result.message) {
                        errorMessage = result.message;
                    } else if (result.errors) {
                        const errors = Object.values(result.errors).flat();
                        errorMessage = errors.join(', ');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta nuevamente.',
                    confirmButtonColor: '#ef4444'
                });
            }
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
                .swal2-confirm,
                .swal2-cancel {
                    font-size: 0.875rem !important;
                    padding: 0.5rem 1rem !important;
                }
                .swal2-actions {
                    margin-top: 1rem !important;
                    gap: 0.5rem !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @include('partials.walee-support-button')
</body>
</html>

