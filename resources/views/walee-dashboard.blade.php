<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard</title>
    <meta name="description" content="Walee Dashboard - Panel de control">
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
    @include('partials.walee-violet-light-mode')
    <style>
        * {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dark .glass-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        /* Light mode glass card */
        html:not(.dark) .glass-card {
            background: rgba(245, 243, 255, 0.9);
            border: 1px solid rgba(221, 214, 254, 0.8);
        }
        
        /* Light mode adjustments */
        html:not(.dark) .stat-card {
            background: rgb(245, 243, 255) !important;
            border-color: rgba(221, 214, 254, 0.8) !important;
        }
        
        html:not(.dark) .stat-card .text-white {
            color: rgb(15, 23, 42) !important;
        }
        
        html:not(.dark) .stat-card .text-slate-400 {
            color: rgb(51, 65, 85) !important;
        }
        
        html:not(.dark) h2.text-slate-300 {
            color: rgb(30, 41, 59) !important;
        }
        
        html:not(.dark) .bg-slate-900\/50 {
            background-color: rgb(245, 243, 255) !important;
            border-color: rgba(221, 214, 254, 0.8) !important;
        }
        
        html:not(.dark) .bg-slate-800 {
            background-color: rgb(237, 233, 254) !important;
            border-color: rgb(221, 214, 254) !important;
        }
        
        html:not(.dark) .bg-slate-100 {
            background-color: rgb(237, 233, 254) !important;
        }
        
        html:not(.dark) .bg-slate-50 {
            background-color: rgb(245, 243, 255) !important;
        }
        
        /* Cambiar bg-white a violeta claro en light mode */
        html:not(.dark) .bg-white {
            background-color: rgb(245, 243, 255) !important;
        }
        
        /* Cambiar gradientes de stat-cards a violeta claro en light mode */
        html:not(.dark) .stat-card.bg-gradient-to-br {
            background: linear-gradient(to bottom right, rgb(245, 243, 255), rgb(237, 233, 254)) !important;
        }
        
        /* Forzar todos los gradientes de stat-cards a violeta claro */
        /* Forzar todos los gradientes de stat-cards a violeta claro - más específico */
        html:not(.dark) .stat-card[class*="from-emerald-50"],
        html:not(.dark) .stat-card[class*="from-blue-50"],
        html:not(.dark) .stat-card[class*="from-walee-50"],
        html:not(.dark) .stat-card[class*="from-violet-50"],
        html:not(.dark) .stat-card[class*="from-cyan-50"],
        html:not(.dark) .stat-card[class*="from-amber-50"],
        html:not(.dark) .stat-card[class*="to-emerald-100"],
        html:not(.dark) .stat-card[class*="to-blue-100"],
        html:not(.dark) .stat-card[class*="to-walee-100"],
        html:not(.dark) .stat-card[class*="to-violet-100"],
        html:not(.dark) .stat-card[class*="to-cyan-100"],
        html:not(.dark) .stat-card[class*="to-amber-100"],
        html:not(.dark) .stat-card.bg-gradient-to-br {
            background: linear-gradient(to bottom right, rgb(245, 243, 255), rgb(237, 233, 254)) !important;
            background-image: none !important;
        }
        
        /* Forzar fondo del body y contenedor principal */
        html:not(.dark) body {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .min-h-screen {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .relative.max-w-\[90rem\] {
            background-color: transparent !important;
        }
        
        /* Forzar todos los elementos con fondo visible a violeta claro */
        html:not(.dark) div[class*="bg-"]:not([class*="dark:"]):not([class*="bg-violet"]):not([class*="bg-emerald"]):not([class*="bg-blue"]):not([class*="bg-walee"]):not([class*="bg-cyan"]):not([class*="bg-amber"]) {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .text-slate-300 {
            color: rgb(51, 65, 85) !important;
        }
        
        html:not(.dark) .text-slate-400 {
            color: rgb(71, 85, 105) !important;
        }
        
        html:not(.dark) .text-slate-500 {
            color: rgb(100, 116, 139) !important;
        }
        
        .bottom-15 {
            bottom: 4.75rem; /* 76px - separación intermedia entre las flechas */
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(213, 159, 59, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(213, 159, 59, 0.5);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .stat-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }
        
        .gradient-border {
            position: relative;
        }
        
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(135deg, #D59F3B 0%, #E0C684 50%, #D59F3B 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }
        
        .walee-gradient {
            background: linear-gradient(135deg, #D59F3B 0%, #E0C684 50%, #C78F2E 100%);
        }
        
        .number-animate {
            display: inline-block;
            transition: transform 0.3s ease;
        }
        
        .number-animate:hover {
            transform: scale(1.05);
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
<body class="bg-violet-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    @php
        use App\Models\Factura;
        use App\Models\Client;
        use App\Models\PropuestaPersonalizada;
        use Carbon\Carbon;
        
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        
        // Ingresos totales
        $ingresosTotales = (float) Factura::where('estado', 'pagada')->sum('total');
        
        // Ingresos del mes
        $ingresosMensual = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfMonth)
            ->sum('total');
        
        // Ingresos de la semana (se reinicia cada lunes)
        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);
        $ingresosSemana = (float) Factura::where('estado', 'pagada')
            ->whereBetween('fecha_emision', [$startOfWeek, $endOfWeek])
            ->sum('total');
        
        // Días transcurridos de la semana (para mostrar progreso)
        $diasTranscurridos = $today->diffInDays($startOfWeek) + 1;
        $diasTotalesSemana = 7;
        
        // Propuestas del mes
        $propuestasEnviadasMes = Client::where('estado', 'propuesta_enviada')
            ->where('updated_at', '>=', $startOfMonth)
            ->count();
        
        $propuestasPersonalizadasMes = Client::where('estado', 'propuesta_personalizada_enviada')
            ->where('updated_at', '>=', $startOfMonth)
            ->count();
        
        $totalPropuestasMes = $propuestasEnviadasMes + $propuestasPersonalizadasMes;
        
        // Propuestas hoy
        $propuestasEnviadasHoy = Client::where('estado', 'propuesta_enviada')
            ->whereDate('updated_at', $today)
            ->count();
        
        $propuestasPersonalizadasHoy = PropuestaPersonalizada::whereDate('created_at', $today)
            ->count();
        
        // Datos para la gráfica
        $startDate = $today->copy()->subDays(29);
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $chartLabels[] = $date->format('d/m');
            
            $ingresoDia = (float) Factura::where('estado', 'pagada')
                ->whereDate('fecha_emision', $date)
                ->sum('total');
            
            $chartData[] = $ingresoDia;
        }
        
        // Formatear números
        $formatNumber = function($number) {
            return number_format($number, 0, ',', '.');
        };
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Dashboard'; @endphp
            @include('partials.walee-navbar')
            
            <!-- User Avatar -->
            <div class="flex items-center justify-end mb-4">
                <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-slate-800 border border-violet-300 dark:border-slate-700 flex items-center justify-center shadow-sm dark:shadow-none">
                    <span class="text-sm font-medium text-walee-600 dark:text-walee-400">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ingresos
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Total Income -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-black dark:border-black p-6 hover:border-black dark:hover:border-black transition-all duration-300 shadow-sm dark:shadow-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-full">Total</span>
                            </div>
                            <p class="number-animate text-3xl font-bold text-slate-900 dark:text-white mb-1">₡{{ $formatNumber($ingresosTotales) }}</p>
                            <p class="text-sm text-slate-700 dark:text-slate-400">Facturas pagadas</p>
                        </div>
                    </div>
                    
                    <!-- Monthly Income -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-black dark:border-black p-6 hover:border-black dark:hover:border-black transition-all duration-300 shadow-sm dark:shadow-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-full">{{ now()->format('M') }}</span>
                            </div>
                            <p class="number-animate text-3xl font-bold text-slate-900 dark:text-white mb-1">₡{{ $formatNumber($ingresosMensual) }}</p>
                            <p class="text-sm text-slate-700 dark:text-slate-400">Este mes</p>
                        </div>
                    </div>
                    
                    <!-- Weekly Income -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-walee-50 to-walee-100/50 dark:from-walee-400/10 dark:to-walee-500/5 border border-black dark:border-black p-6 hover:border-black dark:hover:border-black transition-all duration-300 shadow-sm dark:shadow-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-walee-400/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-walee-100 dark:bg-walee-400/20 text-walee-700 dark:text-walee-400 rounded-full">Semana</span>
                            </div>
                            <p class="number-animate text-3xl font-bold text-slate-900 dark:text-white mb-1">₡{{ $formatNumber($ingresosSemana) }}</p>
                            <p class="text-sm text-slate-700 dark:text-slate-400 mb-2">Esta semana</p>
                            <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Desde {{ $startOfWeek->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Proposals Stats -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                    </svg>
                    Propuestas Enviadas
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Total Proposals -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-black dark:border-black p-6 hover:border-black dark:hover:border-black transition-all duration-300 shadow-sm dark:shadow-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-violet-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="number-animate text-4xl font-bold text-slate-900 dark:text-white mb-1">{{ $totalPropuestasMes }}</p>
                            <p class="text-sm text-slate-700 dark:text-slate-400">Total este mes</p>
                        </div>
                    </div>
                    
                    <!-- Standard Proposals Today -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-50 to-cyan-100/50 dark:from-cyan-500/10 dark:to-cyan-600/5 border border-black dark:border-black p-6 hover:border-black dark:hover:border-black transition-all duration-300 shadow-sm dark:shadow-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-cyan-100 dark:bg-cyan-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="number-animate text-4xl font-bold text-slate-900 dark:text-white mb-1">{{ $propuestasEnviadasHoy }}</p>
                            <p class="text-sm text-slate-700 dark:text-slate-400">Estándar hoy</p>
                        </div>
                    </div>
                    
                    <!-- Personalized Proposals Today -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-600/5 border border-black dark:border-black p-6 hover:border-black dark:hover:border-black transition-all duration-300 shadow-sm dark:shadow-none">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="number-animate text-4xl font-bold text-slate-900 dark:text-white mb-1">{{ $propuestasPersonalizadasHoy }}</p>
                            <p class="text-sm text-slate-700 dark:text-slate-400">Personalizadas hoy</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Chart Section -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Ingresos Últimos 30 Días
                </h2>
                
                <div class="rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black p-4 sm:p-6 animate-fade-in-up shadow-sm dark:shadow-none" style="animation-delay: 0.4s;">
                    <canvas id="incomeChart" class="w-full" style="max-height: 350px;"></canvas>
                </div>
            </section>
            
            <!-- Quick Actions -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Acciones Rápidas
                </h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('walee.clientes') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300">
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
                    
                    <a href="{{ route('walee.emails') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-blue-400/5 dark:hover:bg-blue-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Emails</span>
                    </a>
                    
                    <a href="{{ route('walee.tareas') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-violet-400/5 dark:hover:bg-violet-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Tareas</span>
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
                    
                    <button onclick="openWhatsAppModal()" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300 cursor-pointer">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">WhatsApp</span>
                    </button>
                </div>
            </section>
            
            <!-- WhatsApp Modal -->
            <div id="whatsappModal" class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 hidden items-center justify-center p-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-fade-in-up">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                Generar Mensaje WhatsApp
                            </h3>
                            <button onclick="closeWhatsAppModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Número de teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Número de Teléfono (con código de país, ej: 50612345678)
                            </label>
                            <input type="text" id="whatsappNumber" placeholder="50612345678" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                        </div>
                        
                        <!-- Prompt -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Prompt para generar el mensaje
                            </label>
                            <textarea id="whatsappPrompt" rows="4" placeholder="Ej: Escribe un mensaje profesional para contactar a un cliente sobre nuestros servicios de diseño web..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-none"></textarea>
                        </div>
                        
                        <!-- Botón generar -->
                        <button onclick="generateWhatsAppMessage()" id="generateBtn" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span id="generateBtnText">Generar Mensaje con IA</span>
                        </button>
                        
                        <!-- Mensaje generado -->
                        <div id="generatedMessageContainer" class="hidden">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Mensaje Generado
                            </label>
                            <div class="relative">
                                <textarea id="generatedMessage" rows="6" readonly class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white resize-none"></textarea>
                                <button onclick="copyMessage()" class="absolute top-2 right-2 p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Copiar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Botón abrir WhatsApp -->
                            <button onclick="openWhatsApp()" id="openWhatsAppBtn" class="w-full mt-4 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                Abrir en WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-6 border-t border-slate-200 dark:border-slate-800/50">
                <p class="text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>

    <script>
        // Chart.js Configuration
        const ctx = document.getElementById('incomeChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(213, 159, 59, 0.3)');
        gradient.addColorStop(1, 'rgba(213, 159, 59, 0.01)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Ingresos (₡)',
                    data: @json($chartData),
                    borderColor: '#D59F3B',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#D59F3B',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#D59F3B',
                        bodyColor: '#fff',
                        borderColor: 'rgba(213, 159, 59, 0.3)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '₡' + context.parsed.y.toLocaleString('es-CR');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 8
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(100, 116, 139, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return '₡' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return '₡' + (value / 1000).toFixed(0) + 'K';
                                }
                                return '₡' + value;
                            }
                        },
                        beginAtZero: true
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
    
    @include('partials.walee-support-button')
    @include('partials.walee-floating-chat')
    
    <script>
        // Dark/Light Mode Toggle
        function initDarkMode() {
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                html.classList.add('dark');
                updateIcons(true);
            } else {
                html.classList.remove('dark');
                updateIcons(false);
            }
        }
        
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                updateIcons(false);
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                updateIcons(true);
            }
        }
        
        function updateIcons(isDark) {
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            
            if (isDark) {
                sunIcon?.classList.add('hidden');
                moonIcon?.classList.remove('hidden');
            } else {
                sunIcon?.classList.remove('hidden');
                moonIcon?.classList.add('hidden');
            }
        }
        
        // Initialize on page load
        initDarkMode();
        
        // WhatsApp Modal Functions
        function openWhatsAppModal() {
            document.getElementById('whatsappModal').classList.remove('hidden');
            document.getElementById('whatsappModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeWhatsAppModal() {
            document.getElementById('whatsappModal').classList.add('hidden');
            document.getElementById('whatsappModal').classList.remove('flex');
            document.body.style.overflow = '';
            // Reset form
            document.getElementById('whatsappNumber').value = '';
            document.getElementById('whatsappPrompt').value = '';
            document.getElementById('generatedMessage').value = '';
            document.getElementById('generatedMessageContainer').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('whatsappModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeWhatsAppModal();
            }
        });
        
        async function generateWhatsAppMessage() {
            const prompt = document.getElementById('whatsappPrompt').value.trim();
            const generateBtn = document.getElementById('generateBtn');
            const generateBtnText = document.getElementById('generateBtnText');
            
            if (!prompt) {
                alert('Por favor, ingresa un prompt para generar el mensaje');
                return;
            }
            
            // Disable button and show loading
            generateBtn.disabled = true;
            generateBtnText.textContent = 'Generando...';
            generateBtn.classList.add('opacity-50', 'cursor-not-allowed');
            
            try {
                const response = await fetch('/walee/whatsapp/generar-mensaje', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ prompt: prompt })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('generatedMessage').value = data.message;
                    document.getElementById('generatedMessageContainer').classList.remove('hidden');
                    document.getElementById('generatedMessageContainer').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    alert('Error: ' + (data.message || 'No se pudo generar el mensaje'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al generar el mensaje. Por favor, intenta de nuevo.');
            } finally {
                // Re-enable button
                generateBtn.disabled = false;
                generateBtnText.textContent = 'Generar Mensaje con IA';
                generateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
        
        function copyMessage() {
            const message = document.getElementById('generatedMessage');
            message.select();
            document.execCommand('copy');
            
            // Show feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
            }, 2000);
        }
        
        function openWhatsApp() {
            const number = document.getElementById('whatsappNumber').value.trim();
            const message = document.getElementById('generatedMessage').value.trim();
            
            if (!number) {
                alert('Por favor, ingresa un número de teléfono');
                return;
            }
            
            if (!message) {
                alert('Por favor, genera un mensaje primero');
                return;
            }
            
            // Clean number (remove spaces, dashes, etc.)
            const cleanNumber = number.replace(/[\s\-\(\)]/g, '');
            
            // Encode message for URL
            const encodedMessage = encodeURIComponent(message);
            
            // Open WhatsApp Web/App
            const whatsappUrl = `https://wa.me/${cleanNumber}?text=${encodedMessage}`;
            window.open(whatsappUrl, '_blank');
        }
    </script>
</body>
</html>

