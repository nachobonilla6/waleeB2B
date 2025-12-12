<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-900/70 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="p-4 sm:p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-50">Redactar Email</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Diseño de redacción con editor enriquecido. (Placeholder, sin envío real)</p>
                    </div>
                    <div class="flex gap-3">
                        <x-filament::button color="gray" icon="heroicon-o-inbox-stack" disabled>
                            Borradores
                        </x-filament::button>
                        <x-filament::button color="gray" icon="heroicon-o-paper-airplane" disabled>
                            Enviar
                        </x-filament::button>
                    </div>
                </div>

                <x-filament::section>
                    <x-filament::form wire:submit.prevent="enviar">
                        {{ $this->form }}
                        <div class="mt-4 flex justify-end gap-3">
                            <x-filament::button color="gray" icon="heroicon-o-stop-circle" disabled>
                                Guardar borrador
                            </x-filament::button>
                            <x-filament::button color="primary" icon="heroicon-o-paper-airplane" disabled>
                                Enviar
                            </x-filament::button>
                        </div>
                    </x-filament::form>
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
