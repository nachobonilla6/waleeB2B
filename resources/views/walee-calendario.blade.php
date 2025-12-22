<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Calendario</title>
    <meta name="description" content="Calendario de Citas">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
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
        ::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(16, 185, 129, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $mes = request()->get('mes', now()->month);
        $ano = request()->get('ano', now()->year);
        $fechaActual = \Carbon\Carbon::create($ano, $mes, 1);
        $primerDia = $fechaActual->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $ultimoDia = $fechaActual->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
        
        // Obtener todas las citas del mes (incluyendo recurrentes)
        $citasBase = \App\Models\Cita::with('cliente')
            ->where(function($query) use ($fechaActual) {
                // Citas que empiezan en este mes
                $query->whereBetween('fecha_inicio', [
                    $fechaActual->copy()->startOfMonth()->startOfDay(),
                    $fechaActual->copy()->endOfMonth()->endOfDay()
                ])
                // O citas recurrentes que pueden aparecer en este mes
                ->orWhere(function($q) use ($fechaActual) {
                    $q->where('recurrencia', '!=', 'none')
                      ->where('fecha_inicio', '<=', $fechaActual->copy()->endOfMonth()->endOfDay())
                      ->where(function($subQ) use ($fechaActual) {
                          $subQ->whereNull('recurrencia_fin')
                                ->orWhere('recurrencia_fin', '>=', $fechaActual->copy()->startOfMonth()->startOfDay());
                      });
                });
            })
            ->get();
        
        // Generar citas recurrentes
        $citas = collect();
        foreach ($citasBase as $cita) {
            if ($cita->recurrencia === 'none') {
                // Cita normal, agregarla solo si está en el mes
                if ($cita->fecha_inicio->month == $mes && $cita->fecha_inicio->year == $ano) {
                    $citas->push($cita);
                }
            } else {
                // Cita recurrente, generar todas las ocurrencias del mes
                $fechaInicio = $cita->fecha_inicio->copy();
                $fechaFin = $cita->recurrencia_fin ? \Carbon\Carbon::parse($cita->recurrencia_fin) : $fechaActual->copy()->endOfMonth();
                $mesInicio = $fechaActual->copy()->startOfMonth();
                $mesFin = $fechaActual->copy()->endOfMonth();
                
                // Ajustar fecha inicio si es anterior al mes actual
                if ($fechaInicio->lt($mesInicio)) {
                    if ($cita->recurrencia === 'semanal') {
                        $semanas = ceil($mesInicio->diffInWeeks($fechaInicio));
                        $fechaInicio = $fechaInicio->copy()->addWeeks($semanas);
                    } elseif ($cita->recurrencia === 'mensual') {
                        $meses = ceil($mesInicio->diffInMonths($fechaInicio));
                        $fechaInicio = $fechaInicio->copy()->addMonths($meses);
                    } elseif ($cita->recurrencia === 'anual') {
                        $anos = ceil($mesInicio->diffInYears($fechaInicio));
                        $fechaInicio = $fechaInicio->copy()->addYears($anos);
                    }
                }
                
                // Generar ocurrencias hasta el fin del mes o hasta recurrencia_fin
                $fechaActualCita = $fechaInicio->copy();
                while ($fechaActualCita->lte($mesFin) && $fechaActualCita->lte($fechaFin)) {
                    if ($fechaActualCita->month == $mes && $fechaActualCita->year == $ano) {
                        $citaRecurrente = clone $cita;
                        $citaRecurrente->fecha_inicio = $fechaActualCita->copy();
                        if ($cita->fecha_fin) {
                            $duracion = $cita->fecha_inicio->diffInMinutes($cita->fecha_fin);
                            $citaRecurrente->fecha_fin = $fechaActualCita->copy()->addMinutes($duracion);
                        }
                        $citas->push($citaRecurrente);
                    }
                    
                    // Avanzar según el tipo de recurrencia
                    if ($cita->recurrencia === 'semanal') {
                        $fechaActualCita->addWeek();
                    } elseif ($cita->recurrencia === 'mensual') {
                        $fechaActualCita->addMonth();
                    } elseif ($cita->recurrencia === 'anual') {
                        $fechaActualCita->addYear();
                    }
                }
            }
        }
        
        $citas = $citas->groupBy(function($cita) {
            return $cita->fecha_inicio->format('Y-m-d');
        });
        
        // Obtener todas las tareas del mes (incluyendo recurrentes)
        $tareasBase = \App\Models\Tarea::with('lista')
            ->whereNotNull('fecha_hora')
            ->where(function($query) use ($fechaActual) {
                // Tareas que empiezan en este mes
                $query->whereBetween('fecha_hora', [
                    $fechaActual->copy()->startOfMonth()->startOfDay(),
                    $fechaActual->copy()->endOfMonth()->endOfDay()
                ])
                // O tareas recurrentes que pueden aparecer en este mes
                ->orWhere(function($q) use ($fechaActual) {
                    $q->where('recurrencia', '!=', 'none')
                      ->where('fecha_hora', '<=', $fechaActual->copy()->endOfMonth()->endOfDay())
                      ->where(function($subQ) use ($fechaActual) {
                          $subQ->whereNull('recurrencia_fin')
                                ->orWhere('recurrencia_fin', '>=', $fechaActual->copy()->startOfMonth()->startOfDay());
                      });
                });
            })
            ->get();
        
        // Generar tareas recurrentes
        $tareas = collect();
        foreach ($tareasBase as $tarea) {
            if ($tarea->recurrencia === 'none') {
                // Tarea normal, agregarla solo si está en el mes
                if ($tarea->fecha_hora && $tarea->fecha_hora->month == $mes && $tarea->fecha_hora->year == $ano) {
                    $tareas->push($tarea);
                }
            } else {
                // Tarea recurrente, generar todas las ocurrencias del mes
                $fechaInicio = $tarea->fecha_hora->copy();
                $fechaFin = $tarea->recurrencia_fin ? \Carbon\Carbon::parse($tarea->recurrencia_fin) : $fechaActual->copy()->endOfMonth();
                $mesInicio = $fechaActual->copy()->startOfMonth();
                $mesFin = $fechaActual->copy()->endOfMonth();
                
                // Ajustar fecha inicio si es anterior al mes actual
                if ($fechaInicio->lt($mesInicio)) {
                    if ($tarea->recurrencia === 'diaria') {
                        $dias = ceil($mesInicio->diffInDays($fechaInicio));
                        $fechaInicio = $fechaInicio->copy()->addDays($dias);
                    } elseif ($tarea->recurrencia === 'semanal') {
                        $semanas = ceil($mesInicio->diffInWeeks($fechaInicio));
                        $fechaInicio = $fechaInicio->copy()->addWeeks($semanas);
                    } elseif ($tarea->recurrencia === 'mensual') {
                        $meses = ceil($mesInicio->diffInMonths($fechaInicio));
                        $fechaInicio = $fechaInicio->copy()->addMonths($meses);
                    }
                }
                
                // Generar ocurrencias hasta el fin del mes o hasta recurrencia_fin
                $fechaActualTarea = $fechaInicio->copy();
                while ($fechaActualTarea->lte($mesFin) && $fechaActualTarea->lte($fechaFin)) {
                    if ($fechaActualTarea->month == $mes && $fechaActualTarea->year == $ano) {
                        $tareaRecurrente = clone $tarea;
                        $tareaRecurrente->fecha_hora = $fechaActualTarea->copy();
                        $tareas->push($tareaRecurrente);
                    }
                    
                    // Avanzar según el tipo de recurrencia
                    if ($tarea->recurrencia === 'diaria') {
                        $fechaActualTarea->addDay();
                    } elseif ($tarea->recurrencia === 'semanal') {
                        $fechaActualTarea->addWeek();
                    } elseif ($tarea->recurrencia === 'mensual') {
                        $fechaActualTarea->addMonth();
                    }
                }
            }
        }
        
        $tareas = $tareas->groupBy(function($tarea) {
            return $tarea->fecha_hora->format('Y-m-d');
        });
        
        $clientes = \App\Models\Cliente::orderBy('nombre_empresa')->get();
        $listas = \App\Models\Lista::orderBy('nombre')->get();
        $tiposExistentes = \App\Models\Tarea::select('tipo')->distinct()->whereNotNull('tipo')->pluck('tipo');
        
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        // Generar años (desde 2020 hasta 2030)
        $anos = range(2020, 2030);
        
        // Generar días del mes actual
        $diasDelMes = range(1, $fechaActual->copy()->endOfMonth()->day);
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/10 dark:bg-emerald-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = $meses[$mes] . ' ' . $ano; @endphp
            @include('partials.walee-navbar')
            
            <!-- Calendar Controls -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-3 sm:p-4 shadow-sm dark:shadow-none">
                    <div class="flex flex-col gap-3">
                        <!-- Navegación y Selectores de Fecha -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                            <div class="flex items-center justify-center sm:justify-start gap-2">
                                <a href="?mes={{ $fechaActual->copy()->subMonth()->month }}&ano={{ $fechaActual->copy()->subMonth()->year }}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                                <a href="?mes={{ now()->month }}&ano={{ now()->year }}" class="px-3 sm:px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all text-sm">
                                    Hoy
                                </a>
                                <a href="?mes={{ $fechaActual->copy()->addMonth()->month }}&ano={{ $fechaActual->copy()->addMonth()->year }}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            
                            <!-- Selectores de Fecha -->
                            <div class="flex items-center gap-2 justify-center sm:justify-end">
                                <select 
                                    id="selectDia"
                                    onchange="navegarAFecha()"
                                    class="px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                                >
                                    <option value="">Día</option>
                                    @foreach($diasDelMes as $dia)
                                        <option value="{{ $dia }}" {{ request()->get('dia') == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                                    @endforeach
                                </select>
                                
                                <select 
                                    id="selectMes"
                                    onchange="navegarAFecha()"
                                    class="px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                                >
                                    @foreach($meses as $numMes => $nombreMes)
                                        <option value="{{ $numMes }}" {{ $mes == $numMes ? 'selected' : '' }}>{{ $nombreMes }}</option>
                                    @endforeach
                                </select>
                                
                                <select 
                                    id="selectAno"
                                    onchange="navegarAFecha()"
                                    class="px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                                >
                                    @foreach($anos as $numAno)
                                        <option value="{{ $numAno }}" {{ $ano == $numAno ? 'selected' : '' }}>{{ $numAno }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Botones de Acción -->
                        <div class="flex gap-2 w-full sm:w-auto sm:ml-auto">
                            <button 
                                onclick="showNuevaCitaModal()"
                                class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs sm:text-sm">Cita</span>
                            </button>
                            <button 
                                onclick="showNuevaTareaModal()"
                                class="flex-1 sm:flex-none px-3 sm:px-4 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span class="text-xs sm:text-sm">Tarea</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl overflow-hidden shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.2s;">
                <!-- Days of Week Header -->
                <div class="grid grid-cols-7 border-b border-slate-200 dark:border-slate-700">
                    @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia)
                        <div class="p-2 sm:p-3 text-center text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 border-r border-slate-200 dark:border-slate-700 last:border-r-0">
                            <span class="hidden sm:inline">{{ $dia }}</span>
                            <span class="sm:hidden">{{ substr($dia, 0, 1) }}</span>
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
                            $citasDelDia = $citas->get($fechaKey, collect());
                            $tareasDelDia = $tareas->get($fechaKey, collect());
                            $totalItems = $citasDelDia->count() + $tareasDelDia->count();
                        @endphp
                        <div class="min-h-[80px] sm:min-h-[100px] border-r border-b border-slate-200 dark:border-slate-700 p-1 sm:p-2 {{ !$esMesActual ? 'bg-slate-50 dark:bg-slate-900/30' : '' }} {{ $esHoy ? 'bg-emerald-50 dark:bg-emerald-500/10' : '' }} hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs sm:text-sm font-medium {{ $esHoy ? 'text-emerald-600 dark:text-emerald-400' : ($esMesActual ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-600') }}">
                                    {{ $diaActual->day }}
                                </span>
                                @if($esHoy)
                                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500"></span>
                                @endif
                            </div>
                            <div class="space-y-0.5 sm:space-y-1">
                                @php
                                    $mostrados = 0;
                                    $maxMostrar = 2;
                                @endphp
                                @foreach($citasDelDia->take($maxMostrar) as $cita)
                                    @php $mostrados++; @endphp
                                    @php
                                        $colorCita = $cita->color ?? '#10b981';
                                        $colorHex = ltrim($colorCita, '#');
                                        $r = hexdec(substr($colorHex, 0, 2));
                                        $g = hexdec(substr($colorHex, 2, 2));
                                        $b = hexdec(substr($colorHex, 4, 2));
                                        $colorBg = $cita->estado === 'completada' 
                                            ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' 
                                            : "background-color: rgba({$r}, {$g}, {$b}, 0.2); color: {$colorCita};";
                                    @endphp
                                    <button 
                                        onclick="showCitaDetail({{ $cita->id }})"
                                        class="w-full text-left px-1 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-medium truncate transition-all hover:opacity-80 {{ $cita->estado === 'completada' ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' : '' }}"
                                        style="{{ $cita->estado !== 'completada' ? $colorBg : '' }}"
                                        title="{{ $cita->titulo }}"
                                    >
                                        <span class="hidden sm:inline">{{ $cita->fecha_inicio->format('H:i') }} - </span>{{ $cita->titulo }}
                                    </button>
                                @endforeach
                                @foreach($tareasDelDia->take($maxMostrar - $mostrados) as $tarea)
                                    @php $mostrados++; @endphp
                                    <button 
                                        onclick="showTareaDetail({{ $tarea->id }})"
                                        class="w-full text-left px-1 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-medium truncate transition-all hover:opacity-80 {{ $tarea->estado === 'completado' ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' : 'bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300' }}"
                                        title="{{ $tarea->texto }}"
                                    >
                                        <span class="hidden sm:inline">{{ $tarea->fecha_hora->format('H:i') }} - </span>{{ $tarea->texto }}
                                    </button>
                                @endforeach
                                @if($totalItems > $maxMostrar)
                                    <button 
                                        onclick="showDayItems('{{ $fechaKey }}')"
                                        class="w-full text-left px-1 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all"
                                    >
                                        +{{ $totalItems - $maxMostrar }} más
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
    
    <!-- Modal Nueva/Editar Cita -->
    <div id="citaModal" class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modalTitle">Nueva Cita</h3>
                <button onclick="closeCitaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="cita-form" class="p-4 space-y-4 overflow-y-auto max-h-[70vh]">
                <input type="hidden" name="cita_id" id="cita_id">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Título</label>
                    <input 
                        type="text" 
                        name="titulo" 
                        id="titulo"
                        required
                        placeholder="Título de la cita"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente</label>
                    <select 
                        name="cliente_id" 
                        id="cliente_id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                        <option value="">Sin cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Inicio</label>
                    <input 
                        type="datetime-local" 
                        name="fecha_inicio" 
                        id="fecha_inicio"
                        required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Fin (opcional)</label>
                    <input 
                        type="datetime-local" 
                        name="fecha_fin" 
                        id="fecha_fin"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ubicación (opcional)</label>
                    <input 
                        type="text" 
                        name="ubicacion" 
                        id="ubicacion"
                        placeholder="Ubicación de la cita"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción (opcional)</label>
                    <textarea 
                        name="descripcion" 
                        id="descripcion"
                        rows="3"
                        placeholder="Descripción de la cita..."
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-none"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recurrencia</label>
                    <select 
                        name="recurrencia" 
                        id="recurrencia"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                        onchange="toggleRecurrenciaFin()"
                    >
                        <option value="none">Sin recurrencia</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
                
                <div id="recurrencia_fin_container" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Fin de Recurrencia (opcional)</label>
                    <input 
                        type="datetime-local" 
                        name="recurrencia_fin" 
                        id="recurrencia_fin"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Color</label>
                    <div class="flex items-center gap-3">
                        <input 
                            type="color" 
                            name="color" 
                            id="color"
                            value="#10b981"
                            class="w-16 h-12 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer"
                        >
                        <input 
                            type="text" 
                            id="color_text"
                            value="#10b981"
                            placeholder="#10b981"
                            class="flex-1 px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                            onchange="document.getElementById('color').value = this.value"
                        >
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                    <select 
                        name="estado" 
                        id="estado"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                        <option value="programada">Programada</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all"
                    >
                        Guardar
                    </button>
                    <button 
                        type="button"
                        id="deleteBtn"
                        onclick="deleteCita()"
                        class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all hidden"
                    >
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Nueva/Editar Tarea -->
    <div id="tareaModal" class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="tareaModalTitle">Nueva Tarea</h3>
                <button onclick="closeTareaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="tarea-form" class="p-4 space-y-4 overflow-y-auto max-h-[70vh]">
                <input type="hidden" name="tarea_id" id="tarea_id">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Texto de la Tarea</label>
                    <input 
                        type="text" 
                        name="texto" 
                        id="tarea_texto"
                        required
                        placeholder="Descripción de la tarea"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lista</label>
                    <select 
                        name="lista_id" 
                        id="tarea_lista_id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
                    >
                        <option value="">Sin lista</option>
                        @foreach($listas as $lista)
                            <option value="{{ $lista->id }}">{{ $lista->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora</label>
                    <input 
                        type="datetime-local" 
                        name="fecha_hora" 
                        id="tarea_fecha_hora"
                        required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recurrencia</label>
                    <select 
                        name="recurrencia" 
                        id="tarea_recurrencia"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                        onchange="toggleTareaRecurrenciaFin()"
                    >
                        <option value="none">Sin recurrencia</option>
                        <option value="diaria">Diaria</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                    </select>
                </div>
                
                <div id="tarea_recurrencia_fin_container" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Fin de Recurrencia (opcional)</label>
                    <input 
                        type="datetime-local" 
                        name="recurrencia_fin" 
                        id="tarea_recurrencia_fin"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                    <input 
                        type="text" 
                        name="tipo" 
                        id="tarea_tipo"
                        list="tipos-list"
                        placeholder="Tipo de tarea (opcional)"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                    >
                    <datalist id="tipos-list">
                        @foreach($tiposExistentes as $tipo)
                            <option value="{{ $tipo }}">
                        @endforeach
                    </datalist>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all"
                    >
                        Guardar
                    </button>
                    <button 
                        type="button"
                        id="deleteTareaBtn"
                        onclick="deleteTarea()"
                        class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all hidden"
                    >
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Ver Cita -->
    <div id="citaDetailModal" class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Detalle de Cita</h3>
                <button onclick="closeCitaDetailModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="citaDetailContent" class="p-4 overflow-y-auto max-h-[70vh]">
                <!-- Content will be inserted here -->
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const citasData = @json($citas->flatten());
        const tareasData = @json($tareas->flatten());
        
        function navegarAFecha() {
            const dia = document.getElementById('selectDia').value;
            const mes = document.getElementById('selectMes').value;
            const ano = document.getElementById('selectAno').value;
            
            if (mes && ano) {
                let url = `?mes=${mes}&ano=${ano}`;
                if (dia) {
                    // Si se selecciona un día, navegar a ese día específico
                    // Por ahora solo cambiamos el mes, pero podríamos agregar scroll al día
                    url += `&dia=${dia}`;
                }
                window.location.href = url;
            }
        }
        
        // Actualizar días disponibles cuando cambia el mes
        document.getElementById('selectMes').addEventListener('change', function() {
            const mes = parseInt(this.value);
            const ano = parseInt(document.getElementById('selectAno').value);
            
            if (mes && ano) {
                // Calcular días del mes seleccionado
                const diasEnMes = new Date(ano, mes, 0).getDate();
                const selectDia = document.getElementById('selectDia');
                const diaActual = selectDia.value;
                
                // Limpiar opciones de días
                selectDia.innerHTML = '<option value="">Día</option>';
                
                // Agregar días del mes
                for (let i = 1; i <= diasEnMes; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = i;
                    if (diaActual && i == diaActual && i <= diasEnMes) {
                        option.selected = true;
                    }
                    selectDia.appendChild(option);
                }
            }
        });
        
        function toggleRecurrenciaFin() {
            const recurrencia = document.getElementById('recurrencia').value;
            const container = document.getElementById('recurrencia_fin_container');
            if (recurrencia !== 'none') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
        
        // Sincronizar color picker con input de texto
        document.getElementById('color').addEventListener('input', function(e) {
            document.getElementById('color_text').value = e.target.value;
        });
        
        document.getElementById('color_text').addEventListener('input', function(e) {
            if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                document.getElementById('color').value = e.target.value;
            }
        });
        
        function showNuevaCitaModal() {
            document.getElementById('modalTitle').textContent = 'Nueva Cita';
            document.getElementById('cita-form').reset();
            document.getElementById('cita_id').value = '';
            document.getElementById('deleteBtn').classList.add('hidden');
            document.getElementById('recurrencia_fin_container').classList.add('hidden');
            document.getElementById('color').value = '#10b981';
            document.getElementById('color_text').value = '#10b981';
            document.getElementById('citaModal').classList.remove('hidden');
        }
        
        function closeCitaModal() {
            document.getElementById('citaModal').classList.add('hidden');
        }
        
        function showCitaDetail(citaId) {
            const cita = citasData.find(c => c.id === citaId);
            if (!cita) return;
            
            const fechaInicio = new Date(cita.fecha_inicio).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const fechaFin = cita.fecha_fin ? new Date(cita.fecha_fin).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : null;
            
            document.getElementById('citaDetailContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">${cita.titulo}</h4>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs px-2 py-1 rounded-full ${cita.estado === 'completada' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400' : (cita.estado === 'cancelada' ? 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400')}">
                                ${cita.estado.charAt(0).toUpperCase() + cita.estado.slice(1)}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-slate-600 dark:text-slate-400">Inicio: ${fechaInicio}</p>
                                ${fechaFin ? `<p class="text-slate-600 dark:text-slate-400">Fin: ${fechaFin}</p>` : ''}
                            </div>
                        </div>
                        
                        ${cita.ubicacion ? `
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-slate-600 dark:text-slate-400">${cita.ubicacion}</p>
                            </div>
                        ` : ''}
                        
                        ${cita.cliente ? `
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-slate-600 dark:text-slate-400">${cita.cliente.nombre_empresa}</p>
                            </div>
                        ` : ''}
                        
                        ${cita.descripcion ? `
                            <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                <p class="text-slate-600 dark:text-slate-400 whitespace-pre-wrap">${cita.descripcion}</p>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="flex gap-2 pt-2">
                        <button onclick="editCita(${cita.id})" class="flex-1 px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all">
                            Editar
                        </button>
                        <button onclick="deleteCitaConfirm(${cita.id})" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-medium transition-all">
                            Eliminar
                        </button>
                    </div>
                </div>
            `;
            document.getElementById('citaDetailModal').classList.remove('hidden');
        }
        
        function closeCitaDetailModal() {
            document.getElementById('citaDetailModal').classList.add('hidden');
        }
        
        function editCita(citaId) {
            const cita = citasData.find(c => c.id === citaId);
            if (!cita) return;
            
            document.getElementById('modalTitle').textContent = 'Editar Cita';
            document.getElementById('cita_id').value = cita.id;
            document.getElementById('titulo').value = cita.titulo;
            document.getElementById('cliente_id').value = cita.cliente_id || '';
            document.getElementById('fecha_inicio').value = new Date(cita.fecha_inicio).toISOString().slice(0, 16);
            document.getElementById('fecha_fin').value = cita.fecha_fin ? new Date(cita.fecha_fin).toISOString().slice(0, 16) : '';
            document.getElementById('ubicacion').value = cita.ubicacion || '';
            document.getElementById('descripcion').value = cita.descripcion || '';
            document.getElementById('estado').value = cita.estado || 'programada';
            document.getElementById('recurrencia').value = cita.recurrencia || 'none';
            document.getElementById('recurrencia_fin').value = cita.recurrencia_fin ? new Date(cita.recurrencia_fin).toISOString().slice(0, 16) : '';
            document.getElementById('color').value = cita.color || '#10b981';
            document.getElementById('color_text').value = cita.color || '#10b981';
            toggleRecurrenciaFin();
            document.getElementById('deleteBtn').classList.remove('hidden');
            
            closeCitaDetailModal();
            document.getElementById('citaModal').classList.remove('hidden');
        }
        
        function deleteCitaConfirm(citaId) {
            if (!confirm('¿Estás seguro de eliminar esta cita?')) return;
            deleteCita(citaId);
        }
        
        async function deleteCita(citaId = null) {
            const id = citaId || document.getElementById('cita_id').value;
            if (!id) return;
            
            try {
                const response = await fetch(`/citas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al eliminar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        }
        
        function showDayItems(fecha) {
            const citasDelDia = citasData.filter(c => {
                const citaFecha = new Date(c.fecha_inicio).toISOString().split('T')[0];
                return citaFecha === fecha;
            });
            
            const tareasDelDia = tareasData.filter(t => {
                const tareaFecha = new Date(t.fecha_hora).toISOString().split('T')[0];
                return tareaFecha === fecha;
            });
            
            if (citasDelDia.length === 0 && tareasDelDia.length === 0) return;
            
            let content = '<div class="space-y-2">';
            
            citasDelDia.forEach(cita => {
                content += `
                    <button onclick="showCitaDetail(${cita.id})" class="w-full text-left p-3 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all border border-emerald-200 dark:border-emerald-500/20">
                        <p class="font-medium text-slate-900 dark:text-white">${cita.titulo}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">${new Date(cita.fecha_inicio).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })} - Cita</p>
                    </button>
                `;
            });
            
            tareasDelDia.forEach(tarea => {
                content += `
                    <button onclick="showTareaDetail(${tarea.id})" class="w-full text-left p-3 rounded-lg bg-violet-50 dark:bg-violet-500/10 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-all border border-violet-200 dark:border-violet-500/20">
                        <p class="font-medium text-slate-900 dark:text-white">${tarea.texto}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">${new Date(tarea.fecha_hora).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })} - Tarea</p>
                    </button>
                `;
            });
            
            content += '</div>';
            
            document.getElementById('citaDetailContent').innerHTML = `
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Eventos del ${new Date(fecha).toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</h3>
                    ${content}
                </div>
            `;
            document.getElementById('citaDetailModal').classList.remove('hidden');
        }
        
        function showDayCitas(fecha) {
            showDayItems(fecha);
        }
        
        // Cita Form Submit
        document.getElementById('cita-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const citaId = formData.get('cita_id');
            const url = citaId ? `/citas/${citaId}` : '/citas';
            const method = citaId ? 'PUT' : 'POST';
            
            const data = {
                titulo: formData.get('titulo'),
                cliente_id: formData.get('cliente_id') || null,
                fecha_inicio: formData.get('fecha_inicio'),
                fecha_fin: formData.get('fecha_fin') || null,
                ubicacion: formData.get('ubicacion') || null,
                descripcion: formData.get('descripcion') || null,
                estado: formData.get('estado'),
                recurrencia: formData.get('recurrencia') || 'none',
                recurrencia_fin: formData.get('recurrencia_fin') || null,
                color: formData.get('color') || '#10b981',
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
                    closeCitaModal();
                    location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Error al guardar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
        
        // Close modals on backdrop click
        document.getElementById('citaModal').addEventListener('click', function(e) {
            if (e.target === this) closeCitaModal();
        });
        
        document.getElementById('citaDetailModal').addEventListener('click', function(e) {
            if (e.target === this) closeCitaDetailModal();
        });
        
        // Tarea Functions
        function toggleTareaRecurrenciaFin() {
            const recurrencia = document.getElementById('tarea_recurrencia').value;
            const container = document.getElementById('tarea_recurrencia_fin_container');
            if (recurrencia !== 'none') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }
        
        function showNuevaTareaModal() {
            document.getElementById('tareaModalTitle').textContent = 'Nueva Tarea';
            document.getElementById('tarea-form').reset();
            document.getElementById('tarea_id').value = '';
            document.getElementById('deleteTareaBtn').classList.add('hidden');
            document.getElementById('tarea_recurrencia_fin_container').classList.add('hidden');
            document.getElementById('tareaModal').classList.remove('hidden');
        }
        
        function closeTareaModal() {
            document.getElementById('tareaModal').classList.add('hidden');
        }
        
        function showTareaDetail(tareaId) {
            const tarea = tareasData.find(t => t.id === tareaId);
            if (!tarea) return;
            
            const fechaHora = new Date(tarea.fecha_hora).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            document.getElementById('citaDetailContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">${tarea.texto}</h4>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs px-2 py-1 rounded-full ${tarea.estado === 'completado' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400' : 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400'}">
                                ${tarea.estado === 'completado' ? 'Completado' : 'Pendiente'}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-slate-600 dark:text-slate-400">Fecha y Hora: ${fechaHora}</p>
                        </div>
                        
                        ${tarea.lista ? `
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-slate-600 dark:text-slate-400">Lista: ${tarea.lista.nombre}</p>
                            </div>
                        ` : ''}
                        
                        ${tarea.tipo ? `
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <p class="text-slate-600 dark:text-slate-400">Tipo: ${tarea.tipo}</p>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="flex gap-2 pt-2">
                        <button onclick="editTarea(${tarea.id})" class="flex-1 px-4 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all"
                            Editar
                        </button>
                        <button onclick="deleteTareaConfirm(${tarea.id})" class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-medium transition-all">
                            Eliminar
                        </button>
                    </div>
                </div>
            `;
            document.getElementById('citaDetailModal').classList.remove('hidden');
        }
        
        function editTarea(tareaId) {
            const tarea = tareasData.find(t => t.id === tareaId);
            if (!tarea) return;
            
            document.getElementById('tareaModalTitle').textContent = 'Editar Tarea';
            document.getElementById('tarea_id').value = tarea.id;
            document.getElementById('tarea_texto').value = tarea.texto;
            document.getElementById('tarea_lista_id').value = tarea.lista_id || '';
            document.getElementById('tarea_fecha_hora').value = new Date(tarea.fecha_hora).toISOString().slice(0, 16);
            document.getElementById('tarea_tipo').value = tarea.tipo || '';
            document.getElementById('tarea_recurrencia').value = tarea.recurrencia || 'none';
            document.getElementById('tarea_recurrencia_fin').value = tarea.recurrencia_fin ? new Date(tarea.recurrencia_fin).toISOString().slice(0, 16) : '';
            toggleTareaRecurrenciaFin();
            document.getElementById('deleteTareaBtn').classList.remove('hidden');
            
            closeCitaDetailModal();
            document.getElementById('tareaModal').classList.remove('hidden');
        }
        
        function deleteTareaConfirm(tareaId) {
            if (!confirm('¿Estás seguro de eliminar esta tarea?')) return;
            deleteTarea(tareaId);
        }
        
        async function deleteTarea(tareaId = null) {
            const id = tareaId || document.getElementById('tarea_id').value;
            if (!id) return;
            
            try {
                const response = await fetch(`/tareas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Error al eliminar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        }
        
        // Tarea Form Submit
        document.getElementById('tarea-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const tareaId = formData.get('tarea_id');
            const url = tareaId ? `/tareas/${tareaId}` : '/tareas';
            const method = tareaId ? 'PUT' : 'POST';
            
            const data = {
                texto: formData.get('texto'),
                lista_id: formData.get('lista_id') || null,
                fecha_hora: formData.get('fecha_hora'),
                tipo: formData.get('tipo') || null,
                recurrencia: formData.get('recurrencia') || 'none',
                recurrencia_fin: formData.get('recurrencia_fin') || null,
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
                    closeTareaModal();
                    location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Error al guardar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
            }
        });
        
        // Close modals on backdrop click
        document.getElementById('tareaModal').addEventListener('click', function(e) {
            if (e.target === this) closeTareaModal();
        });
        
        // Close modals on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCitaModal();
                closeCitaDetailModal();
                closeTareaModal();
            }
        });
    </script>
    @include('partials.walee-support-button')
</body>
</html>

