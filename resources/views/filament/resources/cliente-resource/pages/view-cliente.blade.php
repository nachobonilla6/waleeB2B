<x-filament-panels::page>
    @php
        $cliente = $this->record;
        $cliente->loadMissing(['notes.user']);
        $facturasFiltradas = $this->facturasFiltradas;
        $resumen = $this->resumenFacturas;
        
        // Obtener años únicos de las facturas (solo por correo)
        $clientEmail = $cliente->email ?? null;
        $anosQuery = \App\Models\Factura::query();
        if ($clientEmail) {
            $anosQuery->where('correo', $clientEmail);
        }
        $anos = $anosQuery->selectRaw('YEAR(fecha_emision) as ano')
            ->distinct()
            ->orderBy('ano', 'desc')
            ->pluck('ano')
            ->map(fn($ano) => (string)$ano)
            ->prepend('TODOS');
        
        // Obtener series únicas
        $seriesQuery = \App\Models\Factura::query();
        if ($clientEmail) {
            $seriesQuery->where('correo', $clientEmail);
        }
        $series = $seriesQuery->whereNotNull('serie')
            ->select('serie')
            ->distinct()
            ->orderBy('serie')
            ->pluck('serie')
            ->prepend('TODOS');
        
        $meses = collect([
            'TODOS' => 'TODOS',
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ]);
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
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cliente->name }}</h1>
            </div>
            @if($cliente->estado)
                <x-filament::badge :color="match($cliente->estado) {
                    'accepted' => 'success',
                    'pending' => 'warning', 
                    'rejected' => 'danger',
                    default => 'gray'
                }">
                    {{ match($cliente->estado) {
                        'accepted' => 'Activo',
                        'pending' => 'Pendiente',
                        'rejected' => 'Rechazado',
                        default => ucfirst($cliente->estado ?? '')
                    } }}
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
                Crear Cotización
            </x-filament::button>
            <x-filament::button
                wire:click="mountAction('factura')"
                color="gray"
                icon="heroicon-o-banknotes"
            >
                Crear Factura
            </x-filament::button>
        </div>
    </div>

    {{-- Tabs --}}
    <div>
        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200 dark:border-white/10">
            <nav class="-mb-px flex space-x-8">
                <button
                    wire:click="setActiveTab('facturas')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'facturas' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    <x-heroicon-o-banknotes class="inline-block h-5 w-5 mr-2" />
                    Facturas
                </button>
                <button
                    wire:click="setActiveTab('actividades')"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors {{ $activeTab === 'actividades' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}"
                >
                    <x-heroicon-o-clock class="inline-block h-5 w-5 mr-2" />
                    Registro de Actividades
                </button>
            </nav>
        </div>

        {{-- Tab Content: Facturas --}}
        @if($activeTab === 'facturas')
        <div>
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                {{-- Filtros --}}
                <div class="border-b border-gray-200 px-6 py-4 dark:border-white/10">
                    <div class="flex flex-wrap gap-3">
                        <select wire:model.live="filtroAno" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm">
                            <option value="">AÑO TODOS</option>
                            @foreach($anos as $ano)
                                <option value="{{ $ano === 'TODOS' ? '' : $ano }}">{{ $ano }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filtroTrimestre" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm">
                            <option value="">TRIMESTRE TODOS</option>
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>
                        <select wire:model.live="filtroMes" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm">
                            @foreach($meses as $key => $mes)
                                <option value="{{ $key === 'TODOS' ? '' : $key }}">{{ $mes }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filtroSerie" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-sm">
                            <option value="">SERIE TODOS</option>
                            @foreach($series as $serie)
                                <option value="{{ $serie === 'TODOS' ? '' : $serie }}">{{ $serie }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Resumen --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-lg bg-blue-50 dark:bg-blue-500/10 p-4 ring-1 ring-blue-500/20">
                            <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">TOTAL</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">₡{{ number_format($resumen['total'], 2, ',', ' ') }}</p>
                        </div>
                        <div class="rounded-lg bg-green-50 dark:bg-green-500/10 p-4 ring-1 ring-green-500/20">
                            <p class="text-sm text-green-600 dark:text-green-400 font-medium">PAGADO</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">₡{{ number_format($resumen['pagado'], 2, ',', ' ') }}</p>
                        </div>
                        <div class="rounded-lg bg-red-50 dark:bg-red-500/10 p-4 ring-1 ring-red-500/20">
                            <p class="text-sm text-red-600 dark:text-red-400 font-medium">PENDIENTE</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">₡{{ number_format($resumen['pendiente'], 2, ',', ' ') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Lista de Facturas --}}
                <div class="p-6">
                    @php
                        $facturasAgrupadas = $facturasFiltradas->groupBy(function($factura) {
                            return $factura->fecha_emision->format('Y');
                        });
                    @endphp
                    
                    @if($facturasFiltradas->count() > 0)
                        @foreach($facturasAgrupadas as $ano => $facturasAno)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 text-center">{{ $ano }}</h3>
                                @php
                                    $mesesEspanol = [
                                        1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                                        5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                                        9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
                                    ];
                                    $facturasPorMes = $facturasAno->groupBy(function($factura) {
                                        return $factura->fecha_emision->month;
                                    });
                                @endphp
                                @foreach($facturasPorMes->sortKeys()->reverse() as $mesNum => $facturasMes)
                                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3 text-center">{{ $mesesEspanol[$mesNum] ?? '' }}</h4>
                                    <div class="space-y-3">
                                        @foreach($facturasMes as $factura)
                                            <div class="flex items-center justify-between p-4 rounded-lg ring-1 ring-gray-950/5 dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors cursor-pointer"
                                                 onclick="window.location.href='{{ \App\Filament\Resources\FacturaResource::getUrl('view', ['record' => $factura]) }}'">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-1">
                                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $factura->numero_factura }}</span>
                                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $cliente->name }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        @if($factura->estado === 'vencida')
                                                            <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400">VENCIDA</span>
                                                        @endif
                                                        @if($factura->monto_pagado > 0 && $factura->monto_pagado < $factura->total)
                                                            <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400">PAGO PARCIAL</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-lg font-bold text-gray-900 dark:text-white">₡{{ number_format($factura->total, 2, ',', ' ') }}</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $factura->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <x-heroicon-o-banknotes class="w-12 h-12 mx-auto mb-2"/>
                            <p>No hay facturas registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Tab Content: Registro de Actividades --}}
        @if($activeTab === 'actividades')
        <div>
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="p-6">
                    {{ $this->table }}
                </div>
            </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>
