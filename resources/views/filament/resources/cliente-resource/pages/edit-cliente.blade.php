<x-filament-panels::page>
    @php
        $cliente = $this->record;
    @endphp

    {{-- Header Sticky --}}
    <div class="sticky top-0 z-40 -mx-4 sm:-mx-6 lg:-mx-8 -mt-8 mb-6">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 lg:px-8 py-4 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('index') }}" 
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <x-heroicon-o-arrow-left class="w-5 h-5"/>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $cliente->nombre_empresa }}</h1>
                    @if($cliente->estado_cuenta)
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            @if($cliente->estado_cuenta === 'activo') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                            @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                            @elseif($cliente->estado_cuenta === 'suspendido') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif
                        ">{{ ucfirst($cliente->estado_cuenta) }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('view', ['record' => $cliente]) }}" 
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <x-heroicon-o-eye class="w-4 h-4"/>
                        <span class="hidden sm:inline">Ver</span>
                    </a>
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('create') }}" 
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <x-heroicon-o-plus class="w-4 h-4"/>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
                    <button type="button" wire:click="mountAction('delete')"
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <x-heroicon-o-trash class="w-4 h-4"/>
                    </button>
                </div>
            </div>
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
