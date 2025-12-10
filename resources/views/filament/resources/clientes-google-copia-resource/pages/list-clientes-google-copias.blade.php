<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />

        @include('components.google-clients-nav')

        {{-- Formulario abajo --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full" style="width: 100% !important; max-width: 100% !important;">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Enviar Datos (Alpha Bot)</h3>
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
