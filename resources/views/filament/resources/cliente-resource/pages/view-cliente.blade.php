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

    {{-- Header Sticky con info del cliente y acciones --}}
    <div class="sticky top-0 z-40 -mx-4 sm:-mx-6 lg:-mx-8 -mt-8 mb-6">
        <div class="bg-gradient-to-r from-emerald-600 via-green-500 to-teal-500 px-4 sm:px-6 lg:px-8 py-4 shadow-xl">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                {{-- Info del cliente --}}
                <div class="flex items-center gap-4">
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('index') }}" 
                       class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
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

                {{-- Botones de acción --}}
                <div class="flex items-center gap-2">
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('edit', ['record' => $cliente]) }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-pencil class="w-4 h-4"/>
                        <span class="hidden sm:inline">Editar</span>
                    </a>
                    <button type="button" wire:click="mountAction('cotizacion')"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-amber-400/80 hover:bg-amber-400 text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-document-text class="w-4 h-4"/>
                        <span class="hidden sm:inline">Cotización</span>
                    </button>
                    <button type="button" wire:click="mountAction('factura')"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-blue-400/80 hover:bg-blue-400 text-white rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-banknotes class="w-4 h-4"/>
                        <span class="hidden sm:inline">Factura</span>
                    </button>
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('create') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-white/90 hover:bg-white text-emerald-700 rounded-lg text-sm font-medium transition-all">
                        <x-heroicon-o-plus class="w-4 h-4"/>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Wizard de solo lectura --}}
    <div class="space-y-6">
        {{-- Indicador de pasos --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 overflow-x-auto">
            <div class="flex items-center justify-between min-w-max gap-4">
                @php
                    $steps = [
                        ['icon' => 'heroicon-o-building-office', 'label' => 'Empresa', 'id' => 'empresa'],
                        ['icon' => 'heroicon-o-phone', 'label' => 'Contacto', 'id' => 'contacto'],
                        ['icon' => 'heroicon-o-map-pin', 'label' => 'Ubicación', 'id' => 'ubicacion'],
                        ['icon' => 'heroicon-o-globe-alt', 'label' => 'Sitio Web', 'id' => 'sitio'],
                        ['icon' => 'heroicon-o-calendar', 'label' => 'Fechas', 'id' => 'fechas'],
                        ['icon' => 'heroicon-o-document-text', 'label' => 'Notas', 'id' => 'notas'],
                    ];
                @endphp
                @foreach($steps as $index => $step)
                    <a href="#{{ $step['id'] }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center group-hover:bg-emerald-200 dark:group-hover:bg-emerald-900/50 transition-colors">
                            <x-dynamic-component :component="$step['icon']" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"/>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors hidden md:inline">{{ $step['label'] }}</span>
                    </a>
                    @if($index < count($steps) - 1)
                        <div class="flex-1 h-0.5 bg-gray-200 dark:bg-gray-700 min-w-[20px]"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Paso 1: Empresa --}}
        <div id="empresa" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-4">
                <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                    <x-heroicon-o-building-office class="w-6 h-6"/> Paso 1: Información de Empresa
                </h3>
                <p class="text-white/70 text-sm mt-1">Datos básicos de la empresa</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre de Empresa</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white font-medium">{{ $cliente->nombre_empresa ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado del Cliente</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($cliente->estado_cuenta === 'activo') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($cliente->estado_cuenta === 'suspendido') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 @endif
                            ">
                                {{ ucfirst($cliente->estado_cuenta ?? 'Sin estado') }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo de Empresa</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($cliente->tipo_empresa ?? '—') }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Industria</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($cliente->industria ?? '—') }}</p>
                        </div>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descripción del Negocio</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 min-h-[80px]">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->descripcion ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Paso 2: Contacto --}}
        <div id="contacto" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-4">
                <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                    <x-heroicon-o-phone class="w-6 h-6"/> Paso 2: Información de Contacto
                </h3>
                <p class="text-white/70 text-sm mt-1">Datos de contacto del cliente</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Correo Electrónico</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-envelope class="w-5 h-5 text-gray-400"/>
                            @if($cliente->correo)
                                <a href="mailto:{{ $cliente->correo }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $cliente->correo }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teléfono Principal</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-phone class="w-5 h-5 text-gray-400"/>
                            @if($cliente->telefono)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}" target="_blank" class="text-green-600 dark:text-green-400 hover:underline flex items-center gap-2">
                                    {{ $cliente->telefono }}
                                    <span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded">WhatsApp</span>
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teléfono Alternativo</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-phone class="w-5 h-5 text-gray-400"/>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->telefono_alternativo ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">WhatsApp</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-chat-bubble-left class="w-5 h-5 text-gray-400"/>
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
        <div id="ubicacion" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4">
                <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                    <x-heroicon-o-map-pin class="w-6 h-6"/> Paso 3: Ubicación
                </h3>
                <p class="text-white/70 text-sm mt-1">Dirección física</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">País</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-flag class="w-5 h-5 text-gray-400"/>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->pais ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Provincia/Estado</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->estado ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ciudad</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->ciudad ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dirección Completa</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-home class="w-5 h-5 text-gray-400"/>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->direccion ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Código Postal</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-900 dark:text-white">{{ $cliente->codigo_postal ?? '—' }}</p>
                        </div>
                    </div>
                </div>
                @if($cliente->direccion && $cliente->ciudad)
                    <div class="mt-4">
                        <a href="https://maps.google.com/?q={{ urlencode($cliente->direccion . ', ' . $cliente->ciudad . ', ' . ($cliente->pais ?? '')) }}" target="_blank" class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 text-sm px-4 py-2 rounded-xl bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-all duration-200 font-medium">
                            <x-heroicon-o-map class="w-5 h-5"/> Ver en Google Maps
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Paso 4: Sitio Web --}}
        <div id="sitio" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                    <x-heroicon-o-globe-alt class="w-6 h-6"/> Paso 4: Sitio Web
                </h3>
                <p class="text-white/70 text-sm mt-1">Información del sitio web</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre del Dominio</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-globe-alt class="w-5 h-5 text-gray-400"/>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $cliente->nombre_sitio ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">URL del Sitio</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-link class="w-5 h-5 text-gray-400"/>
                            @if($cliente->url_sitio)
                                <a href="{{ $cliente->url_sitio }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline truncate">{{ $cliente->url_sitio }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Proveedor de Hosting</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-server class="w-5 h-5 text-gray-400"/>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->hosting ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha de Expiración</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center gap-3">
                            <x-heroicon-o-calendar class="w-5 h-5 text-gray-400"/>
                            @if($cliente->dominio_expira)
                                @php
                                    $diasExpira = $cliente->dominio_expira->diffInDays(now(), false) * -1;
                                @endphp
                                <p class="text-gray-900 dark:text-white">{{ $cliente->dominio_expira->format('d/m/Y') }}</p>
                                <span class="text-xs px-2 py-0.5 rounded {{ $diasExpira < 30 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                    {{ $diasExpira }} días
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($cliente->url_sitio)
                    <div class="mt-4">
                        <a href="{{ $cliente->url_sitio }}" target="_blank" class="inline-flex items-center gap-2 text-white text-sm px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 transition-all duration-200 font-medium shadow-lg">
                            <x-heroicon-o-arrow-top-right-on-square class="w-5 h-5"/> Visitar Sitio Web
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Paso 5: Fechas y Redes --}}
        <div id="fechas" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-violet-500 px-6 py-4">
                <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                    <x-heroicon-o-calendar class="w-6 h-6"/> Paso 5: Fechas y Redes Sociales
                </h3>
                <p class="text-white/70 text-sm mt-1">Fechas importantes y presencia en redes</p>
            </div>
            <div class="p-6 space-y-6">
                {{-- Fechas --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                        <x-heroicon-o-calendar-days class="w-5 h-5"/> Fechas Importantes
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium mb-1">Registro</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_registro?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
                            <p class="text-xs text-green-600 dark:text-green-400 font-medium mb-1">Creación</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_creacion?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                            <p class="text-xs text-purple-600 dark:text-purple-400 font-medium mb-1">Cotización</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_cotizacion?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="text-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mb-1">Factura</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_factura?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Redes Sociales --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                        <x-heroicon-o-share class="w-5 h-5"/> Redes Sociales
                    </h4>
                    @if(count($redesSociales) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($redesSociales as $red)
                                <a href="{{ $red['url'] ?? '#' }}" target="_blank" class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition-all duration-200 group">
                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                        {{ $iconosRedes[$red['red'] ?? ''] ?? '🌐' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($red['red'] ?? 'Red') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $red['url'] ?? '' }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($red['activo'] ?? false) bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @else bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 @endif
                                    ">
                                        {{ ($red['activo'] ?? false) ? '✓' : '✗' }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <x-heroicon-o-share class="w-10 h-10 text-gray-400 mx-auto mb-2"/>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No hay redes sociales configuradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Paso 6: Notas --}}
        <div id="notas" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 px-6 py-4">
                <h3 class="font-bold text-white flex items-center gap-3 text-lg">
                    <x-heroicon-o-document-text class="w-6 h-6"/> Paso 6: Notas
                </h3>
                <p class="text-white/70 text-sm mt-1">Notas y observaciones</p>
            </div>
            <div class="p-6">
                <div class="space-y-1">
                    <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notas del Cliente</label>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 min-h-[120px]">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $cliente->notas ?? 'Sin notas' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
