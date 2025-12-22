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
        
        // Obtener todas las citas del mes
        $citas = \App\Models\Cita::with('cliente')
            ->whereBetween('fecha_inicio', [
                $fechaActual->copy()->startOfMonth()->startOfDay(),
                $fechaActual->copy()->endOfMonth()->endOfDay()
            ])
            ->get()
            ->groupBy(function($cita) {
                return $cita->fecha_inicio->format('Y-m-d');
            });
        
        // Obtener todas las tareas del mes
        $tareas = \App\Models\Tarea::with('lista')
            ->whereNotNull('fecha_hora')
            ->whereBetween('fecha_hora', [
                $fechaActual->copy()->startOfMonth()->startOfDay(),
                $fechaActual->copy()->endOfMonth()->endOfDay()
            ])
            ->get()
            ->groupBy(function($tarea) {
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
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/10 dark:bg-emerald-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <header class="flex items-center justify-between mb-4 sm:mb-6 animate-fade-in-up">
                <div class="flex items-center gap-2 sm:gap-4 flex-1 min-w-0">
                    <a href="{{ route('walee.dashboard') }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-all shadow-sm dark:shadow-none flex-shrink-0">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-1 sm:gap-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-emerald-500 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="truncate">Calendario</span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $meses[$mes] }} {{ $ano }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    @include('partials.walee-dark-mode-toggle')
                </div>
            </header>
            
            <!-- Calendar Controls -->
            <div class="mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-3 sm:p-4 shadow-sm dark:shadow-none">
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
                        <div class="flex gap-2 w-full sm:w-auto">
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
                                    <button 
                                        onclick="showCitaDetail({{ $cita->id }})"
                                        class="w-full text-left px-1 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-medium truncate transition-all hover:opacity-80 {{ $cita->estado === 'completada' ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' : 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' }}"
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
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 focus:outline-none transition-all"
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
        
        function showNuevaCitaModal() {
            document.getElementById('modalTitle').textContent = 'Nueva Cita';
            document.getElementById('cita-form').reset();
            document.getElementById('cita_id').value = '';
            document.getElementById('deleteBtn').classList.add('hidden');
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
        function showNuevaTareaModal() {
            document.getElementById('tareaModalTitle').textContent = 'Nueva Tarea';
            document.getElementById('tarea-form').reset();
            document.getElementById('tarea_id').value = '';
            document.getElementById('deleteTareaBtn').classList.add('hidden');
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

