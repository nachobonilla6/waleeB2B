<div class="w-full space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ \App\Filament\Pages\SiteScraper::getUrl() }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow border-2 border-gray-200 dark:border-gray-700 ring-1 ring-gray-100 dark:ring-gray-800">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Site Scraper</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Capturar nuevos leads</p>
            </div>
        </div>
    </a>

    <a href="{{ \App\Filament\Resources\ClienteEnProcesoResource::getUrl('index') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow border-2 border-primary-500 dark:border-primary-700 ring-2 ring-primary-200 dark:ring-primary-800">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0zM7 10a2 2 0 11-4 0 2 2 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Clientes en Proceso</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gestionar prospectos pendientes</p>
            </div>
        </div>
    </a>

    <a href="{{ \App\Filament\Resources\ClientPropuestaEnviadaResource::getUrl('index') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow border-2 border-gray-200 dark:border-gray-700 ring-1 ring-gray-100 dark:ring-gray-800">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Propuestas Enviadas</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Clientes con propuesta enviada</p>
            </div>
        </div>
    </a>
</div>
</div>
