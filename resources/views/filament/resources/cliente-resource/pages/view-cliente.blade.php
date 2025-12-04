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

    <div class="space-y-6">
        {{-- Header con info principal --}}
        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-24 h-24 rounded-2xl bg-white/20 flex items-center justify-center text-4xl shadow-lg border-4 border-white/30">
                    🏢
                </div>
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold">{{ $cliente->nombre_empresa }}</h1>
                    <p class="text-blue-100 mt-1">{{ ucfirst($cliente->industria ?? 'Sin industria') }} · {{ ucfirst($cliente->tipo_empresa ?? 'Sin tipo') }}</p>
                    @if($cliente->descripcion)
                        <p class="text-blue-100/80 text-sm mt-2 max-w-2xl">{{ $cliente->descripcion }}</p>
                    @endif
                </div>
                <div class="flex flex-col items-center gap-2">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($cliente->estado_cuenta === 'activo') bg-green-500 
                        @elseif($cliente->estado_cuenta === 'pendiente') bg-yellow-500
                        @elseif($cliente->estado_cuenta === 'suspendido') bg-red-500
                        @else bg-gray-500 @endif
                    ">
                        @if($cliente->estado_cuenta === 'activo') ✓ Activo
                        @elseif($cliente->estado_cuenta === 'pendiente') ⏳ Pendiente
                        @elseif($cliente->estado_cuenta === 'suspendido') ✗ Suspendido
                        @else ⚫ {{ ucfirst($cliente->estado_cuenta ?? 'Sin estado') }} @endif
                    </span>
                    @if($cliente->fecha_registro)
                        <span class="text-xs text-blue-100">Desde {{ $cliente->fecha_registro->format('d/m/Y') }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Estadísticas rápidas --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ count($redesSociales) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Redes Sociales</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                    {{ $cliente->url_sitio ? '✓' : '✗' }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Sitio Web</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $cliente->hosting ?? '-' }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Hosting</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow border border-gray-200 dark:border-gray-700">
                <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">
                    @if($cliente->dominio_expira)
                        {{ $cliente->dominio_expira->diffInDays(now()) }}d
                    @else
                        -
                    @endif
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Días hasta expirar</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Contacto --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="text-lg">📞</span> Contacto
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    {{-- Email --}}
                    @if($cliente->correo)
                        <a href="mailto:{{ $cliente->correo }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                            <span class="text-gray-400 group-hover:scale-110 transition-transform">✉️</span>
                            <span class="text-blue-600 dark:text-blue-400 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-300">{{ $cliente->correo }}</span>
                        </a>
                    @endif
                    {{-- Teléfono con WhatsApp --}}
                    @if($cliente->telefono)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}" target="_blank" class="flex items-center gap-3 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 group">
                            <span class="text-green-500 group-hover:scale-110 transition-transform">📱</span>
                            <span class="text-green-600 dark:text-green-400 text-sm group-hover:text-green-700 dark:group-hover:text-green-300 flex items-center gap-2">
                                {{ $cliente->telefono }}
                                <span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-1.5 py-0.5 rounded">WhatsApp</span>
                            </span>
                        </a>
                    @endif
                    {{-- Teléfono alternativo --}}
                    @if($cliente->telefono_alternativo)
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $cliente->telefono_alternativo) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                            <span class="text-gray-400 group-hover:scale-110 transition-transform">📞</span>
                            <span class="text-blue-600 dark:text-blue-400 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-300 flex items-center gap-2">
                                {{ $cliente->telefono_alternativo }}
                                <span class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-1.5 py-0.5 rounded">Llamar</span>
                            </span>
                        </a>
                    @endif
                    {{-- WhatsApp directo --}}
                    @if($cliente->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" target="_blank" class="flex items-center gap-3 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 group">
                            <span class="text-green-500 group-hover:scale-110 transition-transform">💬</span>
                            <span class="text-green-600 dark:text-green-400 text-sm group-hover:text-green-700 dark:group-hover:text-green-300">WhatsApp: {{ $cliente->whatsapp }}</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Ubicación --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="text-lg">📍</span> Ubicación
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    @if($cliente->direccion)
                        <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $cliente->direccion }}</p>
                    @endif
                    @if($cliente->ciudad || $cliente->estado)
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $cliente->ciudad }}{{ $cliente->ciudad && $cliente->estado ? ', ' : '' }}{{ $cliente->estado }}</p>
                    @endif
                    @if($cliente->pais || $cliente->codigo_postal)
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $cliente->pais }}{{ $cliente->codigo_postal ? ' · C.P. ' . $cliente->codigo_postal : '' }}</p>
                    @endif
                    @if($cliente->direccion && $cliente->ciudad)
                        <a href="https://maps.google.com/?q={{ urlencode($cliente->direccion . ', ' . $cliente->ciudad) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm mt-3 px-3 py-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 group">
                            <span class="group-hover:scale-110 transition-transform">🗺️</span> Ver en mapa
                        </a>
                    @endif
                </div>
            </div>

            {{-- Sitio Web --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="text-lg">🌐</span> Sitio Web
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    @if($cliente->nombre_sitio)
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Dominio</span>
                            <p class="text-gray-700 dark:text-gray-300 font-medium">{{ $cliente->nombre_sitio }}</p>
                        </div>
                    @endif
                    @if($cliente->hosting)
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Hosting</span>
                            <p class="text-gray-700 dark:text-gray-300">{{ $cliente->hosting }}</p>
                        </div>
                    @endif
                    @if($cliente->dominio_expira)
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Expira</span>
                            <p class="text-gray-700 dark:text-gray-300">{{ $cliente->dominio_expira->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    @if($cliente->url_sitio)
                        <a href="{{ $cliente->url_sitio }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm px-3 py-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 group">
                            <span class="group-hover:scale-110 transition-transform">🔗</span> Visitar sitio
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Fechas importantes --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="text-lg">📅</span> Fechas Importantes
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Cotización</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_cotizacion?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Creación</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_creacion?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Registro</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_registro?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Factura</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $cliente->fecha_factura?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Redes Sociales --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="text-lg">📱</span> Redes Sociales
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    @forelse($redesSociales as $red)
                        <a href="{{ $red['url'] ?? '#' }}" target="_blank" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">{{ $iconosRedes[$red['red'] ?? ''] ?? '🌐' }}</span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($red['red'] ?? 'Red') }}</span>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($red['activo'] ?? false) bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @else bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 @endif
                            ">
                                {{ ($red['activo'] ?? false) ? 'Activo' : 'Inactivo' }}
                            </span>
                        </a>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No hay redes sociales configuradas</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Notas --}}
        @if($cliente->notas)
            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800">
                <h3 class="font-semibold text-amber-800 dark:text-amber-300 flex items-center gap-2 mb-2">
                    <span>📝</span> Notas
                </h3>
                <p class="text-amber-700 dark:text-amber-400 text-sm">{{ $cliente->notas }}</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>

