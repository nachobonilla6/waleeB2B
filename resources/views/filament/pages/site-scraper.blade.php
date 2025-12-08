<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ \App\Filament\Pages\SiteScraper::getUrl() }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow border-2 border-success-500 ring-2 ring-success-200 dark:ring-success-800">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Site Scraper</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Herramientas de scraping</p>
                    </div>
                </div>
            </a>

            <a href="{{ \App\Filament\Resources\ClientPropuestaEnviadaResource::getUrl('index') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow border-2 border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Propuestas Enviadas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ver clientes con propuestas enviadas</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Enviar Datos</h3>
            <form wire:submit="enviarWebhook" class="space-y-4">
                {{ $this->form }}
                <div class="flex justify-end">
                    <x-filament::button
                        type="submit"
                        color="success"
                        size="sm"
                    >
                        Enviar a Webhook
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
