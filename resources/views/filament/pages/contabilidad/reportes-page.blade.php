<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Entradas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Facturas pagadas - Ingresos recibidos
                </p>
                <a href="{{ \App\Filament\Pages\Entradas::getUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                    Ver Entradas
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Salidas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Facturas pendientes y vencidas - Pagos pendientes
                </p>
                <a href="{{ \App\Filament\Pages\Salidas::getUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                    Ver Salidas
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page>

