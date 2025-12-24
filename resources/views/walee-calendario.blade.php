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
        ::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(16, 185, 129, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        // Verificar estado de conexión con Google Calendar
        $googleCalendarService = new \App\Services\GoogleCalendarService();
        $googleCalendarConnected = $googleCalendarService->isAuthorized();
        
        $vista = request()->get('vista', 'mensual');
        $mes = request()->get('mes', now()->month);
        $ano = request()->get('ano', now()->year);
        
        // Si es vista semanal, calcular la semana
        if ($vista === 'semanal') {
            $semanaParam = request()->get('semana', now()->format('Y-W'));
            list($anoSemana, $numSemana) = explode('-', $semanaParam);
            $fechaSemana = \Carbon\Carbon::now()->setISODate($anoSemana, $numSemana);
            $inicioSemana = $fechaSemana->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $finSemana = $fechaSemana->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
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
        
        // Obtener todas las citas del período (incluyendo recurrentes)
        $citasBase = \App\Models\Cita::with('cliente')
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                // Citas que empiezan en este período
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                // O citas recurrentes que pueden aparecer en este período
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
        
        // Generar citas recurrentes
        $citas = collect();
        foreach ($citasBase as $cita) {
            if ($cita->recurrencia === 'none') {
                // Cita normal, agregarla solo si está en el período
                if ($vista === 'semanal') {
                    if ($cita->fecha_inicio->gte($inicioSemana) && $cita->fecha_inicio->lte($finSemana)) {
                        $citas->push($cita);
                    }
                } else {
                    if ($cita->fecha_inicio->month == $mes && $cita->fecha_inicio->year == $ano) {
                        $citas->push($cita);
                    }
                }
            } else {
                // Cita recurrente, generar todas las ocurrencias del período
                $fechaInicioRec = $cita->fecha_inicio->copy();
                $fechaFinRec = $cita->recurrencia_fin ? \Carbon\Carbon::parse($cita->recurrencia_fin) : ($vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth());
                $periodoInicio = $vista === 'semanal' ? $inicioSemana : $fechaActual->copy()->startOfMonth();
                $periodoFin = $vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth();
                
                // Ajustar fecha inicio si es anterior al período actual
                if ($fechaInicioRec->lt($periodoInicio)) {
                    if ($cita->recurrencia === 'semanal') {
                        $semanas = ceil($periodoInicio->diffInWeeks($fechaInicioRec));
                        $fechaInicioRec = $fechaInicioRec->copy()->addWeeks($semanas);
                    } elseif ($cita->recurrencia === 'mensual') {
                        $meses = ceil($periodoInicio->diffInMonths($fechaInicioRec));
                        $fechaInicioRec = $fechaInicioRec->copy()->addMonths($meses);
                    } elseif ($cita->recurrencia === 'anual') {
                        $anos = ceil($periodoInicio->diffInYears($fechaInicioRec));
                        $fechaInicioRec = $fechaInicioRec->copy()->addYears($anos);
                    }
                }
                
                // Generar ocurrencias hasta el fin del período o hasta recurrencia_fin
                $fechaActualCita = $fechaInicioRec->copy();
                $recurrenciaDias = $cita->recurrencia_dias ?? [];
                
                if ($cita->recurrencia === 'semanal' && !empty($recurrenciaDias)) {
                    // Recurrencia semanal con días específicos
                    $fechaActualCita = $periodoInicio->copy();
                    while ($fechaActualCita->lte($periodoFin) && $fechaActualCita->lte($fechaFinRec)) {
                        $diaSemana = $fechaActualCita->dayOfWeek; // 0=Domingo, 1=Lunes, etc.
                        $condicionFecha = $vista === 'semanal' ? true : ($fechaActualCita->month == $mes && $fechaActualCita->year == $ano);
                        if (in_array($diaSemana, $recurrenciaDias) && $condicionFecha) {
                            $citaRecurrente = clone $cita;
                            $citaRecurrente->fecha_inicio = $fechaActualCita->copy();
                            if ($cita->fecha_fin) {
                                $duracion = $cita->fecha_inicio->diffInMinutes($cita->fecha_fin);
                                $citaRecurrente->fecha_fin = $fechaActualCita->copy()->addMinutes($duracion);
                            }
                            $citas->push($citaRecurrente);
                        }
                        $fechaActualCita->addDay();
                    }
                } elseif ($cita->recurrencia === 'mensual' && !empty($recurrenciaDias)) {
                    // Recurrencia mensual con días específicos
                    foreach ($recurrenciaDias as $dia) {
                        if (is_numeric($dia) && $dia >= 1 && $dia <= 31) {
                            // Es un día del mes (1-31)
                            $fechaCita = \Carbon\Carbon::create($ano, $mes, min($dia, $mesFin->day));
                            if ($fechaCita->lte($mesFin) && $fechaCita->lte($fechaFin) && $fechaCita->gte($mesInicio)) {
                                $citaRecurrente = clone $cita;
                                $citaRecurrente->fecha_inicio = $fechaCita->copy();
                                if ($cita->fecha_fin) {
                                    $duracion = $cita->fecha_inicio->diffInMinutes($cita->fecha_fin);
                                    $citaRecurrente->fecha_fin = $fechaCita->copy()->addMinutes($duracion);
                                }
                                $citas->push($citaRecurrente);
                            }
                        } elseif (is_numeric($dia) && $dia >= 0 && $dia <= 6) {
                            // Es un día de la semana (0=Domingo, 6=Sábado) - "cada Lunes", etc.
                            $fechaCita = $mesInicio->copy();
                            while ($fechaCita->lte($mesFin) && $fechaCita->lte($fechaFin)) {
                                if ($fechaCita->dayOfWeek == $dia && $fechaCita->month == $mes && $fechaCita->year == $ano) {
                                    $citaRecurrente = clone $cita;
                                    $citaRecurrente->fecha_inicio = $fechaCita->copy();
                                    if ($cita->fecha_fin) {
                                        $duracion = $cita->fecha_inicio->diffInMinutes($cita->fecha_fin);
                                        $citaRecurrente->fecha_fin = $fechaCita->copy()->addMinutes($duracion);
                                    }
                                    $citas->push($citaRecurrente);
                                }
                                $fechaCita->addDay();
                            }
                        }
                    }
                } else {
                    // Recurrencia normal sin días específicos
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
        }
        
        $citas = $citas->groupBy(function($cita) {
            return $cita->fecha_inicio->format('Y-m-d');
        });
        
        // Obtener todas las tareas del período (incluyendo recurrentes)
        $tareasBase = \App\Models\Tarea::with('lista')
            ->whereNotNull('fecha_hora')
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                // Tareas que empiezan en este período
                $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
                // O tareas recurrentes que pueden aparecer en este período
                ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    $q->where('recurrencia', '!=', 'none')
                      ->where('fecha_hora', '<=', $fechaFin)
                      ->where(function($subQ) use ($fechaInicio) {
                          $subQ->whereNull('recurrencia_fin')
                                ->orWhere('recurrencia_fin', '>=', $fechaInicio);
                      });
                });
            })
            ->get();
        
        // Generar tareas recurrentes
        $tareas = collect();
        foreach ($tareasBase as $tarea) {
            if ($tarea->recurrencia === 'none') {
                // Tarea normal, agregarla solo si está en el período
                if ($vista === 'semanal') {
                    if ($tarea->fecha_hora && $tarea->fecha_hora->gte($inicioSemana) && $tarea->fecha_hora->lte($finSemana)) {
                        $tareas->push($tarea);
                    }
                } else {
                    if ($tarea->fecha_hora && $tarea->fecha_hora->month == $mes && $tarea->fecha_hora->year == $ano) {
                        $tareas->push($tarea);
                    }
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
                $recurrenciaDias = $tarea->recurrencia_dias ?? [];
                
                if ($tarea->recurrencia === 'semanal' && !empty($recurrenciaDias)) {
                    // Recurrencia semanal con días específicos
                    $fechaActualTarea = $mesInicio->copy();
                    while ($fechaActualTarea->lte($mesFin) && $fechaActualTarea->lte($fechaFin)) {
                        $diaSemana = $fechaActualTarea->dayOfWeek; // 0=Domingo, 1=Lunes, etc.
                        if (in_array($diaSemana, $recurrenciaDias) && $fechaActualTarea->month == $mes && $fechaActualTarea->year == $ano) {
                            $tareaRecurrente = clone $tarea;
                            $tareaRecurrente->fecha_hora = $fechaActualTarea->copy();
                            $tareas->push($tareaRecurrente);
                        }
                        $fechaActualTarea->addDay();
                    }
                } elseif ($tarea->recurrencia === 'mensual' && !empty($recurrenciaDias)) {
                    // Recurrencia mensual con días específicos
                    foreach ($recurrenciaDias as $dia) {
                        if (is_numeric($dia) && $dia >= 1 && $dia <= 31) {
                            // Es un día del mes (1-31)
                            $fechaTarea = \Carbon\Carbon::create($ano, $mes, min($dia, $mesFin->day));
                            if ($fechaTarea->lte($mesFin) && $fechaTarea->lte($fechaFin) && $fechaTarea->gte($mesInicio)) {
                                $tareaRecurrente = clone $tarea;
                                $tareaRecurrente->fecha_hora = $fechaTarea->copy();
                                $tareas->push($tareaRecurrente);
                            }
                        } elseif (is_numeric($dia) && $dia >= 0 && $dia <= 6) {
                            // Es un día de la semana (0=Domingo, 6=Sábado) - "cada Lunes", etc.
                            $fechaTarea = $mesInicio->copy();
                            while ($fechaTarea->lte($mesFin) && $fechaTarea->lte($fechaFin)) {
                                if ($fechaTarea->dayOfWeek == $dia && $fechaTarea->month == $mes && $fechaTarea->year == $ano) {
                                    $tareaRecurrente = clone $tarea;
                                    $tareaRecurrente->fecha_hora = $fechaTarea->copy();
                                    $tareas->push($tareaRecurrente);
                                }
                                $fechaTarea->addDay();
                            }
                        }
                    }
                } else {
                    // Recurrencia normal sin días específicos
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
        <div class="relative max-w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = $meses[$mes] . ' ' . $ano; @endphp
            @include('partials.walee-navbar')
            
            <!-- Google Calendar Status Indicator -->
            @if(!$googleCalendarConnected)
                <div class="mb-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="rounded-xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/40 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-yellow-900 dark:text-yellow-300">Google Calendar no conectado</h3>
                                <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-0.5">Las citas no se sincronizarán con Google Calendar</p>
                            </div>
                        </div>
                        <a href="{{ route('filament.admin.pages.google-calendar-auth') }}" class="px-4 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white font-medium text-sm transition-all flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Conectar
                        </a>
                    </div>
                </div>
            @else
                <div class="mb-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-3 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-emerald-900 dark:text-emerald-300">Google Calendar conectado</p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400">Las citas se sincronizan automáticamente</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Layout con Sidebar para pantallas medianas/grandes -->
            <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                <!-- Sidebar (solo visible en md+) -->
                <div class="hidden md:flex md:flex-col md:w-64 lg:w-72 flex-shrink-0 gap-4 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-4 shadow-sm dark:shadow-none sticky top-6">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Controles</h3>
                        
                        <!-- Selector de Vista -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Vista</label>
                            <div class="flex gap-2">
                                <a href="{{ route('walee.calendario', ['mes' => $mes, 'ano' => $ano]) }}" class="flex-1 px-3 py-2 rounded-lg {{ !request()->has('vista') || request()->get('vista') !== 'semanal' ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }} font-medium transition-all text-sm text-center">
                                    Mes
                                </a>
                                <a href="{{ route('walee.calendario', ['vista' => 'semanal', 'semana' => request()->get('semana', now()->format('Y-W'))]) }}" class="flex-1 px-3 py-2 rounded-lg {{ request()->get('vista') === 'semanal' ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }} font-medium transition-all text-sm text-center">
                                    Semana
                                </a>
                            </div>
                        </div>
                        
                        <!-- Navegación -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Navegación</label>
                            <div class="flex items-center justify-between gap-2">
                                @if($vista === 'semanal')
                                    <a href="?vista=semanal&semana={{ $inicioSemana->copy()->subWeek()->format('Y-W') }}" class="flex-1 px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                    <a href="?vista=semanal&semana={{ now()->format('Y-W') }}" class="flex-1 px-3 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all text-sm text-center">
                                        Esta Semana
                                    </a>
                                    <a href="?vista=semanal&semana={{ $inicioSemana->copy()->addWeek()->format('Y-W') }}" class="flex-1 px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
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
                                    <a href="?mes={{ now()->month }}&ano={{ now()->year }}" class="flex-1 px-3 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all text-sm text-center">
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
                            <select 
                                id="selectDia"
                                onchange="navegarAFecha()"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                            >
                                <option value="">Día</option>
                                @foreach($diasDelMes as $dia)
                                    <option value="{{ $dia }}" {{ request()->get('dia') == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                                @endforeach
                            </select>
                            
                            <select 
                                id="selectMes"
                                onchange="navegarAFecha()"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                            >
                                @foreach($meses as $numMes => $nombreMes)
                                    <option value="{{ $numMes }}" {{ $mes == $numMes ? 'selected' : '' }}>{{ $nombreMes }}</option>
                                @endforeach
                            </select>
                            
                            <select 
                                id="selectAno"
                                onchange="navegarAFecha()"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all"
                            >
                                @foreach($anos as $numAno)
                                    <option value="{{ $numAno }}" {{ $ano == $numAno ? 'selected' : '' }}>{{ $numAno }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Botones de Acción -->
                        <div class="space-y-2">
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Acciones</label>
                            <button 
                                onclick="showNuevaCitaModal()"
                                class="w-full px-4 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">Nueva Cita</span>
                            </button>
                            <button 
                                onclick="showNuevaTareaModal()"
                                class="w-full px-4 py-2.5 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span class="text-sm">Nueva Tarea</span>
                            </button>
                            <button 
                                onclick="showNuevaNotaModal()"
                                class="w-full px-4 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span class="text-sm">Nueva Nota</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Contenido Principal: Calendario -->
                <div class="flex-1 min-w-0">
                    <!-- Calendar Controls (solo móviles) -->
                    <div class="mb-4 md:hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-3 shadow-sm dark:shadow-none">
                            <div class="flex flex-col gap-3">
                                <!-- Selector de Vista (Móvil) -->
                                <div class="flex gap-2">
                                    <a href="{{ route('walee.calendario', ['mes' => $mes, 'ano' => $ano]) }}" class="flex-1 px-3 py-2 rounded-lg {{ !request()->has('vista') || request()->get('vista') !== 'semanal' ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} font-medium transition-all text-sm text-center">
                                        Mes
                                    </a>
                                    <a href="{{ route('walee.calendario', ['vista' => 'semanal', 'semana' => request()->get('semana', now()->format('Y-W'))]) }}" class="flex-1 px-3 py-2 rounded-lg {{ request()->get('vista') === 'semanal' ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} font-medium transition-all text-sm text-center">
                                        Semana
                                    </a>
                                </div>
                                <!-- Navegación y Selectores de Fecha -->
                                <div class="flex flex-col items-stretch gap-3">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($vista === 'semanal')
                                            <a href="?vista=semanal&semana={{ $inicioSemana->copy()->subWeek()->format('Y-W') }}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                </svg>
                                            </a>
                                            <a href="?vista=semanal&semana={{ now()->format('Y-W') }}" class="px-3 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all text-sm">
                                                Esta Semana
                                            </a>
                                            <a href="?vista=semanal&semana={{ $inicioSemana->copy()->addWeek()->format('Y-W') }}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        @else
                                            <a href="?mes={{ $fechaActual->copy()->subMonth()->month }}&ano={{ $fechaActual->copy()->subMonth()->year }}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                </svg>
                                            </a>
                                            <a href="?mes={{ now()->month }}&ano={{ now()->year }}" class="px-3 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all text-sm">
                                                Hoy
                                            </a>
                                            <a href="?mes={{ $fechaActual->copy()->addMonth()->month }}&ano={{ $fechaActual->copy()->addMonth()->year }}" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <!-- Selectores de Fecha -->
                                    <div class="flex items-center gap-2 justify-center">
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
                                <div class="flex gap-2 w-full">
                                    <button 
                                        onclick="showNuevaCitaModal()"
                                        class="flex-1 px-3 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs">Cita</span>
                                    </button>
                                    <button 
                                        onclick="showNuevaTareaModal()"
                                        class="flex-1 px-3 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        <span class="text-xs">Tarea</span>
                                    </button>
                                    <button 
                                        onclick="showNuevaNotaModal()"
                                        class="flex-1 px-3 py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="text-xs">Nota</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    @if($vista === 'semanal')
                        <!-- Vista Semanal -->
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 rounded-xl overflow-hidden shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.2s;">
                            <!-- Header de la Semana -->
                            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <a href="?vista=semanal&semana={{ $inicioSemana->copy()->subWeek()->format('Y-W') }}" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                                        {{ $inicioSemana->format('d/m') }} - {{ $finSemana->format('d/m/Y') }}
                                    </h2>
                                    <a href="?vista=semanal&semana={{ $inicioSemana->copy()->addWeek()->format('Y-W') }}" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                    <a href="?vista=semanal&semana={{ now()->format('Y-W') }}" class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium transition-all text-sm">
                                        Esta Semana
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Días de la Semana -->
                            <div class="grid grid-cols-7 divide-x divide-slate-200 dark:divide-slate-700">
                                @php
                                    $diaSemana = $inicioSemana->copy();
                                    $hoy = now();
                                @endphp
                                @for($i = 0; $i < 7; $i++)
                                    @php
                                        $esHoy = $diaSemana->isSameDay($hoy);
                                        $fechaKey = $diaSemana->format('Y-m-d');
                                        $citasDelDia = $citas->get($fechaKey, collect());
                                        $tareasDelDia = $tareas->get($fechaKey, collect());
                                        
                                        // Combinar y ordenar por hora
                                        $itemsDelDia = collect();
                                        foreach ($citasDelDia as $cita) {
                                            $itemsDelDia->push([
                                                'tipo' => 'cita',
                                                'item' => $cita,
                                                'hora' => $cita->fecha_inicio
                                            ]);
                                        }
                                        foreach ($tareasDelDia as $tarea) {
                                            $itemsDelDia->push([
                                                'tipo' => 'tarea',
                                                'item' => $tarea,
                                                'hora' => $tarea->fecha_hora
                                            ]);
                                        }
                                        $itemsDelDia = $itemsDelDia->sortBy('hora');
                                        $espaciadoClase = 'space-y-1.5';
                                    @endphp
                                    <div class="min-h-[600px] p-3 {{ $esHoy ? 'bg-emerald-50 dark:bg-emerald-500/10' : 'bg-white dark:bg-slate-800' }}">
                                        <div class="mb-3">
                                            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
                                                {{ ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'][$diaSemana->dayOfWeek] }}
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-semibold {{ $esHoy ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-white' }}">
                                                    {{ $diaSemana->day }}
                                                </span>
                                                @if($esHoy)
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="{{ $espaciadoClase }}">
                                            @foreach($itemsDelDia as $itemOrdenado)
                                                @if($itemOrdenado['tipo'] === 'cita')
                                                    @php $cita = $itemOrdenado['item']; @endphp
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
                                                        class="w-full text-left px-2 py-1.5 rounded text-xs font-medium transition-all hover:opacity-80 {{ $cita->estado === 'completada' ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' : '' }} mb-1"
                                                        style="{{ $cita->estado !== 'completada' ? $colorBg : '' }}"
                                                        title="{{ $cita->titulo }}"
                                                    >
                                                        <div class="text-[10px] text-slate-600 dark:text-slate-400 mb-0.5">{{ $cita->fecha_inicio->format('H:i') }}</div>
                                                        <div class="truncate">{{ $cita->titulo }}</div>
                                                    </button>
                                                @else
                                                    @php $tarea = $itemOrdenado['item']; @endphp
                                                    @php
                                                        $colorTarea = $tarea->color ?? '#8b5cf6';
                                                        $colorHex = ltrim($colorTarea, '#');
                                                        $r = hexdec(substr($colorHex, 0, 2));
                                                        $g = hexdec(substr($colorHex, 2, 2));
                                                        $b = hexdec(substr($colorHex, 4, 2));
                                                        $colorBg = $tarea->estado === 'completado' 
                                                            ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' 
                                                            : "background-color: rgba({$r}, {$g}, {$b}, 0.2); color: {$colorTarea};";
                                                    @endphp
                                                    <button 
                                                        onclick="showTareaDetail({{ $tarea->id }})"
                                                        class="w-full text-left px-2 py-1.5 rounded text-xs font-medium transition-all hover:opacity-80 {{ $tarea->estado === 'completado' ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' : '' }} mb-1"
                                                        style="{{ $tarea->estado !== 'completado' ? $colorBg : '' }}"
                                                        title="{{ $tarea->texto }}"
                                                    >
                                                        <div class="text-[10px] text-slate-600 dark:text-slate-400 mb-0.5">{{ $tarea->fecha_hora->format('H:i') }}</div>
                                                        <div class="truncate">{{ $tarea->texto }}</div>
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @php $diaSemana->addDay(); @endphp
                                @endfor
                            </div>
                        </div>
                    @else
                        <!-- Vista Mensual -->
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
                            
                            // Combinar y ordenar por hora
                            $itemsDelDia = collect();
                            foreach ($citasDelDia as $cita) {
                                $itemsDelDia->push([
                                    'tipo' => 'cita',
                                    'item' => $cita,
                                    'hora' => $cita->fecha_inicio
                                ]);
                            }
                            foreach ($tareasDelDia as $tarea) {
                                $itemsDelDia->push([
                                    'tipo' => 'tarea',
                                    'item' => $tarea,
                                    'hora' => $tarea->fecha_hora
                                ]);
                            }
                            $itemsDelDia = $itemsDelDia->sortBy('hora')->take(7);
                            $totalItems = $citasDelDia->count() + $tareasDelDia->count();
                            $cantidadItems = $itemsDelDia->count();
                            $espaciadoClase = $cantidadItems <= 7 ? 'space-y-1.5 sm:space-y-2' : 'space-y-0.5 sm:space-y-1';
                        @endphp
                        <a href="{{ route('walee.calendario.dia', ['ano' => $diaActual->year, 'mes' => $diaActual->month, 'dia' => $diaActual->day]) }}" class="block min-h-[100px] sm:min-h-[150px] md:min-h-[180px] lg:min-h-[220px] xl:min-h-[250px] border-r border-b border-slate-200 dark:border-slate-700 p-2 sm:p-3 md:p-4 {{ !$esMesActual ? 'bg-slate-50 dark:bg-slate-900/30' : '' }} {{ $esHoy ? 'bg-emerald-50 dark:bg-emerald-500/10' : '' }} hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs sm:text-sm font-medium {{ $esHoy ? 'text-emerald-600 dark:text-emerald-400' : ($esMesActual ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-600') }}">
                                    {{ $diaActual->day }}
                                </span>
                                @if($esHoy)
                                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500"></span>
                                @endif
                            </div>
                            <div class="{{ $espaciadoClase }}">
                                @foreach($itemsDelDia as $itemOrdenado)
                                    @if($itemOrdenado['tipo'] === 'cita')
                                        @php $cita = $itemOrdenado['item']; @endphp
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
                                    @else
                                        @php $tarea = $itemOrdenado['item']; @endphp
                                        @php
                                            $colorTarea = $tarea->color ?? '#8b5cf6';
                                            $colorHex = ltrim($colorTarea, '#');
                                            $r = hexdec(substr($colorHex, 0, 2));
                                            $g = hexdec(substr($colorHex, 2, 2));
                                            $b = hexdec(substr($colorHex, 4, 2));
                                            $colorBg = $tarea->estado === 'completado' 
                                                ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' 
                                                : "background-color: rgba({$r}, {$g}, {$b}, 0.2); color: {$colorTarea};";
                                        @endphp
                                        <button 
                                            onclick="showTareaDetail({{ $tarea->id }})"
                                            class="w-full text-left px-1 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-medium truncate transition-all hover:opacity-80 {{ $tarea->estado === 'completado' ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' : '' }}"
                                            style="{{ $tarea->estado !== 'completado' ? $colorBg : '' }}"
                                            title="{{ $tarea->texto }}"
                                        >
                                            <span class="hidden sm:inline">{{ $tarea->fecha_hora->format('H:i') }} - </span>{{ $tarea->texto }}
                                        </button>
                                    @endif
                                @endforeach
                                @if($totalItems > 7)
                                    <button 
                                        onclick="showDayItems('{{ $fechaKey }}')"
                                        class="w-full text-left px-1 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all"
                                    >
                                        +{{ $totalItems - 7 }} más
                                    </button>
                                @endif
                            </div>
                        </a>
                        @php $diaActual->addDay(); @endphp
                    @endwhile
                </div>
            </div>
                    @endif
                </div>
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
    
    <!-- Modal Nueva/Editar Nota -->
    <div id="notaModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-slate-900 rounded-t-2xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-700 w-full sm:max-w-md max-h-[90vh] overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="notaModalTitle">Nueva Nota</h3>
                <button onclick="closeNotaModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="nota-form" class="p-4 space-y-4 overflow-y-auto max-h-[70vh]">
                <input type="hidden" name="nota_id" id="nota_id">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Contenido de la Nota</label>
                    <textarea 
                        name="content" 
                        id="nota_content" 
                        required 
                        rows="5"
                        placeholder="Escribe el contenido de la nota..."
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all resize-none"
                    ></textarea>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Máximo 5000 caracteres</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                    <select 
                        name="type" 
                        id="nota_type" 
                        required
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                    >
                        <option value="note">Nota</option>
                        <option value="call">Llamada</option>
                        <option value="meeting">Reunión</option>
                        <option value="email">Email</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cliente (opcional)</label>
                    <select 
                        name="cliente_id" 
                        id="nota_cliente_id"
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                    >
                        <option value="">Sin cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="pinned" 
                            id="nota_pinned"
                            class="w-5 h-5 rounded border-slate-300 dark:border-slate-700 text-blue-500 focus:ring-blue-500"
                        >
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Marcar como fijada (pinned)</span>
                    </label>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-7">Las notas fijadas aparecen primero en la lista</p>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-medium transition-all"
                    >
                        Guardar
                    </button>
                    <button 
                        type="button"
                        id="deleteNotaBtn"
                        onclick="deleteNota()"
                        class="px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-medium transition-all hidden"
                    >
                        Eliminar
                    </button>
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
    <div id="citaDetailModal" class="fixed inset-0 bg-black/80 dark:bg-black/90 backdrop-blur-sm z-[9999] hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
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
            document.getElementById('recurrencia_dias_container').classList.add('hidden');
            document.getElementById('color').value = '#10b981';
            document.getElementById('color_text').value = '#10b981';
            
            // Establecer fecha y hora por defecto a "ahora"
            const now = new Date();
            const fechaInicio = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
            document.getElementById('fecha_inicio').value = fechaInicio;
            
            // Establecer fecha fin a 2 horas después
            const fechaFin = new Date(now.getTime() + (2 * 60 * 60 * 1000) - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
            document.getElementById('fecha_fin').value = fechaFin;
            
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
            
            // Obtener días de recurrencia
            let recurrenciaDias = null;
            const recurrencia = formData.get('recurrencia') || 'none';
            if (recurrencia === 'semanal') {
                const diasCheckboxes = document.querySelectorAll('#recurrencia_dias_container input[type="checkbox"]:checked');
                recurrenciaDias = Array.from(diasCheckboxes).map(cb => parseInt(cb.value));
            } else if (recurrencia === 'mensual') {
                const diasMensual = formData.get('recurrencia_dias_mensual');
                if (diasMensual) {
                    // Procesar días del mes: puede ser números separados por comas o días de la semana
                    const dias = diasMensual.split(',').map(d => d.trim());
                    recurrenciaDias = dias.map(d => {
                        // Si es un número, devolverlo; si es un día de la semana, convertir a número
                        const diaNum = parseInt(d);
                        if (!isNaN(diaNum)) return diaNum;
                        // Mapear días de la semana en español a números
                        const diasSemana = {
                            'domingo': 0, 'lunes': 1, 'martes': 2, 'miércoles': 3, 'miercoles': 3,
                            'jueves': 4, 'viernes': 5, 'sábado': 6, 'sabado': 6
                        };
                        const diaLower = d.toLowerCase().replace('cada ', '');
                        return diasSemana[diaLower] !== undefined ? diasSemana[diaLower] : null;
                    }).filter(d => d !== null);
                }
            }
            
            const data = {
                titulo: formData.get('titulo'),
                cliente_id: formData.get('cliente_id') || null,
                fecha_inicio: formData.get('fecha_inicio'),
                fecha_fin: formData.get('fecha_fin') || null,
                ubicacion: formData.get('ubicacion') || null,
                descripcion: formData.get('descripcion') || null,
                estado: formData.get('estado'),
                recurrencia: recurrencia,
                recurrencia_fin: formData.get('recurrencia_fin') || null,
                recurrencia_dias: recurrenciaDias,
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
        
        // Sincronizar color picker de tareas con input de texto
        document.getElementById('tarea_color').addEventListener('input', function(e) {
            document.getElementById('tarea_color_text').value = e.target.value;
        });
        
        document.getElementById('tarea_color_text').addEventListener('input', function(e) {
            if (/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                document.getElementById('tarea_color').value = e.target.value;
            }
        });
        
        function showNuevaTareaModal() {
            document.getElementById('tareaModalTitle').textContent = 'Nueva Tarea';
            document.getElementById('tarea-form').reset();
            document.getElementById('tarea_id').value = '';
            document.getElementById('deleteTareaBtn').classList.add('hidden');
            document.getElementById('tarea_recurrencia_fin_container').classList.add('hidden');
            
            // Establecer fecha y hora por defecto a "ahora"
            const now = new Date();
            const fechaHora = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
            document.getElementById('tarea_fecha_hora').value = fechaHora;
            document.getElementById('tarea_recurrencia_dias_container').classList.add('hidden');
            document.getElementById('tarea_color').value = '#8b5cf6';
            document.getElementById('tarea_color_text').value = '#8b5cf6';
            document.getElementById('tareaModal').classList.remove('hidden');
        }
        
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
            document.getElementById('notaModal').classList.remove('hidden');
        }
        
        function closeNotaModal() {
            document.getElementById('notaModal').classList.add('hidden');
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
            
            // Obtener días de recurrencia
            let recurrenciaDias = null;
            const recurrencia = formData.get('recurrencia') || 'none';
            if (recurrencia === 'semanal') {
                const diasCheckboxes = document.querySelectorAll('#tarea_recurrencia_dias_container input[type="checkbox"]:checked');
                recurrenciaDias = Array.from(diasCheckboxes).map(cb => parseInt(cb.value));
            } else if (recurrencia === 'mensual') {
                const diasMensual = formData.get('recurrencia_dias_mensual');
                if (diasMensual) {
                    // Procesar días del mes: puede ser números separados por comas o días de la semana
                    const dias = diasMensual.split(',').map(d => d.trim());
                    recurrenciaDias = dias.map(d => {
                        // Si es un número, devolverlo; si es un día de la semana, convertir a número
                        const diaNum = parseInt(d);
                        if (!isNaN(diaNum)) return diaNum;
                        // Mapear días de la semana en español a números
                        const diasSemana = {
                            'domingo': 0, 'lunes': 1, 'martes': 2, 'miércoles': 3, 'miercoles': 3,
                            'jueves': 4, 'viernes': 5, 'sábado': 6, 'sabado': 6
                        };
                        const diaLower = d.toLowerCase().replace('cada ', '');
                        return diasSemana[diaLower] !== undefined ? diasSemana[diaLower] : null;
                    }).filter(d => d !== null);
                }
            }
            
            const data = {
                texto: formData.get('texto'),
                lista_id: formData.get('lista_id') || null,
                fecha_hora: formData.get('fecha_hora'),
                tipo: formData.get('tipo') || null,
                recurrencia: recurrencia,
                recurrencia_fin: formData.get('recurrencia_fin') || null,
                recurrencia_dias: recurrenciaDias,
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
                closeNotaModal();
            }
        });
        
        // Nota Functions
        function showNuevaNotaModal() {
            document.getElementById('notaModalTitle').textContent = 'Nueva Nota';
            document.getElementById('nota-form').reset();
            document.getElementById('nota_id').value = '';
            document.getElementById('deleteNotaBtn').classList.add('hidden');
            document.getElementById('nota_type').value = 'note';
            document.getElementById('nota_pinned').checked = false;
            document.getElementById('notaModal').classList.remove('hidden');
        }
        
        function closeNotaModal() {
            document.getElementById('notaModal').classList.add('hidden');
        }
        
        // Nota form handler
        document.getElementById('nota-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const notaId = formData.get('nota_id');
            const url = notaId ? `/notas/${notaId}` : '/notas';
            const method = notaId ? 'PUT' : 'POST';
            
            const data = {
                content: formData.get('content'),
                type: formData.get('type'),
                cliente_id: formData.get('cliente_id') || null,
                pinned: formData.get('pinned') === 'on',
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
                    closeNotaModal();
                    location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Error al guardar'));
                }
            } catch (error) {
                alert('Error de conexión: ' + error.message);
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
        
        // Close nota modal on backdrop click
        document.getElementById('notaModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeNotaModal();
        });
    </script>
    @include('partials.walee-support-button')
</body>
</html>

