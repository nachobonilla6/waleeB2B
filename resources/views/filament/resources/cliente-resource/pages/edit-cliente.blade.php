<x-filament-panels::page>
    @php
        $cliente = $this->record;
    @endphp

    {{-- Header Sticky con info del cliente y acciones --}}
    <div class="sticky top-0 z-40 -mx-4 sm:-mx-6 lg:-mx-8 -mt-6 mb-6">
        <div class="bg-gradient-to-r from-emerald-600 via-green-500 to-teal-500 px-4 sm:px-6 lg:px-8 py-4 shadow-xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                {{-- Info del cliente --}}
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                        <x-heroicon-o-building-office class="w-6 h-6 text-white"/>
                    </div>
                    <div>
                        <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Editando Cliente</p>
                        <h1 class="text-xl sm:text-2xl font-bold text-white">{{ $cliente->nombre_empresa }}</h1>
                    </div>
                    @if($cliente->estado_cuenta)
                        <span class="hidden sm:inline-flex px-3 py-1 rounded-full text-xs font-bold
                            @if($cliente->estado_cuenta === 'activo') bg-green-400 text-green-900
                            @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-400 text-yellow-900
                            @elseif($cliente->estado_cuenta === 'suspendido') bg-red-400 text-red-900
                            @else bg-gray-400 text-gray-900 @endif
                        ">
                            {{ ucfirst($cliente->estado_cuenta) }}
                        </span>
                    @endif
                </div>

                {{-- Botones de acción --}}
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Volver --}}
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('index') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-all duration-200 backdrop-blur-sm">
                        <x-heroicon-o-arrow-left class="w-4 h-4"/>
                        <span class="hidden sm:inline">Volver</span>
                    </a>

                    {{-- Ver --}}
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('view', ['record' => $cliente]) }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-400/80 hover:bg-emerald-400 text-white rounded-lg text-sm font-medium transition-all duration-200">
                        <x-heroicon-o-eye class="w-4 h-4"/>
                        <span class="hidden sm:inline">Ver</span>
                    </a>

                    {{-- Ver Posts --}}
                    <a href="{{ \App\Filament\Resources\VelaSportPostResource::getUrl('index') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-blue-400/80 hover:bg-blue-400 text-white rounded-lg text-sm font-medium transition-all duration-200">
                        <x-heroicon-o-newspaper class="w-4 h-4"/>
                        <span class="hidden sm:inline">Posts</span>
                    </a>

                    {{-- Nuevo Cliente --}}
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('create') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-white/90 hover:bg-white text-emerald-700 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg">
                        <x-heroicon-o-plus class="w-4 h-4"/>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>

                    {{-- Eliminar --}}
                    <button 
                        type="button"
                        wire:click="mountAction('delete')"
                        class="inline-flex items-center gap-2 px-3 py-2 bg-red-500/80 hover:bg-red-500 text-white rounded-lg text-sm font-medium transition-all duration-200">
                        <x-heroicon-o-trash class="w-4 h-4"/>
                        <span class="hidden sm:inline">Eliminar</span>
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

