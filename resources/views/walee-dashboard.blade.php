<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard</title>
    <meta name="description" content="Walee Dashboard - Panel de control">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
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
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dark .glass-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
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
<body class="bg-slate-950 text-white min-h-screen">
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
        
        // Ingresos de la semana
        $ingresosSemana = (float) Factura::where('estado', 'pagada')
            ->where('fecha_emision', '>=', $startOfWeek)
            ->sum('total');
        
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
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl walee-gradient flex items-center justify-center shadow-lg" style="animation: pulse-glow 3s infinite;">
                        <img src="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg" alt="Walee" class="w-10 h-10 rounded-xl object-cover">
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-walee-300 via-walee-400 to-walee-500 bg-clip-text text-transparent">
                            Walee
                        </h1>
                        <p class="text-sm text-slate-400">Dashboard · {{ now()->format('d M, Y') }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('walee') }}" class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl bg-walee-400/10 hover:bg-walee-400/20 text-walee-400 transition-all duration-300 border border-walee-400/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span class="text-sm font-medium">Chat</span>
                    </a>
                    
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition-all duration-300 border border-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="text-sm font-medium hidden sm:inline">Admin</span>
                    </a>
                    
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center">
                            <span class="text-sm font-medium text-walee-400">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ingresos
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Total Income -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 border border-emerald-500/20 p-6 hover:border-emerald-500/40 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-emerald-500/20 text-emerald-400 rounded-full">Total</span>
                            </div>
                            <p class="number-animate text-3xl font-bold text-white mb-1">₡{{ $formatNumber($ingresosTotales) }}</p>
                            <p class="text-sm text-slate-400">Facturas pagadas</p>
                        </div>
                    </div>
                    
                    <!-- Monthly Income -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500/10 to-blue-600/5 border border-blue-500/20 p-6 hover:border-blue-500/40 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-500/20 text-blue-400 rounded-full">{{ now()->format('M') }}</span>
                            </div>
                            <p class="number-animate text-3xl font-bold text-white mb-1">₡{{ $formatNumber($ingresosMensual) }}</p>
                            <p class="text-sm text-slate-400">Este mes</p>
                        </div>
                    </div>
                    
                    <!-- Weekly Income -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-walee-400/10 to-walee-500/5 border border-walee-400/20 p-6 hover:border-walee-400/40 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-walee-400/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-walee-400/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-walee-400/20 text-walee-400 rounded-full">Semana</span>
                            </div>
                            <p class="number-animate text-3xl font-bold text-white mb-1">₡{{ $formatNumber($ingresosSemana) }}</p>
                            <p class="text-sm text-slate-400">Esta semana</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Proposals Stats -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                    </svg>
                    Propuestas Enviadas
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Total Proposals -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500/10 to-violet-600/5 border border-violet-500/20 p-6 hover:border-violet-500/40 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-violet-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="number-animate text-4xl font-bold text-white mb-1">{{ $totalPropuestasMes }}</p>
                            <p class="text-sm text-slate-400">Total este mes</p>
                        </div>
                    </div>
                    
                    <!-- Standard Proposals Today -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500/10 to-cyan-600/5 border border-cyan-500/20 p-6 hover:border-cyan-500/40 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-cyan-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="number-animate text-4xl font-bold text-white mb-1">{{ $propuestasEnviadasHoy }}</p>
                            <p class="text-sm text-slate-400">Estándar hoy</p>
                        </div>
                    </div>
                    
                    <!-- Personalized Proposals Today -->
                    <div class="stat-card opacity-0 group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/10 to-amber-600/5 border border-amber-500/20 p-6 hover:border-amber-500/40 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="number-animate text-4xl font-bold text-white mb-1">{{ $propuestasPersonalizadasHoy }}</p>
                            <p class="text-sm text-slate-400">Personalizadas hoy</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Chart Section -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Ingresos Últimos 30 Días
                </h2>
                
                <div class="rounded-2xl bg-slate-900/50 border border-slate-800 p-4 sm:p-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <canvas id="incomeChart" class="w-full" style="max-height: 350px;"></canvas>
                </div>
            </section>
            
            <!-- Quick Actions -->
            <section class="mb-8">
                <h2 class="text-lg font-semibold text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Acciones Rápidas
                </h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('walee') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-walee-400/50 hover:bg-walee-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-300 group-hover:text-walee-400 transition-colors">Chat</span>
                    </a>
                    
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-blue-400/50 hover:bg-blue-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-300 group-hover:text-blue-400 transition-colors">Admin</span>
                    </a>
                    
                    <a href="/admin/clientes" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-emerald-400/50 hover:bg-emerald-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-300 group-hover:text-emerald-400 transition-colors">Clientes</span>
                    </a>
                    
                    <a href="/admin/facturas" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-violet-400/50 hover:bg-violet-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-violet-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-300 group-hover:text-violet-400 transition-colors">Facturas</span>
                    </a>
                </div>
            </section>
            
            <!-- Footer -->
            <footer class="text-center py-6 border-t border-slate-800/50">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · wesolutions.work
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
</body>
</html>

