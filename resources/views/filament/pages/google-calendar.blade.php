<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Barra de acciones --}}
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <a href="https://calendar.google.com/calendar/r/eventedit" target="_blank" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition-all text-sm">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Nuevo Evento
                </a>
                <a href="https://calendar.google.com" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-all text-sm">
                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                    Abrir Google Calendar
                </a>
            </div>
        </div>

        {{-- Calendario embebido --}}
        <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <iframe 
                src="https://calendar.google.com/calendar/embed?src=nachobonilla6%40gmail.com&ctz=America%2FCosta_Rica&showTitle=0&showNav=1&showDate=1&showPrint=0&showTabs=1&showCalendars=0&showTz=0&mode=WEEK&bgcolor=%23ffffff" 
                style="border: 0" 
                width="100%" 
                height="800" 
                frameborder="0" 
                scrolling="no"
                class="dark:invert dark:hue-rotate-180 dark:contrast-90"
            ></iframe>
        </div>
    </div>
</x-filament-panels::page>
