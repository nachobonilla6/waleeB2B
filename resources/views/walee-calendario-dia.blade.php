<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $fecha->format('d M Y') }} - Calendario</title>
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
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
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = $meses[$fecha->month] . ' ' . $fecha->day . ', ' . $fecha->year; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header del Día -->
            <div class="mb-6 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                            {{ $fecha->format('l') }}, {{ $fecha->day }} de {{ $meses[$fecha->month] }} de {{ $fecha->year }}
                        </h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            {{ $items->count() }} {{ $items->count() === 1 ? 'evento' : 'eventos' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button 
                            onclick="showNuevaCitaModal()"
                            class="flex-1 sm:flex-none px-3 sm:px-4 py-2.5 sm:py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-medium transition-all flex items-center justify-center gap-2 text-sm sm:text-base"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Nueva Cita</span>
                        </button>
                        <button 
                            onclick="showNuevaTareaModal()"
                            class="flex-1 sm:flex-none px-3 sm:px-4 py-2.5 sm:py-2 rounded-xl bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all flex items-center justify-center gap-2 text-sm sm:text-base"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Nueva Tarea</span>
                        </button>
                        <button 
                            onclick="showNuevaNotaModal()"
                            class="flex-1 sm:flex-none px-3 sm:px-4 py-2.5 sm:py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all flex items-center justify-center gap-2 text-sm sm:text-base"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Nueva Nota</span>
                        </button>
                        <a href="{{ route('walee.calendario', ['mes' => $fecha->month, 'ano' => $fecha->year]) }}" class="flex-shrink-0 px-3 sm:px-4 py-2.5 sm:py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all border border-slate-300 dark:border-slate-700 flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="hidden sm:inline">Volver</span>
                        </a>
                    </div>
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
                                                        @elseif($item['tipo'] === 'tarea')
                                                            ✅ Tarea
                                                        @elseif($item['tipo'] === 'nota')
                                                            📝 Nota
                                                        @endif
                                                    </span>
                                                    @if($item['estado'] === 'completada' || $item['estado'] === 'completado')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                                                            Completado
                                                        </span>
                                                    @endif
                                                </div>
                                                <a href="{{ $item['tipo'] === 'cita' ? route('walee.cita.detalle', $item['id']) : ($item['tipo'] === 'tarea' ? route('walee.tarea.detalle', $item['id']) : '#') }}" class="block" {{ $item['tipo'] === 'nota' ? 'onclick="event.preventDefault(); editNota(' . $item['id'] . ');"' : '' }}>
                                                    <h3 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-1 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                                        {{ $item['titulo'] }}
                                                    </h3>
                                                </a>
                                                @if($item['tipo'] === 'cita' && isset($item['cliente_id']) && $item['cliente_id'])
                                                    @if(isset($item['cliente']) && $item['cliente'])
                                                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1 flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                            </svg>
                                                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $item['cliente'] }}</span>
                                                        </p>
                                                    @else
                                                        @php
                                                            $cliente = \App\Models\Cliente::find($item['cliente_id']);
                                                        @endphp
                                                        @if($cliente && $cliente->nombre_empresa)
                                                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-1 flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                                </svg>
                                                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $cliente->nombre_empresa }}</span>
                                                            </p>
                                                        @else
                                                            <p class="text-sm text-slate-500 dark:text-slate-500 mb-1 italic">
                                                                Cliente ID: {{ $item['cliente_id'] }} (no encontrado)
                                                            </p>
                                                        @endif
                                                    @endif
                                                @endif
                                                @if($item['tipo'] === 'tarea' && isset($item['tipo_tarea']) && $item['tipo_tarea'])
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                        Tipo: <span class="font-medium">{{ $item['tipo_tarea'] }}</span>
                                                    </p>
                                                @endif
                                                @if($item['tipo'] === 'tarea' && isset($item['lista']) && $item['lista'])
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                        Lista: <span class="font-medium">{{ $item['lista'] }}</span>
                                                    </p>
                                                @endif
                                                @if($item['tipo'] === 'nota')
                                                    @if(isset($item['tipo_nota']))
                                                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                            Tipo: <span class="font-medium uppercase">{{ $item['tipo_nota'] === 'note' ? 'Nota' : ($item['tipo_nota'] === 'call' ? 'Llamada' : ($item['tipo_nota'] === 'meeting' ? 'Reunión' : 'Email')) }}</span>
                                                        </p>
                                                    @endif
                                                    @if(isset($item['cliente']) && $item['cliente'])
                                                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                            🏢 Cliente: <span class="font-medium">{{ $item['cliente'] }}</span>
                                                        </p>
                                                    @endif
                                                    @if(isset($item['pinned']) && $item['pinned'])
                                                        <p class="text-sm text-blue-600 dark:text-blue-400 mb-1">
                                                            📌 Nota fijada
                                                        </p>
                                                    @endif
                                                @endif
                                                @if($item['tipo'] === 'cita' && isset($item['ubicacion']) && $item['ubicacion'])
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
                                            
                                            <!-- Time and Actions -->
                                            <div class="text-right flex-shrink-0 flex flex-col items-end gap-2">
                                                <div>
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
                                                <div class="flex items-center gap-1">
                                                    <button 
                                                        onclick="{{ $item['tipo'] === 'cita' ? 'editCita(' . $item['id'] . ')' : ($item['tipo'] === 'tarea' ? 'editTarea(' . $item['id'] . ')' : 'editNota(' . $item['id'] . ')') }}"
                                                        class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-400 transition-all"
                                                        title="Editar"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button 
                                                        onclick="{{ $item['tipo'] === 'cita' ? 'deleteCitaConfirm(' . $item['id'] . ')' : ($item['tipo'] === 'tarea' ? 'deleteTareaConfirm(' . $item['id'] . ')' : 'deleteNota(' . $item['id'] . ')') }}"
                                                        class="p-2 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-500/20 dark:hover:bg-red-500/30 text-red-600 dark:text-red-400 transition-all"
                                                        title="Eliminar"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
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
    
    <!-- Modal Nueva/Editar Cita -->
    <div id="citaModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
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
                    <input type="text" name="titulo" id="titulo" required placeholder="Título de la cita" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                        <option value="">Sin cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora de Fin (opcional)</label>
                    <input type="datetime-local" name="fecha_fin" id="fecha_fin" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ubicación (opcional)</label>
                    <input type="text" name="ubicacion" id="ubicacion" placeholder="Ubicación de la cita" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción (opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="3" placeholder="Descripción de la cita..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all resize-none"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Invitar personas (emails separados por comas)</label>
                    <input 
                        type="text" 
                        name="invitados_emails" 
                        id="invitados_emails"
                        placeholder="email1@ejemplo.com, email2@ejemplo.com"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                    >
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Los invitados recibirán una invitación automática de Google Calendar</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recurrencia</label>
                    <select 
                        name="recurrencia" 
                        id="recurrencia"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                        onchange="toggleRecurrenciaOptions()"
                    >
                        <option value="none">Sin recurrencia</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
                
                <div id="recurrencia_dias_container" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Días específicos</label>
                    <div id="recurrencia_dias_semanal" class="hidden">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Selecciona los días de la semana:</p>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="0" class="rounded">
                                <span class="text-sm">Dom</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="1" class="rounded">
                                <span class="text-sm">Lun</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="2" class="rounded">
                                <span class="text-sm">Mar</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="3" class="rounded">
                                <span class="text-sm">Mié</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="4" class="rounded">
                                <span class="text-sm">Jue</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="5" class="rounded">
                                <span class="text-sm">Vie</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="6" class="rounded">
                                <span class="text-sm">Sáb</span>
                            </label>
                        </div>
                    </div>
                    <div id="recurrencia_dias_mensual" class="hidden">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Selecciona los días del mes (1-31):</p>
                        <input 
                            type="text" 
                            name="recurrencia_dias_mensual" 
                            id="recurrencia_dias_mensual"
                            placeholder="Ej: 1,15,30 o cada Lunes"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Puedes escribir números separados por comas (1,15,30) o días de la semana (cada Lunes, cada Martes, etc.)</p>
                    </div>
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
                        <input type="color" name="color" id="color" value="#10b981" class="w-16 h-12 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer">
                        <input type="text" id="color_text" value="#10b981" placeholder="#10b981" class="flex-1 px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all" onchange="document.getElementById('color').value = this.value">
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
                    <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all">Guardar</button>
                    <button type="button" id="deleteBtn" onclick="deleteCita()" class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all hidden">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Nueva/Editar Tarea -->
    <div id="tareaModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
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
                    <input type="text" name="texto" id="tarea_texto" required placeholder="Descripción de la tarea" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Lista</label>
                    <select name="lista_id" id="tarea_lista_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                        <option value="">Sin lista</option>
                        @foreach($listas as $lista)
                            <option value="{{ $lista->id }}">{{ $lista->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha_hora" id="tarea_fecha_hora" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Recurrencia</label>
                    <select 
                        name="recurrencia" 
                        id="tarea_recurrencia"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                        onchange="toggleTareaRecurrenciaOptions()"
                    >
                        <option value="none">Sin recurrencia</option>
                        <option value="diaria">Diaria</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                    </select>
                </div>
                
                <div id="tarea_recurrencia_dias_container" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Días específicos</label>
                    <div id="tarea_recurrencia_dias_semanal" class="hidden">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Selecciona los días de la semana:</p>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="0" class="rounded">
                                <span class="text-sm">Dom</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="1" class="rounded">
                                <span class="text-sm">Lun</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="2" class="rounded">
                                <span class="text-sm">Mar</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="3" class="rounded">
                                <span class="text-sm">Mié</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="4" class="rounded">
                                <span class="text-sm">Jue</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="5" class="rounded">
                                <span class="text-sm">Vie</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">
                                <input type="checkbox" name="recurrencia_dias[]" value="6" class="rounded">
                                <span class="text-sm">Sáb</span>
                            </label>
                        </div>
                    </div>
                    <div id="tarea_recurrencia_dias_mensual" class="hidden">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Selecciona los días del mes (1-31):</p>
                        <input 
                            type="text" 
                            name="recurrencia_dias_mensual" 
                            id="tarea_recurrencia_dias_mensual"
                            placeholder="Ej: 1,15,30 o cada Lunes"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                        >
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Puedes escribir números separados por comas (1,15,30) o días de la semana (cada Lunes, cada Martes, etc.)</p>
                    </div>
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
                    <input type="text" name="tipo" id="tarea_tipo" list="tipos-list" placeholder="Tipo de tarea (opcional)" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                    <datalist id="tipos-list">
                        @foreach($tiposExistentes as $tipo)
                            <option value="{{ $tipo }}">
                        @endforeach
                    </datalist>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Color</label>
                    <div class="flex items-center gap-3">
                        <input 
                            type="color" 
                            name="color" 
                            id="tarea_color"
                            value="#8b5cf6"
                            class="w-16 h-12 rounded-lg border border-slate-300 dark:border-slate-700 cursor-pointer"
                        >
                        <input 
                            type="text" 
                            id="tarea_color_text"
                            value="#8b5cf6"
                            placeholder="#8b5cf6"
                            class="flex-1 px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all"
                            onchange="document.getElementById('tarea_color').value = this.value"
                        >
                    </div>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all">Guardar</button>
                    <button type="button" id="deleteTareaBtn" onclick="deleteTarea()" class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all hidden">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Nueva/Editar Nota -->
    <div id="notaModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md md:max-w-2xl max-h-[85vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="notaModalTitle">Nueva Nota</h3>
                <button onclick="closeNotaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="nota-form" class="p-4 md:p-6 space-y-4 overflow-y-auto max-h-[65vh]">
                <input type="hidden" name="nota_id" id="nota_id">
                
                <!-- Primera fila: Fecha y Tipo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha</label>
                        <input 
                            type="date" 
                            name="fecha" 
                            id="nota_fecha" 
                            required
                            value="{{ $fecha->format('Y-m-d') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                        <select 
                            name="type" 
                            id="nota_type" 
                            required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                        >
                            <option value="note">Nota</option>
                            <option value="call">Llamada</option>
                            <option value="meeting">Reunión</option>
                            <option value="email">Email</option>
                        </select>
                    </div>
                </div>
                
                <!-- Contenido (ancho completo) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contenido de la Nota</label>
                    <textarea 
                        name="content" 
                        id="nota_content" 
                        required 
                        rows="4"
                        placeholder="Escribe el contenido de la nota..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all resize-none"
                    ></textarea>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Máximo 5000 caracteres</p>
                </div>
                
                <!-- Segunda fila: Cliente y Pinned -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente (opcional)</label>
                        <select 
                            name="cliente_id" 
                            id="nota_cliente_id"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                        >
                            <option value="">Sin cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre_empresa }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado</label>
                        <label class="flex items-center gap-2 cursor-pointer h-full min-h-[42px] px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl">
                            <input 
                                type="checkbox" 
                                name="pinned" 
                                id="nota_pinned"
                                class="w-5 h-5 rounded border-slate-300 dark:border-slate-700 text-blue-500 focus:ring-blue-500"
                            >
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Marcar como fijada</span>
                        </label>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Las notas fijadas aparecen primero</p>
                    </div>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all">Guardar</button>
                    <button type="button" id="deleteNotaBtn" onclick="deleteNota()" class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all hidden">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
    
    @php
        $citasDataArray = $citas->map(function($cita) {
            return [
                'id' => $cita->id,
                'titulo' => $cita->titulo,
                'cliente_id' => $cita->cliente_id,
                'fecha_inicio' => $cita->fecha_inicio,
                'fecha_fin' => $cita->fecha_fin,
                'ubicacion' => $cita->ubicacion,
                'descripcion' => $cita->descripcion,
                'color' => $cita->color ?? '#10b981',
                'estado' => $cita->estado,
                'recurrencia' => $cita->recurrencia ?? 'none',
                'recurrencia_fin' => $cita->recurrencia_fin,
                'recurrencia_dias' => $cita->recurrencia_dias
            ];
        })->values();
        
        $tareasDataArray = $tareas->map(function($tarea) {
            return [
                'id' => $tarea->id,
                'texto' => $tarea->texto,
                'lista_id' => $tarea->lista_id,
                'fecha_hora' => $tarea->fecha_hora,
                'tipo' => $tarea->tipo,
                'recurrencia' => $tarea->recurrencia ?? 'none',
                'recurrencia_fin' => $tarea->recurrencia_fin,
                'recurrencia_dias' => $tarea->recurrencia_dias,
                'color' => $tarea->color ?? '#8b5cf6'
            ];
        })->values();
    @endphp
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const citasData = @json($citasDataArray);
        const tareasData = @json($tareasDataArray);
        const fechaActual = '{{ $fecha->format("Y-m-d") }}';
        
        // Sincronizar color picker
        document.getElementById('color')?.addEventListener('input', function(e) {
            document.getElementById('color_text').value = e.target.value;
        });
        document.getElementById('color_text')?.addEventListener('input', function(e) {
            if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                document.getElementById('color').value = e.target.value;
            }
        });
        
        function toggleRecurrenciaOptions() {
            const recurrencia = document.getElementById('recurrencia').value;
            const finContainer = document.getElementById('recurrencia_fin_container');
            const diasContainer = document.getElementById('recurrencia_dias_container');
            const diasSemanal = document.getElementById('recurrencia_dias_semanal');
            const diasMensual = document.getElementById('recurrencia_dias_mensual');
            
            // Reset checkboxes
            document.querySelectorAll('#recurrencia_dias_container input[type="checkbox"]').forEach(cb => cb.checked = false);
            document.getElementById('recurrencia_dias_mensual').value = '';
            
            if (recurrencia === 'none') {
                finContainer.classList.add('hidden');
                diasContainer.classList.add('hidden');
            } else {
                finContainer.classList.remove('hidden');
                if (recurrencia === 'semanal') {
                    diasContainer.classList.remove('hidden');
                    diasSemanal.classList.remove('hidden');
                    diasMensual.classList.add('hidden');
                } else if (recurrencia === 'mensual') {
                    diasContainer.classList.remove('hidden');
                    diasSemanal.classList.add('hidden');
                    diasMensual.classList.remove('hidden');
                } else {
                    diasContainer.classList.add('hidden');
                }
            }
        }
        
        function showNuevaCitaModal() {
            document.getElementById('modalTitle').textContent = 'Nueva Cita';
            document.getElementById('cita-form').reset();
            document.getElementById('cita_id').value = '';
            document.getElementById('deleteBtn').classList.add('hidden');
            document.getElementById('fecha_inicio').value = fechaActual + 'T09:00';
            document.getElementById('color').value = '#10b981';
            document.getElementById('color_text').value = '#10b981';
            document.getElementById('recurrencia').value = 'none';
            document.getElementById('recurrencia_fin_container').classList.add('hidden');
            document.getElementById('recurrencia_dias_container').classList.add('hidden');
            document.getElementById('citaModal').classList.remove('hidden');
        }
        
        function closeCitaModal() {
            document.getElementById('citaModal').classList.add('hidden');
        }
        
        function showNuevaTareaModal() {
            document.getElementById('tareaModalTitle').textContent = 'Nueva Tarea';
            document.getElementById('tarea-form').reset();
            document.getElementById('tarea_id').value = '';
            document.getElementById('deleteTareaBtn').classList.add('hidden');
            document.getElementById('tarea_fecha_hora').value = fechaActual + 'T09:00';
            document.getElementById('tarea_recurrencia').value = 'none';
            document.getElementById('tarea_recurrencia_fin_container').classList.add('hidden');
            document.getElementById('tarea_recurrencia_dias_container').classList.add('hidden');
            document.getElementById('tarea_color').value = '#8b5cf6';
            document.getElementById('tarea_color_text').value = '#8b5cf6';
            document.getElementById('tareaModal').classList.remove('hidden');
        }
        
        function toggleTareaRecurrenciaOptions() {
            const recurrencia = document.getElementById('tarea_recurrencia').value;
            const finContainer = document.getElementById('tarea_recurrencia_fin_container');
            const diasContainer = document.getElementById('tarea_recurrencia_dias_container');
            const diasSemanal = document.getElementById('tarea_recurrencia_dias_semanal');
            const diasMensual = document.getElementById('tarea_recurrencia_dias_mensual');
            
            // Reset checkboxes
            document.querySelectorAll('#tarea_recurrencia_dias_container input[type="checkbox"]').forEach(cb => cb.checked = false);
            document.getElementById('tarea_recurrencia_dias_mensual').value = '';
            
            if (recurrencia === 'none') {
                finContainer.classList.add('hidden');
                diasContainer.classList.add('hidden');
            } else {
                finContainer.classList.remove('hidden');
                if (recurrencia === 'semanal') {
                    diasContainer.classList.remove('hidden');
                    diasSemanal.classList.remove('hidden');
                    diasMensual.classList.add('hidden');
                } else if (recurrencia === 'mensual') {
                    diasContainer.classList.remove('hidden');
                    diasSemanal.classList.add('hidden');
                    diasMensual.classList.remove('hidden');
                } else {
                    diasContainer.classList.add('hidden');
                }
            }
        }
        
        // Sincronizar color picker de tareas
        document.getElementById('tarea_color')?.addEventListener('input', function(e) {
            document.getElementById('tarea_color_text').value = e.target.value;
        });
        
        document.getElementById('tarea_color_text')?.addEventListener('input', function(e) {
            if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                document.getElementById('tarea_color').value = e.target.value;
            }
        });
        
        function closeTareaModal() {
            document.getElementById('tareaModal').classList.add('hidden');
        }
        
        function showNuevaNotaModal() {
            document.getElementById('notaModalTitle').textContent = 'Nueva Nota';
            document.getElementById('nota-form').reset();
            document.getElementById('nota_id').value = '';
            document.getElementById('deleteNotaBtn').classList.add('hidden');
            document.getElementById('nota_type').value = 'note';
            document.getElementById('nota_pinned').checked = false;
            document.getElementById('nota_fecha').value = '{{ $fecha->format('Y-m-d') }}';
            document.getElementById('notaModal').classList.remove('hidden');
        }
        
        function closeNotaModal() {
            document.getElementById('notaModal').classList.add('hidden');
        }
        
        async function editNota(notaId) {
            try {
                const response = await fetch(`/notas/${notaId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                
                const data = await response.json();
                
                if (data.success && data.nota) {
                    const nota = data.nota;
                    document.getElementById('nota_id').value = nota.id;
                    document.getElementById('nota_content').value = nota.content || '';
                    document.getElementById('nota_type').value = nota.type || 'note';
                    document.getElementById('nota_cliente_id').value = nota.cliente_id || '';
                    document.getElementById('nota_pinned').checked = nota.pinned || false;
                    // La fecha ya viene formateada desde el backend como YYYY-MM-DD
                    document.getElementById('nota_fecha').value = nota.fecha || '{{ $fecha->format('Y-m-d') }}';
                    document.getElementById('notaModalTitle').textContent = 'Editar Nota';
                    const deleteBtn = document.getElementById('deleteNotaBtn');
                    if (deleteBtn) deleteBtn.classList.remove('hidden');
                    document.getElementById('notaModal').classList.remove('hidden');
                } else {
                    alert('Error al cargar la nota');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
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
            document.getElementById('invitados_emails').value = cita.invitados_emails || '';
            document.getElementById('color').value = cita.color || '#10b981';
            document.getElementById('color_text').value = cita.color || '#10b981';
            document.getElementById('estado').value = cita.estado || 'programada';
            document.getElementById('recurrencia').value = cita.recurrencia || 'none';
            document.getElementById('recurrencia_fin').value = cita.recurrencia_fin ? new Date(cita.recurrencia_fin).toISOString().slice(0, 16) : '';
            
            // Cargar días de recurrencia
            if (cita.recurrencia_dias && Array.isArray(cita.recurrencia_dias)) {
                if (cita.recurrencia === 'semanal') {
                    cita.recurrencia_dias.forEach(dia => {
                        const checkbox = document.querySelector(`#recurrencia_dias_container input[value="${dia}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                } else if (cita.recurrencia === 'mensual') {
                    document.getElementById('recurrencia_dias_mensual').value = cita.recurrencia_dias.join(',');
                }
            }
            
            toggleRecurrenciaOptions();
            document.getElementById('deleteBtn').classList.remove('hidden');
            document.getElementById('citaModal').classList.remove('hidden');
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
            document.getElementById('tarea_color').value = tarea.color || '#8b5cf6';
            document.getElementById('tarea_color_text').value = tarea.color || '#8b5cf6';
            
            // Cargar días de recurrencia
            if (tarea.recurrencia_dias && Array.isArray(tarea.recurrencia_dias)) {
                if (tarea.recurrencia === 'semanal') {
                    tarea.recurrencia_dias.forEach(dia => {
                        const checkbox = document.querySelector(`#tarea_recurrencia_dias_container input[value="${dia}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                } else if (tarea.recurrencia === 'mensual') {
                    document.getElementById('tarea_recurrencia_dias_mensual').value = tarea.recurrencia_dias.join(',');
                }
            }
            
            toggleTareaRecurrenciaOptions();
            document.getElementById('deleteTareaBtn').classList.remove('hidden');
            document.getElementById('tareaModal').classList.remove('hidden');
        }
        
        function deleteCitaConfirm(citaId) {
            if (!confirm('¿Estás seguro de eliminar esta cita?')) return;
            deleteCita(citaId);
        }
        
        function deleteTareaConfirm(tareaId) {
            if (!confirm('¿Estás seguro de eliminar esta tarea?')) return;
            deleteTarea(tareaId);
        }
        
        async function deleteCita(citaId = null) {
            const id = citaId || document.getElementById('cita_id').value;
            if (!id) return;
            
            try {
                const response = await fetch(`/citas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al eliminar la cita');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la cita');
            }
        }
        
        async function deleteTarea(tareaId = null) {
            const id = tareaId || document.getElementById('tarea_id').value;
            if (!id) return;
            
            try {
                const response = await fetch(`/walee-tareas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al eliminar la tarea');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la tarea');
            }
        }
        
        // Form handlers
        document.getElementById('cita-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Obtener días de recurrencia
            let recurrenciaDias = null;
            const recurrencia = document.getElementById('recurrencia').value;
            if (recurrencia === 'semanal') {
                const diasCheckboxes = document.querySelectorAll('#recurrencia_dias_container input[type="checkbox"]:checked');
                recurrenciaDias = Array.from(diasCheckboxes).map(cb => parseInt(cb.value));
            } else if (recurrencia === 'mensual') {
                const diasMensual = document.getElementById('recurrencia_dias_mensual').value;
                if (diasMensual) {
                    const dias = diasMensual.split(',').map(d => d.trim());
                    recurrenciaDias = dias.map(d => {
                        const diaNum = parseInt(d);
                        if (!isNaN(diaNum)) return diaNum;
                        const diasSemana = {
                            'domingo': 0, 'lunes': 1, 'martes': 2, 'miércoles': 3, 'miercoles': 3,
                            'jueves': 4, 'viernes': 5, 'sábado': 6, 'sabado': 6
                        };
                        const diaLower = d.toLowerCase().replace('cada ', '');
                        return diasSemana[diaLower] !== undefined ? diasSemana[diaLower] : null;
                    }).filter(d => d !== null);
                }
            }
            
            const formData = {
                titulo: document.getElementById('titulo').value,
                cliente_id: document.getElementById('cliente_id').value || null,
                fecha_inicio: document.getElementById('fecha_inicio').value,
                fecha_fin: document.getElementById('fecha_fin').value || null,
                ubicacion: document.getElementById('ubicacion').value || null,
                descripcion: document.getElementById('descripcion').value || null,
                color: document.getElementById('color').value,
                estado: document.getElementById('estado').value,
                recurrencia: recurrencia,
                recurrencia_fin: document.getElementById('recurrencia_fin').value || null,
                recurrencia_dias: recurrenciaDias
            };
            
            const citaId = document.getElementById('cita_id').value;
            const url = citaId ? `/citas/${citaId}` : '/citas';
            const method = citaId ? 'PUT' : 'POST';
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al guardar la cita: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la cita');
            }
        });
        
        document.getElementById('tarea-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Obtener días de recurrencia
            let recurrenciaDias = null;
            const recurrencia = document.getElementById('tarea_recurrencia').value;
            if (recurrencia === 'semanal') {
                const diasCheckboxes = document.querySelectorAll('#tarea_recurrencia_dias_container input[type="checkbox"]:checked');
                recurrenciaDias = Array.from(diasCheckboxes).map(cb => parseInt(cb.value));
            } else if (recurrencia === 'mensual') {
                const diasMensual = document.getElementById('tarea_recurrencia_dias_mensual').value;
                if (diasMensual) {
                    const dias = diasMensual.split(',').map(d => d.trim());
                    recurrenciaDias = dias.map(d => {
                        const diaNum = parseInt(d);
                        if (!isNaN(diaNum)) return diaNum;
                        const diasSemana = {
                            'domingo': 0, 'lunes': 1, 'martes': 2, 'miércoles': 3, 'miercoles': 3,
                            'jueves': 4, 'viernes': 5, 'sábado': 6, 'sabado': 6
                        };
                        const diaLower = d.toLowerCase().replace('cada ', '');
                        return diasSemana[diaLower] !== undefined ? diasSemana[diaLower] : null;
                    }).filter(d => d !== null);
                }
            }
            
            const formData = {
                texto: document.getElementById('tarea_texto').value,
                lista_id: document.getElementById('tarea_lista_id').value || null,
                fecha_hora: document.getElementById('tarea_fecha_hora').value,
                tipo: document.getElementById('tarea_tipo').value || null,
                recurrencia: recurrencia,
                recurrencia_fin: document.getElementById('tarea_recurrencia_fin').value || null,
                recurrencia_dias: recurrenciaDias,
                color: document.getElementById('tarea_color').value
            };
            
            const tareaId = document.getElementById('tarea_id').value;
            const url = tareaId ? `/walee-tareas/${tareaId}` : '/walee-tareas';
            const method = tareaId ? 'PUT' : 'POST';
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al guardar la tarea: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la tarea');
            }
        });
        
        // Cerrar modales al hacer clic fuera
        document.getElementById('citaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeCitaModal();
        });
        
        document.getElementById('tareaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeTareaModal();
        });
        
        document.getElementById('notaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeNotaModal();
        });
        
        document.getElementById('notaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeNotaModal();
        });
        
        // Nota form handler
        document.getElementById('nota-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = {
                content: document.getElementById('nota_content').value,
                type: document.getElementById('nota_type').value,
                cliente_id: document.getElementById('nota_cliente_id').value || null,
                pinned: document.getElementById('nota_pinned').checked,
                fecha: document.getElementById('nota_fecha').value || new Date().toISOString().split('T')[0]
            };
            
            const notaId = document.getElementById('nota_id').value;
            const url = notaId ? `/notas/${notaId}` : '/notas';
            const method = notaId ? 'PUT' : 'POST';
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al guardar la nota: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar la nota');
            }
        });
        
        async function deleteNota(notaId = null) {
            const id = notaId || document.getElementById('nota_id').value;
            if (!id) return;
            
            if (!confirm('¿Estás seguro de eliminar esta nota?')) return;
            
            try {
                const response = await fetch(`/notas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error al eliminar la nota');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la nota');
            }
        }
    </script>
    
    @include('partials.walee-support-button')
</body>
</html>

