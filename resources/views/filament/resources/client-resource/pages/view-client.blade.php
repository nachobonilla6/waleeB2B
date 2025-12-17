<x-filament-panels::page>
    <div class="flex gap-6">
        <!-- Barra lateral izquierda -->
        <div class="w-64 flex-shrink-0">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 min-h-[600px]">
                <!-- Foto cuadrada -->
                <div class="w-32 h-32 mb-4 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center aspect-square overflow-hidden">
                    @if($this->record->foto ?? false)
                        <img src="{{ $this->record->foto }}" alt="{{ $this->record->name }}" class="w-full h-full object-cover rounded-lg">
                    @else
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpqzpN0OXHCje7AJYyuAOQwf3asqFGJYWpzg&s" alt="{{ $this->record->name ?? 'Cliente' }}" class="w-full h-full object-cover rounded-lg">
                    @endif
                </div>
                
                <!-- Nombre y detalles -->
                <div class="text-left">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $this->record->name ?? 'N/A' }}
                    </h2>
                    
                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-6">
                        @if($this->record->email)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:{{ $this->record->email }}" class="hover:text-primary-600 dark:hover:text-primary-400 truncate">
                                    {{ $this->record->email }}
                                </a>
                            </div>
                        @endif
                        
                        @if($this->record->telefono_1)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $this->record->telefono_1) }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary-600 dark:hover:text-primary-400">
                                    {{ $this->record->telefono_1 }}
                                </a>
                            </div>
                        @endif
                        
                        @if($this->record->telefono_2)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $this->record->telefono_2) }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary-600 dark:hover:text-primary-400">
                                    {{ $this->record->telefono_2 }}
                                </a>
                            </div>
                        @endif
                        
                        @if($this->record->website)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <a href="{{ str_starts_with($this->record->website, 'http') ? $this->record->website : 'https://' . $this->record->website }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="hover:text-primary-600 dark:hover:text-primary-400 truncate">
                                    {{ $this->record->website }}
                                </a>
                            </div>
                        @endif
                        
                        @if($this->record->address)
                            <div class="flex items-start gap-2 pt-2">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $this->record->address }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Sección Alerts -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Alerts</h3>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Account Status:</span>
                                <x-filament::badge color="success" class="font-bold">
                                    Activo
                                </x-filament::badge>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenido principal - Finanzas del cliente -->
        <div class="flex-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6">
                    @php
                        $email = $this->record->email ?? null;
                        $facturas = $email
                            ? \App\Models\Factura::where('correo', $email)->orderByDesc('fecha_emision')->get()
                            : collect();
                        $total = $facturas->sum('total');
                        $pagado = $facturas->where('estado', 'pagada')->sum('total');
                        $pendiente = $total - $pagado;
                    @endphp

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Finanzas
                    </h3>

                    {{-- Resumen tipo app de facturación --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="rounded-lg bg-blue-50 dark:bg-blue-500/10 p-4 ring-1 ring-blue-500/20">
                            <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 mb-1">TOTAL</p>
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                                ₡{{ number_format($total, 2, ',', ' ') }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-green-50 dark:bg-green-500/10 p-4 ring-1 ring-green-500/20">
                            <p class="text-xs font-semibold text-green-700 dark:text-green-300 mb-1">PAGADO</p>
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300">
                                ₡{{ number_format($pagado, 2, ',', ' ') }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-red-50 dark:bg-red-500/10 p-4 ring-1 ring-red-500/20">
                            <p class="text-xs font-semibold text-red-700 dark:text-red-300 mb-1">PENDIENTE</p>
                            <p class="text-2xl font-bold text-red-700 dark:text-red-300">
                                ₡{{ number_format($pendiente, 2, ',', ' ') }}
                            </p>
                        </div>
                    </div>

                    {{-- Lista de facturas inspirada en la app de facturación --}}
                    @if ($facturas->isEmpty())
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            No hay facturas relacionadas con este cliente todavía.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($facturas as $factura)
                                <div class="flex items-center justify-between px-4 py-3 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $factura->numero_factura }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $factura->fecha_emision?->format('d/m/Y') ?? '—' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            ₡{{ number_format($factura->total, 2, ',', ' ') }}
                                        </p>
                                        <x-filament::badge :color="match($factura->estado) {
                                            'pagada' => 'success',
                                            'pendiente' => 'warning',
                                            'vencida' => 'danger',
                                            default => 'gray',
                                        }" class="mt-1">
                                            {{ strtoupper($factura->estado) }}
                                        </x-filament::badge>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
