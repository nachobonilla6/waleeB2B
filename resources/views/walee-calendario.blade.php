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
        
        /* Asegurar que el contenido ocupe casi toda la modal en mobile */
        @media (max-width: 640px) {
            .swal2-popup {
                max-height: 90vh !important;
                display: flex !important;
                flex-direction: column !important;
            }
            
            .swal2-html-container {
                flex: 1 !important;
                overflow-y: auto !important;
                padding: 0.5rem !important;
                margin: 0 !important;
            }
            
            .swal2-title {
                padding: 0.5rem 0.5rem 0.25rem 0.5rem !important;
                margin: 0 !important;
                font-size: 1rem !important;
            }
            
            .swal2-actions {
                padding: 0.5rem !important;
                margin: 0 !important;
                flex-shrink: 0 !important;
            }
        }
        
        /* En desktop, asegurar que sea más ancha que alta */
        @media (min-width: 641px) {
            .swal2-popup {
                max-height: 85vh !important;
            }
            
            .swal2-html-container {
                max-height: calc(85vh - 120px) !important;
                overflow-y: auto !important;
            }
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
                                    <img src="https://img.icons8.com/color/1200/my-bussiness.jpg" alt="{{ $cliente->name }}" class="w-full h-full object-cover">
                                @endif
                            </a>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base md:text-lg font-semibold text-emerald-900 dark:text-emerald-300 truncate">{{ $cliente->name }}</h3>
                                <p class="text-xs md:text-sm text-emerald-700 dark:text-emerald-400 truncate">Calendario de Citas</p>
                            </div>
                        </div>
                        @if($vista === 'semanal')
                            <div class="flex items-center gap-2 flex-shrink-0 flex-wrap header-actions">
                                <a href="?cliente_id={{ $cliente->id }}&vista=semanal&semana={{ $semanaAnteriorFormato }}" class="px-4 py-3 sm:px-4 sm:py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-2 sm:gap-2 shadow-lg hover:shadow-xl active:scale-95 min-h-[44px] sm:min-h-0">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Anterior</span>
                                    <span class="sm:hidden">Anterior</span>
                                </a>
                                <a href="?cliente_id={{ $cliente->id }}&vista=semanal&semana={{ $semanaSiguienteFormato }}" class="px-4 py-3 sm:px-4 sm:py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold text-sm sm:text-sm transition-all flex items-center justify-center gap-2 sm:gap-2 shadow-lg hover:shadow-xl active:scale-95 min-h-[44px] sm:min-h-0">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
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
                                        <div class="flex md:flex-col items-center justify-between md:text-center gap-2">
                                            <div class="flex-1">
                                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">{{ $diaSemana->format('D') }}</p>
                                                <p class="text-lg md:text-lg font-bold {{ $esHoy ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-white' }} mt-1">
                                                    {{ $diaSemana->day }} {{ $meses[$diaSemana->month] }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button 
                                                    onclick="openCreateCitaModal('{{ $diaSemana->format('Y-m-d') }}')"
                                                    class="w-10 h-10 md:w-6 md:h-6 flex items-center justify-center rounded-xl md:rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white transition-all hover:scale-110 active:scale-95 shadow-lg hover:shadow-xl"
                                                    title="Agregar cita"
                                                >
                                                    <svg class="w-5 h-5 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                                <div class="md:hidden">
                                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/50 px-2 py-1 rounded-lg">{{ $citasOrdenadas->count() }} citas</span>
                                                </div>
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
                                                    class="w-full text-left px-4 py-3 md:px-2 md:py-1.5 rounded-xl md:rounded text-sm md:text-xs font-semibold transition-all hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] {{ $claseBtn }} shadow-md md:shadow-none border-l-4"
                                                    style="border-left-color: {{ $colorBorde }};"
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
        const clienteId = {{ $cliente->id }};
        
        // Variables globales para el flujo de fases
        let citaModalData = {
            fecha: '',
            horaInicio: '',
            horaFin: '',
            tipoCita: '',
            titulo: '',
            descripcion: '',
            ubicacion: '',
            estado: 'programada'
        };
        
        async function showCitaDetail(citaId) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const isMobile = window.innerWidth < 640;
            
            try {
                // Mostrar loading
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Obtener detalles de la cita
                const response = await fetch(`/api/citas/${citaId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Error al cargar la cita');
                }
                
                const cita = data.cita;
                
                // Formatear estado
                let estadoBadge = '';
                let estadoColor = '';
                if (cita.estado === 'completada') {
                    estadoBadge = '<span class="px-2 py-1 rounded-lg text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">Completada</span>';
                    estadoColor = '#10b981';
                } else if (cita.estado === 'cancelada') {
                    estadoBadge = '<span class="px-2 py-1 rounded-lg text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">Cancelada</span>';
                    estadoColor = '#ef4444';
                } else {
                    estadoBadge = '<span class="px-2 py-1 rounded-lg text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">Programada</span>';
                    estadoColor = '#3b82f6';
                }
                
                // Construir HTML del modal
                const html = `
                    <div class="text-left space-y-3">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2">
                                ${estadoBadge}
                            </div>
                            ${cita.fecha_inicio_formatted ? `<span class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-600'}">${cita.fecha_inicio_formatted}</span>` : ''}
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Título</label>
                            <p class="text-sm ${isDarkMode ? 'text-slate-200' : 'text-slate-800'} font-semibold">${cita.titulo || 'Sin título'}</p>
                        </div>
                        
                        ${cita.hora_inicio ? `
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Hora inicio</label>
                                <p class="text-sm ${isDarkMode ? 'text-slate-200' : 'text-slate-800'}">${cita.hora_inicio}</p>
                            </div>
                            ${cita.hora_fin ? `
                            <div>
                                <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Hora fin</label>
                                <p class="text-sm ${isDarkMode ? 'text-slate-200' : 'text-slate-800'}">${cita.hora_fin}</p>
                            </div>
                            ` : ''}
                        </div>
                        ` : ''}
                        
                        ${cita.ubicacion ? `
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Ubicación</label>
                            <p class="text-sm ${isDarkMode ? 'text-slate-200' : 'text-slate-800'}">${cita.ubicacion}</p>
                        </div>
                        ` : ''}
                        
                        ${cita.descripcion ? `
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Descripción</label>
                            <p class="text-sm ${isDarkMode ? 'text-slate-200' : 'text-slate-800'} whitespace-pre-wrap">${cita.descripcion}</p>
                        </div>
                        ` : ''}
                        
                        ${cita.cliente_nombre ? `
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Cliente</label>
                            <p class="text-sm ${isDarkMode ? 'text-slate-200' : 'text-slate-800'}">${cita.cliente_nombre}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                // Mostrar modal
                Swal.fire({
                    title: 'Detalles de la Cita',
                    html: html,
                    width: isMobile ? '90%' : '600px',
                    padding: isMobile ? '0.75rem' : '1rem',
                    showConfirmButton: true,
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: '#10b981',
                    background: isDarkMode ? '#1e293b' : '#ffffff',
                    color: isDarkMode ? '#e2e8f0' : '#1e293b',
                    customClass: {
                        popup: isDarkMode ? 'dark-swal' : 'light-swal',
                        title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                        htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                        confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    }
                });
                
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al cargar los detalles de la cita',
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
        
        function openCreateCitaModal(fecha) {
            // Calcular fecha de hoy en formato YYYY-MM-DD
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            const fechaHoy = hoy.toISOString().split('T')[0];
            
            // Comparar si la fecha seleccionada es hoy
            const fechaSeleccionada = new Date(fecha + 'T00:00:00');
            fechaSeleccionada.setHours(0, 0, 0, 0);
            const esHoy = fechaSeleccionada.getTime() === hoy.getTime();
            
            let horaInicioFormato, horaFinFormato;
            
            if (esHoy) {
                // Si es el mismo día: una hora adelante, en punto, y 2 horas de duración
                const horaActual = new Date().getHours();
                const horaInicio = horaActual + 1;
                horaInicioFormato = String(horaInicio).padStart(2, '0') + ':00';
                
                // Calcular hora de fin: 2 horas más que la hora de inicio
                const horaFin = horaInicio + 2;
                horaFinFormato = String(horaFin).padStart(2, '0') + ':00';
            } else {
                // Si es otro día futuro: 9:00 AM a 11:00 AM
                horaInicioFormato = '09:00';
                horaFinFormato = '11:00';
            }
            
            // Resetear datos con valores por defecto
            citaModalData.fecha = fecha;
            citaModalData.horaInicio = horaInicioFormato;
            citaModalData.horaFin = horaFinFormato;
            citaModalData.tipoCita = '';
            citaModalData.titulo = '';
            citaModalData.descripcion = '';
            citaModalData.ubicacion = '';
            citaModalData.estado = 'programada';
            
            showCitaPhase1();
        }
        
        function showCitaPhase1() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '90%';
            if (window.innerWidth >= 1024) {
                modalWidth = '600px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '550px';
            }
            
            // Formatear fecha para mostrar
            const fechaObj = new Date(citaModalData.fecha + 'T00:00:00');
            const fechaFormateada = fechaObj.toLocaleDateString('es-ES', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            const html = `
                <div class="space-y-2.5 text-left">
                    <div class="flex items-center justify-center gap-1 mb-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Fecha</label>
                        <input type="date" id="cita_fecha" value="${citaModalData.fecha}" required
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        <p class="text-[9px] ${isDarkMode ? 'text-slate-400' : 'text-slate-500'} mt-0.5">${fechaFormateada}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Hora inicio <span class="text-red-500">*</span></label>
                            <input type="time" id="cita_hora_inicio" value="${citaModalData.horaInicio}" required
                                class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Hora fin</label>
                            <input type="time" id="cita_hora_fin" value="${citaModalData.horaFin}"
                                class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>Nueva Cita - Paso 1</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : '1rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: () => {
                    const fecha = document.getElementById('cita_fecha').value;
                    const horaInicio = document.getElementById('cita_hora_inicio').value;
                    const horaFin = document.getElementById('cita_hora_fin').value;
                    
                    if (!horaInicio) {
                        Swal.showValidationMessage('La hora de inicio es requerida');
                        return false;
                    }
                    
                    citaModalData.fecha = fecha;
                    citaModalData.horaInicio = horaInicio;
                    citaModalData.horaFin = horaFin;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showCitaPhase2();
                }
            });
        }
        
        function showCitaPhase2() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '90%';
            if (window.innerWidth >= 1024) {
                modalWidth = '600px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '550px';
            }
            
            const html = `
                <div class="space-y-2.5 text-left">
                    <div class="flex items-center justify-center gap-1 mb-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        <div class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Título <span class="text-red-500">*</span></label>
                        <select id="cita_tipo" 
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                            <option value="">Seleccionar título...</option>
                            <option value="Consulta" ${citaModalData.titulo === 'Consulta' ? 'selected' : ''}>Consulta</option>
                            <option value="Reunión" ${citaModalData.titulo === 'Reunión' ? 'selected' : ''}>Reunión</option>
                            <option value="Seguimiento" ${citaModalData.titulo === 'Seguimiento' ? 'selected' : ''}>Seguimiento</option>
                            <option value="Presentación" ${citaModalData.titulo === 'Presentación' ? 'selected' : ''}>Presentación</option>
                            <option value="Llamada" ${citaModalData.titulo === 'Llamada' ? 'selected' : ''}>Llamada</option>
                            <option value="Visita" ${citaModalData.titulo === 'Visita' ? 'selected' : ''}>Visita</option>
                            <option value="Capacitación" ${citaModalData.titulo === 'Capacitación' ? 'selected' : ''}>Capacitación</option>
                            <option value="Otro" ${citaModalData.titulo === 'Otro' ? 'selected' : ''}>Otro</option>
                        </select>
                        <input type="text" id="cita_titulo" value="${citaModalData.titulo}" required placeholder="O escribir título personalizado..."
                            class="mt-2 w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Descripción</label>
                        <textarea id="cita_descripcion" rows="4" placeholder="Notas adicionales..."
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-none">${citaModalData.descripcion}</textarea>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span>Nueva Cita - Paso 2</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : '1rem',
                showCancelButton: true,
                confirmButtonText: 'Siguiente',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#10b981',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
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
                    // Autocompletar título cuando se selecciona un tipo
                    const tipoSelect = document.getElementById('cita_tipo');
                    const tituloInput = document.getElementById('cita_titulo');
                    
                    tipoSelect.addEventListener('change', function() {
                        if (this.value) {
                            tituloInput.value = this.value;
                        }
                    });
                },
                preConfirm: () => {
                    const titulo = document.getElementById('cita_titulo').value;
                    const descripcion = document.getElementById('cita_descripcion').value;
                    
                    if (!titulo) {
                        Swal.showValidationMessage('El título es requerido');
                        return false;
                    }
                    
                    citaModalData.titulo = titulo;
                    citaModalData.descripcion = descripcion;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showCitaPhase3();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    showCitaPhase1();
                }
            });
        }
        
        function showCitaPhase3() {
            const isMobile = window.innerWidth < 640;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            let modalWidth = '90%';
            if (window.innerWidth >= 1024) {
                modalWidth = '600px';
            } else if (window.innerWidth >= 640) {
                modalWidth = '550px';
            }
            
            const html = `
                <div class="space-y-2.5 text-left">
                    <div class="flex items-center justify-center gap-1 mb-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                        <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Ubicación</label>
                        <input type="text" id="cita_ubicacion" value="${citaModalData.ubicacion}" placeholder="Ej: Oficina, Zoom, etc."
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium ${isDarkMode ? 'text-slate-300' : 'text-slate-700'} mb-1">Estado</label>
                        <select id="cita_estado"
                            class="w-full px-2.5 py-1.5 text-xs ${isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'} border rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                            <option value="programada" ${citaModalData.estado === 'programada' ? 'selected' : ''}>Programada</option>
                            <option value="completada" ${citaModalData.estado === 'completada' ? 'selected' : ''}>Completada</option>
                            <option value="cancelada" ${citaModalData.estado === 'cancelada' ? 'selected' : ''}>Cancelada</option>
                        </select>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: '<div class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span>Nueva Cita - Paso 3</span></div>',
                html: html,
                width: modalWidth,
                padding: isMobile ? '0.75rem' : '1rem',
                showCancelButton: true,
                confirmButtonText: 'Crear Cita',
                cancelButtonText: 'Anterior',
                confirmButtonColor: '#10b981',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                reverseButtons: true,
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                preConfirm: async () => {
                    const ubicacion = document.getElementById('cita_ubicacion').value;
                    const estado = document.getElementById('cita_estado').value;
                    
                    citaModalData.ubicacion = ubicacion;
                    citaModalData.estado = estado;
                    
                    // Combinar fecha y hora
                    const fechaInicio = `${citaModalData.fecha} ${citaModalData.horaInicio}:00`;
                    const fechaFin = citaModalData.horaFin ? `${citaModalData.fecha} ${citaModalData.horaFin}:00` : null;
                    
                    try {
                        const response = await fetch('/citas', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                client_id: clienteId,
                                titulo: citaModalData.titulo,
                                fecha_inicio: fechaInicio,
                                fecha_fin: fechaFin,
                                ubicacion: citaModalData.ubicacion || null,
                                descripcion: citaModalData.descripcion || null,
                                estado: citaModalData.estado,
                                color: '#10b981'
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cita creada',
                                text: data.message || 'La cita se ha creado correctamente',
                                confirmButtonColor: '#10b981',
                                background: isDarkMode ? '#1e293b' : '#ffffff',
                                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                                customClass: {
                                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al crear la cita',
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
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    showCitaPhase2();
                }
            });
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>
