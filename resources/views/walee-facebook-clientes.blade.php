<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Dashboard Facebook</title>
    <meta name="description" content="Walee - Dashboard de Control Facebook">
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
        
        /* Forzar color amarillo en Dashboard Facebook */
        .dashboard-facebook-title {
            color: #eab308 !important;
            -webkit-text-fill-color: #eab308 !important;
            text-fill-color: #eab308 !important;
        }
        
        h1 .dashboard-facebook-title {
            color: #eab308 !important;
        }
        
        .dark h1 .dashboard-facebook-title {
            color: #eab308 !important;
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
            @php $pageTitle = 'Facebook - Dashboard'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 flex items-center gap-3">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="dashboard-facebook-title" style="color: #eab308 !important;">Dashboard Facebook</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                        Estadísticas y control de publicaciones
                    </p>
                </div>
                <a href="{{ route('walee.dashboard') }}" class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-all shadow-sm dark:shadow-none">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
                <!-- Total Publicaciones -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalPublicaciones) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Total Publicaciones</p>
                </div>
                
                <!-- Este Mes -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($publicacionesEsteMes) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Este Mes</p>
                </div>
                
                <!-- Esta Semana -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($publicacionesEstaSemana) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Esta Semana</p>
                </div>
                
                <!-- Clientes Activos -->
                <div class="stat-card rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-3 md:p-4" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-walee-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-walee-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-lg md:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesActivos) }}</p>
                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 mt-1">Clientes Activos</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <!-- Gráfico de Publicaciones -->
                <div class="lg:col-span-2 rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4 md:p-6 animate-fade-in-up" style="animation-delay: 0.5s;">
                    <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-4">Publicaciones - Últimos 15 Días</h3>
                    <div class="h-48 md:h-64">
                        <canvas id="publicacionesChart"></canvas>
                    </div>
                </div>
                
                <!-- Top Clientes -->
                <div class="rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4 md:p-6 animate-fade-in-up" style="animation-delay: 0.6s;">
                    <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white mb-4">Top Clientes</h3>
                    <div class="space-y-3">
                        @forelse($clientesTop as $index => $cliente)
                            @php
                                // Buscar el cliente en la tabla Cliente para obtener el ID del planeador
                                $clientePlaneador = \App\Models\Cliente::where('correo', $cliente->email)
                                    ->orWhere('nombre_empresa', 'like', '%' . $cliente->name . '%')
                                    ->first();
                                
                                // Si existe cliente en el planeador, construir URL del planeador
                                if ($clientePlaneador) {
                                    $semanaActual = now()->format('Y-W');
                                    $urlPlaneador = route('walee.planeador.publicidad', $clientePlaneador->id) . '?vista=semanal&semana=' . $semanaActual;
                                } else {
                                    // Si no existe, usar la ruta de detalle del cliente
                                    $urlPlaneador = route('walee.cliente.detalle', $cliente->id);
                                }
                            @endphp
                            <a href="{{ $urlPlaneador }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg bg-gradient-to-br from-blue-500/20 to-blue-600/10 border border-blue-500/30 flex items-center justify-center flex-shrink-0">
                                    @if($cliente->foto)
                                        <img src="/storage/{{ $cliente->foto }}" alt="{{ $cliente->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-sm md:text-base font-bold text-blue-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs md:text-sm font-medium text-slate-900 dark:text-white truncate">{{ $cliente->name }}</p>
                                    <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400">{{ $cliente->posts_count }} publicaciones</p>
                                </div>
                                <span class="text-xs md:text-sm font-bold text-walee-500">#{{ $index + 1 }}</span>
                            </a>
                        @empty
                            <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 text-center py-4">No hay clientes con publicaciones</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Publicaciones Recientes -->
            <div class="rounded-xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4 md:p-6 animate-fade-in-up" style="animation-delay: 0.7s;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white">Publicaciones Recientes</h3>
                    <a href="{{ route('walee.facebook.publicaciones') }}" class="text-xs md:text-sm text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Ver todas →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($publicacionesRecientes as $publicacion)
                        <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            @if($publicacion->image_url)
                                <img src="{{ $publicacion->image_url }}" alt="{{ $publicacion->title }}" class="w-12 h-12 md:w-16 md:h-16 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="text-xs md:text-sm font-semibold text-slate-900 dark:text-white line-clamp-1">{{ $publicacion->title }}</h4>
                                    <span class="text-[10px] md:text-xs text-slate-500 dark:text-slate-500 whitespace-nowrap">{{ $publicacion->created_at->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 line-clamp-2 mb-1">{{ $publicacion->content }}</p>
                                @if($publicacion->cliente)
                                    <a href="{{ route('walee.cliente.settings', $publicacion->cliente->id) }}" class="text-[10px] md:text-xs text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $publicacion->cliente->name }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400">No hay publicaciones recientes</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Paginación -->
                @if($publicacionesRecientes->hasPages())
                    <div class="mt-4 flex items-center justify-center">
                        {{ $publicacionesRecientes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        // Gráfico de Publicaciones - Últimos 15 Días (Estilo Montaña) - Basado en publicaciones reales
        const ctx = document.getElementById('publicacionesChart');
        if (ctx) {
            const publicacionesReales = @json($publicacionesPorDia);
            
            // Extraer fechas y datos de las publicaciones reales
            const fechas = publicacionesReales.map(item => {
                const fecha = new Date(item.dia);
                return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
            });
            const datos = publicacionesReales.map(item => item.total);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: fechas,
                    datasets: [{
                        label: 'Publicaciones',
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
                                    return 'Publicaciones: ' + context.parsed.y;
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
