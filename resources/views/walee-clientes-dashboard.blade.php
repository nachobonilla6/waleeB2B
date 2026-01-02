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
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        use App\Models\Client;
        use Carbon\Carbon;
        
        // Estadísticas generales
        $totalClientes = Client::count();
        $clientesPending = Client::where('estado', 'pending')->count();
        $clientesPropuestaEnviada = Client::where('estado', 'propuesta_enviada')->count();
        $clientesActivos = Client::where('estado', 'activo')->count();
        
        // Clientes nuevos
        $clientesHoy = Client::whereDate('created_at', today())->count();
        $clientesEsteMes = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Clientes recientes (últimos 5)
        $clientesRecientes = Client::orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Clientes en proceso recientes (últimos 5)
        $clientesEnProcesoRecientes = Client::where('estado', 'pending')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Datos para gráfico de últimos 7 días
        $ultimos7Dias = [];
        $clientesPorDia = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $ultimos7Dias[] = $fecha->format('d/m');
            $clientesPorDia[] = Client::whereDate('created_at', $fecha->format('Y-m-d'))->count();
        }
        
        // Distribución por estado
        $distribucionEstados = [
            'pending' => Client::where('estado', 'pending')->count(),
            'propuesta_enviada' => Client::where('estado', 'propuesta_enviada')->count(),
            'activo' => Client::where('estado', 'activo')->count(),
            'otros' => Client::whereNotIn('estado', ['pending', 'propuesta_enviada', 'activo'])->count(),
        ];
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
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Resumen y estadísticas de tus clientes</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ \App\Filament\Resources\ClientResource::getUrl('create') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Agregar Cliente</span>
                        <span class="sm:hidden">Agregar</span>
                    </a>
                    <a href="{{ route('walee.clientes') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                    @include('partials.walee-dark-mode-toggle')
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Total Clientes -->
                <div class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Total Clientes</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalClientes) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $clientesEsteMes }} este mes</span>
                    </div>
                </div>
                
                <!-- Clientes en Proceso -->
                <div class="stat-card bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">En Proceso</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesPending) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-violet-600 dark:text-violet-400 font-medium hidden sm:inline">Propuesta enviada: {{ $clientesPropuestaEnviada }}</span>
                        <span class="text-violet-600 dark:text-violet-400 font-medium sm:hidden">{{ $clientesPropuestaEnviada }}</span>
                    </div>
                </div>
                
                <!-- Clientes Activos -->
                <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Clientes Activos</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesActivos) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-blue-600 dark:text-blue-400 font-medium hidden sm:inline">Total: {{ $totalClientes }}</span>
                        <span class="text-blue-600 dark:text-blue-400 font-medium sm:hidden">{{ $totalClientes }}</span>
                    </div>
                </div>
                
                <!-- Nuevos Hoy -->
                <div class="stat-card bg-gradient-to-br from-walee-50 to-walee-100/50 dark:from-walee-500/10 dark:to-walee-600/5 border border-walee-200 dark:border-walee-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-walee-500/20 dark:bg-walee-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Nuevos Hoy</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesHoy) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-walee-600 dark:text-walee-400 font-medium">{{ $clientesEsteMes }} este mes</span>
                    </div>
                </div>
            </div>
            
            <!-- Charts and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Chart -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.5s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Clientes Nuevos - Últimos 7 Días</h2>
                    <div class="relative" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <canvas id="clientesChart"></canvas>
                    </div>
                </div>
                
                <!-- Distribution Chart -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.6s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Distribución por Estado</h2>
                    <div class="relative" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <canvas id="estadosChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up mb-4 sm:mb-6 md:mb-8" style="animation-delay: 0.7s">
                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Acciones Rápidas</h2>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3">
                    <a href="{{ \App\Filament\Resources\ClientResource::getUrl('create') }}" class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Agregar Cliente</p>
                        </div>
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors flex-shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="{{ route('walee.clientes') }}" class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Todos los Clientes</p>
                        </div>
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors flex-shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="{{ route('walee.clientes.proceso') }}" class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-all group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">En Proceso</p>
                        </div>
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors flex-shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="{{ route('walee.clientes.activos') }}" class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Activos</p>
                        </div>
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors flex-shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="{{ route('walee.dashboard') }}" class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-walee-50 dark:bg-walee-500/10 border border-walee-200 dark:border-walee-500/20 hover:bg-walee-100 dark:hover:bg-walee-500/20 transition-all group">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-walee-500/20 dark:bg-walee-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Dashboard</p>
                        </div>
                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-slate-400 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors flex-shrink-0 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Recent Clients -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Recent All -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.8s">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white">Clientes Recientes</h2>
                        <a href="{{ route('walee.clientes') }}" class="text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 hover:underline">Ver todos</a>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($clientesRecientes as $cliente)
                            <div class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-500/30 transition-all">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">{{ $cliente->name ?: 'Sin nombre' }}</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $cliente->email ?: 'Sin email' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5 sm:mt-1">{{ $cliente->updated_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 text-xs rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 flex-shrink-0">
                                    {{ ucfirst($cliente->estado) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-center py-3 sm:py-4">No hay clientes recientes</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- Recent In Process -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.9s">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white">En Proceso</h2>
                        <a href="{{ route('walee.clientes.proceso') }}" class="text-xs sm:text-sm text-violet-600 dark:text-violet-400 hover:underline">Ver todos</a>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($clientesEnProcesoRecientes as $cliente)
                            <div class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-violet-400 dark:hover:border-violet-500/30 transition-all">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">{{ $cliente->name ?: 'Sin nombre' }}</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $cliente->email ?: 'Sin email' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5 sm:mt-1">{{ $cliente->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-center py-3 sm:py-4">No hay clientes en proceso</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
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
                    maintainAspectRatio: true,
                    aspectRatio: 1.5,
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
        
        // Chart configuration - Distribución por estado
        const ctxEstados = document.getElementById('estadosChart');
        if (ctxEstados) {
            new Chart(ctxEstados, {
                type: 'doughnut',
                data: {
                    labels: ['En Proceso', 'Propuesta Enviada', 'Activos', 'Otros'],
                    datasets: [{
                        data: [
                            {{ $distribucionEstados['pending'] }},
                            {{ $distribucionEstados['propuesta_enviada'] }},
                            {{ $distribucionEstados['activo'] }},
                            {{ $distribucionEstados['otros'] }}
                        ],
                        backgroundColor: [
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(148, 163, 184, 0.8)'
                        ],
                        borderColor: [
                            'rgb(139, 92, 246)',
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(148, 163, 184)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#fff' : '#1e293b',
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

