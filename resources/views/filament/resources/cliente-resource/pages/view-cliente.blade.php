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
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('edit', ['record' => $cliente]) }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-700 dark:bg-emerald-800 hover:bg-emerald-800 dark:hover:bg-emerald-900 text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-pencil class="w-4 h-4"/>
                        <span class="hidden sm:inline">Editar</span>
                    </a>
                    <button type="button" wire:click="mountAction('cotizacion')"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-document-text class="w-4 h-4"/>
                        <span class="hidden sm:inline">Cotización</span>
                    </button>
                    <button type="button" wire:click="mountAction('factura')"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-banknotes class="w-4 h-4"/>
                        <span class="hidden sm:inline">Factura</span>
                    </button>
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('create') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-white hover:bg-gray-100 text-emerald-700 rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-plus class="w-4 h-4"/>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Wizard con navegación --}}
    <div x-data="{ currentStep: 1, totalSteps: 6 }" class="space-y-6">
        
        {{-- Indicador de pasos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                @php
                    $steps = [
                        ['icon' => 'heroicon-o-building-office', 'label' => 'Empresa'],
                        ['icon' => 'heroicon-o-phone', 'label' => 'Contacto'],
                        ['icon' => 'heroicon-o-map-pin', 'label' => 'Ubicación'],
                        ['icon' => 'heroicon-o-globe-alt', 'label' => 'Sitio Web'],
                        ['icon' => 'heroicon-o-share', 'label' => 'Redes'],
                        ['icon' => 'heroicon-o-document-text', 'label' => 'Notas'],
                    ];
                @endphp
                @foreach($steps as $index => $step)
                    <button 
                        @click="currentStep = {{ $index + 1 }}"
                        :class="currentStep === {{ $index + 1 }} ? 'bg-emerald-100 dark:bg-emerald-900/50 border-emerald-500' : 'bg-gray-50 dark:bg-gray-700 border-transparent hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg border-2 transition-all"
                    >
                        <div :class="currentStep === {{ $index + 1 }} ? 'bg-emerald-500 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'"
                             class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">
                            {{ $index + 1 }}
                        </div>
                        <span :class="currentStep === {{ $index + 1 }} ? 'text-emerald-700 dark:text-emerald-400' : 'text-gray-600 dark:text-gray-400'"
                              class="hidden md:inline text-sm font-medium">{{ $step['label'] }}</span>
                    </button>
                    @if($index < count($steps) - 1)
                        <div class="flex-1 h-0.5 bg-gray-200 dark:bg-gray-700 mx-2"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Contenido de cada paso --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            {{-- Paso 1: Empresa --}}
            <div x-show="currentStep === 1" x-transition>
                <div class="bg-emerald-600 dark:bg-emerald-700 px-6 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-building-office class="w-6 h-6"/> Información de Empresa
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre de Empresa</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white font-medium">{{ $cliente->nombre_empresa ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($cliente->estado_cuenta === 'activo') bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400
                                    @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400
                                    @elseif($cliente->estado_cuenta === 'suspendido') bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400
                                    @else bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-300 @endif
                                ">{{ ucfirst($cliente->estado_cuenta ?? 'Sin estado') }}</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tipo de Empresa</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ ucfirst($cliente->tipo_empresa ?? '—') }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Industria</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ ucfirst($cliente->industria ?? '—') }}</p>
                            </div>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Descripción</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 min-h-[80px]">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->descripcion ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paso 2: Contacto --}}
            <div x-show="currentStep === 2" x-transition>
                <div class="bg-blue-600 dark:bg-blue-700 px-6 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-phone class="w-6 h-6"/> Información de Contacto
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Correo Electrónico</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                @if($cliente->correo)
                                    <a href="mailto:{{ $cliente->correo }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $cliente->correo }}</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Teléfono Principal</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                @if($cliente->telefono)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}" target="_blank" class="text-green-600 dark:text-green-400 hover:underline">{{ $cliente->telefono }}</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Teléfono Alternativo</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->telefono_alternativo ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">WhatsApp</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                @if($cliente->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" target="_blank" class="text-green-600 dark:text-green-400 hover:underline">{{ $cliente->whatsapp }}</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paso 3: Ubicación --}}
            <div x-show="currentStep === 3" x-transition>
                <div class="bg-purple-600 dark:bg-purple-700 px-6 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-map-pin class="w-6 h-6"/> Ubicación
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">País</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->pais ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado/Provincia</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->estado ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ciudad</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->ciudad ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dirección</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->direccion ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Código Postal</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->codigo_postal ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                    @if($cliente->direccion && $cliente->ciudad)
                        <div class="mt-4">
                            <a href="https://maps.google.com/?q={{ urlencode($cliente->direccion . ', ' . $cliente->ciudad) }}" target="_blank" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-all">
                                <x-heroicon-o-map class="w-5 h-5"/> Ver en Google Maps
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Paso 4: Sitio Web --}}
            <div x-show="currentStep === 4" x-transition>
                <div class="bg-amber-600 dark:bg-amber-700 px-6 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-globe-alt class="w-6 h-6"/> Sitio Web
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dominio</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white font-medium">{{ $cliente->nombre_sitio ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">URL</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                @if($cliente->url_sitio)
                                    <a href="{{ $cliente->url_sitio }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline truncate block">{{ $cliente->url_sitio }}</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Hosting</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-900 dark:text-white">{{ $cliente->hosting ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Expira</label>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                @if($cliente->dominio_expira)
                                    @php $diasExpira = $cliente->dominio_expira->diffInDays(now(), false) * -1; @endphp
                                    <span class="text-gray-900 dark:text-white">{{ $cliente->dominio_expira->format('d/m/Y') }}</span>
                                    <span class="ml-2 text-xs px-2 py-0.5 rounded {{ $diasExpira < 30 ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' }}">{{ $diasExpira }}d</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($cliente->url_sitio)
                        <div class="mt-4">
                            <a href="{{ $cliente->url_sitio }}" target="_blank" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium transition-all">
                                <x-heroicon-o-arrow-top-right-on-square class="w-5 h-5"/> Visitar Sitio
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Paso 5: Redes Sociales --}}
            <div x-show="currentStep === 5" x-transition>
                <div class="bg-indigo-600 dark:bg-indigo-700 px-6 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-share class="w-6 h-6"/> Redes Sociales
                    </h3>
                </div>
                <div class="p-6">
                    @if(count($redesSociales) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($redesSociales as $red)
                                <a href="{{ $red['url'] ?? '#' }}" target="_blank" 
                                   class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-400 dark:hover:border-indigo-500 transition-all">
                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center text-xl">
                                        {{ $iconosRedes[$red['red'] ?? ''] ?? '🌐' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($red['red'] ?? 'Red') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $red['url'] ?? '' }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-bold
                                        @if($red['activo'] ?? false) bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400
                                        @else bg-gray-200 text-gray-500 dark:bg-gray-600 dark:text-gray-400 @endif
                                    ">{{ ($red['activo'] ?? false) ? '✓' : '✗' }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <x-heroicon-o-share class="w-12 h-12 mx-auto mb-3"/>
                            <p>No hay redes sociales configuradas</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Paso 6: Notas --}}
            <div x-show="currentStep === 6" x-transition>
                <div class="bg-rose-600 dark:bg-rose-700 px-6 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                        <x-heroicon-o-document-text class="w-6 h-6"/> Notas
                    </h3>
                </div>
                <div class="p-6">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 min-h-[150px]">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $cliente->notas ?? 'Sin notas' }}</p>
                    </div>
                </div>
            </div>

            {{-- Navegación Next/Back --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                <button 
                    x-show="currentStep > 1"
                    @click="currentStep--"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-medium transition-all"
                >
                    <x-heroicon-o-arrow-left class="w-4 h-4"/> Anterior
                </button>
                <div x-show="currentStep === 1"></div>
                
                <button 
                    x-show="currentStep < totalSteps"
                    @click="currentStep++"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-all"
                >
                    Siguiente <x-heroicon-o-arrow-right class="w-4 h-4"/>
                </button>
                <div x-show="currentStep === totalSteps"></div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
