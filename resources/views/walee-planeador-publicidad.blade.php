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
        
        .plataforma-radio input[type="radio"]:checked + label,
        input[type="radio"]:checked ~ label.plataforma-radio {
            border-color: currentColor;
        }
        
        input[type="radio"]:checked ~ label.plataforma-radio[for="plataforma_facebook"] {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        input[type="radio"]:checked ~ label.plataforma-radio[for="plataforma_instagram"] {
            border-color: #ec4899;
            background-color: #fdf2f8;
        }
        
        input[type="radio"]:checked ~ label.plataforma-radio[for="plataforma_linkedin"] {
            border-color: #1e40af;
            background-color: #eff6ff;
        }
        
        input[type="radio"]:checked ~ label.plataforma-radio[for="plataforma_twitter"] {
            border-color: #0ea5e9;
            background-color: #f0f9ff;
        }
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
        
        /* Mejorar botones en mobile */
        @media (max-width: 640px) {
            button, a[class*="px-"], a[class*="py-"] {
                min-height: 44px !important;
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
                font-weight: 600 !important;
                border-radius: 0.75rem !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            }
            
            button:active, a[class*="px-"]:active {
                transform: scale(0.98) !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
            }
            
            /* Botones principales del header */
            .header-actions button,
            .header-actions a {
                min-width: 44px !important;
                padding: 0.625rem 1rem !important;
            }
            
            /* Botones del modal */
            #evento-form button[type="submit"],
            #evento-form button[type="button"] {
                min-height: 48px !important;
                padding: 0.875rem 1.25rem !important;
                font-size: 1rem !important;
            }
            
            /* Botón de generar AI */
            #btnGenerarTexto {
                min-height: 44px !important;
                padding: 0.75rem 1rem !important;
            }
        }
        
        /* SweetAlert Dark/Light Mode */
        .dark-swal {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }
        
        .light-swal {
            background-color: #ffffff !important;
            color: #1e293b !important;
        }
        
        .dark-swal-title {
            color: #e2e8f0 !important;
        }
        
        .light-swal-title {
            color: #1e293b !important;
        }
        
        .dark-swal-html {
            color: #e2e8f0 !important;
        }
        
        .light-swal-html {
            color: #1e293b !important;
        }
        
        .dark-swal-confirm {
            background-color: #8b5cf6 !important;
            color: #ffffff !important;
        }
        
        .light-swal-confirm {
            background-color: #8b5cf6 !important;
            color: #ffffff !important;
        }
        
        .dark-swal-cancel {
            background-color: #475569 !important;
            color: #ffffff !important;
        }
        
        .light-swal-cancel {
            background-color: #6b7280 !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $vista = request()->get('vista', 'semanal');
        $mes = request()->get('mes', now()->month);
        $ano = request()->get('ano', now()->year);
        
        // Si es vista semanal, calcular la semana usando método similar a meses
        if ($vista === 'semanal') {
            $semanaParam = request()->get('semana');
            // Si viene parámetro de semana (formato Y-W), parsearlo
            if ($semanaParam && strpos($semanaParam, '-') !== false) {
                $partes = explode('-', $semanaParam);
                $anoSemana = isset($partes[0]) ? (int)trim($partes[0]) : null;
                $numSemana = isset($partes[1]) ? (int)trim($partes[1]) : null;
                
                if ($anoSemana && $numSemana && $numSemana >= 1 && $numSemana <= 53) {
                    try {
                        // Usar setISODate para obtener la fecha de la semana
                        // setISODate maneja correctamente las semanas ISO, incluyendo el cambio de año
                        // setISODate devuelve el lunes de la semana ISO
                        $fechaSemana = \Carbon\Carbon::now()->setISODate($anoSemana, $numSemana);
                        // Ajustar al domingo como inicio de semana (restar 1 día del lunes ISO)
                        // Esto mantiene la semana ISO correcta pero muestra desde el domingo
                        $inicioSemana = $fechaSemana->copy()->subDay()->startOfWeek(\Carbon\Carbon::SUNDAY);
                        // Si al restar 1 día cambiamos de semana ISO, usar el lunes original
                        if ($inicioSemana->format('o') != $anoSemana || $inicioSemana->format('W') != $numSemana) {
                            $inicioSemana = $fechaSemana->copy();
                        }
                        $finSemana = $inicioSemana->copy()->addDays(6);
                    } catch (\Exception $e) {
                        // Si falla, usar la semana actual
                        $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                        $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                    }
                } else {
                    $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                    $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                }
            } else {
                // Si no viene parámetro, usar la semana actual
                $inicioSemana = now()->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                $finSemana = now()->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
            }
            
            // Calcular semana anterior y siguiente para los botones de navegación en el header
            $anoActualSemana = (int)$inicioSemana->format('o');
            $numActualSemana = (int)$inicioSemana->format('W');
            
            // Calcular semana siguiente
            try {
                $fechaSiguiente = \Carbon\Carbon::now()->setISODate($anoActualSemana, $numActualSemana)->addWeek();
                $anoSemanaSiguiente = (int)$fechaSiguiente->format('o');
                $numSemanaSiguiente = (int)$fechaSiguiente->format('W');
            } catch (\Exception $e) {
                // Si falla, calcular manualmente
                $numSemanaSiguiente = $numActualSemana + 1;
                $anoSemanaSiguiente = $anoActualSemana;
                if ($numSemanaSiguiente > 53) {
                    $numSemanaSiguiente = 1;
                    $anoSemanaSiguiente = $anoActualSemana + 1;
                }
            }
            
            // Calcular semana anterior
            try {
                $fechaAnterior = \Carbon\Carbon::now()->setISODate($anoActualSemana, $numActualSemana)->subWeek();
                $anoSemanaAnterior = (int)$fechaAnterior->format('o');
                $numSemanaAnterior = (int)$fechaAnterior->format('W');
            } catch (\Exception $e) {
                // Si falla, calcular manualmente
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
        
        // Buscar el Client correspondiente para el botón de perfil
        $client = \App\Models\Client::where('email', $cliente->correo)
            ->orWhere('name', 'like', '%' . $cliente->nombre_empresa . '%')
            ->first();
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
            <div class="mb-4 md:mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="rounded-xl bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 p-3 md:p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4">
                        <div class="flex items-center gap-2 md:gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base md:text-lg font-semibold text-violet-900 dark:text-violet-300 truncate">{{ $cliente->nombre_empresa }}</h3>
                                <p class="text-xs md:text-sm text-violet-700 dark:text-violet-400 truncate">Planeador de Publicidad</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 flex-wrap header-actions">
                            @if($client)
                                <a href="{{ route('walee.cliente.detalle', $client->id) }}" class="px-2 py-1.5 sm:px-2.5 sm:py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 font-medium text-xs transition-all flex items-center justify-center shadow-sm hover:shadow">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($vista === 'semanal')
                                <a href="?vista=semanal&semana={{ $semanaAnteriorFormato }}" class="px-3 py-2.5 sm:px-4 sm:py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Semana Anterior</span>
                                    <span class="sm:inline">Anterior</span>
                                </a>
                                <a href="?vista=semanal&semana={{ $semanaSiguienteFormato }}" class="px-3 py-2.5 sm:px-4 sm:py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Semana Siguiente</span>
                                    <span class="sm:inline">Siguiente</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contenido Principal: Calendario -->
            <div class="flex-1 min-w-0">
                    @if($vista === 'semanal')
                        <!-- Vista Semanal -->
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
                                        
                                        // Ordenar eventos por hora
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
                                        </div>
                                        
                                        <!-- Eventos del día -->
                                        <div class="flex-1 p-3 md:p-2 space-y-2 md:space-y-1.5 overflow-y-auto">
                                            @if($eventosOrdenados->count() > 0)
                                                @foreach($eventosOrdenados as $evento)
                                                    @php
                                                        $ahora = now();
                                                        $yaPaso = $evento->fecha_inicio->lt($ahora);
                                                        
                                                        // Si ya pasó el tiempo → verde "publicada"
                                                        // Si aún falta → amarillo "programada"
                                                        if ($yaPaso) {
                                                            $estadoVisual = 'publicado';
                                                            $colorBg = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-l-3 border-emerald-500';
                                                            $claseBtn = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
                                                        } else {
                                                            $estadoVisual = 'programado';
                                                            $colorBg = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 border-l-3 border-yellow-500';
                                                            $claseBtn = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300';
                                                        }
                                                    @endphp
                                                    <button 
                                                        onclick="event.preventDefault(); showEventoDetail({{ $evento->id }});"
                                                        class="w-full text-left px-3 py-2.5 md:px-2 md:py-1.5 rounded-lg md:rounded text-sm md:text-xs font-medium transition-all hover:opacity-80 {{ $claseBtn }} shadow-sm md:shadow-none"
                                                        style="border-left: 4px solid {{ $yaPaso ? '#10b981' : '#eab308' }};"
                                                        title="{{ $evento->titulo }}"
                                                    >
                                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-1.5">
                                                            <div class="flex items-center justify-between md:justify-start gap-2">
                                                                <span class="text-xs md:text-[10px] font-semibold">{{ $evento->fecha_inicio->format('H:i') }}</span>
                                                                @if($yaPaso)
                                                                    <span class="text-[10px] md:text-[9px] font-medium px-2 py-0.5 rounded-full bg-emerald-200 dark:bg-emerald-800/50">Publicado</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($evento->titulo && strlen($evento->titulo) > 0)
                                                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 md:hidden line-clamp-2">{{ $evento->titulo }}</p>
                                                        @endif
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
                    @else
                        <!-- Vista Mensual -->
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
                                                    $ahora = now();
                                                    $yaPaso = $evento->fecha_inicio->lt($ahora);
                                                    
                                                    // Si ya pasó el tiempo → verde "publicada"
                                                    // Si aún falta → amarillo "programada"
                                                    if ($yaPaso) {
                                                        $estadoVisual = 'publicado';
                                                        $colorBg = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-l-3 border-emerald-500';
                                                        $claseBtn = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
                                                    } else {
                                                        $estadoVisual = 'programado';
                                                        $colorBg = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 border-l-3 border-yellow-500';
                                                        $claseBtn = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300';
                                                    }
                                                @endphp
                                                <button 
                                                    onclick="event.preventDefault(); showEventoDetail({{ $evento->id }});"
                                                    class="w-full text-left px-2 py-1 rounded text-xs font-medium transition-all hover:opacity-80 {{ $claseBtn }}"
                                                    style="border-left: 3px solid {{ $yaPaso ? '#10b981' : '#eab308' }};"
                                                    title="{{ $evento->titulo }}"
                                                >
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-[10px] font-semibold opacity-75">{{ $evento->fecha_inicio->format('H:i') }}</span>
                                                        <span class="flex-1 truncate">{{ $evento->titulo }}</span>
                                                        @if($yaPaso)
                                                            <span class="text-[9px] font-medium opacity-70">Publicado</span>
                                                        @endif
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
                    @endif
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
                
                <div class="flex gap-2 sm:gap-2 pt-2">
                    <button type="submit" class="flex-1 px-4 py-3 sm:py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-semibold text-base sm:text-sm transition-all shadow-md hover:shadow-lg active:scale-95">
                        Guardar
                    </button>
                    <button type="button" onclick="closeEventoModal()" class="px-4 py-3 sm:py-2 rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-base sm:text-sm transition-all shadow-md hover:shadow-lg active:scale-95">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function navegarASemana() {
            const semanaInput = document.getElementById('selectSemana').value;
            if (semanaInput) {
                const [ano, semana] = semanaInput.split('-W');
                window.location.href = `?vista=semanal&semana=${ano}-${semana}`;
            }
        }
        
        function navegarAFecha() {
            // Para vista mensual, redirigir a semanal de ese mes
            const mes = document.getElementById('selectMes').value;
            const ano = document.getElementById('selectAno').value;
            const fecha = new Date(ano, mes - 1, 15); // Usar día 15 para estar en medio del mes
            const inicioSemana = new Date(fecha);
            inicioSemana.setDate(fecha.getDate() - fecha.getDay()); // Ir al domingo de esa semana
            
            const anoSemana = inicioSemana.getFullYear();
            const semana = getWeekNumber(inicioSemana);
            window.location.href = `?vista=semanal&semana=${anoSemana}-${semana}`;
        }
        
        function getWeekNumber(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
            return Math.ceil((((d - yearStart) / 86400000) + 1)/7);
        }
        
        function showProgramarPublicacionModal() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Establecer fecha por defecto: hora actual
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const fechaDefault = `${year}-${month}-${day}T${hours}:${minutes}`;
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '900px';
            } else if (isTablet) {
                modalWidth = '600px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            const html = `
                <form id="programar-publicacion-form" class="space-y-3 text-left">
                    <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                    
                    <!-- Prompt personalizado (arriba) -->
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5">Prompt Personalizado (opcional)</label>
                        <textarea name="prompt_personalizado" id="prompt_personalizado" rows="1" placeholder="Describe el tipo de publicación que quieres generar con AI..." class="w-full px-2 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"></textarea>
                    </div>
                    
                    <!-- Layout horizontal: Imagen y Texto lado a lado -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Imagen -->
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5">Imagen</label>
                            <div class="relative">
                                <input type="file" name="imagen" id="imagen_publicacion" accept="image/*" class="hidden" onchange="previewImage(event)">
                                <label for="imagen_publicacion" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed ${isDarkMode ? 'border-slate-700 bg-slate-800/50 hover:border-violet-500' : 'border-slate-300 bg-slate-50 hover:border-violet-500'} rounded-lg cursor-pointer transition-colors">
                                    <div id="imagePreview" class="hidden w-full h-full rounded-lg overflow-hidden">
                                        <img id="previewImg" src="" alt="Preview" class="w-full h-full object-cover">
                                    </div>
                                    <div id="imagePlaceholder" class="flex flex-col items-center justify-center p-4">
                                        <svg class="w-8 h-8 ${isDarkMode ? 'text-slate-500' : 'text-slate-400'} mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}">Subir imagen</p>
                                    </div>
                                </label>
                                <button type="button" id="removeImageBtn" onclick="removeImage()" class="hidden mt-1.5 px-2 py-1 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-medium transition-all">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Texto con AI -->
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5">Texto</label>
                            <div class="flex gap-2">
                                <textarea name="texto" id="texto_publicacion" rows="5" placeholder="Escribe el texto o genera con AI..." class="flex-1 px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg placeholder-slate-500 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all resize-none"></textarea>
                                <button type="button" onclick="generarTextoAI()" id="btnGenerarTexto" class="px-4 py-2.5 sm:px-3 sm:py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white text-sm sm:text-sm font-semibold transition-all flex items-center justify-center gap-1.5 whitespace-nowrap h-fit shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <span class="hidden sm:inline text-xs">AI</span>
                                    <span class="sm:hidden">AI</span>
                                </button>
                            </div>
                            <div id="aiLoading" class="hidden text-xs text-violet-600 dark:text-violet-400 flex items-center gap-2 mt-1">
                                <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Generando...
                            </div>
                        </div>
                    </div>
                    
                    <!-- Plataforma con iconos y Fecha -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5">Plataforma</label>
                            <div class="grid grid-cols-4 gap-2">
                                <input type="radio" name="plataforma_publicacion" id="plataforma_facebook" value="facebook" checked class="hidden">
                                <label for="plataforma_facebook" class="flex items-center justify-center p-2 rounded-lg border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20 cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all plataforma-radio" title="Facebook">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </label>
                                
                                <input type="radio" name="plataforma_publicacion" id="plataforma_instagram" value="instagram" class="hidden">
                                <label for="plataforma_instagram" class="flex items-center justify-center p-2 rounded-lg border-2 ${isDarkMode ? 'border-slate-700 bg-slate-800 hover:bg-slate-700' : 'border-slate-300 bg-slate-50 hover:bg-slate-100'} cursor-pointer transition-all plataforma-radio" title="Instagram">
                                    <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </label>
                                
                                <input type="radio" name="plataforma_publicacion" id="plataforma_linkedin" value="linkedin" class="hidden">
                                <label for="plataforma_linkedin" class="flex items-center justify-center p-2 rounded-lg border-2 ${isDarkMode ? 'border-slate-700 bg-slate-800 hover:bg-slate-700' : 'border-slate-300 bg-slate-50 hover:bg-slate-100'} cursor-pointer transition-all plataforma-radio" title="LinkedIn">
                                    <svg class="w-4 h-4 text-blue-700 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </label>
                                
                                <input type="radio" name="plataforma_publicacion" id="plataforma_twitter" value="twitter" class="hidden">
                                <label for="plataforma_twitter" class="flex items-center justify-center p-2 rounded-lg border-2 ${isDarkMode ? 'border-slate-700 bg-slate-800 hover:bg-slate-700' : 'border-slate-300 bg-slate-50 hover:bg-slate-100'} cursor-pointer transition-all plataforma-radio" title="Twitter">
                                    <svg class="w-4 h-4 text-sky-500 dark:text-sky-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1.5">Fecha y Hora</label>
                            <input type="datetime-local" name="fecha_publicacion" id="fecha_publicacion" required value="${fechaDefault}" class="w-full px-3 py-2 text-sm ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 focus:outline-none transition-all">
                        </div>
                    </div>
                </form>
            `;
            
            Swal.fire({
                title: 'Crear Publicación',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Crear Publicación',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    // Pre-seleccionar Facebook y actualizar estilos
                    document.getElementById('plataforma_facebook').checked = true;
                    updatePlataformaStyles();
                    
                    // Agregar event listeners a los radio buttons
                    document.querySelectorAll('input[name="plataforma_publicacion"]').forEach(radio => {
                        radio.addEventListener('change', updatePlataformaStyles);
                    });
                },
                preConfirm: () => {
                    const form = document.getElementById('programar-publicacion-form');
                    const formData = new FormData(form);
                    const imagen = formData.get('imagen');
                    
                    // Validar que haya imagen o texto
                    if (!imagen || imagen.size === 0) {
                        if (!formData.get('texto') || formData.get('texto').trim() === '') {
                            Swal.showValidationMessage('Por favor, agrega una imagen o un texto para la publicación');
                            return false;
                        }
                    }
                    
                    return formData;
                }
            }).then(async (result) => {
                if (result.isConfirmed && result.value) {
                    const formData = result.value;
                    try {
                        Swal.fire({
                            title: 'Programando...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b',
                            customClass: {
                                popup: isDarkMode ? 'dark-swal' : 'light-swal',
                                title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                            }
                        });
                        
                        const response = await fetch('/publicidad-eventos/programar', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: formData
                        });
                        
                        const responseData = await response.json();
                        
                        if (response.ok && responseData.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Publicación creada!',
                                text: responseData.message || 'La publicación se ha creado correctamente',
                                confirmButtonColor: '#8b5cf6',
                                timer: 2000,
                                showConfirmButton: false,
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                                customClass: {
                                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            let errorMessage = 'Error al programar la publicación.';
                            if (responseData.errors) {
                                errorMessage = Object.values(responseData.errors).flat().join('\n');
                            } else if (responseData.message) {
                                errorMessage = responseData.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                confirmButtonColor: '#ef4444',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                                customClass: {
                                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo conectar con el servidor. Intenta de nuevo.',
                            confirmButtonColor: '#ef4444',
                            background: isDarkMode ? '#1e293b' : '#ffffff',
                            color: isDarkMode ? '#e2e8f0' : '#1e293b',
                            customClass: {
                                popup: isDarkMode ? 'dark-swal' : 'light-swal',
                                title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                                confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                            }
                        });
                    }
                }
            });
        }
        
        function updatePlataformaStyles() {
            // Resetear todos los estilos
            document.querySelectorAll('.plataforma-radio').forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20', 'border-pink-500', 'bg-pink-50', 'dark:bg-pink-900/20', 'border-blue-700', 'border-sky-500', 'bg-sky-50', 'dark:bg-sky-900/20');
                label.classList.add('border-slate-300', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-800');
            });
            
            // Aplicar estilos al seleccionado
            const selected = document.querySelector('input[name="plataforma_publicacion"]:checked');
            if (selected) {
                const label = document.querySelector(`label[for="${selected.id}"]`);
                if (label) {
                    label.classList.remove('border-slate-300', 'dark:border-slate-700', 'bg-slate-50', 'dark:bg-slate-800');
                    if (selected.value === 'facebook') {
                        label.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                    } else if (selected.value === 'instagram') {
                        label.classList.add('border-pink-500', 'bg-pink-50', 'dark:bg-pink-900/20');
                    } else if (selected.value === 'linkedin') {
                        label.classList.add('border-blue-700', 'bg-blue-50', 'dark:bg-blue-900/20');
                    } else if (selected.value === 'twitter') {
                        label.classList.add('border-sky-500', 'bg-sky-50', 'dark:bg-sky-900/20');
                    }
                }
            }
        }
        
        // Agregar event listeners a los radio buttons cuando se carga el DOM
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('input[name="plataforma_publicacion"]').forEach(radio => {
                    radio.addEventListener('change', updatePlataformaStyles);
                });
            });
        } else {
            document.querySelectorAll('input[name="plataforma_publicacion"]').forEach(radio => {
                radio.addEventListener('change', updatePlataformaStyles);
            });
        }
        
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImg');
                    const previewContainer = document.getElementById('imagePreview');
                    const placeholder = document.getElementById('imagePlaceholder');
                    const removeBtn = document.getElementById('removeImageBtn');
                    
                    if (preview) preview.src = e.target.result;
                    if (previewContainer) previewContainer.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                    if (removeBtn) removeBtn.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
        
        function removeImage() {
            const fileInput = document.getElementById('imagen_publicacion');
            const previewContainer = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');
            const removeBtn = document.getElementById('removeImageBtn');
            
            if (fileInput) fileInput.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
            if (removeBtn) removeBtn.classList.add('hidden');
        }
        
        async function generarTextoAI() {
            const textoInput = document.getElementById('texto_publicacion');
            const btnGenerar = document.getElementById('btnGenerarTexto');
            const aiLoading = document.getElementById('aiLoading');
            
            // Obtener la plataforma seleccionada del radio button
            const plataformaRadio = document.querySelector('input[name="plataforma_publicacion"]:checked');
            const plataforma = plataformaRadio ? plataformaRadio.value : null;
            
            const promptPersonalizado = document.getElementById('prompt_personalizado').value;
            
            btnGenerar.disabled = true;
            aiLoading.classList.remove('hidden');
            
            try {
                // Si hay prompt personalizado, usarlo; si no, usar uno genérico
                let prompt = promptPersonalizado.trim();
                if (!prompt) {
                    prompt = `Create an engaging social media post for ${plataforma || 'social media'}. Make it creative, professional, and include a clear call to action.`;
                }
                
                const response = await fetch('/publicidad-eventos/generar-texto-ai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        prompt: prompt,
                        cliente_id: '{{ $cliente->id }}',
                        cliente_nombre: '{{ $cliente->nombre_empresa }}'
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    textoInput.value = result.texto;
                } else {
                    alert('Error: ' + (result.message || 'Error al generar texto'));
                }
            } catch (error) {
                console.error('Error al generar texto AI:', error);
                alert('Error de conexión: ' + error.message);
            } finally {
                btnGenerar.disabled = false;
                aiLoading.classList.add('hidden');
            }
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
        
        async function showEventoDetail(eventoId) {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            try {
                // Mostrar loading con SweetAlert
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    customClass: {
                        popup: isDarkMode ? 'dark-swal' : 'light-swal',
                        title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    }
                });
                
                // Obtener detalles del evento
                const response = await fetch(`/publicidad-eventos/${eventoId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.evento) {
                    const evento = data.evento;
                    
                    // Formatear fecha
                    let fechaFormateada = 'No especificada';
                    if (evento.fecha_inicio) {
                        const fecha = new Date(evento.fecha_inicio);
                        fechaFormateada = fecha.toLocaleDateString('es-ES', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                    
                    // Icono de plataforma
                    let plataformaIcono = '';
                    let plataformaNombre = evento.plataforma ? evento.plataforma.charAt(0).toUpperCase() + evento.plataforma.slice(1) : 'No especificada';
                    
                    if (evento.plataforma === 'facebook') {
                        plataformaIcono = '<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
                    } else if (evento.plataforma === 'instagram') {
                        plataformaIcono = '<svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
                    } else if (evento.plataforma === 'linkedin') {
                        plataformaIcono = '<svg class="w-5 h-5 text-blue-700 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
                    } else if (evento.plataforma === 'twitter') {
                        plataformaIcono = '<svg class="w-5 h-5 text-sky-500 dark:text-sky-400" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>';
                    }
                    
                    // Estado con badge - verificar si ya pasó la fecha
                    let estadoBadge = '';
                    let fechaInicio = evento.fecha_inicio ? new Date(evento.fecha_inicio) : null;
                    let yaPaso = fechaInicio && fechaInicio < new Date();
                    
                    // Si está cancelado, mostrar cancelado
                    if (evento.estado === 'cancelado') {
                        estadoBadge = '<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300">Cancelado</span>';
                    } 
                    // Si ya pasó el tiempo → verde "Publicada"
                    else if (yaPaso) {
                        estadoBadge = '<span class="px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300">Publicada</span>';
                    } 
                    // Si aún falta → amarillo "Programada"
                    else {
                        estadoBadge = '<span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-300">Programada</span>';
                    }
                    
                    // Construir HTML del detalle
                    let imagenHTML = '';
                    if (evento.imagen_url) {
                        let imageUrl = evento.imagen_url;
                        
                        // Si la URL ya es completa (http/https), usarla directamente
                        if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://')) {
                            // URL completa, usar tal cual
                        } else if (imageUrl.startsWith('//')) {
                            // URL protocolo-relativa, agregar https:
                            imageUrl = 'https:' + imageUrl;
                        } else if (imageUrl.startsWith('/')) {
                            // Ruta absoluta, construir URL completa
                            imageUrl = window.location.origin + imageUrl;
                        } else {
                            // Ruta relativa, construir URL completa
                            if (imageUrl.includes('publicidad/')) {
                                imageUrl = window.location.origin + '/' + imageUrl;
                            } else {
                                imageUrl = window.location.origin + '/' + imageUrl;
                            }
                        }
                        
                        imagenHTML = `
                            <div class="mt-3 space-y-2">
                                <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Imagen</label>
                                <div class="relative">
                                    <img src="${imageUrl}" alt="Imagen de publicación" class="w-full h-auto rounded-lg border ${isDarkMode ? 'border-slate-700' : 'border-slate-200'}" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div style="display:none;" class="mt-2 p-3 rounded-lg ${isDarkMode ? 'bg-red-900/20 border-red-800' : 'bg-red-50 border-red-200'} border">
                                        <p class="text-xs ${isDarkMode ? 'text-red-400' : 'text-red-600'} mb-1">⚠️ No se pudo cargar la imagen</p>
                                        <a href="${imageUrl}" target="_blank" class="text-xs ${isDarkMode ? 'text-violet-400' : 'text-violet-600'} hover:underline">Abrir en nueva pestaña</a>
                                    </div>
                                    <div class="mt-2">
                                        <a href="${imageUrl}" target="_blank" class="text-xs ${isDarkMode ? 'text-violet-400' : 'text-violet-600'} hover:underline">Abrir imagen en nueva pestaña</a>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    let modalWidth = '95%';
                    if (isDesktop) {
                        modalWidth = '900px';
                    } else if (isTablet) {
                        modalWidth = '600px';
                    } else if (isMobile) {
                        modalWidth = '95%';
                    }
                    
                    // Layout horizontal: imagen a la izquierda, contenido a la derecha
                    const html = `
                        <div class="text-left ${isMobile ? 'text-xs space-y-2' : 'text-sm'}">
                            <div class="grid ${isDesktop ? 'grid-cols-2 gap-4' : 'grid-cols-1 gap-3'}">
                                <!-- Columna izquierda: Imagen (solo en desktop) -->
                                ${isDesktop && imagenHTML ? `
                                <div class="flex-shrink-0">
                                    ${imagenHTML.replace('mt-3 space-y-2', 'space-y-2')}
                                </div>
                                ` : ''}
                                
                                <!-- Columna derecha: Contenido -->
                                <div class="space-y-2.5 ${isDesktop ? '' : 'space-y-2'}">
                                    <div>
                                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Título</label>
                                        <p class="font-semibold ${isDarkMode ? 'text-white' : 'text-slate-900'}">${evento.titulo || 'Sin título'}</p>
                                    </div>
                                    
                                    <div class="grid ${isDesktop ? 'grid-cols-3' : 'grid-cols-1'} gap-3">
                                        <div>
                                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Plataforma</label>
                                            <div class="flex items-center gap-2">
                                                ${plataformaIcono}
                                                <span class="${isDarkMode ? 'text-slate-200' : 'text-slate-800'}">${plataformaNombre}</span>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Estado</label>
                                            ${estadoBadge}
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Fecha y Hora</label>
                                            <p class="${isDarkMode ? 'text-slate-200' : 'text-slate-800'} text-xs">${fechaFormateada}</p>
                                        </div>
                                    </div>
                                    
                                    ${evento.texto ? `
                                    <div>
                                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Texto</label>
                                        <p class="${isDarkMode ? 'text-slate-200' : 'text-slate-800'} whitespace-pre-wrap text-xs">${evento.texto}</p>
                                    </div>
                                    ` : ''}
                                    
                                    ${evento.descripcion ? `
                                    <div>
                                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Descripción</label>
                                        <p class="${isDarkMode ? 'text-slate-200' : 'text-slate-800'} whitespace-pre-wrap text-xs">${evento.descripcion}</p>
                                    </div>
                                    ` : ''}
                                    
                                    ${!isDesktop && imagenHTML ? `
                                    <div class="mt-2">
                                        ${imagenHTML}
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    
                    Swal.fire({
                        title: 'Detalle de Publicación',
                        html: html,
                        width: modalWidth,
                        padding: isMobile ? '1rem' : '1.5rem',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar',
                        confirmButtonColor: '#8b5cf6',
                        heightAuto: false,
                        maxHeight: isDesktop ? '80vh' : '90vh',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                        customClass: {
                            popup: isDarkMode ? 'dark-swal' : 'light-swal',
                            title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                            htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                            confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar los detalles del evento',
                        confirmButtonColor: '#ef4444',
                        background: isDarkMode ? '#1e293b' : '#ffffff',
                        color: isDarkMode ? '#e2e8f0' : '#1e293b',
                        customClass: {
                            popup: isDarkMode ? 'dark-swal' : 'light-swal',
                            title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                            confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                        }
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: error.message,
                    confirmButtonColor: '#ef4444',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    customClass: {
                        popup: isDarkMode ? 'dark-swal' : 'light-swal',
                        title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                        confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    }
                });
            }
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
        
    </script>
    @include('partials.walee-support-button')
</body>
</html>

