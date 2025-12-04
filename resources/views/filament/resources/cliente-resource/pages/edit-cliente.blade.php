<x-filament-panels::page>
    @php
        $cliente = $this->record;
    @endphp

    {{-- Header Sticky --}}
    <div class="sticky top-0 z-40 -mx-4 sm:-mx-6 lg:-mx-8 -mt-8 mb-6">
        <div class="bg-emerald-600 dark:bg-emerald-700 px-4 sm:px-6 lg:px-8 py-4 shadow-xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('index') }}" 
                       class="w-10 h-10 bg-emerald-700 dark:bg-emerald-800 rounded-lg flex items-center justify-center hover:bg-emerald-800 dark:hover:bg-emerald-900 transition-colors">
                        <x-heroicon-o-arrow-left class="w-5 h-5 text-white"/>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white">{{ $cliente->nombre_empresa }}</h1>
                    </div>
                    @if($cliente->estado_cuenta)
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            @if($cliente->estado_cuenta === 'activo') bg-green-400 text-green-900
                            @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-400 text-yellow-900
                            @elseif($cliente->estado_cuenta === 'suspendido') bg-red-400 text-red-900
                            @else bg-gray-400 text-gray-900 @endif
                        ">
                            {{ ucfirst($cliente->estado_cuenta) }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('view', ['record' => $cliente]) }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-700 dark:bg-gray-700 hover:bg-emerald-800 dark:hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-eye class="w-4 h-4"/>
                        <span class="hidden sm:inline">Ver</span>
                    </a>
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('create') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-emerald-700 dark:text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-plus class="w-4 h-4"/>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
                    <button type="button" wire:click="mountAction('delete')"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-red-500 dark:bg-gray-700 hover:bg-red-600 dark:hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-all">
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
