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
        
        // Obtener clientes con email para el selector
        $clientesConEmail = \App\Models\Client::whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email']);
        
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
                        'has_accepted' => $eventoData['has_accepted'] ?? false,
                        'has_declined' => $eventoData['has_declined'] ?? false,
                        'has_tentative' => $eventoData['has_tentative'] ?? false,
                        'attendees' => $eventoData['attendees'] ?? [],
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
        
        // Verificar si está autorizado y obtener información del calendario
        $isAuthorized = false;
        $calendarInfo = null;
        $credentialsError = null;
        
        try {
            $googleService = new \App\Services\GoogleCalendarService();
            $isAuthorized = $googleService->isAuthorized();
            
            // Obtener información del calendario en uso
            $configuredCalendarId = config('services.google.calendar_id', 'primary');
            $calendarIdInUse = $googleService->getCalendarId();
            $authorizedEmail = $googleService->getAuthorizedEmail();
            
            // Buscar el archivo de credenciales en diferentes ubicaciones
            $credentialsPath = $googleService->findCredentialsFile();
            
            // Definir todas las rutas posibles para el mensaje de error
            $possiblePaths = [
                config('services.google.credentials_path'),
                storage_path('app/google-credentials.json'),
                base_path('storage/app/google-credentials.json'),
                base_path('google-credentials.json'),
                __DIR__ . '/../../storage/app/google-credentials.json',
            ];
            
            // Si no se encuentra, mostrar el error con todas las rutas verificadas
            if (!$credentialsPath) {
                $credentialsError = 'No encontrado en ninguna de estas ubicaciones: ' . implode(', ', array_filter($possiblePaths));
            }
            
            $calendarInfo = [
                'configured_id' => $configuredCalendarId,
                'calendar_id_in_use' => $calendarIdInUse,
                'authorized_email' => $authorizedEmail,
                'credentials_path' => $credentialsPath,
                'credentials_exists' => $credentialsPath !== null,
                'possible_paths' => array_filter($possiblePaths),
            ];
        } catch (\Exception $e) {
            \Log::error('Error verificando autorización: ' . $e->getMessage());
            $credentialsError = $e->getMessage();
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
            
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-lg text-emerald-700 dark:text-emerald-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg text-red-700 dark:text-red-300 text-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($credentialsError)
                <div class="mb-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-700 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="flex-1">
                            <h4 class="font-semibold text-amber-900 dark:text-amber-200 mb-1">Archivo de credenciales no encontrado</h4>
                            <p class="text-sm text-amber-800 dark:text-amber-300 mb-2">
                                El archivo de credenciales de Google no se encuentra en la ruta esperada.
                            </p>
                            <div class="bg-amber-100 dark:bg-amber-900/30 rounded p-2 mb-2">
                                <p class="text-xs font-semibold text-amber-900 dark:text-amber-200 mb-1">Rutas verificadas:</p>
                                @if(isset($calendarInfo['possible_paths']))
                                    @foreach($calendarInfo['possible_paths'] as $path)
                                        <p class="text-xs font-mono text-amber-800 dark:text-amber-300 break-all {{ file_exists($path) ? 'text-emerald-700 dark:text-emerald-300' : '' }}">
                                            {{ file_exists($path) ? '✓ ' : '✗ ' }}{{ $path }}
                                        </p>
                                    @endforeach
                                @else
                                    <p class="text-xs font-mono text-amber-900 dark:text-amber-200 break-all">
                                        {{ $calendarInfo['credentials_path'] ?? $credentialsError }}
                                    </p>
                                @endif
                            </div>
                            <p class="text-xs text-amber-700 dark:text-amber-400">
                                <strong>Instrucciones:</strong> Sube el archivo <code class="bg-amber-200 dark:bg-amber-800 px-1 rounded">google-credentials.json</code> a esa ubicación en el servidor. 
                                Puedes obtener este archivo desde <a href="https://console.cloud.google.com/" target="_blank" class="underline">Google Cloud Console</a> en la sección de credenciales OAuth2.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($calendarInfo && $calendarInfo['credentials_exists'] && $isAuthorized)
                <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg text-blue-800 dark:text-blue-200 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>
                            <strong>Calendario en uso:</strong> 
                            {{ $calendarInfo['calendar_id_in_use'] === 'primary' ? 'Calendario Principal (primary)' : $calendarInfo['calendar_id_in_use'] }}
                            @if($calendarInfo['calendar_id_in_use'] === 'primary')
                                <span class="text-xs opacity-75">(o WEBSOLUTIONS-TEST si existe)</span>
                            @endif
                            @if($calendarInfo['authorized_email'])
                                <br><strong>Cuenta:</strong> {{ $calendarInfo['authorized_email'] }}
                            @endif
                        </span>
                    </div>
                </div>
            @endif
            
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
                            <a href="{{ route('walee.dashboard') }}" class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg bg-gradient-to-r from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 hover:from-slate-300 hover:to-slate-400 dark:hover:from-slate-600 dark:hover:to-slate-700 text-slate-900 dark:text-white font-semibold text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                <svg class="w-4 h-4 sm:w-5 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span class="hidden sm:inline">Volver</span>
                                <span class="sm:hidden">Volver</span>
                            </a>
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
                            <a href="{{ route('walee.calendario.aplicaciones.mensual') }}" class="px-3 py-2 sm:px-4 sm:py-2 rounded-lg bg-slate-500 hover:bg-slate-600 text-white font-semibold text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                <svg class="w-4 h-4 sm:w-5 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="hidden sm:inline">Vista Mensual</span>
                                <span class="sm:hidden">Mensual</span>
                            </a>
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
                                                <div class="group relative">
                                                    <button 
                                                        onclick="event.preventDefault(); showEventoDetail('{{ $evento->google_event_id ?? '' }}', '{{ addslashes($titulo) }}', '{{ addslashes($evento->descripcion ?? '') }}', '{{ $fechaInicio->format('Y-m-d H:i') }}', '{{ $evento->ubicacion ?? '' }}', '{{ $fechaInicio->format('Y-m-d\TH:i') }}', {{ isset($evento->has_accepted) && $evento->has_accepted ? 'true' : 'false' }}, {{ isset($evento->has_declined) && $evento->has_declined ? 'true' : 'false' }}, {{ isset($evento->has_tentative) && $evento->has_tentative ? 'true' : 'false' }});"
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
                                                            @if(isset($evento->has_accepted) && $evento->has_accepted)
                                                                <span class="text-[9px] font-medium px-1.5 py-0.5 rounded bg-emerald-200 dark:bg-emerald-800/50 text-emerald-700 dark:text-emerald-300 flex items-center gap-0.5" title="Invitado aceptó">
                                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Aceptado
                                                                </span>
                                                            @endif
                                                            @if(isset($evento->has_declined) && $evento->has_declined)
                                                                <span class="text-[9px] font-medium px-1.5 py-0.5 rounded bg-red-200 dark:bg-red-800/50 text-red-700 dark:text-red-300 flex items-center gap-0.5" title="Invitado rechazó">
                                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Rechazado
                                                                </span>
                                                            @endif
                                                            @if(isset($evento->has_tentative) && $evento->has_tentative)
                                                                <span class="text-[9px] font-medium px-1.5 py-0.5 rounded bg-amber-200 dark:bg-amber-800/50 text-amber-700 dark:text-amber-300 flex items-center gap-0.5" title="Invitado respondió tal vez">
                                                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Tal vez
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                        <p class="text-sm md:text-sm font-medium text-slate-700 dark:text-slate-300 mt-1.5 line-clamp-2">{{ $titulo }}</p>
                                                    </button>
                                                    @if(isset($evento->google_event_id) && $evento->google_event_id)
                                                        <div class="absolute top-1 right-1 flex gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <button 
                                                                onclick="event.stopPropagation(); showEditarEventoModal('{{ $evento->google_event_id }}', '{{ addslashes($titulo) }}', '{{ addslashes($evento->descripcion ?? '') }}', '{{ $fechaInicio->format('Y-m-d\TH:i') }}');"
                                                                class="p-0.5 rounded bg-blue-500/20 hover:bg-blue-500/40 dark:bg-blue-400/20 dark:hover:bg-blue-400/40 text-blue-600 dark:text-blue-400 border border-blue-500/30 dark:border-blue-400/30 transition-all backdrop-blur-sm"
                                                                title="Editar">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                            </button>
                                                            <button 
                                                                onclick="event.stopPropagation(); eliminarEvento('{{ $evento->google_event_id }}');"
                                                                class="p-0.5 rounded bg-red-500/20 hover:bg-red-500/40 dark:bg-red-400/20 dark:hover:bg-red-400/40 text-red-600 dark:text-red-400 border border-red-500/30 dark:border-red-400/30 transition-all backdrop-blur-sm"
                                                                title="Eliminar">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
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
            
            // Generar opciones del select de clientes
            const clientesOptions = `
                <option value="">Seleccionar cliente...</option>
                @foreach($clientesConEmail as $cliente)
                <option value="{{ $cliente->email }}">{{ $cliente->name }} ({{ $cliente->email }})</option>
                @endforeach
            `;
            
            Swal.fire({
                title: 'Nueva Cita',
                html: `
                    <form id="nuevoEventoForm" class="space-y-3 text-left">
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Título</label>
                            <input type="text" id="evento_titulo" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Invitado (Elegir un email de cliente)</label>
                            <select id="evento_cliente_email" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                                ${clientesOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Fecha y Hora</label>
                            <input type="datetime-local" id="evento_fecha" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                buttonsStyling: true,
                reverseButtons: true, // Cancelar a la izquierda, Guardar a la derecha
                preConfirm: async () => {
                    const titulo = document.getElementById('evento_titulo').value;
                    const fecha = document.getElementById('evento_fecha').value;
                    const clienteEmail = document.getElementById('evento_cliente_email').value;
                    
                    if (!titulo || !fecha) {
                        Swal.showValidationMessage('Título y fecha son requeridos');
                        return false;
                    }
                    
                    if (!clienteEmail) {
                        Swal.showValidationMessage('Debes seleccionar un cliente como invitado');
                        return false;
                    }
                    
                    // El cliente seleccionado es el invitado
                    const emailFinal = clienteEmail;
                    
                    // Construir descripción con información del cliente
                    const descripcion = `Cliente/Invitado: ${clienteEmail}`;
                    
                    try {
                        const response = await fetch('{{ route("walee.calendario.aplicaciones.crear") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                titulo: titulo,
                                fecha_inicio: fecha,
                                descripcion: descripcion,
                                invitado_email: emailFinal,
                            }),
                        });
                        
                        // Verificar si la respuesta es JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Respuesta no es JSON:', text);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error del servidor',
                                html: `
                                    <p>El servidor devolvió una respuesta inesperada (${response.status}).</p>
                                    <p class="text-xs mt-2">Por favor, revisa los logs del servidor o contacta al administrador.</p>
                                    <details class="mt-2 text-xs">
                                        <summary>Detalles técnicos</summary>
                                        <pre class="text-left mt-2 p-2 bg-slate-100 dark:bg-slate-800 rounded overflow-auto max-h-40">${text.substring(0, 500)}</pre>
                                    </details>
                                `,
                                confirmButtonColor: '#ef4444'
                            });
                            return false;
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cita creada',
                                text: 'La cita se ha creado y sincronizado con Google Calendar',
                                confirmButtonColor: '#8b5cf6'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            let errorMessage = data.message || 'Error al crear la cita';
                            
                            // Si necesita autorización, mostrar botón para conectar
                            if (data.needs_auth) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Autorización requerida',
                                    html: `
                                        <p>${errorMessage}</p>
                                        <p class="mt-2 text-sm">Necesitas conectar tu cuenta de Google Calendar primero.</p>
                                    `,
                                    showCancelButton: true,
                                    confirmButtonText: 'Conectar Google Calendar',
                                    cancelButtonText: 'Cancelar',
                                    confirmButtonColor: '#8b5cf6',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '{{ route("google-calendar.auth") }}';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                    confirmButtonColor: '#ef4444'
                                });
                            }
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
            
            // Generar opciones del select de clientes
            const clientesOptions = `
                <option value="">Seleccionar cliente...</option>
                @foreach($clientesConEmail as $cliente)
                <option value="{{ $cliente->email }}">{{ $cliente->name }} ({{ $cliente->email }})</option>
                @endforeach
            `;
            
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
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Invitado (Elegir un email de cliente)</label>
                            <select id="evento_cliente_email" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                                ${clientesOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Fecha y Hora</label>
                            <input type="datetime-local" id="evento_fecha" value="${fechaHora}" required
                                class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true, // Cancelar a la izquierda, Guardar a la derecha
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                preConfirm: async () => {
                    const titulo = document.getElementById('evento_titulo').value;
                    const fecha = document.getElementById('evento_fecha').value;
                    const clienteEmail = document.getElementById('evento_cliente_email').value;
                    
                    if (!titulo || !fecha) {
                        Swal.showValidationMessage('Título y fecha son requeridos');
                        return false;
                    }
                    
                    if (!clienteEmail) {
                        Swal.showValidationMessage('Debes seleccionar un cliente como invitado');
                        return false;
                    }
                    
                    // El cliente seleccionado es el invitado
                    const emailFinal = clienteEmail;
                    
                    // Construir descripción con información del cliente
                    const descripcion = `Cliente/Invitado: ${clienteEmail}`;
                    
                    try {
                        const response = await fetch('{{ route("walee.calendario.aplicaciones.crear") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                titulo: titulo,
                                fecha_inicio: fecha,
                                descripcion: descripcion,
                                invitado_email: emailFinal,
                            }),
                        });
                        
                        // Verificar si la respuesta es JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Respuesta no es JSON:', text);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error del servidor',
                                html: `
                                    <p>El servidor devolvió una respuesta inesperada (${response.status}).</p>
                                    <p class="text-xs mt-2">Por favor, revisa los logs del servidor o contacta al administrador.</p>
                                    <details class="mt-2 text-xs">
                                        <summary>Detalles técnicos</summary>
                                        <pre class="text-left mt-2 p-2 bg-slate-100 dark:bg-slate-800 rounded overflow-auto max-h-40">${text.substring(0, 500)}</pre>
                                    </details>
                                `,
                                confirmButtonColor: '#ef4444'
                            });
                            return false;
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cita creada',
                                text: 'La cita se ha creado y sincronizado con Google Calendar',
                                confirmButtonColor: '#8b5cf6'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            let errorMessage = data.message || 'Error al crear la cita';
                            
                            // Si necesita autorización, mostrar botón para conectar
                            if (data.needs_auth) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Autorización requerida',
                                    html: `
                                        <p>${errorMessage}</p>
                                        <p class="mt-2 text-sm">Necesitas conectar tu cuenta de Google Calendar primero.</p>
                                    `,
                                    showCancelButton: true,
                                    confirmButtonText: 'Conectar Google Calendar',
                                    cancelButtonText: 'Cancelar',
                                    confirmButtonColor: '#8b5cf6',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '{{ route("google-calendar.auth") }}';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                    confirmButtonColor: '#ef4444'
                                });
                            }
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
        
        function showEventoDetail(eventoId, titulo, descripcion, fecha, ubicacion, fechaInput, hasAccepted = false, hasDeclined = false, hasTentative = false) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Extraer información del invitado de la descripción
            let invitadoEmail = '';
            let descripcionLimpia = descripcion || '';
            
            if (descripcion && descripcion.includes('Cliente/Invitado:')) {
                const match = descripcion.match(/Cliente\/Invitado:\s*([^\n\r]+)/);
                if (match && match[1]) {
                    invitadoEmail = match[1].trim();
                    // Remover la línea del invitado de la descripción
                    descripcionLimpia = descripcion.replace(/Cliente\/Invitado:\s*[^\n\r]+/g, '').trim();
                }
            }
            
            let buttonsHtml = '';
            if (eventoId) {
                buttonsHtml = `
                    <div class="flex gap-2 mt-4">
                        <button onclick="Swal.close(); showEditarEventoModal('${eventoId}', '${titulo.replace(/'/g, "\\'")}', '${(descripcion || '').replace(/'/g, "\\'")}', '${fechaInput}');" class="flex-1 px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 dark:bg-blue-400/10 dark:hover:bg-blue-400/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 dark:border-blue-400/30 rounded-lg transition-all text-sm">
                            Editar
                        </button>
                        <button onclick="Swal.close(); eliminarEvento('${eventoId}');" class="flex-1 px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 dark:bg-red-400/10 dark:hover:bg-red-400/20 text-red-600 dark:text-red-400 border border-red-500/30 dark:border-red-400/30 rounded-lg transition-all text-sm">
                            Eliminar
                        </button>
                    </div>
                `;
            }
            
            let responseBadge = '';
            if (hasAccepted) {
                responseBadge = `
                    <div class="flex items-center gap-2 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">El invitado ha aceptado la invitación</span>
                    </div>
                `;
            } else if (hasDeclined) {
                responseBadge = `
                    <div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-red-700 dark:text-red-300">El invitado ha rechazado la invitación</span>
                    </div>
                `;
            } else if (hasTentative) {
                responseBadge = `
                    <div class="flex items-center gap-2 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-amber-700 dark:text-amber-300">El invitado respondió "Tal vez"</span>
                    </div>
                `;
            }
            
            Swal.fire({
                title: titulo || 'Evento',
                html: `
                    <div class="text-left space-y-4">
                        ${fecha ? `
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-0.5">Fecha y Hora</p>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">${fecha}</p>
                                </div>
                            </div>
                        ` : ''}
                        ${invitadoEmail ? `
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200 dark:border-emerald-700">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide mb-1">Invitado</p>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-100">${invitadoEmail}</p>
                                        <a href="mailto:${invitadoEmail}" class="p-1 rounded-md bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 transition-all" title="Enviar email">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                        ${ubicacion ? `
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-0.5">Ubicación</p>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">${ubicacion}</p>
                                </div>
                            </div>
                        ` : ''}
                        ${descripcionLimpia ? `
                            <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-0.5">Descripción</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap">${descripcionLimpia}</p>
                                </div>
                            </div>
                        ` : ''}
                        ${responseBadge}
                        ${buttonsHtml}
                    </div>
                `,
                showCancelButton: false,
                showConfirmButton: false,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                buttonsStyling: false,
                didOpen: () => {
                    // Agregar X roja en la esquina superior izquierda de la modal (afuera)
                    const swalContainer = document.querySelector('.swal2-popup');
                    if (swalContainer) {
                        const closeBtn = document.createElement('button');
                        closeBtn.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        `;
                        closeBtn.className = 'absolute -top-3 -left-3 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all z-50 cursor-pointer';
                        closeBtn.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                        closeBtn.onclick = () => Swal.close();
                        swalContainer.style.position = 'relative';
                        swalContainer.appendChild(closeBtn);
                    }
                }
            });
        }
        
        function showEditarEventoModal(eventoId, tituloActual, descripcionActual, fechaActual) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Generar opciones del select de clientes
            const clientesOptions = `
                <option value="">Seleccionar cliente...</option>
                @foreach($clientesConEmail as $cliente)
                <option value="{{ $cliente->email }}">{{ $cliente->name }} ({{ $cliente->email }})</option>
                @endforeach
            `;
            
            // Extraer email del cliente de la descripción si existe
            let emailClienteSeleccionado = '';
            if (descripcionActual && descripcionActual.includes('Cliente/Invitado:')) {
                const match = descripcionActual.match(/Cliente\/Invitado:\s*([^\s]+)/);
                if (match && match[1]) {
                    emailClienteSeleccionado = match[1].trim();
                }
            }
            
            Swal.fire({
                title: 'Editar Evento',
                html: `
                    <form id="editarEventoForm" class="space-y-3 text-left">
                            <div>
                                <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Título</label>
                                <input type="text" id="editar_titulo" value="${tituloActual.replace(/"/g, '&quot;')}" required
                                    class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Invitado (Elegir un email de cliente)</label>
                                <select id="editar_cliente_email" 
                                    class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                                    ${clientesOptions}
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Fecha y Hora</label>
                                <input type="datetime-local" id="editar_fecha" value="${fechaActual}" required
                                    class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none">
                            </div>
                            <div class="pt-2">
                                <button type="button" id="editar_guardar_btn" class="w-full px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white font-semibold rounded-lg transition-all">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                `,
                showCancelButton: false,
                showConfirmButton: false,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                buttonsStyling: false,
                didOpen: () => {
                    // Agregar X roja en la esquina superior izquierda de la modal (afuera)
                    const swalContainer = document.querySelector('.swal2-popup');
                    if (swalContainer) {
                        const closeBtn = document.createElement('button');
                        closeBtn.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        `;
                        closeBtn.className = 'absolute -top-3 -left-3 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all z-50 cursor-pointer';
                        closeBtn.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                        closeBtn.onclick = () => Swal.close();
                        swalContainer.style.position = 'relative';
                        swalContainer.appendChild(closeBtn);
                    }
                    
                    // Seleccionar el cliente si existe en la descripción
                    if (emailClienteSeleccionado) {
                        const select = document.getElementById('editar_cliente_email');
                        if (select) {
                            select.value = emailClienteSeleccionado;
                        }
                    }
                    
                    // Agregar evento al botón Guardar
                    const guardarBtn = document.getElementById('editar_guardar_btn');
                    if (guardarBtn) {
                        guardarBtn.onclick = async () => {
                            const titulo = document.getElementById('editar_titulo').value;
                            const fecha = document.getElementById('editar_fecha').value;
                            const clienteEmail = document.getElementById('editar_cliente_email').value;
                            
                            if (!titulo || !fecha) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Campos requeridos',
                                    text: 'Título y fecha son requeridos',
                                    confirmButtonColor: '#8b5cf6'
                                });
                                return;
                            }
                            
                            // Construir descripción con información del cliente
                            const descripcion = clienteEmail ? `Cliente/Invitado: ${clienteEmail}` : '';
                            
                            try {
                                const response = await fetch('{{ route("walee.calendario.aplicaciones.actualizar") }}', {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    },
                                    body: JSON.stringify({
                                        evento_id: eventoId,
                                        titulo: titulo,
                                        fecha_inicio: fecha,
                                        descripcion: descripcion,
                                        invitado_email: clienteEmail,
                                    }),
                                });
                                
                                const contentType = response.headers.get('content-type');
                                if (!contentType || !contentType.includes('application/json')) {
                                    const text = await response.text();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error del servidor',
                                        html: `<p>El servidor devolvió una respuesta inesperada (${response.status}).</p>`,
                                        confirmButtonColor: '#ef4444'
                                    });
                                    return;
                                }
                                
                                const data = await response.json();
                                
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Cita actualizada',
                                        text: 'La cita se ha actualizado y sincronizado con Google Calendar',
                                        confirmButtonColor: '#8b5cf6'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message || 'Error al actualizar la cita',
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
                        };
                    }
                }
            });
        }
        
        function eliminarEvento(eventoId) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                title: '¿Eliminar cita?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                reverseButtons: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch('{{ route("walee.calendario.aplicaciones.eliminar") }}', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                evento_id: eventoId,
                            }),
                        });
                        
                        // Verificar si la respuesta es JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Respuesta no es JSON:', text);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error del servidor',
                                html: `
                                    <p>El servidor devolvió una respuesta inesperada (${response.status}).</p>
                                    <p class="text-xs mt-2">Por favor, revisa los logs del servidor o contacta al administrador.</p>
                                `,
                                confirmButtonColor: '#ef4444'
                            });
                            return;
                        }
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cita eliminada',
                                text: 'La cita se ha eliminado y se ha sincronizado con Google Calendar',
                                confirmButtonColor: '#8b5cf6'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al eliminar la cita',
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
                }
            });
        }
    </script>
    
    @include('partials.walee-support-button')
</body>
</html>

