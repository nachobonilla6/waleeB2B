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
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Calendario Visual -->
                    <div class="lg:col-span-2">
                        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                                    Calendario de Citas
                                </h3>
                                
                                <!-- Calendario Mensual -->
                                @php
                                    $hoy = now();
                                    $inicioMes = $hoy->copy()->startOfMonth()->startOfWeek();
                                    $finMes = $hoy->copy()->endOfMonth()->endOfWeek();
                                    $citasPorDia = $this->getCitasPorDiaProperty();
                                @endphp
                                
                                <div class="overflow-x-auto">
                                    <div class="min-w-full">
                                        <!-- Encabezado del mes -->
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $hoy->translatedFormat('F Y') }}
                                            </h4>
                                        </div>
                                        
                                        <!-- Días de la semana -->
                                        <div class="grid grid-cols-7 gap-1 mb-2">
                                            @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia)
                                                <div class="text-center text-sm font-semibold text-gray-600 dark:text-gray-400 py-2">
                                                    {{ $dia }}
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Días del mes -->
                                        <div class="grid grid-cols-7 gap-1">
                                            @for($fecha = $inicioMes->copy(); $fecha->lte($finMes); $fecha->addDay())
                                                @php
                                                    $fechaStr = $fecha->format('Y-m-d');
                                                    $esHoy = $fecha->isToday();
                                                    $esMesActual = $fecha->month === $hoy->month;
                                                    $citasDelDia = $citasPorDia->get($fechaStr, collect());
                                                @endphp
                                                <div 
                                                    wire:click="seleccionarFecha('{{ $fechaStr }}')"
                                                    class="min-h-[80px] p-2 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer transition-colors
                                                        {{ $esHoy ? 'bg-primary-50 dark:bg-primary-900/20 border-primary-300 dark:border-primary-700' : '' }}
                                                        {{ !$esMesActual ? 'opacity-50' : 'hover:bg-gray-50 dark:hover:bg-gray-800' }}
                                                        {{ $fechaSeleccionada === $fechaStr ? 'ring-2 ring-primary-500' : '' }}">
                                                    <div class="text-sm font-medium mb-1
                                                        {{ $esHoy ? 'text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300' }}">
                                                        {{ $fecha->day }}
                                                    </div>
                                                    <div class="space-y-1">
                                                        @foreach($citasDelDia->take(2) as $cita)
                                                            <div class="text-xs px-1.5 py-0.5 rounded
                                                                @if($cita->estado === 'completada') bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200
                                                                @elseif($cita->estado === 'programada') bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200
                                                                @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                                                @endif">
                                                                {{ Str::limit($cita->titulo, 15) }}
                                                            </div>
                                                        @endforeach
                                                        @if($citasDelDia->count() > 2)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                +{{ $citasDelDia->count() - 2 }} más
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Citas del día seleccionado -->
                    <div class="lg:col-span-1">
                        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                                    Citas del {{ \Carbon\Carbon::parse($fechaSeleccionada)->translatedFormat('d \d\e F') }}
                                </h3>
                                <div class="space-y-3" wire:key="citas-dia-{{ $fechaSeleccionada }}">
                                    @forelse($this->getCitasDelDiaProperty() as $cita)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $cita->titulo }}
                                                        </h4>
                                                        <span class="px-1.5 py-0.5 text-xs font-medium rounded-full
                                                            @if($cita->estado === 'completada') bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200
                                                            @elseif($cita->estado === 'programada') bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200
                                                            @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                                            @endif">
                                                            {{ ucfirst($cita->estado) }}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-600 dark:text-gray-400 space-y-0.5">
                                                        <p><strong>Hora:</strong> {{ $cita->fecha_inicio->format('H:i') }}
                                                            @if($cita->fecha_fin)
                                                                - {{ $cita->fecha_fin->format('H:i') }}
                                                            @endif
                                                        </p>
                                                        @if($cita->cliente_id && $cita->cliente)
                                                            <p><strong>Cliente:</strong> {{ Str::limit($cita->cliente->nombre_empresa, 20) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                            <p class="text-sm">No hay citas programadas para este día</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($activeTab === 'citas')
                <!-- Lista de Citas -->
                <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                            Citas Programadas
                        </h3>
                        <div class="space-y-4" wire:key="citas-list">
                            @forelse($this->getCitasProperty() as $cita)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $cita->titulo }}
                                                </h4>
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if($cita->estado === 'completada') bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200
                                                    @elseif($cita->estado === 'programada') bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200
                                                    @endif">
                                                    {{ ucfirst($cita->estado) }}
                                                </span>
                                                @if($cita->google_event_id)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                                        ✓ Google Calendar
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                                <p><strong>Fecha:</strong> {{ $cita->fecha_inicio->format('d/m/Y H:i') }} 
                                                    @if($cita->fecha_fin)
                                                        - {{ $cita->fecha_fin->format('H:i') }}
                                                    @endif
                                                </p>
                                                @if($cita->cliente_id && $cita->cliente)
                                                    <p><strong>Cliente:</strong> {{ $cita->cliente->nombre_empresa }}</p>
                                                @elseif($cita->cliente)
                                                    <p><strong>Cliente:</strong> {{ $cita->cliente }}</p>
                                                @endif
                                                @if($cita->ubicacion)
                                                    <p><strong>Ubicación:</strong> {{ $cita->ubicacion }}</p>
                                                @endif
                                                @if($cita->descripcion)
                                                    <p class="mt-2">{{ $cita->descripcion }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <button
                                                wire:click="mountAction('edit', { id: {{ $cita->id }} })"
                                                class="fi-btn fi-btn-size-sm fi-color-info fi-btn-color-info inline-flex items-center justify-center gap-x-1 rounded-lg px-2.5 py-1.5 text-sm font-semibold shadow-sm ring-1 transition duration-75 hover:bg-gray-50 focus-visible:outline-none disabled:opacity-70 disabled:pointer-events-none dark:hover:bg-white/5">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                                Editar
                                            </button>
                                            @if($cita->google_event_id)
                                                <x-filament::button
                                                    tag="a"
                                                    href="{{ (new \App\Services\GoogleCalendarService())->getCreateEventUrl($cita) }}"
                                                    target="_blank"
                                                    size="sm"
                                                    color="success"
                                                    icon="heroicon-o-arrow-top-right-on-square">
                                                    Ver en Google
                                                </x-filament::button>
                                            @endif
                                            <x-filament::button
                                                wire:click="deleteCita({{ $cita->id }})"
                                                wire:confirm="¿Estás seguro de eliminar esta cita?"
                                                size="sm"
                                                color="danger"
                                                icon="heroicon-o-trash">
                                                Eliminar
                                            </x-filament::button>
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
