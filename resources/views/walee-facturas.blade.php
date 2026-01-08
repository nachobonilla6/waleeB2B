<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee B2B - Finance Dashboard</title>
    <meta name="description" content="Walee B2B - Finance and Invoices Dashboard">
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
        .stat-card:nth-child(7) { animation-delay: 0.7s; }
        
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
            @php $pageTitle = 'Finance Dashboard'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Finance Dashboard
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Invoiced vs Expenses</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('walee.dashboard') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 hover:from-slate-300 hover:to-slate-400 dark:hover:from-slate-600 dark:hover:to-slate-700 text-slate-900 dark:text-white font-semibold rounded-xl sm:rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                    <a href="{{ route('walee.facturas.lista') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm shadow-sm hover:shadow">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>View Invoices</span>
                    </a>
                </div>
            </header>
            
            <!-- Stats Grid - 6 Widgets -->
            @php
                // Los gastos están en dólares, las facturas en colones
                $totalGastosEsteMesUSD = $totalGastosEsteMes ?? 0;
                $totalGastosEsteMesCRC = $totalGastosEsteMesUSD * $tasaCambio;
                $porcentajeGastos = $totalFacturadoEsteMes > 0 ? ($totalGastosEsteMesCRC / $totalFacturadoEsteMes) * 100 : 0;
                $diferencia = $totalFacturadoEsteMes - $totalGastosEsteMesCRC;
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-5 mb-4 sm:mb-6">
                <!-- Total Facturas Pagadas Este Mes -->
                <a href="{{ route('walee.facturas.lista') }}" class="stat-card bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 rounded-lg sm:rounded-xl p-4 sm:p-5 md:p-6 shadow-lg dark:shadow-none hover:shadow-xl transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1.5 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1.5">Total Paid Invoices This Month</p>
                        <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 dark:text-white">{{ $facturasPagadasEsteMes ?? 0 }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">Invoices</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-blue-600 dark:text-blue-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('F Y') }}</span>
                    </div>
                </a>
                
                <!-- Total Expenses This Month -->
                <a href="{{ route('walee.gastos') }}" class="stat-card bg-gradient-to-br from-orange-50 to-orange-100/50 dark:from-orange-500/10 dark:to-orange-600/5 border border-orange-200 dark:border-orange-500/20 rounded-lg sm:rounded-xl p-4 sm:p-5 md:p-6 shadow-lg dark:shadow-none hover:shadow-xl transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-orange-500/20 dark:bg-orange-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1.5 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1.5">Total Expenses This Month</p>
                        <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 dark:text-white">${{ number_format($totalGastosEsteMes ?? 0, 2, '.', ',') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">₡{{ number_format(($totalGastosEsteMes ?? 0) * $tasaCambio, 2, '.', ',') }}</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-orange-600 dark:text-orange-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('F Y') }}</span>
                    </div>
                </a>
                
                <!-- Pérdidas Este Mes -->
                <div class="stat-card bg-gradient-to-br from-red-50 to-red-100/50 dark:from-red-500/10 dark:to-red-600/5 border border-red-200 dark:border-red-500/20 rounded-lg sm:rounded-xl p-4 sm:p-5 md:p-6 shadow-lg dark:shadow-none">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-red-500/20 dark:bg-red-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1.5 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1.5">Losses This Month</p>
                        <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-red-600 dark:text-red-400">${{ number_format(($perdidasEsteMes ?? 0) / $tasaCambio, 2, '.', ',') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">₡{{ number_format($perdidasEsteMes ?? 0, 2, '.', ',') }}</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-red-600 dark:text-red-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('F Y') }}</span>
                    </div>
                </div>
                
                <!-- Porcentaje de Pérdidas -->
                <div class="stat-card bg-gradient-to-br from-red-50 to-red-100/50 dark:from-red-500/10 dark:to-red-600/5 border border-red-200 dark:border-red-500/20 rounded-lg sm:rounded-xl p-4 sm:p-5 md:p-6 shadow-lg dark:shadow-none">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-red-500/20 dark:bg-red-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1.5 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1.5">% Losses</p>
                        <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-red-600 dark:text-red-400">{{ number_format($porcentajePerdidas ?? 0, 1) }}%</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-red-600 dark:text-red-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('F Y') }}</span>
                    </div>
                </div>
                
                <!-- Diferencia -->
                <div class="stat-card bg-gradient-to-br {{ $diferencia >= 0 ? 'from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border-emerald-200 dark:border-emerald-500/20' : 'from-red-50 to-red-100/50 dark:from-red-500/10 dark:to-red-600/5 border-red-200 dark:border-red-500/20' }} border rounded-lg sm:rounded-xl p-4 sm:p-5 md:p-6 shadow-lg dark:shadow-none">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg {{ $diferencia >= 0 ? 'bg-emerald-500/20 dark:bg-emerald-500/10' : 'bg-red-500/20 dark:bg-red-500/10' }} flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $diferencia >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1.5 sm:mb-2">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1.5">Difference</p>
                        <p class="text-2xl sm:text-3xl md:text-4xl font-bold {{ $diferencia >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $diferencia >= 0 ? '+' : '' }}${{ number_format($diferencia / $tasaCambio, 2, '.', ',') }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">₡{{ number_format($diferencia, 2, '.', ',') }}</p>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs {{ $diferencia >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('F Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Comparación y Diferencia -->
            @php
                // Los gastos están en dólares, las facturas en colones
                $totalGastosEsteMesUSD = \App\Models\Gasto::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total');
                $totalGastosEsteMesCRC = $totalGastosEsteMesUSD * $tasaCambio;
                $diferencia = $totalFacturadoEsteMes - $totalGastosEsteMesCRC;
                $porcentajeGastos = $totalFacturadoEsteMes > 0 ? ($totalGastosEsteMesCRC / $totalFacturadoEsteMes) * 100 : 0;
            @endphp
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-sm dark:shadow-none mb-6 sm:mb-8 animate-fade-in-up">
                <h3 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-4 sm:mb-6">Month Summary</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div class="text-center sm:text-left">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1">Difference</p>
                        <p class="text-xl sm:text-2xl font-bold {{ $diferencia >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $diferencia >= 0 ? '+' : '' }}${{ number_format($diferencia / $tasaCambio, 2, '.', ',') }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">
                            ₡{{ number_format($diferencia, 2, '.', ',') }}
                        </p>
                    </div>
                    <div class="text-center sm:text-left">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1">Expenses Percentage</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">
                            {{ number_format($porcentajeGastos, 1) }}%
                        </p>
                    </div>
                    <div class="text-center sm:text-left">
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1">Status</p>
                        <p class="text-xl sm:text-2xl font-bold {{ $diferencia >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $diferencia >= 0 ? '✓ Positive' : 'Negative' }}
                        </p>
                    </div>
                </div>
                
                <!-- Barra de progreso -->
                <div class="mt-4 sm:mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">Expenses vs Invoiced</span>
                        <span class="text-xs sm:text-sm font-medium text-slate-900 dark:text-white">{{ number_format($porcentajeGastos, 1) }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 sm:h-4 overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full transition-all duration-500"
                            style="width: {{ min($porcentajeGastos, 100) }}%"
                        ></div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfica de Gastos y Entradas -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-sm dark:shadow-none mb-6 sm:mb-8 animate-fade-in-up">
                <h3 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-4 sm:mb-6">Expenses vs Income This Month</h3>
                <div class="relative w-full" style="height: 300px;">
                    <canvas id="gastosEntradasChart"></canvas>
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
        // Datos para la gráfica de Gastos y Entradas
        @php
            // Obtener todos los días del mes actual
            $diasDelMes = [];
            $gastosPorDia = [];
            $entradasPorDia = [];
            
            $mesActual = now()->month;
            $añoActual = now()->year;
            $diasEnMes = now()->daysInMonth;
            
            for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                $fecha = \Carbon\Carbon::create($añoActual, $mesActual, $dia);
                $diasDelMes[] = $fecha->format('d/m');
                
                // Gastos del día
                $gastosDia = \App\Models\Gasto::whereDate('created_at', $fecha->format('Y-m-d'))
                    ->sum('total');
                $gastosPorDia[] = $gastosDia;
                
                // Entradas (facturas) del día
                $entradasDia = \App\Models\Factura::whereDate('created_at', $fecha->format('Y-m-d'))
                    ->sum('total');
                $entradasPorDia[] = $entradasDia;
            }
        @endphp
        
        const diasDelMes = @json($diasDelMes);
        const gastosPorDia = @json($gastosPorDia);
        const entradasPorDia = @json($entradasPorDia);
        
        // Configuración de la gráfica
        const isDarkMode = document.documentElement.classList.contains('dark');
        const ctx = document.getElementById('gastosEntradasChart');
        
        if (ctx) {
            window.gastosEntradasChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: diasDelMes,
                    datasets: [
                        {
                            label: 'Gastos',
                            data: gastosPorDia,
                            borderColor: isDarkMode ? 'rgb(249, 115, 22)' : 'rgb(249, 115, 22)',
                            backgroundColor: isDarkMode ? 'rgba(249, 115, 22, 0.1)' : 'rgba(249, 115, 22, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: isDarkMode ? 'rgb(249, 115, 22)' : 'rgb(249, 115, 22)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: isDarkMode ? 'rgb(249, 115, 22)' : 'rgb(249, 115, 22)',
                            pointHoverBorderWidth: 2
                        },
                        {
                            label: 'Entradas (Facturado)',
                            data: entradasPorDia,
                            borderColor: isDarkMode ? 'rgb(59, 130, 246)' : 'rgb(59, 130, 246)',
                            backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.1)' : 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: isDarkMode ? 'rgb(59, 130, 246)' : 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: isDarkMode ? 'rgb(59, 130, 246)' : 'rgb(59, 130, 246)',
                            pointHoverBorderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                                font: {
                                    size: 12
                                },
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? 'rgba(30, 41, 59, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                            titleColor: isDarkMode ? '#e2e8f0' : '#1e293b',
                            bodyColor: isDarkMode ? '#e2e8f0' : '#1e293b',
                            borderColor: isDarkMode ? 'rgba(148, 163, 184, 0.2)' : 'rgba(148, 163, 184, 0.2)',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ₡' + context.parsed.y.toLocaleString('es-CR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
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
                                callback: function(value) {
                                    return '₡' + value.toLocaleString('es-CR');
                                }
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
                                },
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Actualizar gráfica cuando cambie el modo oscuro
        const darkModeToggle = document.querySelector('[data-dark-mode-toggle]');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                setTimeout(() => {
                    if (ctx && window.gastosEntradasChartInstance) {
                        window.gastosEntradasChartInstance.destroy();
                        location.reload();
                    }
                }, 100);
            });
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>
