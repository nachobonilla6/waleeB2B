<x-filament-panels::page>
    <div class="space-y-6">
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-6 sm:p-8">
                <div class="max-w-2xl mx-auto text-center space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Autorizar Google Calendar
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            Conecta tu cuenta de Google Calendar para sincronizar citas y permitir que los clientes agenden citas directamente.
                        </p>
                    </div>

                    @if($this->checkAuthStatus())
                        <div class="bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-lg p-4">
                            <div class="space-y-3">
                            <div class="flex items-center justify-center gap-3">
                                <svg class="h-6 w-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-success-800 dark:text-success-200 font-medium">
                                    Google Calendar está autorizado y conectado
                                </span>
                                </div>
                                @if($this->getAuthorizedEmail())
                                    <div class="text-sm text-success-700 dark:text-success-300">
                                        <strong>Cuenta:</strong> {{ $this->getAuthorizedEmail() }}
                                    </div>
                                @endif
                                <div class="text-sm text-success-700 dark:text-success-300">
                                    <strong>Calendario:</strong> {{ $this->getCalendarId() === 'primary' ? 'Calendario Principal (primary)' : $this->getCalendarId() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg p-4">
                            <div class="flex items-center justify-center gap-3 mb-4">
                                <svg class="h-6 w-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="text-warning-800 dark:text-warning-200 font-medium">
                                    Google Calendar no está autorizado
                                </span>
                            </div>
                            <p class="text-sm text-warning-700 dark:text-warning-300 mb-4">
                                Necesitas autorizar el acceso a Google Calendar para sincronizar citas.
                            </p>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @if(!$this->checkAuthStatus())
                            <x-filament::button
                                wire:click="authorizeGoogleCalendar"
                                size="lg"
                                color="primary"
                                icon="heroicon-o-arrow-right-on-rectangle">
                                Autorizar con Google Calendar
                            </x-filament::button>
                        @else
                            <div class="flex gap-3 justify-center">
                            <x-filament::button
                                wire:click="authorizeGoogleCalendar"
                                size="lg"
                                color="gray"
                                icon="heroicon-o-arrow-path">
                                Re-autorizar
                            </x-filament::button>
                                <x-filament::button
                                    wire:click="disconnectGoogleCalendar"
                                    wire:confirm="¿Estás seguro de que quieres desconectar Google Calendar? Esto te permitirá conectarlo con otra cuenta."
                                    size="lg"
                                    color="danger"
                                    icon="heroicon-o-x-circle">
                                    Desconectar
                                </x-filament::button>
                            </div>
                        @endif

                        <div class="text-sm text-gray-500 dark:text-gray-400 space-y-2">
                            <p><strong>¿Qué permite esta autorización?</strong></p>
                            <ul class="list-disc list-inside space-y-1 text-left max-w-md mx-auto">
                                <li>Leer eventos de tu calendario</li>
                                <li>Crear nuevas citas en tu calendario</li>
                                <li>Actualizar citas existentes</li>
                                <li>Eliminar citas cuando sea necesario</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
