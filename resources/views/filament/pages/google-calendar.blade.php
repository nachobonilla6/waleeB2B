<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Pestañas -->
        <x-filament::tabs>
            <x-filament::tabs.item
                :active="$activeTab === 'calendario'"
                icon="heroicon-o-calendar-days"
                wire:click="$set('activeTab', 'calendario')"
            >
                Calendario
            </x-filament::tabs.item>
            <x-filament::tabs.item
                :active="$activeTab === 'citas'"
                icon="heroicon-o-list-bullet"
                wire:click="$set('activeTab', 'citas')"
            >
                Lista de Citas
            </x-filament::tabs.item>
        </x-filament::tabs>

        <!-- Contenido de las pestañas -->
        <div>
            @if($activeTab === 'calendario')
                <!-- Calendario Visual -->
                <div class="fi-section rounded-2xl bg-white dark:bg-gray-900 shadow-lg ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
                    <div class="p-8">
                        <!-- Encabezado del mes -->
                        @php
                            $hoy = now();
                            $inicioMes = $hoy->copy()->startOfMonth()->startOfWeek();
                            $finMes = $hoy->copy()->endOfMonth()->endOfWeek();
                            $citasPorDia = $this->getCitasPorDiaProperty();
                        @endphp
                        
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                                {{ $hoy->translatedFormat('F Y') }}
                            </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $hoy->translatedFormat('l, d') }} de {{ $hoy->translatedFormat('F') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Días de la semana -->
                        <div class="grid grid-cols-7 gap-3 mb-4">
                            @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia)
                                <div class="text-center text-sm font-bold text-gray-600 dark:text-gray-400 py-3 uppercase tracking-wide">
                                    {{ $dia }}
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Días del mes -->
                        <div class="grid grid-cols-7 gap-3">
                            @for($fecha = $inicioMes->copy(); $fecha->lte($finMes); $fecha->addDay())
                                @php
                                    $fechaStr = $fecha->format('Y-m-d');
                                    $esHoy = $fecha->isToday();
                                    $esMesActual = $fecha->month === $hoy->month;
                                    $citasDelDia = $citasPorDia->get($fechaStr, collect());
                                @endphp
                                <div 
                                    class="min-h-[120px] p-3 rounded-xl border-2 transition-all duration-200
                                        {{ $esHoy ? 'bg-gradient-to-br from-primary-50 to-primary-100/50 dark:from-primary-900/30 dark:to-primary-800/20 border-primary-400 dark:border-primary-600 ring-2 ring-primary-300/50 dark:ring-primary-700/50 shadow-md' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800' }}
                                        {{ !$esMesActual ? 'opacity-30' : 'hover:shadow-md hover:border-gray-300 dark:hover:border-gray-600' }}">
                                    <!-- Número del día -->
                                    <div class="flex items-center justify-between mb-2.5">
                                        <span class="text-base font-bold
                                            {{ $esHoy ? 'text-primary-700 dark:text-primary-300 bg-white dark:bg-primary-900/50 rounded-full w-7 h-7 flex items-center justify-center' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $fecha->day }}
                                        </span>
                                        @if($citasDelDia->count() > 0)
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $esHoy ? 'bg-primary-200 dark:bg-primary-800 text-primary-800 dark:text-primary-200' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                                {{ $citasDelDia->count() }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Citas del día -->
                                    <div class="space-y-1.5">
                                        @foreach($citasDelDia->take(3) as $cita)
                                            @php
                                                $isArray = is_array($cita);
                                                $titulo = $isArray ? $cita['titulo'] : $cita->titulo;
                                                $estado = $isArray ? ($cita['estado'] ?? 'programada') : $cita->estado;
                                                $fechaInicio = $isArray ? $cita['fecha_inicio'] : $cita->fecha_inicio;
                                                $fromGoogle = $isArray ? ($cita['from_google'] ?? false) : false;
                                                $citaId = $isArray ? ($cita['id'] ?? null) : $cita->id;
                                                $cliente = $isArray ? ($cita['cliente'] ?? null) : $cita->cliente;
                                                
                                                if ($fechaInicio instanceof \DateTime) {
                                                    $hora = $fechaInicio->format('H:i');
                                                } elseif (is_string($fechaInicio)) {
                                                    $hora = (new \DateTime($fechaInicio))->format('H:i');
                                                } else {
                                                    $hora = '';
                                                }
                                            @endphp
                                            <button
                                                @if($citaId && !$fromGoogle)
                                                    wire:click="mountAction('edit', { id: {{ $citaId }} })"
                                                @endif
                                                class="w-full text-left text-xs px-2.5 py-2 rounded-lg font-medium transition-all hover:scale-[1.03] shadow-sm {{ $citaId && !$fromGoogle ? 'cursor-pointer' : 'cursor-default' }}
                                                    @if($estado === 'completada') 
                                                        bg-success-100 text-success-900 dark:bg-success-900/60 dark:text-success-100 hover:bg-success-200 dark:hover:bg-success-900/80 border border-success-300 dark:border-success-700
                                                    @elseif($estado === 'programada' || $fromGoogle) 
                                                        bg-warning-100 text-warning-900 dark:bg-warning-900/60 dark:text-warning-100 hover:bg-warning-200 dark:hover:bg-warning-900/80 border border-warning-300 dark:border-warning-700
                                                    @else 
                                                        bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600
                                                    @endif">
                                                <div class="truncate font-semibold mb-0.5 leading-tight">
                                                    {{ Str::limit($titulo, 20) }}
                                                    @if($fromGoogle)
                                                        <span class="text-[9px] opacity-70 font-normal">(GC)</span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] opacity-80 mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $hora }}
                                                    @if($cliente && !$isArray && $cliente->nombre_empresa)
                                                        <span class="mx-1">·</span>
                                                        <span class="truncate">{{ Str::limit($cliente->nombre_empresa, 10) }}</span>
                                                    @endif
                                                </div>
                                            </button>
                                        @endforeach
                                        @if($citasDelDia->count() > 3)
                                            <div class="text-[10px] text-gray-600 dark:text-gray-400 text-center py-1.5 font-medium">
                                                +{{ $citasDelDia->count() - 3 }} más
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @elseif($activeTab === 'citas')
                <!-- Lista de Citas -->
                <div class="fi-section rounded-2xl bg-white dark:bg-gray-900 shadow-lg ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-950 dark:text-white mb-6">
                            Citas Programadas
                        </h3>
                        <div class="space-y-5" wire:key="citas-list">
                            @forelse($this->getCitasProperty() as $cita)
                                @php
                                    $isArray = is_array($cita);
                                    $titulo = $isArray ? $cita['titulo'] : $cita->titulo;
                                    $descripcion = $isArray ? ($cita['descripcion'] ?? null) : $cita->descripcion;
                                    $fechaInicio = $isArray ? $cita['fecha_inicio'] : $cita->fecha_inicio;
                                    $fechaFin = $isArray ? ($cita['fecha_fin'] ?? null) : $cita->fecha_fin;
                                    $estado = $isArray ? ($cita['estado'] ?? 'programada') : $cita->estado;
                                    $ubicacion = $isArray ? ($cita['ubicacion'] ?? null) : $cita->ubicacion;
                                    $googleEventId = $isArray ? ($cita['google_event_id'] ?? null) : $cita->google_event_id;
                                    $fromGoogle = $isArray ? ($cita['from_google'] ?? false) : false;
                                    $citaId = $isArray ? ($cita['id'] ?? null) : $cita->id;
                                    $cliente = $isArray ? ($cita['cliente'] ?? null) : $cita->cliente;
                                    
                                    if ($fechaInicio instanceof \DateTime) {
                                        $fechaInicioFormatted = $fechaInicio->format('d/m/Y H:i');
                                        $horaInicio = $fechaInicio->format('H:i');
                                    } elseif (is_string($fechaInicio)) {
                                        $dt = new \DateTime($fechaInicio);
                                        $fechaInicioFormatted = $dt->format('d/m/Y H:i');
                                        $horaInicio = $dt->format('H:i');
                                    } else {
                                        $fechaInicioFormatted = '';
                                        $horaInicio = '';
                                    }
                                    
                                    if ($fechaFin) {
                                        if ($fechaFin instanceof \DateTime) {
                                            $horaFin = $fechaFin->format('H:i');
                                        } elseif (is_string($fechaFin)) {
                                            $horaFin = (new \DateTime($fechaFin))->format('H:i');
                                        } else {
                                            $horaFin = '';
                                        }
                                    } else {
                                        $horaFin = null;
                                    }
                                @endphp
                                <div class="border-2 border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:border-gray-300 dark:hover:border-gray-600 transition-all shadow-sm hover:shadow-md">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3 flex-wrap">
                                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                                    {{ $titulo }}
                                                </h4>
                                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                                    @if($estado === 'completada') bg-success-100 text-success-900 dark:bg-success-900 dark:text-success-100 border border-success-300 dark:border-success-700
                                                    @elseif($estado === 'programada' || $fromGoogle) bg-warning-100 text-warning-900 dark:bg-warning-900 dark:text-warning-100 border border-warning-300 dark:border-warning-700
                                                    @else bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100 border border-gray-300 dark:border-gray-600
                                                    @endif">
                                                    {{ ucfirst($estado) }}
                                                </span>
                                                @if($googleEventId || $fromGoogle)
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-success-100 text-success-900 dark:bg-success-900 dark:text-success-100 border border-success-300 dark:border-success-700">
                                                        ✓ Google Calendar
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                                <p class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <strong class="text-gray-900 dark:text-white">Fecha:</strong> 
                                                    <span>{{ $fechaInicioFormatted }} 
                                                        @if($horaFin)
                                                            - {{ $horaFin }}
                                                    @endif
                                                    </span>
                                                </p>
                                                @if($cliente)
                                                    @if($isArray && is_string($cliente))
                                                        <p class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                            <strong class="text-gray-900 dark:text-white">Cliente:</strong> {{ $cliente }}
                                                        </p>
                                                    @elseif(!$isArray && $cliente->nombre_empresa)
                                                        <p class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                            <strong class="text-gray-900 dark:text-white">Cliente:</strong> {{ $cliente->nombre_empresa }}
                                                        </p>
                                                    @endif
                                                @endif
                                                @if($ubicacion)
                                                    <p class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <strong class="text-gray-900 dark:text-white">Ubicación:</strong> {{ $ubicacion }}
                                                    </p>
                                                @endif
                                                @if($descripcion)
                                                    <p class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 leading-relaxed">
                                                        {{ $descripcion }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            @if($citaId && !$fromGoogle)
                                            <button
                                                    wire:click="mountAction('edit', { id: {{ $citaId }} })"
                                                class="fi-btn fi-btn-size-sm fi-color-info fi-btn-color-info inline-flex items-center justify-center gap-x-1 rounded-lg px-2.5 py-1.5 text-sm font-semibold shadow-sm ring-1 transition duration-75 hover:bg-gray-50 focus-visible:outline-none disabled:opacity-70 disabled:pointer-events-none dark:hover:bg-white/5">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                                Editar
                                            </button>
                                            @endif
                                            @if($googleEventId || $fromGoogle)
                                                <x-filament::button
                                                    tag="a"
                                                    href="https://calendar.google.com/calendar/event?eid={{ base64_encode($googleEventId ?? '') }}"
                                                    target="_blank"
                                                    size="sm"
                                                    color="success"
                                                    icon="heroicon-o-arrow-top-right-on-square">
                                                    Ver en Google
                                                </x-filament::button>
                                            @endif
                                            @if($citaId && !$fromGoogle)
                                            <x-filament::button
                                                    wire:click="deleteCita({{ $citaId }})"
                                                wire:confirm="¿Estás seguro de eliminar esta cita?"
                                                size="sm"
                                                color="danger"
                                                icon="heroicon-o-trash">
                                                Eliminar
                                            </x-filament::button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <p class="text-lg mb-2">No hay citas programadas</p>
                                    <p class="text-sm">Crea una nueva cita usando el botón "Nueva Cita"</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
