<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Calendario de Citas - {{ $cliente->name }}</title>
    <meta name="description" content="Calendario de Citas para {{ $cliente->name }}">
    <meta name="theme-color" content="#10b981">
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
        ::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(16, 185, 129, 0.5); }
        
        /* SweetAlert Dark/Light Mode */
        .dark-swal {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }
        
        .light-swal {
            background-color: #ffffff !important;
            color: #1e293b !important;
        }
        
        .swal2-container {
            z-index: 99999 !important;
        }
        
        .swal2-backdrop-show {
            z-index: 99999 !important;
        }
        
        .swal2-popup {
            z-index: 99999 !important;
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
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $vista = request()->get('vista', 'semanal');
        $mes = request()->get('mes', now()->month);
        $ano = request()->get('ano', now()->year);
        
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
        
        $fechaActual = \Carbon\Carbon::create($ano, $mes, 1);
        
        // Determinar rango de fechas según la vista
        if ($vista === 'semanal') {
            $fechaInicio = $inicioSemana->copy()->startOfDay();
            $fechaFin = $finSemana->copy()->endOfDay();
        } else {
            $fechaInicio = $fechaActual->copy()->startOfMonth()->startOfDay();
            $fechaFin = $fechaActual->copy()->endOfMonth()->endOfDay();
        }
        
        // Obtener citas del cliente
        $citasBase = \App\Models\Cita::where('client_id', $cliente->id)
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
        
        // Generar citas recurrentes
        $citas = collect();
        foreach ($citasBase as $cita) {
            if ($cita->recurrencia === 'none') {
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
                $fechaInicioRec = $cita->fecha_inicio->copy();
                $fechaFinRec = $cita->recurrencia_fin ? \Carbon\Carbon::parse($cita->recurrencia_fin) : ($vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth());
                $periodoInicio = $vista === 'semanal' ? $inicioSemana : $fechaActual->copy()->startOfMonth();
                $periodoFin = $vista === 'semanal' ? $finSemana : $fechaActual->copy()->endOfMonth();
                
                if ($fechaInicioRec->lt($periodoInicio)) {
                    if ($cita->recurrencia === 'semanal') {
                        $semanas = ceil($periodoInicio->diffInWeeks($fechaInicioRec));
                        $fechaInicioRec = $fechaInicioRec->copy()->addWeeks($semanas);
                    } elseif ($cita->recurrencia === 'mensual') {
                        $meses = ceil($periodoInicio->diffInMonths($fechaInicioRec));
                        $fechaInicioRec = $fechaInicioRec->copy()->addMonths($meses);
                    }
                }
                
                $fechaActualCita = $fechaInicioRec->copy();
                while ($fechaActualCita->lte($periodoFin) && $fechaActualCita->lte($fechaFinRec)) {
                    if ($fechaActualCita->gte($cita->fecha_inicio->copy()->startOfDay())) {
                        $condicionFecha = $vista === 'semanal' ? true : ($fechaActualCita->month == $mes && $fechaActualCita->year == $ano);
                        if ($condicionFecha) {
                            $citaRecurrente = clone $cita;
                            $citaRecurrente->fecha_inicio = $fechaActualCita->copy();
                            if ($cita->fecha_fin) {
                                $duracion = $cita->fecha_inicio->diffInMinutes($cita->fecha_fin);
                                $citaRecurrente->fecha_fin = $fechaActualCita->copy()->addMinutes($duracion);
                            }
                            $citas->push($citaRecurrente);
                        }
                    }
                    
                    if ($cita->recurrencia === 'semanal') {
                        $fechaActualCita->addWeek();
                    } elseif ($cita->recurrencia === 'mensual') {
                        $fechaActualCita->addMonth();
                    } elseif ($cita->recurrencia === 'anual') {
                        $fechaActualCita->addYear();
                    } else {
                        break;
                    }
                }
            }
        }
        
        $citas = $citas->groupBy(function($cita) {
            return $cita->fecha_inicio->format('Y-m-d');
        });
        
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        // Obtener foto del cliente
        $fotoUrl = null;
        if ($cliente->foto) {
            $fotoPath = $cliente->foto;
            if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                $fotoUrl = $fotoPath;
            } else {
                $filename = basename($fotoPath);
                $fotoUrl = route('storage.clientes', ['filename' => $filename]);
            }
        }
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/10 dark:bg-emerald-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Calendario de Citas - ' . $cliente->name; @endphp
            @include('partials.walee-navbar')
            
            <!-- Cliente Info -->
            <div class="mb-4 md:mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-3 md:p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4">
                        <div class="flex items-center gap-2 md:gap-3 min-w-0 flex-1">
                            <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 hover:opacity-90 transition-opacity cursor-pointer border-2 border-emerald-500/30 dark:border-emerald-500/20">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-500/20 to-walee-500/20 flex items-center justify-center">
                                        <span class="text-lg md:text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </a>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base md:text-lg font-semibold text-emerald-900 dark:text-emerald-300 truncate">{{ $cliente->name }}</h3>
                                <p class="text-xs md:text-sm text-emerald-700 dark:text-emerald-400 truncate">Calendario de Citas</p>
                            </div>
                        </div>
                        @if($vista === 'semanal')
                            <div class="flex items-center gap-2 flex-shrink-0 flex-wrap header-actions">
                                <a href="?cliente_id={{ $cliente->id }}&vista=semanal&semana={{ $semanaAnteriorFormato }}" class="px-3 py-2.5 sm:px-4 sm:py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Anterior</span>
                                    <span class="sm:hidden">Anterior</span>
                                </a>
                                <a href="?cliente_id={{ $cliente->id }}&vista=semanal&semana={{ $semanaSiguienteFormato }}" class="px-3 py-2.5 sm:px-4 sm:py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-1.5 sm:gap-2 shadow-md hover:shadow-lg active:scale-95">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Siguiente</span>
                                    <span class="sm:hidden">Siguiente</span>
                                </a>
                            </div>
                        @endif
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
                                    $citasDelDia = $citas->get($fechaKey, collect());
                                    
                                    // Ordenar citas por hora
                                    $citasOrdenadas = $citasDelDia->sortBy('fecha_inicio');
                                @endphp
                                <div class="min-h-[300px] md:min-h-[400px] flex flex-col border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-700 last:border-b-0">
                                    <!-- Header del día -->
                                    <div class="p-3 md:p-3 border-b border-slate-200 dark:border-slate-700 {{ $esHoy ? 'bg-emerald-50 dark:bg-emerald-500/10' : 'bg-slate-50 dark:bg-slate-800/30' }}">
                                        <div class="flex md:block items-center justify-between md:text-center">
                                            <div>
                                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">{{ $diaSemana->format('D') }}</p>
                                                <p class="text-lg md:text-lg font-bold {{ $esHoy ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-white' }} mt-1">
                                                    {{ $diaSemana->day }} {{ $meses[$diaSemana->month] }}
                                                </p>
                                            </div>
                                            <div class="md:hidden">
                                                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $citasOrdenadas->count() }} citas</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Citas del día -->
                                    <div class="flex-1 p-3 md:p-2 space-y-2 md:space-y-1.5 overflow-y-auto">
                                        @if($citasOrdenadas->count() > 0)
                                            @foreach($citasOrdenadas as $cita)
                                                @php
                                                    $ahora = now();
                                                    $yaPaso = $cita->fecha_inicio->lt($ahora);
                                                    
                                                    // Colores según estado
                                                    if ($cita->estado === 'completada') {
                                                        $colorBg = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-l-3 border-emerald-500';
                                                        $claseBtn = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
                                                        $colorBorde = '#10b981';
                                                    } elseif ($cita->estado === 'cancelada') {
                                                        $colorBg = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-l-3 border-red-500';
                                                        $claseBtn = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300';
                                                        $colorBorde = '#ef4444';
                                                    } else {
                                                        $colorBg = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-l-3 border-blue-500';
                                                        $claseBtn = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300';
                                                        $colorBorde = '#3b82f6';
                                                    }
                                                @endphp
                                                <button 
                                                    onclick="event.preventDefault(); showCitaDetail({{ $cita->id }});"
                                                    class="w-full text-left px-3 py-2.5 md:px-2 md:py-1.5 rounded-lg md:rounded text-sm md:text-xs font-medium transition-all hover:opacity-80 {{ $claseBtn }} shadow-sm md:shadow-none"
                                                    style="border-left: 4px solid {{ $colorBorde }};"
                                                    title="{{ $cita->titulo }}"
                                                >
                                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-1.5">
                                                        <div class="flex items-center justify-between md:justify-start gap-2">
                                                            <span class="text-xs md:text-[10px] font-semibold">{{ $cita->fecha_inicio->format('H:i') }}</span>
                                                            @if($cita->fecha_fin)
                                                                <span class="text-xs md:text-[10px] font-medium opacity-75">- {{ $cita->fecha_fin->format('H:i') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($cita->titulo && strlen($cita->titulo) > 0)
                                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 md:hidden line-clamp-2">{{ $cita->titulo }}</p>
                                                    @endif
                                                </button>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4 md:py-2">
                                                <p class="text-xs text-slate-400 dark:text-slate-500">Sin citas</p>
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function showCitaDetail(citaId) {
            window.location.href = `/citas/${citaId}/detalle`;
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>
