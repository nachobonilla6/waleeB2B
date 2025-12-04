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
                :href="\App\Filament\Resources\ClienteResource::getUrl('edit', ['record' => $cliente])"
                tag="a"
                color="gray"
                icon="heroicon-o-pencil"
            >
                Editar
            </x-filament::button>
            <x-filament::button
                wire:click="mountAction('cotizacion')"
                color="gray"
                icon="heroicon-o-document-text"
            >
                Cotización
            </x-filament::button>
            <x-filament::button
                wire:click="mountAction('factura')"
                color="gray"
                icon="heroicon-o-banknotes"
            >
                Factura
            </x-filament::button>
            <x-filament::button
                :href="\App\Filament\Resources\ClienteResource::getUrl('create')"
                tag="a"
                color="gray"
                icon="heroicon-o-plus"
            >
                Nuevo
            </x-filament::button>
        </div>
    </div>

    {{-- Wizard --}}
    <div x-data="{ currentStep: 1 }">
        {{-- Steps Navigation --}}
        <nav class="fi-wi-header mb-6">
            <ol class="fi-wi-header-steps flex items-center justify-center gap-x-2">
                @php
                    $steps = [
                        ['icon' => 'heroicon-o-building-office', 'label' => 'Empresa'],
                        ['icon' => 'heroicon-o-phone', 'label' => 'Contacto'],
                        ['icon' => 'heroicon-o-map-pin', 'label' => 'Ubicación'],
                        ['icon' => 'heroicon-o-globe-alt', 'label' => 'Sitio Web'],
                        ['icon' => 'heroicon-o-share', 'label' => 'Redes'],
                        ['icon' => 'heroicon-o-document-text', 'label' => 'Notas'],
                        ['icon' => 'heroicon-o-document-currency-dollar', 'label' => 'Contabilidad'],
                    ];
                @endphp
                
                @foreach($steps as $index => $step)
                    <li class="fi-wi-header-step flex items-center gap-x-2 shrink-0">
                        <button 
                            type="button"
                            @click="currentStep = {{ $index + 1 }}"
                            title="{{ $step['label'] }}"
                            class="fi-wi-header-step-btn flex items-center justify-center w-10 h-10 rounded-full transition-all"
                            x-bind:class="currentStep === {{ $index + 1 }} 
                                ? 'bg-primary-500 text-white shadow-lg scale-110' 
                                : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 dark:bg-white/10 dark:text-gray-400 dark:hover:bg-white/20 dark:hover:text-gray-200'"
                        >
                            <x-dynamic-component :component="$step['icon']" class="h-5 w-5"/>
                        </button>
                        
                        @if($index < count($steps) - 1)
                            <div class="h-0.5 w-6 bg-gray-200 dark:bg-white/10 transition-colors"
                                 x-bind:class="currentStep > {{ $index + 1 }} ? 'bg-primary-500' : ''"></div>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>

        {{-- Step Content --}}
        <div class="fi-wi-step rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            {{-- Paso 1: Empresa --}}
            <div x-show="currentStep === 1" x-cloak>
                <div class="fi-section-header border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-building-office class="h-5 w-5 text-gray-400"/> Información de Empresa
                    </h3>
                </div>
                <div class="fi-section-content p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-filament::input.wrapper label="Nombre de Empresa">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->nombre_empresa ?? '—' }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Estado">
                            <div class="fi-input-wrp-input px-3 py-2">{{ ucfirst($cliente->estado_cuenta ?? '—') }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Tipo de Empresa">
                            <div class="fi-input-wrp-input px-3 py-2">{{ ucfirst($cliente->tipo_empresa ?? '—') }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Industria">
                            <div class="fi-input-wrp-input px-3 py-2">{{ ucfirst($cliente->industria ?? '—') }}</div>
                        </x-filament::input.wrapper>
                        <div class="md:col-span-2">
                            <x-filament::input.wrapper label="Descripción">
                                <div class="fi-input-wrp-input px-3 py-2 min-h-[60px]">{{ $cliente->descripcion ?? '—' }}</div>
                            </x-filament::input.wrapper>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paso 2: Contacto --}}
            <div x-show="currentStep === 2" x-cloak>
                <div class="fi-section-header border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-phone class="h-5 w-5 text-gray-400"/> Información de Contacto
                    </h3>
                </div>
                <div class="fi-section-content p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-filament::input.wrapper label="Email">
                            <div class="fi-input-wrp-input px-3 py-2">
                                @if($cliente->correo)
                                    <a href="mailto:{{ $cliente->correo }}" class="text-primary-600 hover:underline dark:text-primary-400">{{ $cliente->correo }}</a>
                                @else —
                                @endif
                            </div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Teléfono">
                            <div class="fi-input-wrp-input px-3 py-2">
                                @if($cliente->telefono)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}" target="_blank" class="text-primary-600 hover:underline dark:text-primary-400">{{ $cliente->telefono }}</a>
                                @else —
                                @endif
                            </div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Tel. Alternativo">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->telefono_alternativo ?? '—' }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="WhatsApp">
                            <div class="fi-input-wrp-input px-3 py-2">
                                @if($cliente->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" target="_blank" class="text-primary-600 hover:underline dark:text-primary-400">{{ $cliente->whatsapp }}</a>
                                @else —
                                @endif
                            </div>
                        </x-filament::input.wrapper>
                    </div>
                </div>
            </div>

            {{-- Paso 3: Ubicación --}}
            <div x-show="currentStep === 3" x-cloak>
                <div class="fi-section-header border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-map-pin class="h-5 w-5 text-gray-400"/> Ubicación
                    </h3>
                </div>
                <div class="fi-section-content p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-filament::input.wrapper label="País">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->pais ?? '—' }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Estado">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->estado ?? '—' }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Ciudad">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->ciudad ?? '—' }}</div>
                        </x-filament::input.wrapper>
                        <div class="md:col-span-2">
                            <x-filament::input.wrapper label="Dirección">
                                <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->direccion ?? '—' }}</div>
                            </x-filament::input.wrapper>
                        </div>
                        <x-filament::input.wrapper label="Código Postal">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->codigo_postal ?? '—' }}</div>
                        </x-filament::input.wrapper>
                    </div>
                    @if($cliente->direccion && $cliente->ciudad)
                        <div class="mt-4">
                            <x-filament::button
                                :href="'https://maps.google.com/?q=' . urlencode($cliente->direccion . ', ' . $cliente->ciudad)"
                                tag="a"
                                target="_blank"
                                color="gray"
                                icon="heroicon-o-map"
                            >
                                Ver en Maps
                            </x-filament::button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Paso 4: Sitio Web --}}
            <div x-show="currentStep === 4" x-cloak>
                <div class="fi-section-header border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-globe-alt class="h-5 w-5 text-gray-400"/> Sitio Web
                    </h3>
                </div>
                <div class="fi-section-content p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-filament::input.wrapper label="Dominio">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->nombre_sitio ?? '—' }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="URL">
                            <div class="fi-input-wrp-input px-3 py-2">
                                @if($cliente->url_sitio)
                                    <a href="{{ $cliente->url_sitio }}" target="_blank" class="text-primary-600 hover:underline dark:text-primary-400">{{ $cliente->url_sitio }}</a>
                                @else —
                                @endif
                            </div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Hosting">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->hosting ?? '—' }}</div>
                        </x-filament::input.wrapper>
                        <x-filament::input.wrapper label="Expira">
                            <div class="fi-input-wrp-input px-3 py-2">{{ $cliente->dominio_expira?->format('d/m/Y') ?? '—' }}</div>
                        </x-filament::input.wrapper>
                    </div>
                    @if($cliente->url_sitio)
                        <div class="mt-4">
                            <x-filament::button
                                :href="$cliente->url_sitio"
                                tag="a"
                                target="_blank"
                                color="gray"
                                icon="heroicon-o-arrow-top-right-on-square"
                            >
                                Visitar Sitio
                            </x-filament::button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Paso 5: Redes --}}
            <div x-show="currentStep === 5" x-cloak>
                <div class="fi-section-header border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-share class="h-5 w-5 text-gray-400"/> Redes Sociales
                    </h3>
                </div>
                <div class="fi-section-content p-6">
                    @if(count($redesSociales) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($redesSociales as $red)
                                <a href="{{ $red['url'] ?? '#' }}" target="_blank" 
                                   class="flex items-center gap-3 p-3 rounded-lg ring-1 ring-gray-950/5 dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <span class="text-2xl">{{ $iconosRedes[$red['red'] ?? ''] ?? '🌐' }}</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-950 dark:text-white">{{ ucfirst($red['red'] ?? 'Red') }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $red['url'] ?? '' }}</p>
                                    </div>
                                    <x-filament::badge :color="($red['activo'] ?? false) ? 'success' : 'gray'">
                                        {{ ($red['activo'] ?? false) ? 'Activo' : 'Inactivo' }}
                                    </x-filament::badge>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <x-heroicon-o-share class="w-12 h-12 mx-auto mb-2"/>
                            <p>Sin redes sociales</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Paso 6: Notas --}}
            <div x-show="currentStep === 6" x-cloak>
                <div class="fi-section-header border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-document-text class="h-5 w-5 text-gray-400"/> Notas
                    </h3>
                </div>
                <div class="fi-section-content p-6">
                    <div class="rounded-lg ring-1 ring-gray-950/5 dark:ring-white/10 p-4 min-h-[120px]">
                        <p class="text-gray-950 dark:text-white whitespace-pre-wrap">{{ $cliente->notas ?? 'Sin notas' }}</p>
                    </div>
                </div>
            </div>

            {{-- Paso 7: Contabilidad --}}
            <div x-show="currentStep === 7" x-cloak>
                <div class="fi-section-header border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <h3 class="fi-section-header-heading text-base font-semibold text-gray-950 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-document-currency-dollar class="h-5 w-5 text-gray-400"/> Contabilidad
                    </h3>
                </div>
                <div class="fi-section-content p-6 space-y-8">
                    {{-- Resumen --}}
                    @php
                        $facturas = $cliente->facturas ?? collect();
                        $totalFacturado = $facturas->sum('total');
                        $facturasPendientes = $facturas->where('estado', 'pendiente');
                        $facturasPagadas = $facturas->where('estado', 'pagada');
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-4 ring-1 ring-gray-950/5 dark:ring-white/10">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Facturado</p>
                            <p class="text-2xl font-bold text-gray-950 dark:text-white">${{ number_format($totalFacturado, 2) }}</p>
                        </div>
                        <div class="rounded-lg bg-green-50 dark:bg-green-500/10 p-4 ring-1 ring-green-500/20">
                            <p class="text-sm text-green-600 dark:text-green-400">Pagadas</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $facturasPagadas->count() }}</p>
                        </div>
                        <div class="rounded-lg bg-amber-50 dark:bg-amber-500/10 p-4 ring-1 ring-amber-500/20">
                            <p class="text-sm text-amber-600 dark:text-amber-400">Pendientes</p>
                            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $facturasPendientes->count() }}</p>
                        </div>
                    </div>

                    {{-- Facturas --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-950 dark:text-white mb-4 flex items-center gap-2">
                            <x-heroicon-o-document-currency-dollar class="h-5 w-5 text-gray-400"/>
                            Facturas
                        </h4>
                        @if($facturas->count() > 0)
                            <div class="overflow-x-auto rounded-lg ring-1 ring-gray-950/5 dark:ring-white/10">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-white/5">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400"># Factura</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Fecha</th>
                                            <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Concepto</th>
                                            <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Total</th>
                                            <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                        @foreach($facturas as $factura)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                                <td class="px-4 py-3 font-medium text-gray-950 dark:text-white">{{ $factura->numero_factura }}</td>
                                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $factura->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                                                <td class="px-4 py-3 text-gray-950 dark:text-white">{{ Str::limit($factura->concepto, 40) }}</td>
                                                <td class="px-4 py-3 text-right font-medium text-gray-950 dark:text-white">${{ number_format($factura->total, 2) }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <x-filament::badge :color="match($factura->estado) {
                                                        'pagada' => 'success',
                                                        'pendiente' => 'warning',
                                                        'vencida' => 'danger',
                                                        'cancelada' => 'gray',
                                                        default => 'gray'
                                                    }">
                                                        {{ ucfirst($factura->estado ?? 'N/A') }}
                                                    </x-filament::badge>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400 rounded-lg ring-1 ring-gray-950/5 dark:ring-white/10">
                                <x-heroicon-o-document-currency-dollar class="w-12 h-12 mx-auto mb-2"/>
                                <p>Sin facturas registradas</p>
                            </div>
                        @endif
                    </div>

                    {{-- Cotizaciones (usando fecha_cotizacion del cliente) --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-950 dark:text-white mb-4 flex items-center gap-2">
                            <x-heroicon-o-document-text class="h-5 w-5 text-gray-400"/>
                            Última Cotización
                        </h4>
                        <div class="rounded-lg ring-1 ring-gray-950/5 dark:ring-white/10 p-4">
                            @if($cliente->fecha_cotizacion)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Fecha de última cotización</p>
                                        <p class="text-lg font-medium text-gray-950 dark:text-white">{{ $cliente->fecha_cotizacion->format('d/m/Y') }}</p>
                                    </div>
                                    <x-filament::badge color="info">Enviada</x-filament::badge>
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-400">
                                    <p>Sin cotizaciones enviadas</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones rápidas --}}
                    <div class="flex gap-3">
                        <x-filament::button
                            wire:click="mountAction('cotizacion')"
                            color="gray"
                            icon="heroicon-o-document-plus"
                        >
                            Nueva Cotización
                        </x-filament::button>
                        <x-filament::button
                            wire:click="mountAction('factura')"
                            color="gray"
                            icon="heroicon-o-document-currency-dollar"
                        >
                            Nueva Factura
                        </x-filament::button>
                    </div>
                </div>
            </div>

            {{-- Footer Navigation --}}
            <div class="fi-wi-footer flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-white/10">
                <div>
                    <x-filament::button
                        x-show="currentStep > 1"
                        @click="currentStep--"
                        color="gray"
                        icon="heroicon-o-arrow-left"
                    >
                        Anterior
                    </x-filament::button>
                </div>
                <div>
                    <x-filament::button
                        x-show="currentStep < 7"
                        @click="currentStep++"
                        color="gray"
                        icon="heroicon-o-arrow-right"
                        icon-position="after"
                    >
                        Siguiente
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
