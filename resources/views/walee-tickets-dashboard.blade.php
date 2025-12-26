<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard Tickets</title>
    <meta name="description" content="Walee - Dashboard de Estadísticas de Tickets">
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
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-blue-400/10 dark:bg-blue-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Dashboard Tickets'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-3">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Dashboard Tickets
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        Estadísticas y control de tickets
                    </p>
                </div>
                <a href="{{ route('walee.tickets.tab', ['tab' => 'enviados']) }}" class="inline-flex items-center gap-2 px-3 md:px-4 py-2 md:py-2.5 rounded-lg md:rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all text-xs md:text-sm shadow-sm">
                    <span>Ver Tickets</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
                <!-- Total Tickets -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalTickets) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Total Tickets</p>
                </div>
                
                <!-- Enviados -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-amber-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($ticketsEnviados) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Enviados</p>
                </div>
                
                <!-- Recibidos -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($ticketsRecibidos) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Recibidos</p>
                </div>
                
                <!-- Resueltos -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($ticketsResueltos) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Resueltos</p>
                </div>
            </div>
            
            <!-- Secondary Stats -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-3 md:gap-4 mb-6 md:mb-8">
                <!-- Este Mes -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.5s;">
                    <p class="text-base md:text-xl font-bold text-slate-900 dark:text-white">{{ number_format($ticketsEsteMes) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Este Mes</p>
                </div>
                
                <!-- Esta Semana -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.6s;">
                    <p class="text-base md:text-xl font-bold text-slate-900 dark:text-white">{{ number_format($ticketsEstaSemana) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Esta Semana</p>
                </div>
                
                <!-- Hoy -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.7s;">
                    <p class="text-base md:text-xl font-bold text-slate-900 dark:text-white">{{ number_format($ticketsHoy) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Hoy</p>
                </div>
                
                <!-- Urgentes -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-red-200 dark:border-red-500/20 p-3 md:p-4" style="animation-delay: 0.8s;">
                    <p class="text-base md:text-xl font-bold text-red-600 dark:text-red-400">{{ number_format($ticketsUrgentes) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Urgentes</p>
                </div>
                
                <!-- Prioritarios -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-orange-200 dark:border-orange-500/20 p-3 md:p-4" style="animation-delay: 0.9s;">
                    <p class="text-base md:text-xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($ticketsPrioritarios) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Prioritarios</p>
                </div>
                
                <!-- A Discutir -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-purple-200 dark:border-purple-500/20 p-3 md:p-4" style="animation-delay: 1s;">
                    <p class="text-base md:text-xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($ticketsADiscutir) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">A Discutir</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Gráfico de Tickets -->
                <div class="lg:col-span-2 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4 md:p-6 animate-fade-in-up" style="animation-delay: 1.1s;">
                    <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-4">Tickets - Últimos 15 Días</h3>
                    <div class="h-48 md:h-64">
                        <canvas id="ticketsChart"></canvas>
                    </div>
                </div>
                
                <!-- Distribución por Estado -->
                <div class="rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4 md:p-6 animate-fade-in-up" style="animation-delay: 1.2s;">
                    <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-4">Por Estado</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-2 rounded-lg bg-amber-50 dark:bg-amber-500/10">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <span class="text-xs md:text-sm text-slate-700 dark:text-slate-300">Enviados</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">{{ $ticketsEnviados }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50 dark:bg-blue-500/10">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                <span class="text-xs md:text-sm text-slate-700 dark:text-slate-300">Recibidos</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">{{ $ticketsRecibidos }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-green-50 dark:bg-green-500/10">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="text-xs md:text-sm text-slate-700 dark:text-slate-300">Resueltos</span>
                            </div>
                            <span class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">{{ $ticketsResueltos }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tickets Recientes -->
            <div class="rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4 md:p-6 animate-fade-in-up" style="animation-delay: 1.3s;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white">Tickets Recientes</h3>
                    <a href="{{ route('walee.tickets.tab', ['tab' => 'todos']) }}" class="text-xs md:text-sm text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Ver todos →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($ticketsRecientes as $ticket)
                        <a href="{{ route('walee.tickets.tab', ['tab' => $ticket->estado]) }}" class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="text-xs md:text-sm font-semibold text-slate-900 dark:text-white line-clamp-1">{{ $ticket->asunto }}</h4>
                                    <span class="text-[10px] md:text-xs text-slate-500 dark:text-slate-500 whitespace-nowrap">{{ $ticket->created_at->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 line-clamp-2 mb-1">{{ $ticket->mensaje }}</p>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] md:text-xs font-medium
                                        @if($ticket->estado == 'enviado') bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-400
                                        @elseif($ticket->estado == 'recibido') bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-400
                                        @else bg-green-100 dark:bg-green-500/20 text-green-800 dark:text-green-400
                                        @endif">
                                        {{ ucfirst($ticket->estado) }}
                                    </span>
                                    @if($ticket->urgente)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] md:text-xs font-medium bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-400">Urgente</span>
                                    @endif
                                    @if($ticket->prioritario)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] md:text-xs font-medium bg-orange-100 dark:bg-orange-500/20 text-orange-800 dark:text-orange-400">Prioritario</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400">No hay tickets recientes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Gráfico de Tickets - Últimos 15 Días (Estilo Montaña)
        const ctx = document.getElementById('ticketsChart');
        if (ctx) {
            const datos = @json(collect($ticketsPorDia)->pluck('total'));
            const fechas = @json(collect($ticketsPorDia)->map(function($item) {
                $fecha = \Carbon\Carbon::createFromFormat('Y-m-d', $item['dia']);
                return $fecha->format('d/m');
            }));
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: fechas,
                    datasets: [{
                        label: 'Tickets',
                        data: datos,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderWidth: 2,
                        tension: 0.5,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: 'rgb(37, 99, 235)',
                        pointHoverBorderColor: '#fff'
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 12
                            },
                            bodyFont: {
                                size: 12
                            },
                            displayColors: false,
                            callbacks: {
                                title: function(context) {
                                    return 'Fecha: ' + context[0].label;
                                },
                                label: function(context) {
                                    return 'Tickets: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
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

