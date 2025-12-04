<x-filament-panels::page>
    @php
        $cliente = $this->record;
        $redesSociales = $cliente->redes_sociales ?? [];
        $iconosRedes = [
            'facebook' => 'fab fa-facebook',
            'instagram' => 'fab fa-instagram',
            'tiktok' => 'fab fa-tiktok',
            'twitter' => 'fab fa-x-twitter',
            'linkedin' => 'fab fa-linkedin',
            'youtube' => 'fab fa-youtube',
            'pinterest' => 'fab fa-pinterest',
        ];
        $coloresRedes = [
            'facebook' => 'bg-blue-500',
            'instagram' => 'bg-gradient-to-r from-purple-500 via-pink-500 to-orange-500',
            'tiktok' => 'bg-black',
            'twitter' => 'bg-gray-900',
            'linkedin' => 'bg-blue-700',
            'youtube' => 'bg-red-600',
            'pinterest' => 'bg-red-500',
        ];
    @endphp

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                        <p class="text-white/70 text-xs font-medium uppercase tracking-wider">Perfil del Cliente</p>
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

                    {{-- Editar --}}
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('edit', ['record' => $cliente]) }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-400/80 hover:bg-emerald-400 text-white rounded-lg text-sm font-medium transition-all duration-200">
                        <x-heroicon-o-pencil class="w-4 h-4"/>
                        <span class="hidden sm:inline">Editar</span>
                    </a>

                    {{-- Cotización --}}
                    <button 
                        type="button"
                        wire:click="mountAction('cotizacion')"
                        class="inline-flex items-center gap-2 px-3 py-2 bg-amber-400/80 hover:bg-amber-400 text-white rounded-lg text-sm font-medium transition-all duration-200">
                        <x-heroicon-o-document-text class="w-4 h-4"/>
                        <span class="hidden sm:inline">Cotización</span>
                    </button>

                    {{-- Factura --}}
                    <button 
                        type="button"
                        wire:click="mountAction('factura')"
                        class="inline-flex items-center gap-2 px-3 py-2 bg-blue-400/80 hover:bg-blue-400 text-white rounded-lg text-sm font-medium transition-all duration-200">
                        <x-heroicon-o-banknotes class="w-4 h-4"/>
                        <span class="hidden sm:inline">Factura</span>
                    </button>

                    {{-- Ver Posts --}}
                    <a href="{{ \App\Filament\Resources\VelaSportPostResource::getUrl('index') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-purple-400/80 hover:bg-purple-400 text-white rounded-lg text-sm font-medium transition-all duration-200">
                        <x-heroicon-o-newspaper class="w-4 h-4"/>
                        <span class="hidden sm:inline">Posts</span>
                    </a>

                    {{-- Nuevo Cliente --}}
                    <a href="{{ \App\Filament\Resources\ClienteResource::getUrl('create') }}" 
                       class="inline-flex items-center gap-2 px-3 py-2 bg-white/90 hover:bg-white text-emerald-700 rounded-lg text-sm font-medium transition-all duration-200 shadow-lg">
                        <x-heroicon-o-plus class="w-4 h-4"/>
                        <span class="hidden sm:inline">Nuevo</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Estadísticas rápidas --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ count($redesSociales) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Redes Sociales</div>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-share-alt text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold {{ $cliente->url_sitio ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                            {{ $cliente->url_sitio ? '✓' : '—' }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sitio Web</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-globe text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 truncate max-w-[100px]">
                            {{ $cliente->hosting ?? '—' }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Hosting</div>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-server text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-lg border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        @php
                            $diasExpira = $cliente->dominio_expira ? $cliente->dominio_expira->diffInDays(now(), false) * -1 : null;
                        @endphp
                        <div class="text-3xl font-bold {{ $diasExpira !== null && $diasExpira < 30 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $diasExpira !== null ? $diasExpira . 'd' : '—' }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Días Restantes</div>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-hourglass-half text-amber-600 dark:text-amber-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Contacto --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="bg-gradient-to-r from-emerald-500 to-green-500 px-5 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3">
                        <i class="fas fa-address-book text-lg"></i> Información de Contacto
                    </h3>
                </div>
                <div class="p-5 space-y-3">
                    @if($cliente->correo)
                        <a href="mailto:{{ $cliente->correo }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-200 group border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $cliente->correo }}</p>
                            </div>
                            <i class="fas fa-external-link-alt text-gray-400 group-hover:text-emerald-500 transition-colors"></i>
                        </a>
                    @endif
                    @if($cliente->telefono)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}" target="_blank" class="flex items-center gap-4 p-3 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 group border border-transparent hover:border-green-200 dark:hover:border-green-800">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp text-green-600 dark:text-green-400 text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">WhatsApp</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $cliente->telefono }}</p>
                            </div>
                            <i class="fas fa-external-link-alt text-gray-400 group-hover:text-green-500 transition-colors"></i>
                        </a>
                    @endif
                    @if($cliente->telefono_alternativo)
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $cliente->telefono_alternativo) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group border border-transparent hover:border-blue-200 dark:hover:border-blue-800">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-phone-alt text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Teléfono Alt.</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $cliente->telefono_alternativo }}</p>
                            </div>
                            <i class="fas fa-phone text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                        </a>
                    @endif
                    @if(!$cliente->correo && !$cliente->telefono && !$cliente->telefono_alternativo)
                        <div class="text-center py-6 text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm">Sin información de contacto</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Ubicación --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-5 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3">
                        <i class="fas fa-map-marker-alt text-lg"></i> Ubicación
                    </h3>
                </div>
                <div class="p-5">
                    @if($cliente->direccion || $cliente->ciudad || $cliente->pais)
                        <div class="space-y-4">
                            @if($cliente->direccion)
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-home text-gray-400 mt-1"></i>
                                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $cliente->direccion }}</p>
                                </div>
                            @endif
                            <div class="flex flex-wrap gap-2">
                                @if($cliente->ciudad)
                                    <span class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg text-sm font-medium">
                                        <i class="fas fa-city mr-1"></i> {{ $cliente->ciudad }}
                                    </span>
                                @endif
                                @if($cliente->estado)
                                    <span class="px-3 py-1.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-lg text-sm font-medium">
                                        {{ $cliente->estado }}
                                    </span>
                                @endif
                                @if($cliente->pais)
                                    <span class="px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg text-sm font-medium">
                                        <i class="fas fa-flag mr-1"></i> {{ $cliente->pais }}
                                    </span>
                                @endif
                                @if($cliente->codigo_postal)
                                    <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium">
                                        CP: {{ $cliente->codigo_postal }}
                                    </span>
                                @endif
                            </div>
                            @if($cliente->direccion && $cliente->ciudad)
                                <a href="https://maps.google.com/?q={{ urlencode($cliente->direccion . ', ' . $cliente->ciudad . ', ' . ($cliente->pais ?? '')) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm mt-2 px-4 py-2 rounded-xl bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all duration-200 font-medium">
                                    <i class="fas fa-map-marked-alt"></i> Ver en Google Maps
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-400">
                            <i class="fas fa-map text-3xl mb-2"></i>
                            <p class="text-sm">Sin información de ubicación</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sitio Web --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-5 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3">
                        <i class="fas fa-globe text-lg"></i> Sitio Web
                    </h3>
                </div>
                <div class="p-5">
                    @if($cliente->nombre_sitio || $cliente->url_sitio || $cliente->hosting)
                        <div class="space-y-4">
                            @if($cliente->nombre_sitio)
                                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Dominio</p>
                                    <p class="text-gray-900 dark:text-white font-semibold">{{ $cliente->nombre_sitio }}</p>
                                </div>
                            @endif
                            <div class="grid grid-cols-2 gap-3">
                                @if($cliente->hosting)
                                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                                        <p class="text-xs text-purple-600 dark:text-purple-400 mb-1"><i class="fas fa-server mr-1"></i> Hosting</p>
                                        <p class="text-gray-900 dark:text-white font-medium text-sm">{{ $cliente->hosting }}</p>
                                    </div>
                                @endif
                                @if($cliente->dominio_expira)
                                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                                        <p class="text-xs text-amber-600 dark:text-amber-400 mb-1"><i class="fas fa-calendar-times mr-1"></i> Expira</p>
                                        <p class="text-gray-900 dark:text-white font-medium text-sm">{{ $cliente->dominio_expira->format('d/m/Y') }}</p>
                                    </div>
                                @endif
                            </div>
                            @if($cliente->url_sitio)
                                <a href="{{ $cliente->url_sitio }}" target="_blank" class="flex items-center justify-center gap-2 w-full text-white text-sm px-4 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                                    <i class="fas fa-external-link-alt"></i> Visitar Sitio Web
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-400">
                            <i class="fas fa-globe text-3xl mb-2"></i>
                            <p class="text-sm">Sin sitio web configurado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Empresa --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3">
                        <i class="fas fa-building text-lg"></i> Información de Empresa
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl">
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 mb-1"><i class="fas fa-briefcase mr-1"></i> Tipo</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ ucfirst($cliente->tipo_empresa ?? '—') }}</p>
                        </div>
                        <div class="p-3 bg-violet-50 dark:bg-violet-900/20 rounded-xl">
                            <p class="text-xs text-violet-600 dark:text-violet-400 mb-1"><i class="fas fa-industry mr-1"></i> Industria</p>
                            <p class="text-gray-900 dark:text-white font-medium">{{ ucfirst($cliente->industria ?? '—') }}</p>
                        </div>
                    </div>
                    @if($cliente->descripcion)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2"><i class="fas fa-align-left mr-1"></i> Descripción</p>
                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $cliente->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Fechas importantes --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-4">
                    <h3 class="font-bold text-white flex items-center gap-3">
                        <i class="fas fa-calendar-alt text-lg"></i> Fechas Importantes
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/30 rounded-xl border border-blue-200 dark:border-blue-800">
                            <i class="fas fa-file-invoice text-blue-500 text-2xl mb-2"></i>
                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Cotización</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $cliente->fecha_cotizacion?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/30 rounded-xl border border-green-200 dark:border-green-800">
                            <i class="fas fa-rocket text-green-500 text-2xl mb-2"></i>
                            <p class="text-xs text-green-600 dark:text-green-400 font-medium">Creación</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $cliente->fecha_creacion?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-900/30 rounded-xl border border-purple-200 dark:border-purple-800">
                            <i class="fas fa-user-plus text-purple-500 text-2xl mb-2"></i>
                            <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Registro</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $cliente->fecha_registro?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-900/30 rounded-xl border border-amber-200 dark:border-amber-800">
                            <i class="fas fa-file-invoice-dollar text-amber-500 text-2xl mb-2"></i>
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">Factura</p>
                            <p class="font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $cliente->fecha_factura?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Redes Sociales --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-5 py-4">
                <h3 class="font-bold text-white flex items-center gap-3">
                    <i class="fas fa-hashtag text-lg"></i> Redes Sociales
                </h3>
            </div>
            <div class="p-5">
                @if(count($redesSociales) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($redesSociales as $red)
                            <a href="{{ $red['url'] ?? '#' }}" target="_blank" class="flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 hover:shadow-md">
                                <div class="w-12 h-12 {{ $coloresRedes[$red['red'] ?? ''] ?? 'bg-gray-500' }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                                    <i class="{{ $iconosRedes[$red['red'] ?? ''] ?? 'fas fa-globe' }} text-white text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($red['red'] ?? 'Red Social') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $red['url'] ?? '' }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($red['activo'] ?? false) bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 @endif
                                ">
                                    {{ ($red['activo'] ?? false) ? 'Activo' : 'Inactivo' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-share-alt text-4xl mb-3"></i>
                        <p class="text-sm">No hay redes sociales configuradas</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Notas --}}
        @if($cliente->notas)
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-2xl p-6 border border-amber-200 dark:border-amber-800 shadow-lg">
                <h3 class="font-bold text-amber-800 dark:text-amber-300 flex items-center gap-3 mb-3">
                    <i class="fas fa-sticky-note text-xl"></i> Notas
                </h3>
                <p class="text-amber-700 dark:text-amber-400 leading-relaxed">{{ $cliente->notas }}</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
