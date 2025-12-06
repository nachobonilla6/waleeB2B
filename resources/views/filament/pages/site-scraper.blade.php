<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ ClientResource::getUrl('index') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Clientes Google</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gestionar clientes extraídos de Google</p>
                    </div>
                </div>
            </a>

            <a href="{{ ClientPropuestaEnviadaResource::getUrl('index') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow">
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
    </div>
</x-filament-panels::page>
