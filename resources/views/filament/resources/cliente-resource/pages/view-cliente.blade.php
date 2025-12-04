<x-filament-panels::page>
    @php
        $cliente = $this->record;
        $redesSociales = $cliente->redes_sociales ?? [];
        $iconosRedes = [
            'facebook' => '📘',
            'instagram' => '📸',
            'tiktok' => '🎵',
            'twitter' => '🐦',
            'linkedin' => '💼',
            'youtube' => '▶️',
            'pinterest' => '📌',
        ];
    @endphp

    {{-- Header Sticky --}}
    <div class="sticky top-0 z-40 -mx-4 sm:-mx-6 lg:-mx-8 -mt-8 mb-6">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 lg:px-8 py-4 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('index') }}" 
                       class="fi-btn fi-btn-size-md fi-btn-color-gray inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
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
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('edit', ['record' => $cliente]) }}" 
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <x-heroicon-o-pencil class="w-4 h-4"/>
                        <span class="hidden sm:inline">Editar</span>
                    </a>
                    <button type="button" wire:click="mountAction('cotizacion')"
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <x-heroicon-o-document-text class="w-4 h-4"/>
                        <span class="hidden sm:inline">Cotización</span>
                    </button>
                    <button type="button" wire:click="mountAction('factura')"
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <x-heroicon-o-banknotes class="w-4 h-4"/>
                        <span class="hidden sm:inline">Factura</span>
                    </button>
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('create') }}" 
                       class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <x-heroicon-o-plus class="w-4 h-4"/>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Wizard con navegación --}}
    <div x-data="{ currentStep: 1 }" class="space-y-6">
        
        {{-- Indicador de pasos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
            <div class="flex items-center gap-2 min-w-max">
                <template x-for="step in 6" :key="step">
                    <div class="flex items-center">
                        <button @click="currentStep = step"
                            class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all border-2"
                            x-bind:class="currentStep === step ? 'bg-primary-500 text-white border-primary-500' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            x-text="step">
                        </button>
                        <div x-show="step < 6" class="w-8 h-0.5 bg-gray-200 dark:bg-gray-600 mx-1"></div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Contenido --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            {{-- Paso 1: Empresa --}}
            <div x-show="currentStep === 1">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-building-office class="w-6 h-6 text-gray-500 dark:text-gray-400"/> 1. Empresa
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->nombre_empresa ?? '—' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($cliente->estado_cuenta ?? '—') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tipo</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($cliente->tipo_empresa ?? '—') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Industria</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($cliente->industria ?? '—') }}</p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Descripción</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 min-h-[60px]">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->descripcion ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paso 2: Contacto --}}
            <div x-show="currentStep === 2">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-phone class="w-6 h-6 text-gray-500 dark:text-gray-400"/> 2. Contacto
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            @if($cliente->correo)
                                <a href="mailto:{{ $cliente->correo }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ $cliente->correo }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Teléfono</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            @if($cliente->telefono)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">{{ $cliente->telefono }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tel. Alternativo</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->telefono_alternativo ?? '—' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">WhatsApp</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            @if($cliente->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">{{ $cliente->whatsapp }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paso 3: Ubicación --}}
            <div x-show="currentStep === 3">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-map-pin class="w-6 h-6 text-gray-500 dark:text-gray-400"/> 3. Ubicación
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">País</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->pais ?? '—' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->estado ?? '—' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ciudad</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->ciudad ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dirección</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->direccion ?? '—' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">C.P.</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->codigo_postal ?? '—' }}</p>
                        </div>
                    </div>
                </div>
                @if($cliente->direccion && $cliente->ciudad)
                    <div class="px-6 pb-6">
                        <a href="https://maps.google.com/?q={{ urlencode($cliente->direccion . ', ' . $cliente->ciudad) }}" target="_blank" 
                           class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <x-heroicon-o-map class="w-5 h-5"/> Ver en Maps
                        </a>
                    </div>
                @endif
            </div>

            {{-- Paso 4: Sitio Web --}}
            <div x-show="currentStep === 4">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-globe-alt class="w-6 h-6 text-gray-500 dark:text-gray-400"/> 4. Sitio Web
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dominio</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->nombre_sitio ?? '—' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">URL</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            @if($cliente->url_sitio)
                                <a href="{{ $cliente->url_sitio }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline truncate block">{{ $cliente->url_sitio }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hosting</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->hosting ?? '—' }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Expira</label>
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->dominio_expira?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>
                @if($cliente->url_sitio)
                    <div class="px-6 pb-6">
                        <a href="{{ $cliente->url_sitio }}" target="_blank" 
                           class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <x-heroicon-o-arrow-top-right-on-square class="w-5 h-5"/> Visitar
                        </a>
                    </div>
                @endif
            </div>

            {{-- Paso 5: Redes --}}
            <div x-show="currentStep === 5">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-share class="w-6 h-6 text-gray-500 dark:text-gray-400"/> 5. Redes Sociales
                    </h3>
                </div>
                <div class="p-6">
                    @if(count($redesSociales) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($redesSociales as $red)
                                <a href="{{ $red['url'] ?? '#' }}" target="_blank" 
                                   class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                    <span class="text-2xl">{{ $iconosRedes[$red['red'] ?? ''] ?? '🌐' }}</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($red['red'] ?? 'Red') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $red['url'] ?? '' }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded {{ ($red['activo'] ?? false) ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-400' : 'bg-gray-200 text-gray-500 dark:bg-gray-600 dark:text-gray-400' }}">
                                        {{ ($red['activo'] ?? false) ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                            <x-heroicon-o-share class="w-12 h-12 mx-auto mb-2"/>
                            <p>Sin redes sociales</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Paso 6: Notas --}}
            <div x-show="currentStep === 6">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-document-text class="w-6 h-6 text-gray-500 dark:text-gray-400"/> 6. Notas
                    </h3>
                </div>
                <div class="p-6">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 min-h-[120px]">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $cliente->notas ?? 'Sin notas' }}</p>
                    </div>
                </div>
            </div>

            {{-- Navegación --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                <button x-show="currentStep > 1" @click="currentStep--"
                    class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-4 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <x-heroicon-o-arrow-left class="w-4 h-4"/> Anterior
                </button>
                <div x-show="currentStep === 1"></div>
                <button x-show="currentStep < 6" @click="currentStep++"
                    class="fi-btn fi-btn-size-md inline-flex items-center justify-center gap-1 font-medium rounded-lg border transition-colors focus:outline-none px-4 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Siguiente <x-heroicon-o-arrow-right class="w-4 h-4"/>
                </button>
                <div x-show="currentStep === 6"></div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
