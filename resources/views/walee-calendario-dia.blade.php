<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $fecha->format('d M Y') }} - Calendario</title>
    @include('partials.walee-dark-mode-init')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        walee: {
                            50: '#fef3e2',
                            100: '#fde4b8',
                            200: '#fbc98a',
                            300: '#f9a95c',
                            400: '#f78a3a',
                            500: '#d59f3b',
                            600: '#b8852e',
                            700: '#9a6b26',
                            800: '#7c5220',
                            900: '#5e3a1a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
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
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        .walee-gradient {
            background: linear-gradient(135deg, #D59F3B 0%, #C78F2E 100%);
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(213, 159, 59, 0.4); }
            50% { box-shadow: 0 0 30px rgba(213, 159, 59, 0.6); }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/10 dark:bg-emerald-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = $meses[$fecha->month] . ' ' . $fecha->day . ', ' . $fecha->year; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header del Día -->
            <div class="mb-6 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                            {{ $fecha->format('l') }}, {{ $fecha->day }} de {{ $meses[$fecha->month] }} de {{ $fecha->year }}
                        </h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            {{ $items->count() }} {{ $items->count() === 1 ? 'evento' : 'eventos' }}
                        </p>
                    </div>
                    <a href="{{ route('walee.calendario', ['mes' => $fecha->month, 'ano' => $fecha->year]) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all border border-slate-300 dark:border-slate-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver al calendario</span>
                    </a>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl overflow-hidden shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.1s;">
                @if($items->count() > 0)
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($items as $index => $item)
                            @php
                                $hora = \Carbon\Carbon::parse($item['hora']);
                                $horaFin = $item['hora_fin'] ? \Carbon\Carbon::parse($item['hora_fin']) : null;
                                $color = $item['color'];
                                $colorHex = ltrim($color, '#');
                                $r = hexdec(substr($colorHex, 0, 2));
                                $g = hexdec(substr($colorHex, 2, 2));
                                $b = hexdec(substr($colorHex, 4, 2));
                            @endphp
                            <div class="p-4 sm:p-6 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors animate-fade-in-up" style="animation-delay: {{ $index * 0.05 }}s;">
                                <div class="flex gap-4">
                                    <!-- Timeline Line -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-3 h-3 rounded-full border-2 border-white dark:border-slate-800" style="background-color: {{ $color }}; box-shadow: 0 0 0 2px {{ $color }}40;"></div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 flex-1 bg-slate-200 dark:bg-slate-700 mt-2"></div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4 mb-2">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium" style="background-color: {{ $color }}20; color: {{ $color }};">
                                                        @if($item['tipo'] === 'cita')
                                                            📅 Cita
                                                        @else
                                                            ✅ Tarea
                                                        @endif
                                                    </span>
                                                    @if($item['estado'] === 'completada' || $item['estado'] === 'completado')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                                                            Completado
                                                        </span>
                                                    @endif
                                                </div>
                                                <h3 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-1">
                                                    {{ $item['titulo'] }}
                                                </h3>
                                                @if($item['tipo'] === 'cita' && $item['cliente'])
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                        Cliente: <span class="font-medium">{{ $item['cliente'] }}</span>
                                                    </p>
                                                @endif
                                                @if($item['tipo'] === 'tarea' && $item['tipo_tarea'])
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                        Tipo: <span class="font-medium">{{ $item['tipo_tarea'] }}</span>
                                                    </p>
                                                @endif
                                                @if($item['tipo'] === 'cita' && $item['ubicacion'])
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                        📍 {{ $item['ubicacion'] }}
                                                    </p>
                                                @endif
                                                @if(isset($item['descripcion']) && $item['descripcion'])
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">
                                                        {{ $item['descripcion'] }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            <!-- Time -->
                                            <div class="text-right flex-shrink-0">
                                                <div class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                                                    {{ $hora->format('H:i') }}
                                                </div>
                                                @if($horaFin)
                                                    <div class="text-sm text-slate-600 dark:text-slate-400">
                                                        - {{ $horaFin->format('H:i') }}
                                                    </div>
                                                    <div class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                                                        {{ $hora->diffInMinutes($horaFin) }} min
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No hay eventos este día</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                            No hay citas ni tareas programadas para este día.
                        </p>
                        <a href="{{ route('walee.calendario', ['mes' => $fecha->month, 'ano' => $fecha->year]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear evento
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @include('partials.walee-support-button')
</body>
</html>

