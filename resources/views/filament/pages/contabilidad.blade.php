<x-filament-panels::page>
    <div class="space-y-6">
        <div class="fi-section-header-actions">
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    wire:click="setActiveTab('facturas')"
                    class="fi-btn fi-btn-size-sm fi-btn-color-gray fi-btn-variant-{{ $activeTab === 'facturas' ? 'filled' : 'ghost' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75m15 0h3m-3 0h3m-16.5 0h3m-3 0h3m-16.5 0h3m-3 0h3"></path>
                    </svg>
                    Facturas
                    @if($activeTab === 'facturas')
                        <span class="ml-2 inline-flex items-center rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                            {{ \App\Models\Factura::count() }}
                        </span>
                    @endif
                </button>
                
                <button
                    type="button"
                    wire:click="setActiveTab('cotizaciones')"
                    class="fi-btn fi-btn-size-sm fi-btn-color-gray fi-btn-variant-{{ $activeTab === 'cotizaciones' ? 'filled' : 'ghost' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Cotizaciones
                    @if($activeTab === 'cotizaciones')
                        <span class="ml-2 inline-flex items-center rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                            {{ \App\Models\Cotizacion::count() }}
                        </span>
                    @endif
                </button>
                
                <button
                    type="button"
                    wire:click="setActiveTab('reportes')"
                    class="fi-btn fi-btn-size-sm fi-btn-color-gray fi-btn-variant-{{ $activeTab === 'reportes' ? 'filled' : 'ghost' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reportes
                </button>
            </div>
        </div>

        <div class="mt-6">
            @if($activeTab === 'facturas')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Facturas</h2>
                            <a href="{{ \App\Filament\Resources\FacturaResource::getUrl('index') }}" class="fi-btn fi-btn-size-sm fi-btn-color-primary rounded-lg px-4 py-2 text-sm font-medium">
                                Abrir en página completa
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <iframe 
                            src="{{ \App\Filament\Resources\FacturaResource::getUrl('index') }}" 
                            class="w-full border-0 rounded-lg"
                            style="height: calc(100vh - 400px); min-height: 600px;"
                            frameborder="0"
                        ></iframe>
                    </div>
                </div>
            @elseif($activeTab === 'cotizaciones')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Cotizaciones</h2>
                            <a href="{{ \App\Filament\Resources\CotizacionResource::getUrl('index') }}" class="fi-btn fi-btn-size-sm fi-btn-color-primary rounded-lg px-4 py-2 text-sm font-medium">
                                Abrir en página completa
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <iframe 
                            src="{{ \App\Filament\Resources\CotizacionResource::getUrl('index') }}" 
                            class="w-full border-0 rounded-lg"
                            style="height: calc(100vh - 400px); min-height: 600px;"
                            frameborder="0"
                        ></iframe>
                    </div>
                </div>
            @elseif($activeTab === 'reportes')
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
            @endif
        </div>
    </div>
</x-filament-panels::page>

