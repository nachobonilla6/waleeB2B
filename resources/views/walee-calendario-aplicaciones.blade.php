<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Calendario de Aplicaciones</title>
    <meta name="description" content="Calendario de Aplicaciones">
    <meta name="theme-color" content="#8b5cf6">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        $vista = request()->get('vista', 'semanal');
        
        // Si es vista semanal, calcular la semana
        if ($vista === 'semanal') {
            $semanaParam = request()->get('semana');
            if ($semanaParam && strpos($semanaParam, '-') !== false) {
                $partes = explode('-', $semanaParam);
                $anoSemana = isset($partes[0]) ? (int)trim($partes[0]) : null;
                $numSemana = isset($partes[1]) ? (int)trim($partes[1]) : null;
                
                if ($anoSemana && $numSemana && $numSemana >= 1 && $numSemana <= 53) {
                    try {
                        $fechaSemana = \Carbon\Carbon::now()->setISODate($anoSemana, $numSemana);
                        $inicioSemana = $fechaSemana->copy()->subDay()->startOfWeek(\Carbon\Carbon::SUNDAY);
                        if ($inicioSemana->format('o') != $anoSemana || $inicioSemana->format('W') != $numSemana) {
                            $inicioSemana = $fechaSemana->copy();
                        }
                        $finSemana = $inicioSemana->copy()->addDays(6);
                    } catch (\Exception $e) {
                        $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                        $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                    }
                } else {
                    $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                    $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                }
            } else {
                $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
            }
            
            // Calcular semana anterior y siguiente
            $anoActualSemana = (int)$inicioSemana->format('o');
            $numActualSemana = (int)$inicioSemana->format('W');
            
            try {
                $fechaSiguiente = $inicioSemana->copy()->addWeek();
                $anoSemanaSiguiente = (int)$fechaSiguiente->format('o');
                $numSemanaSiguiente = (int)$fechaSiguiente->format('W');
            } catch (\Exception $e) {
                $numSemanaSiguiente = $numActualSemana + 1;
                $anoSemanaSiguiente = $anoActualSemana;
                if ($numSemanaSiguiente > 53) {
                    $numSemanaSiguiente = 1;
                    $anoSemanaSiguiente = $anoActualSemana + 1;
                }
            }
            
            try {
                $fechaAnterior = $inicioSemana->copy()->subWeek();
                $anoSemanaAnterior = (int)$fechaAnterior->format('o');
                $numSemanaAnterior = (int)$fechaAnterior->format('W');
            } catch (\Exception $e) {
                $numSemanaAnterior = $numActualSemana - 1;
                $anoSemanaAnterior = $anoActualSemana;
                if ($numSemanaAnterior < 1) {
                    $numSemanaAnterior = 53;
                    $anoSemanaAnterior = $anoActualSemana - 1;
                }
            }
            
            $semanaAnteriorFormato = $anoSemanaAnterior . '-' . str_pad($numSemanaAnterior, 2, '0', STR_PAD_LEFT);
            $semanaSiguienteFormato = $anoSemanaSiguiente . '-' . str_pad($numSemanaSiguiente, 2, '0', STR_PAD_LEFT);
        }
        
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        // Obtener eventos de Google Calendar
        $eventos = collect();
        try {
            $googleService = new \App\Services\GoogleCalendarService();
            
            // Obtener eventos de la semana
            $fechaInicio = $inicioSemana->copy()->startOfDay();
            $fechaFin = $finSemana->copy()->endOfDay();
            
            $googleEvents = $googleService->getEvents($fechaInicio, $fechaFin);
            
            foreach ($googleEvents as $googleEvent) {
                $eventoData = $googleService->convertGoogleEventToCita($googleEvent);
                if ($eventoData) {
                    // Convertir a objeto para facilitar el uso en la vista
                    $evento = (object) [
                        'id' => $eventoData['google_event_id'] ?? null,
                        'titulo' => $eventoData['titulo'] ?? 'Sin título',
                        'descripcion' => $eventoData['descripcion'] ?? null,
                        'fecha_inicio' => $eventoData['fecha_inicio'],
                        'fecha_fin' => $eventoData['fecha_fin'] ?? null,
                        'ubicacion' => $eventoData['ubicacion'] ?? null,
                        'google_event_id' => $eventoData['google_event_id'] ?? null,
                        'from_google' => true,
                    ];
                    
                    $fechaKey = $eventoData['fecha_inicio']->format('Y-m-d');
                    if (!$eventos->has($fechaKey)) {
                        $eventos->put($fechaKey, collect());
                    }
                    $eventos->get($fechaKey)->push($evento);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error obteniendo eventos de Google Calendar: ' . $e->getMessage());
        }
        
        // Verificar si está autorizado
        $isAuthorized = false;
        try {
            $googleService = new \App\Services\GoogleCalendarService();
            $isAuthorized = $googleService->isAuthorized();
        } catch (\Exception $e) {
            \Log::error('Error verificando autorización: ' . $e->getMessage());
        }
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Calendario de Aplicaciones'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <div class="mb-4 md:mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="rounded-xl bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 p-3 md:p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4">
                        <div class="flex items-center gap-2 md:gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base md:text-lg font-semibold text-violet-900 dark:text-violet-300 truncate">Calendario de Aplicaciones</h3>
                                <p class="text-xs md:text-sm text-violet-700 dark:text-violet-400 truncate">Vista semanal</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-2 flex-shrink-0 flex-wrap header-actions">
                            @if(!$isAuthorized)
                                <a href="{{ route('google-calendar.auth') }}" class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Conectar Google Calendar</span>
                                    <span class="sm:hidden">Conectar</span>
                                </a>
                            @else
                                <button onclick="sincronizarGoogleCalendar()" class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span class="hidden sm:inline">Sincronizar</span>
                                    <span class="sm:hidden">Sync</span>
                                </button>
                            @endif
                            <button onclick="showNuevoEventoModal()" class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                <svg class="w-4 h-4 sm:w-5 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span class="hidden sm:inline">Nuevo Evento</span>
                                <span class="sm:hidden">Nuevo</span>
                            </button>
                            @if($vista === 'semanal')
                                <a href="?vista=semanal&semana={{ $semanaAnteriorFormato }}" class="px-3 py-2.5 sm:px-4 sm:py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Anterior</span>
                                    <span class="sm:hidden">Ant</span>
                                </a>
                                <a href="?vista=semanal&semana={{ $semanaSiguienteFormato }}" class="px-3 py-2.5 sm:px-4 sm:py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Siguiente</span>
                                    <span class="sm:hidden">Sig</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Vista Semanal -->
            <div class="flex-1 min-w-0">
                @if($vista === 'semanal')
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl overflow-hidden shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.2s;">
                        <!-- Header de la Semana -->
                        <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                                {{ $inicioSemana->format('d') }} - {{ $finSemana->format('d') }} {{ $meses[$finSemana->month] }} {{ $finSemana->year }}
                            </h2>
                        </div>
                        
                        <!-- Días de la Semana -->
                        <div class="grid grid-cols-1 md:grid-cols-7 divide-y md:divide-y-0 md:divide-x divide-slate-200 dark:divide-slate-700">
                            @php
                                $diaSemana = $inicioSemana->copy();
                                $hoy = now();
                            @endphp
                            @for($i = 0; $i < 7; $i++)
                                @php
                                    $esHoy = $diaSemana->isSameDay($hoy);
                                    $fechaKey = $diaSemana->format('Y-m-d');
                                    $eventosDelDia = $eventos->get($fechaKey, collect());
                                    $eventosOrdenados = $eventosDelDia->sortBy('fecha_inicio');
                                @endphp
                                <div class="min-h-[300px] md:min-h-[400px] flex flex-col border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-700 last:border-b-0">
                                    <!-- Header del día -->
                                    <div class="p-3 md:p-3 border-b border-slate-200 dark:border-slate-700 {{ $esHoy ? 'bg-violet-50 dark:bg-violet-500/10' : 'bg-slate-50 dark:bg-slate-800/30' }}">
                                        <div class="flex md:block items-center justify-between md:text-center">
                                            <div>
                                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">{{ $diaSemana->format('D') }}</p>
                                                <p class="text-lg md:text-lg font-bold {{ $esHoy ? 'text-violet-600 dark:text-violet-400' : 'text-slate-900 dark:text-white' }} mt-1">
                                                    {{ $diaSemana->day }} {{ $meses[$diaSemana->month] }}
                                                </p>
                                            </div>
                                            <div class="md:hidden">
                                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $eventosOrdenados->count() }} eventos</span>
                                            </div>
                                        </div>
                                        <button 
                                            onclick="showNuevoEventoModalConFecha('{{ $diaSemana->format('Y-m-d') }}', '09:00')"
                                            class="mt-2 w-full px-2 py-1.5 text-xs font-medium rounded-lg bg-violet-500 hover:bg-violet-600 text-white transition-all flex items-center justify-center gap-1 shadow-sm hover:shadow-md active:scale-95"
                                            title="Crear evento a las 9 AM">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <span>9 AM</span>
                                        </button>
                                    </div>
                                    
                                    <!-- Eventos del día -->
                                    <div class="flex-1 p-3 md:p-2 space-y-2 md:space-y-1.5 overflow-y-auto">
                                        @if($eventosOrdenados->count() > 0)
                                            @foreach($eventosOrdenados as $evento)
                                                @php
                                                    $fechaInicio = $evento->fecha_inicio instanceof \DateTime 
                                                        ? $evento->fecha_inicio 
                                                        : \Carbon\Carbon::parse($evento->fecha_inicio);
                                                    $hora = $fechaInicio->format('H:i');
                                                    $titulo = $evento->titulo ?? 'Sin título';
                                                @endphp
                                                <button 
                                                    onclick="event.preventDefault(); showEventoDetail('{{ $evento->google_event_id ?? '' }}', '{{ addslashes($titulo) }}', '{{ addslashes($evento->descripcion ?? '') }}', '{{ $fechaInicio->format('Y-m-d H:i') }}', '{{ $evento->ubicacion ?? '' }}');"
                                                    class="w-full text-left px-3 py-2.5 md:px-2 md:py-1.5 rounded-lg md:rounded text-sm md:text-xs font-medium transition-all hover:opacity-80 active:scale-95 bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 shadow-sm md:shadow-none"
                                                    style="border-left: 4px solid #8b5cf6;"
                                                    title="{{ $titulo }}"
                                                >
                                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-1.5">
                                                        <div class="flex items-center justify-between md:justify-start gap-2">
                                                            <span class="text-xs md:text-[10px] font-semibold">{{ $hora }}</span>
                                                            @if(isset($evento->from_google) && $evento->from_google)
                                                                <span class="text-[9px] font-medium px-1.5 py-0.5 rounded bg-blue-200 dark:bg-blue-800/50 text-blue-700 dark:text-blue-300">Google</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 line-clamp-2">{{ $titulo }}</p>
                                                </button>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4 md:py-2">
                                                <p class="text-xs text-slate-400 dark:text-slate-500">Sin eventos</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @php $diaSemana->addDay(); @endphp
                            @endfor
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        function sincronizarGoogleCalendar() {
            Swal.fire({
                title: 'Sincronizando...',
                text: 'Obteniendo eventos de Google Calendar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Recargar la página para obtener los eventos actualizados
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
        
        function showNuevoEventoModal() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: 'Nuevo Evento',
                html: `
                    <form id="nuevoEventoForm" class="space-y-3 text-left">
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Título</label>
                            <input type="text" id="evento_titulo" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Fecha y Hora</label>
                            <input type="datetime-local" id="evento_fecha" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Descripción (opcional)</label>
                            <textarea id="evento_descripcion" rows="3"
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none resize-none"></textarea>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Crear',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                preConfirm: async () => {
                    const titulo = document.getElementById('evento_titulo').value;
                    const fecha = document.getElementById('evento_fecha').value;
                    const descripcion = document.getElementById('evento_descripcion').value;
                    
                    if (!titulo || !fecha) {
                        Swal.showValidationMessage('Título y fecha son requeridos');
                        return false;
                    }
                    
                    try {
                        const response = await fetch('{{ route("walee.calendario.aplicaciones.crear") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                titulo: titulo,
                                fecha_inicio: fecha,
                                descripcion: descripcion,
                            }),
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Evento creado',
                                text: 'El evento se ha creado y sincronizado con Google Calendar',
                                confirmButtonColor: '#8b5cf6'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al crear el evento',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error de conexión: ' + error.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                    
                    return false;
                }
            });
        }
        
        function showNuevoEventoModalConFecha(fecha, hora) {
            const fechaHora = fecha + 'T' + hora;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: 'Nuevo Evento',
                html: `
                    <form id="nuevoEventoForm" class="space-y-3 text-left">
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Título</label>
                            <input type="text" id="evento_titulo" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Fecha y Hora</label>
                            <input type="datetime-local" id="evento_fecha" value="${fechaHora}" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Descripción (opcional)</label>
                            <textarea id="evento_descripcion" rows="3"
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none resize-none"></textarea>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Crear',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                preConfirm: async () => {
                    const titulo = document.getElementById('evento_titulo').value;
                    const fecha = document.getElementById('evento_fecha').value;
                    const descripcion = document.getElementById('evento_descripcion').value;
                    
                    if (!titulo || !fecha) {
                        Swal.showValidationMessage('Título y fecha son requeridos');
                        return false;
                    }
                    
                    try {
                        const response = await fetch('{{ route("walee.calendario.aplicaciones.crear") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                titulo: titulo,
                                fecha_inicio: fecha,
                                descripcion: descripcion,
                            }),
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Evento creado',
                                text: 'El evento se ha creado y sincronizado con Google Calendar',
                                confirmButtonColor: '#8b5cf6'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al crear el evento',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error de conexión: ' + error.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                    
                    return false;
                }
            });
        }
        
        function showEventoDetail(eventoId, titulo, descripcion, fecha, ubicacion) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: titulo || 'Evento',
                html: `
                    <div class="text-left space-y-2">
                        ${fecha ? `<p class="text-sm"><strong>Fecha:</strong> ${fecha}</p>` : ''}
                        ${ubicacion ? `<p class="text-sm"><strong>Ubicación:</strong> ${ubicacion}</p>` : ''}
                        ${descripcion ? `<p class="text-sm"><strong>Descripción:</strong> ${descripcion}</p>` : ''}
                        ${eventoId ? `<a href="https://calendar.google.com/calendar/event?eid=${encodeURIComponent(eventoId)}" target="_blank" class="text-sm text-violet-600 dark:text-violet-400 hover:underline">Abrir en Google Calendar</a>` : ''}
                    </div>
                `,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#8b5cf6',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b'
            });
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

