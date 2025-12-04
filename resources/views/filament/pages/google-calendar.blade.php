<x-filament-panels::page>
    <div class="space-y-6">
        <div class="fi-section rounded-xl bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                    Acceso al Calendario
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Usa los botones de arriba para crear nuevos eventos o abrir Google Calendar en una nueva pestaña.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <x-filament::button
                        tag="a"
                        href="https://calendar.google.com/calendar/r/eventedit"
                        target="_blank"
                        icon="heroicon-o-plus"
                        color="success"
                        size="lg"
                    >
                        Nuevo Evento
                    </x-filament::button>
                    <x-filament::button
                        tag="a"
                        href="https://calendar.google.com"
                        target="_blank"
                        icon="heroicon-o-arrow-top-right-on-square"
                        color="gray"
                        size="lg"
                    >
                        Abrir Google Calendar
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
