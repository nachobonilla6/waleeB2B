<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard de Finanzas</title>
    <meta name="description" content="Walee - Dashboard de Finanzas y Facturas">
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
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }
        
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
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Dashboard de Finanzas'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Dashboard de Finanzas
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Resumen y estadísticas de facturas y cotizaciones</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('walee.dashboard') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 hover:from-slate-300 hover:to-slate-400 dark:hover:from-slate-600 dark:hover:to-slate-700 text-slate-900 dark:text-white font-semibold rounded-xl sm:rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                    <a href="{{ route('walee.facturas.lista') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm shadow-sm hover:shadow">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Ver Facturas</span>
                    </a>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Total Facturas -->
                <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Total Facturas</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalFacturas) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $facturasHoy }} hoy</span>
                    </div>
                </div>
                
                <!-- Facturas Este Mes -->
                <div class="stat-card bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Este Mes</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($facturasEsteMes) }}</p>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                        <span class="text-violet-600 dark:text-violet-400 font-medium">{{ $facturasEstaSemana }} esta semana</span>
                    </div>
                </div>
                
                <!-- Pendientes -->
                <div class="stat-card bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-600/5 border border-amber-200 dark:border-amber-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-amber-500/20 dark:bg-amber-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Pendientes</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($facturasPendientes) }}</p>
                    </div>
                </div>
                
                <!-- Pagadas -->
                <div class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Pagadas</p>
                        <p class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($facturasPagadas) }}</p>
                    </div>
                </div>
                
                <!-- Total Facturado -->
                <div class="stat-card bg-gradient-to-br from-walee-50 to-walee-100/50 dark:from-walee-500/10 dark:to-walee-600/5 border border-walee-200 dark:border-walee-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-walee-500/20 dark:bg-walee-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Total Facturado</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-slate-900 dark:text-white">₡{{ number_format($totalFacturado, 2, '.', ',') }}</p>
                    </div>
                </div>
                
                <!-- Pendiente por Cobrar -->
                <div class="stat-card bg-gradient-to-br from-red-50 to-red-100/50 dark:from-red-500/10 dark:to-red-600/5 border border-red-200 dark:border-red-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-red-500/20 dark:bg-red-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Por Cobrar</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-slate-900 dark:text-white">₡{{ number_format($totalPendiente, 2, '.', ',') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Charts and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Chart -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.5s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Facturas Últimos 15 Días</h2>
                    <div class="relative w-full" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <canvas id="facturasChart"></canvas>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.6s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Acciones Rápidas</h2>
                    <div class="space-y-2 sm:space-y-3">
                        <a href="{{ route('walee.facturas.crear') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/20 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Crear Factura</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">Crear una nueva factura manualmente</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('walee.facturas.crear-ai') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-purple-50 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 hover:bg-purple-100 dark:hover:bg-purple-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-purple-500/20 dark:bg-purple-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Crear con AI</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">Generar factura automáticamente con IA</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('walee.facturas.lista') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Ver Facturas</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">Gestionar todas las facturas</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        
                        <a href="{{ route('walee.cotizaciones') }}" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-all group">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-amber-500/20 dark:bg-amber-500/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate">Cotizaciones</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $totalCotizaciones }} total</p>
                            </div>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Facturas -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.7s">
                <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white">Facturas Recientes</h2>
                    <a href="{{ route('walee.facturas.lista') }}" class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline">Ver todas</a>
                </div>
                <div class="space-y-2 sm:space-y-3">
                    @forelse($facturasRecientes as $factura)
                        <div class="flex items-start gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500/30 hover:shadow-md dark:hover:shadow-lg transition-all group">
                            <a href="{{ route('walee.factura.ver', $factura->id) }}" class="flex items-start gap-2 sm:gap-3 flex-1 min-w-0 cursor-pointer">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-semibold text-xs sm:text-sm text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $factura->numero_factura }}
                                        </h3>
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
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">
                                        {{ $factura->cliente?->nombre_empresa ?? 'Sin cliente' }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">
                                        ₡{{ number_format($factura->total, 2, '.', ',') }} · {{ $factura->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                            <a href="{{ route('walee.factura.pdf', $factura->id) }}" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation();" class="flex-shrink-0 w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-red-100 dark:bg-red-500/20 hover:bg-red-200 dark:hover:bg-red-500/30 flex items-center justify-center transition-all group/pdf" title="Ver PDF">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-400 group-hover/pdf:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-slate-500 dark:text-slate-400">No hay facturas recientes</p>
                        </div>
                    @endforelse
                </div>
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
        // Chart data
        const ultimos15Dias = [];
        const facturasPorDia = [];
        
        @for ($i = 14; $i >= 0; $i--)
            @php
                $fecha = now()->subDays($i);
                $facturasDia = \App\Models\Factura::whereDate('created_at', $fecha->format('Y-m-d'))->count();
            @endphp
            ultimos15Dias.push('{{ $fecha->format("d/m") }}');
            facturasPorDia.push({{ $facturasDia }});
        @endfor
        
        // Chart configuration
        const isDarkMode = document.documentElement.classList.contains('dark');
        const ctx = document.getElementById('facturasChart');
        
        if (ctx) {
            window.facturasChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ultimos15Dias,
                    datasets: [{
                        label: 'Facturas',
                        data: facturasPorDia,
                        borderColor: isDarkMode ? 'rgb(99, 102, 241)' : 'rgb(99, 102, 241)',
                        backgroundColor: isDarkMode ? 'rgba(99, 102, 241, 0.1)' : 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: isDarkMode ? 'rgb(99, 102, 241)' : 'rgb(99, 102, 241)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: isDarkMode ? 'rgb(99, 102, 241)' : 'rgb(99, 102, 241)',
                        pointHoverBorderWidth: 2
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
                            backgroundColor: isDarkMode ? 'rgba(30, 41, 59, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                            titleColor: isDarkMode ? '#e2e8f0' : '#1e293b',
                            bodyColor: isDarkMode ? '#e2e8f0' : '#1e293b',
                            borderColor: isDarkMode ? 'rgba(148, 163, 184, 0.2)' : 'rgba(148, 163, 184, 0.2)',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Facturas: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: isDarkMode ? '#94a3b8' : '#64748b',
                                font: {
                                    size: 11
                                },
                                stepSize: 1
                            },
                            grid: {
                                color: isDarkMode ? 'rgba(148, 163, 184, 0.1)' : 'rgba(148, 163, 184, 0.1)',
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            ticks: {
                                color: isDarkMode ? '#94a3b8' : '#64748b',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
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
                    if (ctx && window.facturasChartInstance) {
                        window.facturasChartInstance.destroy();
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
