<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />

        <div class="w-full max-w-full space-y-4" style="width: 100% !important; max-width: 100% !important; margin-left: -1.5rem !important; margin-right: -1.5rem !important; padding-left: 1.5rem !important; padding-right: 1.5rem !important;">
            <div class="flex flex-wrap gap-4 w-full" style="width: 100% !important;">
                <a href="{{ \App\Filament\Resources\ClienteEnProcesoResource::getUrl('index') }}" class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors font-medium">
                    Clientes Google
                </a>

                <a href="https://websolutions.work/admin/clientes-google-enviadas" class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors font-medium">
                    Propuestas Enviadas
                </a>

                <a href="{{ \App\Filament\Resources\ClientesGoogleCopiaResource::getUrl('index') }}" class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors font-medium">
                    Extraer Nuevos Clientes
                </a>
            </div>
        </div>

        {{-- Formulario abajo --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full" style="width: 100% !important; max-width: 100% !important;">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Enviar Datos (Site Scraper)</h3>
            <x-filament-panels::form
                wire:submit="enviar"
            >
                {{ $this->form }}
                <div class="flex justify-end mt-4">
                    <x-filament::button
                        type="submit"
                        color="success"
                        size="sm"
                    >
                        Enviar a Webhook
                    </x-filament::button>
                </div>
            </x-filament-panels::form>
        </div>
    </div>
</x-filament-panels::page>
