<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard de Emails</title>
    <meta name="description" content="Walee - Dashboard de Emails">
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
        // Total de clientes en proceso (excluyendo activos) - Negocios Extraídos
        $totalClientesEnProceso = \App\Models\Client::where('estado', '!=', 'activo')->count();
        
        // Clientes en proceso creados hoy (excluyendo activos)
        $clientesEnProcesoHoy = \App\Models\Client::where('estado', '!=', 'activo')
            ->whereDate('created_at', today())
            ->count();
        
        // Clientes con estado received
        $clientesReceived = \App\Models\Client::where('estado', 'received')->count();
        
        // Clientes marcados como received hoy
        $clientesReceivedHoy = \App\Models\Client::where('estado', 'received')
            ->whereDate('updated_at', today())
            ->count();
        
        // Clientes con estado pending
        $clientesPending = \App\Models\Client::where('estado', 'pending')->count();
        
        // Clientes marcados como pending hoy
        $clientesPendingHoy = \App\Models\Client::where('estado', 'pending')
            ->whereDate('updated_at', today())
            ->count();
        
        // Obtener correos de clientes en proceso (excluyendo activos)
        $clientesEmails = \App\Models\Client::where('estado', '!=', 'activo')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->map(function($email) {
                return strtolower(trim($email));
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        
        // Estadísticas de emails recibidos (solo de clientes en proceso)
        if (!empty($clientesEmails) && count($clientesEmails) > 0) {
            $emailsRecibidosQuery = \App\Models\EmailRecibido::where(function($query) use ($clientesEmails) {
                foreach ($clientesEmails as $clienteEmail) {
                    $query->orWhereRaw('
                        CASE 
                            WHEN from_email LIKE "%<%" THEN 
                                LOWER(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(from_email, "<", -1), ">", 1))) = ?
                            ELSE 
                                LOWER(TRIM(from_email)) = ?
                        END
                    ', [$clienteEmail, $clienteEmail]);
                }
            });
            
            $totalRecibidos = $emailsRecibidosQuery->count();
            $noLeidos = (clone $emailsRecibidosQuery)->where('is_read', false)->count();
            $recibidosHoy = (clone $emailsRecibidosQuery)->whereDate('received_at', today())->count();
            $recibidosEsteMes = (clone $emailsRecibidosQuery)
                ->whereMonth('received_at', now()->month)
                ->whereYear('received_at', now()->year)
                ->count();
        } else {
            $totalRecibidos = 0;
            $noLeidos = 0;
            $recibidosHoy = 0;
            $recibidosEsteMes = 0;
        }
        
        // Estadísticas de emails enviados (basado en clientes en proceso)
        $totalEnviados = $totalClientesEnProceso;
        $enviadosHoy = \App\Models\Client::where('estado', '!=', 'activo')
            ->whereDate('created_at', today())
            ->count();
        $enviadosEsteMes = \App\Models\Client::where('estado', '!=', 'activo')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Emails recientes recibidos (últimos 5)
        if (!empty($clientesEmails) && count($clientesEmails) > 0) {
            $emailsRecientesRecibidos = \App\Models\EmailRecibido::where(function($query) use ($clientesEmails) {
                foreach ($clientesEmails as $clienteEmail) {
                    $query->orWhereRaw('
                        CASE 
                            WHEN from_email LIKE "%<%" THEN 
                                LOWER(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(from_email, "<", -1), ">", 1))) = ?
                            ELSE 
                                LOWER(TRIM(from_email)) = ?
                        END
                    ', [$clienteEmail, $clienteEmail]);
                }
            })
            ->orderBy('received_at', 'desc')
            ->limit(5)
            ->get();
        } else {
            $emailsRecientesRecibidos = collect();
        }
        
        // Clientes recientes agregados en proceso (últimos 5, excluyendo activos)
        $emailsRecientesEnviados = \App\Models\Client::where('estado', '!=', 'activo')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Datos para gráfico de emails enviados últimos 15 días
        $ultimos15Dias = [];
        $enviadosPorDia = [];
        
        for ($i = 14; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $ultimos15Dias[] = $fecha->format('d/m');
            
            // Clientes agregados en proceso por día (excluyendo activos)
            $enviadosPorDia[] = \App\Models\Client::where('estado', '!=', 'activo')
                ->whereDate('created_at', $fecha->format('Y-m-d'))
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
            @php $pageTitle = 'Dashboard de Emails'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Dashboard de Emails
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Resumen y estadísticas de tu actividad de emails</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('walee.emails') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Negocios Extraídos -->
                <div class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Negocios Extraídos</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalClientesEnProceso) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $clientesEnProcesoHoy }} hoy</span>
                    </div>
                </div>
                
                <!-- Estado Pending - Emails Enviados -->
                <div class="stat-card bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Emails Enviados</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesPending) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-violet-600 dark:text-violet-400 font-medium">{{ $clientesPendingHoy }} hoy</span>
                    </div>
                </div>
                
                <!-- Estado Received - Faltan -->
                <div class="stat-card bg-gradient-to-br from-walee-50 to-walee-100/50 dark:from-walee-500/10 dark:to-walee-600/5 border border-walee-200 dark:border-walee-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-walee-500/20 dark:bg-walee-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Faltan</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesReceived) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-walee-600 dark:text-walee-400 font-medium">{{ $clientesReceivedHoy }} hoy</span>
                    </div>
                </div>
            </div>
            
            <!-- Charts and Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Chart -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.5s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Emails Enviados Últimos 15 Días</h2>
                    <div class="relative w-full" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.6s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Acciones Rápidas</h2>
                    <div class="space-y-2 sm:space-y-3">
                        <a href="{{ route('walee.emails.crear') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Crear Email con AI</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">Genera un email personalizado</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('walee.emails.recibidos') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Ver Recibidos</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $noLeidos }} no leídos</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('walee.emails.enviados') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Ver Enviados</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $totalEnviados }} total</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('walee.emails.templates') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-walee-50 dark:bg-walee-500/10 border border-walee-200 dark:border-walee-500/20 hover:bg-walee-100 dark:hover:bg-walee-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-walee-500/20 dark:bg-walee-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Templates</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">Gestionar plantillas</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('walee.clientes') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Clientes</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">Gestionar clientes</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Emails -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Recent Received -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.7s">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white">Emails Recibidos Recientes</h2>
                        <a href="{{ route('walee.emails.recibidos') }}" class="text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 hover:underline">Ver todos</a>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($emailsRecientesRecibidos as $email)
                            <a href="{{ route('walee.emails.recibidos') }}" class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-emerald-400 dark:hover:border-emerald-500/30 hover:shadow-md dark:hover:shadow-lg transition-all cursor-pointer group">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">{{ $email->subject ?: 'Sin asunto' }}</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $email->from_email }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5 sm:mt-1">{{ $email->received_at ? $email->received_at->diffForHumans() : 'N/A' }}</p>
                                </div>
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                                    @if(!$email->is_read)
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    @endif
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-center py-3 sm:py-4">No hay emails recibidos recientes</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- Recent Sent -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.8s">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white">Clientes Agregados Recientes</h2>
                        <a href="{{ route('walee.clientes.proceso') }}" class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline">Ver todos</a>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($emailsRecientesEnviados as $cliente)
                            <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500/30 hover:shadow-md dark:hover:shadow-lg transition-all cursor-pointer group">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">{{ $cliente->name ?: 'Sin nombre' }}</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $cliente->email ?: 'Sin email' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5 sm:mt-1">{{ $cliente->created_at->diffForHumans() }}</p>
                                </div>
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-center py-3 sm:py-4">No hay clientes agregados recientes</p>
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
        // Chart configuration
        const ctx = document.getElementById('activityChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($ultimos15Dias),
                    datasets: [
                        {
                            label: 'Emails Enviados',
                            data: @json($enviadosPorDia),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
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
        
        // Update chart colors on dark mode toggle
        const darkModeToggle = document.querySelector('[data-dark-mode-toggle]');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                setTimeout(() => {
                    if (ctx && window.chartInstance) {
                        window.chartInstance.destroy();
                        // Recreate chart with updated colors
                        location.reload();
                    }
                }, 100);
            });
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

