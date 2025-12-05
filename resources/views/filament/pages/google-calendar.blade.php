<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Calendario embebido de Google Calendar -->
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                    Calendario de Citas
                </h3>
                <div class="relative" style="padding-bottom: 75%; height: 0; overflow: hidden;">
                    <iframe 
                        src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=America%2FMexico_City&showTitle=0&showNav=1&showDate=1&showPrint=0&showTabs=0&showCalendars=0&showTz=0"
                        style="border-width:0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        frameborder="0"
                        scrolling="no">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Lista de Citas -->
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                    Citas Programadas
                </h3>
                <div class="space-y-4">
                    @forelse($this->getCitas() as $cita)
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
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
