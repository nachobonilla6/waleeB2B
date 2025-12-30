<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Planeador de Publicidad - {{ $cliente->nombre_empresa }}</title>
    <meta name="description" content="Planeador de Publicidad para {{ $cliente->nombre_empresa }}">
    <meta name="theme-color" content="#8b5cf6">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
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
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(139, 92, 246, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(139, 92, 246, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $vista = request()->get('vista', 'mensual');
        $mes = request()->get('mes', now()->month);
        $ano = request()->get('ano', now()->year);
        
        // Si es vista semanal, calcular la semana
        if ($vista === 'semanal') {
            $semanaParam = request()->get('semana');
            if ($semanaParam && strpos($semanaParam, '-') !== false) {
                list($anoSemana, $numSemana) = explode('-', $semanaParam);
                try {
                    $fechaSemana = \Carbon\Carbon::now()->setISODate((int)$anoSemana, (int)$numSemana);
                    $inicioSemana = $fechaSemana->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                    $finSemana = $fechaSemana->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                } catch (\Exception $e) {
                    $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                    $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                }
            } else {
                $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
            }
        }
        
        $fechaActual = \Carbon\Carbon::create($ano, $mes, 1);
        $primerDia = $fechaActual->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $ultimoDia = $fechaActual->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
        
        // Determinar rango de fechas según la vista
        if ($vista === 'semanal') {
            $fechaInicio = $inicioSemana->copy()->startOfDay();
            $fechaFin = $finSemana->copy()->endOfDay();
        } else {
            $fechaInicio = $fechaActual->copy()->startOfMonth()->startOfDay();
            $fechaFin = $fechaActual->copy()->endOfMonth()->endOfDay();
        }
        
        // Obtener eventos de publicidad del cliente
        $eventosBase = \App\Models\PublicidadEvento::where('cliente_id', $cliente->id)
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    $q->where('recurrencia', '!=', 'none')
                      ->where('fecha_inicio', '<=', $fechaFin)
                      ->where(function($subQ) use ($fechaInicio) {
                          $subQ->whereNull('recurrencia_fin')
                                ->orWhere('recurrencia_fin', '>=', $fechaInicio);
                      });
                });
            })
            ->get();
        
        // Generar eventos recurrentes (similar a citas)
        $eventos = collect();
        foreach ($eventosBase as $evento) {
            if ($evento->recurrencia === 'none') {
                if ($vista === 'semanal') {
                    if ($evento->fecha_inicio->gte($inicioSemana) && $evento->fecha_inicio->lte($finSemana)) {
                        $eventos->push($evento);
                    }
                } else {
                    if ($evento->fecha_inicio->month == $mes && $evento->fecha_inicio->year == $ano) {
                        $eventos->push($evento);
                    }
                }
            } else {
                // Lógica de recurrencia simplificada
                $fechaInicioRec = $evento->fecha_inicio->copy();
                $fechaFinRec = $evento->recurrencia_fin ? \Carbon\Carbon::parse($evento->recurrencia_fin) : ($vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth());
                $periodoInicio = $vista === 'semanal' ? $inicioSemana : $fechaActual->copy()->startOfMonth();
                $periodoFin = $vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth();
                
                if ($fechaInicioRec->lt($periodoInicio)) {
                    if ($evento->recurrencia === 'semanal') {
                        $semanas = ceil($periodoInicio->diffInWeeks($fechaInicioRec));
                        $fechaInicioRec = $fechaInicioRec->copy()->addWeeks($semanas);
                    } elseif ($evento->recurrencia === 'mensual') {
                        $meses = ceil($periodoInicio->diffInMonths($fechaInicioRec));
                        $fechaInicioRec = $fechaInicioRec->copy()->addMonths($meses);
                    }
                }
                
                $fechaActualEvento = $fechaInicioRec->copy();
                while ($fechaActualEvento->lte($periodoFin) && $fechaActualEvento->lte($fechaFinRec)) {
                    if ($fechaActualEvento->gte($evento->fecha_inicio->copy()->startOfDay())) {
                        $condicionFecha = $vista === 'semanal' ? true : ($fechaActualEvento->month == $mes && $fechaActualEvento->year == $ano);
                        if ($condicionFecha) {
                            $eventoRecurrente = clone $evento;
                            $eventoRecurrente->fecha_inicio = $fechaActualEvento->copy();
                            if ($evento->fecha_fin) {
                                $duracion = $evento->fecha_inicio->diffInMinutes($evento->fecha_fin);
                                $eventoRecurrente->fecha_fin = $fechaActualEvento->copy()->addMinutes($duracion);
                            }
                            $eventos->push($eventoRecurrente);
                        }
                    }
                    
                    if ($evento->recurrencia === 'semanal') {
                        $fechaActualEvento->addWeek();
                    } elseif ($evento->recurrencia === 'mensual') {
                        $fechaActualEvento->addMonth();
                    } elseif ($evento->recurrencia === 'anual') {
                        $fechaActualEvento->addYear();
                    } else {
                        break;
                    }
                }
            }
        }
        
        $eventos = $eventos->groupBy(function($evento) {
            return $evento->fecha_inicio->format('Y-m-d');
        });
        
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $anos = range(2020, 2030);
        $diasDelMes = range(1, $fechaActual->copy()->endOfMonth()->day);
        
        $tiposPublicidad = ['post', 'historia', 'reel', 'anuncio', 'video', 'carousel'];
        $plataformas = ['facebook', 'instagram', 'tiktok', 'twitter', 'linkedin', 'youtube'];
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Planeador de Publicidad - ' . $cliente->nombre_empresa . ' · ' . $meses[$mes] . ' ' . $ano; @endphp
            @include('partials.walee-navbar')
            
            <!-- Cliente Info -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="rounded-xl bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-violet-900 dark:text-violet-300">{{ $cliente->nombre_empresa }}</h3>
                            <p class="text-sm text-violet-700 dark:text-violet-400">Planeador de Publicidad</p>
                        </div>
                    </div>
                    <a href="{{ route('walee.calendario') }}" class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium text-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
            
            <!-- Layout con Sidebar -->
            <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                <!-- Sidebar -->
                <div class="hidden md:flex md:flex-col md:w-64 lg:w-72 flex-shrink-0 gap-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 shadow-sm dark:shadow-none sticky top-6">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Controles</h3>
                        
                        <!-- Selector de Vista -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Vista</label>
                            <div class="flex gap-2">
                                <a href="{{ route('walee.planeador.publicidad', $cliente->id) }}?mes={{ $mes }}&ano={{ $ano }}" class="flex-1 px-3 py-2 rounded-lg {{ !request()->has('vista') || request()->get('vista') !== 'semanal' ? 'bg-violet-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }} font-medium transition-all text-sm text-center">
                                    Mes
                                </a>
                                <a href="{{ route('walee.planeador.publicidad', $cliente->id) }}?vista=semanal&semana={{ request()->get('semana', now()->format('Y-W')) }}" class="flex-1 px-3 py-2 rounded-lg {{ request()->get('vista') === 'semanal' ? 'bg-violet-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }} font-medium transition-all text-sm text-center">
                                    Semana
                                </a>
                            </div>
                        </div>
                        
                        <!-- Navegación -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Navegación</label>
                            <div class="flex items-center justify-between gap-2">
                                @if($vista === 'semanal')
                                    @php
                                        $semanaAnterior = $inicioSemana->copy()->subWeek();
                                        $semanaSiguiente = $inicioSemana->copy()->addWeek();
                                        $semanaActual = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                                        $semanaAnteriorFormato = $semanaAnterior->format('Y') . '-' . $semanaAnterior->format('W');
                                        $semanaSiguienteFormato = $semanaSiguiente->format('Y') . '-' . $semanaSiguiente->format('W');
                                        $semanaActualFormato = $semanaActual->format('Y') . '-' . $semanaActual->format('W');
                                    @endphp
                                    <a href="?vista=semanal&semana={{ $semanaAnteriorFormato }}" class="flex-1 px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                    <a href="?vista=semanal&semana={{ $semanaActualFormato }}" class="flex-1 px-3 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all text-sm text-center">
                                        Esta Semana
                                    </a>
                                    <a href="?vista=semanal&semana={{ $semanaSiguienteFormato }}" class="flex-1 px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="?mes={{ $fechaActual->copy()->subMonth()->month }}&ano={{ $fechaActual->copy()->subMonth()->year }}" class="flex-1 px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                    <a href="?mes={{ now()->month }}&ano={{ now()->year }}" class="flex-1 px-3 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all text-sm text-center">
                                        Hoy
                                    </a>
                                    <a href="?mes={{ $fechaActual->copy()->addMonth()->month }}&ano={{ $fechaActual->copy()->addMonth()->year }}" class="flex-1 px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Selectores de Fecha -->
                        <div class="mb-4 space-y-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Fecha</label>
                            <select id="selectMes" onchange="navegarAFecha()" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                                @foreach($meses as $numMes => $nombreMes)
                                    <option value="{{ $numMes }}" {{ $mes == $numMes ? 'selected' : '' }}>{{ $nombreMes }}</option>
                                @endforeach
                            </select>
                            
                            <select id="selectAno" onchange="navegarAFecha()" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                                @foreach($anos as $numAno)
                                    <option value="{{ $numAno }}" {{ $ano == $numAno ? 'selected' : '' }}>{{ $numAno }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Botón Nueva Publicación -->
                        <div class="space-y-2">
                            <button onclick="showNuevoEventoModal()" class="w-full px-4 py-2.5 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span class="text-sm">Nueva Publicación</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Contenido Principal: Calendario -->
                <div class="flex-1 min-w-0">
                    <!-- Calendar Header -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 mb-4 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.2s;">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $meses[$mes] }} {{ $ano }}</h2>
                        </div>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl shadow-sm dark:shadow-none overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                        <!-- Days of Week Header -->
                        <div class="grid grid-cols-7 border-b border-slate-200 dark:border-slate-700">
                            @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia)
                                <div class="p-2 sm:p-3 text-center text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 border-r border-slate-200 dark:border-slate-700 last:border-r-0">
                                    {{ $dia }}
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Calendar Days -->
                        <div class="grid grid-cols-7">
                            @php
                                $diaActual = $primerDia->copy();
                                $hoy = now();
                            @endphp
                            @while($diaActual <= $ultimoDia)
                                @php
                                    $esHoy = $diaActual->isSameDay($hoy);
                                    $esMesActual = $diaActual->month == $mes;
                                    $fechaKey = $diaActual->format('Y-m-d');
                                    $eventosDelDia = $eventos->get($fechaKey, collect());
                                    $totalEventos = $eventosDelDia->count();
                                @endphp
                                <div class="block min-h-[120px] sm:min-h-[150px] md:min-h-[180px] border-r border-b border-slate-200 dark:border-slate-700 p-2 sm:p-3 {{ !$esMesActual ? 'bg-slate-50 dark:bg-slate-900/30' : '' }} {{ $esHoy ? 'bg-violet-50 dark:bg-violet-500/10' : '' }} hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-sm font-semibold {{ $esHoy ? 'text-violet-600 dark:text-violet-400' : ($esMesActual ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-600') }}">
                                            {{ $diaActual->day }}
                                        </span>
                                        @if($esHoy)
                                            <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        @foreach($eventosDelDia->take(5) as $evento)
                                            @php
                                                $colorEvento = $evento->color ?? '#8b5cf6';
                                                $colorHex = ltrim($colorEvento, '#');
                                                $r = hexdec(substr($colorHex, 0, 2));
                                                $g = hexdec(substr($colorHex, 2, 2));
                                                $b = hexdec(substr($colorHex, 4, 2));
                                                $colorBg = $evento->estado === 'publicado' 
                                                    ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' 
                                                    : "background-color: rgba({$r}, {$g}, {$b}, 0.25); color: {$colorEvento}; border-left: 3px solid {$colorEvento};";
                                            @endphp
                                            <button 
                                                onclick="event.preventDefault(); showEventoDetail({{ $evento->id }});"
                                                class="w-full text-left px-2 py-1 rounded text-xs font-medium transition-all hover:opacity-80 {{ $evento->estado === 'publicado' ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' : '' }}"
                                                style="{{ $evento->estado !== 'publicado' ? $colorBg : '' }}"
                                                title="{{ $evento->titulo }}"
                                            >
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[10px] font-semibold opacity-75">{{ $evento->fecha_inicio->format('H:i') }}</span>
                                                    <span class="flex-1 truncate">{{ $evento->titulo }}</span>
                                                </div>
                                            </button>
                                        @endforeach
                                        @if($totalEventos > 5)
                                            <button class="w-full text-left px-2 py-1 rounded text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                                +{{ $totalEventos - 5 }} más
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                @php $diaActual->addDay(); @endphp
                            @endwhile
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Nueva/Editar Evento -->
    <div id="eventoModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md md:max-w-2xl max-h-[85vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modalTitle">Nueva Publicación</h3>
                <button onclick="closeEventoModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="evento-form" class="p-4 md:p-6 space-y-4 overflow-y-auto max-h-[65vh]">
                <input type="hidden" name="evento_id" id="evento_id">
                <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Título</label>
                        <input type="text" name="titulo" id="titulo" required placeholder="Título de la publicación" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de Publicidad</label>
                        <select name="tipo_publicidad" id="tipo_publicidad" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                            <option value="">Seleccionar tipo</option>
                            @foreach($tiposPublicidad as $tipo)
                                <option value="{{ $tipo }}">{{ ucfirst($tipo) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Plataforma</label>
                        <select name="plataforma" id="plataforma" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                            <option value="">Seleccionar plataforma</option>
                            @foreach($plataformas as $plataforma)
                                <option value="{{ $plataforma }}">{{ ucfirst($plataforma) }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                        <select name="estado" id="estado" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                            <option value="programado">Programado</option>
                            <option value="publicado">Publicado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Inicio</label>
                        <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Fin (opcional)</label>
                        <input type="datetime-local" name="fecha_fin" id="fecha_fin" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" placeholder="Descripción de la publicación..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" id="color" value="#8b5cf6" class="w-16 h-10 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer">
                        <input type="text" name="color_text" id="color_text" value="#8b5cf6" class="flex-1 px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    </div>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all">
                        Guardar
                    </button>
                    <button type="button" onclick="closeEventoModal()" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function navegarAFecha() {
            const mes = document.getElementById('selectMes').value;
            const ano = document.getElementById('selectAno').value;
            window.location.href = `?mes=${mes}&ano=${ano}`;
        }
        
        function showNuevoEventoModal() {
            document.getElementById('modalTitle').textContent = 'Nueva Publicación';
            document.getElementById('evento-form').reset();
            document.getElementById('evento_id').value = '';
            document.getElementById('eventoModal').classList.remove('hidden');
        }
        
        function closeEventoModal() {
            document.getElementById('eventoModal').classList.add('hidden');
        }
        
        function showEventoDetail(eventoId) {
            // Implementar detalle del evento
            alert('Detalle del evento ' + eventoId);
        }
        
        // Sincronizar color picker
        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('color_text').value = e.target.value;
        });
        
        document.getElementById('color_text').addEventListener('input', function(e) {
            document.getElementById('color').value = e.target.value;
        });
        
        // Form submit
        document.getElementById('evento-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const eventoId = formData.get('evento_id');
            const url = eventoId ? `/publicidad-eventos/${eventoId}` : '/publicidad-eventos';
            const method = eventoId ? 'PUT' : 'POST';
            
            const data = {
                titulo: formData.get('titulo'),
                descripcion: formData.get('descripcion'),
                cliente_id: formData.get('cliente_id'),
                tipo_publicidad: formData.get('tipo_publicidad'),
                plataforma: formData.get('plataforma'),
                estado: formData.get('estado'),
                fecha_inicio: formData.get('fecha_inicio'),
                fecha_fin: formData.get('fecha_fin') || null,
                color: formData.get('color') || '#8b5cf6',
            };
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeEventoModal();
                    location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Error al guardar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
        
        // Close modal on backdrop click
        document.getElementById('eventoModal').addEventListener('click', function(e) {
            if (e.target === this) closeEventoModal();
        });
        
        // Close modal on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEventoModal();
            }
        });
    </script>
    @include('partials.walee-support-button')
</body>
</html>

