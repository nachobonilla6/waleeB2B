<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Análisis Emails vs Clientes</title>
    <meta name="description" content="Walee - Análisis de balance entre emails enviados y clientes agregados">
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
        // Configuración
        $emailsPorHora = 2; // 1 cada 30 minutos
        $horasPorDia = 24;
        $emailsPorDia = $emailsPorHora * $horasPorDia;
        $emailsPorMes = $emailsPorDia * 30;
        $periodosPorDia = $horasPorDia / 2; // Cada 2 horas
        
        // Escenarios
        $escenarios = [];
        for ($i = 1; $i <= 6; $i++) {
            $clientesPorDia = $i * 12;
            $ratio = $emailsPorDia / $clientesPorDia;
            
            $estado = '';
            $estadoColor = '';
            if ($ratio >= 2.0) {
                $estado = '✅ Sobran emails';
                $estadoColor = 'text-emerald-500';
            } elseif ($ratio >= 1.5) {
                $estado = '✅ Balanceado';
                $estadoColor = 'text-emerald-500';
            } elseif ($ratio >= 1.0) {
                $estado = '⚠️ Justo';
                $estadoColor = 'text-amber-500';
            } elseif ($ratio >= 0.8) {
                $estado = '⚠️ Crítico';
                $estadoColor = 'text-orange-500';
            } else {
                $estado = '❌ Insuficiente';
                $estadoColor = 'text-red-500';
            }
            
            // Simular backlog
            $backlog = 0;
            $backlogMaximo = 0;
            for ($dia = 1; $dia <= 7; $dia++) {
                $clientesNuevos = $clientesPorDia;
                $clientesPendientes = $backlog;
                $totalClientes = $clientesNuevos + $clientesPendientes;
                $emailsDisponibles = $emailsPorDia;
                
                if ($totalClientes <= $emailsDisponibles) {
                    $backlog = 0;
                } else {
                    $backlog = $totalClientes - $emailsDisponibles;
                }
                
                if ($backlog > $backlogMaximo) {
                    $backlogMaximo = $backlog;
                }
            }
            
            $escenarios[] = [
                'clientes_cada_2h' => $i,
                'clientes_por_dia' => $clientesPorDia,
                'ratio' => $ratio,
                'estado' => $estado,
                'estado_color' => $estadoColor,
                'backlog_maximo' => $backlogMaximo,
                'tiene_backlog' => $backlogMaximo > 0
            ];
        }
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-20 w-60 h-60 bg-blue-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-violet-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Análisis Emails vs Clientes'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Análisis: Emails vs Clientes
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">
                        Balance entre emails enviados y clientes agregados
                    </p>
                </div>
                <a href="{{ route('walee.clientes.dashboard') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg sm:rounded-xl transition-all flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="hidden sm:inline">Volver</span>
                </a>
            </header>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6 md:mb-8">
                <!-- Emails por día -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-none animate-fade-in-up">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1">Emails por día</p>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($emailsPorDia) }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">1 cada 30 minutos</p>
                    </div>
                </div>
                
                <!-- Emails por mes -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1">Emails por mes</p>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($emailsPorMes) }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">30 días</p>
                    </div>
                </div>
                
                <!-- Períodos por día -->
                <div class="bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-1">Períodos por día</p>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">{{ $periodosPorDia }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">Cada 2 horas</p>
                    </div>
                </div>
            </div>
            
            <!-- Chart -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-none mb-4 sm:mb-6 md:mb-8 animate-fade-in-up" style="animation-delay: 0.3s">
                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-4">Ratio de Emails por Cliente</h2>
                <div class="relative w-full" style="height: 300px;">
                    <canvas id="ratioChart"></canvas>
                </div>
            </div>
            
            <!-- Tabla de Escenarios -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-none mb-4 sm:mb-6 md:mb-8 animate-fade-in-up" style="animation-delay: 0.4s">
                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-4">Escenarios de Balance</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="text-left py-2 px-2 sm:px-4 font-semibold text-slate-700 dark:text-slate-300">Clientes/2h</th>
                                <th class="text-left py-2 px-2 sm:px-4 font-semibold text-slate-700 dark:text-slate-300">Clientes/día</th>
                                <th class="text-left py-2 px-2 sm:px-4 font-semibold text-slate-700 dark:text-slate-300">Ratio</th>
                                <th class="text-left py-2 px-2 sm:px-4 font-semibold text-slate-700 dark:text-slate-300">Estado</th>
                                <th class="text-left py-2 px-2 sm:px-4 font-semibold text-slate-700 dark:text-slate-300">Backlog</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($escenarios as $escenario)
                            <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="py-3 px-2 sm:px-4 font-medium text-slate-900 dark:text-white">{{ $escenario['clientes_cada_2h'] }}</td>
                                <td class="py-3 px-2 sm:px-4 text-slate-700 dark:text-slate-300">{{ $escenario['clientes_por_dia'] }}</td>
                                <td class="py-3 px-2 sm:px-4 text-slate-700 dark:text-slate-300">{{ number_format($escenario['ratio'], 2) }}</td>
                                <td class="py-3 px-2 sm:px-4">
                                    <span class="{{ $escenario['estado_color'] }} font-medium">{{ $escenario['estado'] }}</span>
                                </td>
                                <td class="py-3 px-2 sm:px-4">
                                    @if($escenario['tiene_backlog'])
                                        <span class="text-red-500 font-medium">{{ $escenario['backlog_maximo'] }} clientes</span>
                                    @else
                                        <span class="text-emerald-500 font-medium">Sin backlog</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Recomendaciones -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 dark:from-amber-500/10 dark:to-amber-600/5 border border-amber-200 dark:border-amber-500/20 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.5s">
                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Recomendaciones
                </h2>
                <div class="space-y-3 text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                    <div>
                        <p class="font-semibold mb-1">1. Para mantener balance saludable:</p>
                        <ul class="list-disc list-inside ml-2 space-y-1">
                            <li>Máximo 2-3 clientes cada 2 horas (24-36 clientes/día)</li>
                            <li>Ratio recomendado: 1.5 - 2.5 emails por cliente</li>
                            <li>Margen de seguridad: Mantener al menos 1.5 emails por cliente</li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-semibold mb-1">2. Si se agregan más de 3 clientes cada 2 horas:</p>
                        <ul class="list-disc list-inside ml-2 space-y-1">
                            <li>Aumentar frecuencia de envío (cada 20-25 minutos)</li>
                            <li>O reducir la cantidad de clientes agregados</li>
                            <li>Implementar sistema de priorización</li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-semibold mb-1">3. Fórmula para calcular:</p>
                        <p class="ml-2 font-mono bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                            Ratio = (48 emails/día) / (Clientes cada 2h × 12)
                        </p>
                        <p class="ml-2 mt-1">Si Ratio &lt; 1.5 → ⚠️ Riesgo | Si Ratio &lt; 1.0 → ❌ Insuficiente</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Chart configuration
        const ctxRatio = document.getElementById('ratioChart');
        if (ctxRatio) {
            const escenarios = @json($escenarios);
            const labels = escenarios.map(e => e.clientes_cada_2h + ' clientes/2h');
            const ratios = escenarios.map(e => parseFloat(e.ratio.toFixed(2)));
            
            new Chart(ctxRatio, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Emails por Cliente',
                        data: ratios,
                        backgroundColor: ratios.map(r => {
                            if (r >= 2.0) return 'rgba(16, 185, 129, 0.8)';
                            if (r >= 1.5) return 'rgba(16, 185, 129, 0.6)';
                            if (r >= 1.0) return 'rgba(251, 191, 36, 0.8)';
                            if (r >= 0.8) return 'rgba(249, 115, 22, 0.8)';
                            return 'rgba(239, 68, 68, 0.8)';
                        }),
                        borderColor: ratios.map(r => {
                            if (r >= 2.0) return 'rgb(16, 185, 129)';
                            if (r >= 1.5) return 'rgb(16, 185, 129)';
                            if (r >= 1.0) return 'rgb(251, 191, 36)';
                            if (r >= 0.8) return 'rgb(249, 115, 22)';
                            return 'rgb(239, 68, 68)';
                        }),
                        borderWidth: 2
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
                            callbacks: {
                                label: function(context) {
                                    return 'Ratio: ' + context.parsed.y.toFixed(2) + ' emails por cliente';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#94a3b8' : '#64748b',
                                stepSize: 0.5
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
    </script>
    @include('partials.walee-support-button')
</body>
</html>

