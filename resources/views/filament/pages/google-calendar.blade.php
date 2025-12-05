<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Calendario embebido de Google Calendar -->
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                    Vista de Calendario
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

        <!-- Tabla de Citas -->
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">
                    Lista de Citas
                </h3>
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
