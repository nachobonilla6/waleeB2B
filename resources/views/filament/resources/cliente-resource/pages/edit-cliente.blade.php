<x-filament-panels::page>
    @php
        $cliente = $this->record;
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <x-filament::button
                :href="\App\Filament\Resources\ClienteResource::getUrl('index')"
                tag="a"
                color="gray"
                icon="heroicon-o-arrow-left"
            />
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cliente->nombre_empresa }}</h1>
            </div>
            @if($cliente->estado_cuenta)
                <x-filament::badge :color="match($cliente->estado_cuenta) {
                    'activo' => 'success',
                    'pendiente' => 'warning', 
                    'suspendido' => 'danger',
                    default => 'gray'
                }">
                    {{ ucfirst($cliente->estado_cuenta) }}
                </x-filament::badge>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <x-filament::button
                :href="\App\Filament\Resources\ClienteResource::getUrl('view', ['record' => $cliente])"
                tag="a"
                color="gray"
                icon="heroicon-o-eye"
            >
                Ver
            </x-filament::button>
            <x-filament::button
                :href="\App\Filament\Resources\ClienteResource::getUrl('create')"
                tag="a"
                color="gray"
                icon="heroicon-o-plus"
            >
                Nuevo
            </x-filament::button>
            <x-filament::button
                wire:click="mountAction('delete')"
                color="danger"
                icon="heroicon-o-trash"
            >
                Eliminar
            </x-filament::button>
        </div>
    </div>

    {{-- Formulario --}}
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
</x-filament-panels::page>
