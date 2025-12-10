<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />

        <div class="w-full max-w-full space-y-4" style="width: 100% !important; max-width: 100% !important; margin-left: -1.5rem !important; margin-right: -1.5rem !important; padding-left: 1.5rem !important; padding-right: 1.5rem !important;">
            <div class="flex flex-wrap gap-4 w-full" style="width: 100% !important;">
                <a href="{{ \App\Filament\Resources\ClientesGoogleCopiaResource::getUrl('index') }}" class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors font-medium">
                    Site Scraper
                </a>

                <a href="{{ \App\Filament\Resources\ClienteEnProcesoResource::getUrl('index') }}" class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors font-medium">
                    Clientes Google
                </a>

                <a href="https://websolutions.work/admin/clientes-google-enviadas" class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors font-medium">
                    Propuestas Enviadas
                </a>
            </div>
        </div>

        {{-- Formulario abajo --}}
        <x-filament-widgets::widgets :widgets="[ \App\Filament\Resources\ClientesGoogleCopiaResource\Widgets\SiteScraperFormWidget::class ]" />
    </div>
</x-filament-panels::page>
