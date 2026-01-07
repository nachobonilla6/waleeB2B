<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Gastos</title>
    <meta name="description" content="Gestión de Gastos">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        walee: {
                            50: '#FBF7EE',
                            100: '#F5ECD6',
                            200: '#EBD9AD',
                            300: '#E0C684',
                            400: '#D59F3B',
                            500: '#C78F2E',
                            600: '#A67524',
                            700: '#7F5A1C',
                            800: '#594013',
                            900: '#33250B',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
        
        html:not(.dark) body {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .min-h-screen {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .bg-white {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html:not(.dark) .bg-slate-50 {
            background-color: rgb(245, 243, 255) !important;
        }
        
        html.dark,
        html.dark body,
        html.dark body.bg-slate-50,
        .dark body,
        .dark body.bg-slate-50,
        html.dark #html-root,
        .dark #html-root {
            background-color: rgb(15, 23, 42) !important;
        }
        
        .dark .bg-white,
        .dark .bg-white\/50,
        html.dark .bg-white,
        html.dark .bg-white\/50 {
            background-color: rgb(30, 41, 59) !important;
        }
        
        .dark .bg-slate-50,
        .dark body.bg-slate-50,
        html.dark .bg-slate-50,
        html.dark body.bg-slate-50 {
            background-color: rgb(15, 23, 42) !important;
        }
        
        /* SweetAlert Dark Mode Support */
        .dark-mode-swal {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        
        .dark-mode-swal-title {
            color: #f1f5f9 !important;
        }
        
        .dark-mode-swal-html {
            color: #f1f5f9 !important;
        }
        
        .light-mode-swal {
            background-color: #ffffff !important;
            color: #0f172a !important;
        }
        
        .light-mode-swal-title {
            color: #0f172a !important;
        }
        
        .light-mode-swal-html {
            color: #0f172a !important;
        }
        
        html.dark .swal2-popup input,
        html.dark .swal2-popup textarea,
        html.dark .swal2-popup select {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            border-color: #334155 !important;
        }
        
        html.dark .swal2-popup {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        
        html.dark .swal2-title {
            color: #f1f5f9 !important;
        }
        
        html.dark .swal2-html-container {
            color: #f1f5f9 !important;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        $gastos = \App\Models\Gasto::orderBy('proxima_fecha_pago', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalGastos = $gastos->sum('total');
        $gastosPendientes = $gastos->where('pagado', false)->sum('total');
        $gastosPagados = $gastos->where('pagado', true)->sum('total');
        $gastosMensuales = $gastos->where('tipo', 'mensual')->sum('total');
        $gastosAnuales = $gastos->where('tipo', 'anual')->sum('total');
        $gastosProximos = $gastos->where('pagado', false)
            ->where('proxima_fecha_pago', '>=', now())
            ->where('proxima_fecha_pago', '<=', now()->addDays(30))
            ->count();
        
        // Gastos del mes actual
        $totalGastosEsteMes = \App\Models\Gasto::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
    @endphp
    
    <div class="min-h-screen relative overflow-hidden">
        <div class="relative max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            @include('partials.walee-navbar')
            
            <div class="mt-6 sm:mt-8 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Gastos</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Gestiona tus gastos recurrentes</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <a 
                            href="{{ route('walee.facturas') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors shadow-sm"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="hidden sm:inline">Volver</span>
                        </a>
                        <button 
                            onclick="openCreateGastoModal()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">Nuevo Gasto</span>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Formulario de Agregar Gasto (Izquierda en pantallas grandes) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-md sticky top-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Agregar Gasto</h3>
                            <form id="gastoForm" class="space-y-4">
                                @csrf
                                <input type="hidden" id="gastoId" name="gasto_id">
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre</label>
                                    <input 
                                        type="text" 
                                        id="gastoNombre" 
                                        name="nombre" 
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        placeholder="Ej: Hosting, Dominio, etc."
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción</label>
                                    <textarea 
                                        id="gastoDescripcion" 
                                        name="descripcion" 
                                        rows="3"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"
                                        placeholder="Descripción del gasto..."
                                    ></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Link</label>
                                    <input 
                                        type="url" 
                                        id="gastoLink" 
                                        name="link" 
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        placeholder="https://..."
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Monto (USD)</label>
                                    <input 
                                        type="number" 
                                        id="gastoMonto" 
                                        name="total" 
                                        step="0.01"
                                        min="0"
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        placeholder="0.00 USD"
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo</label>
                                    <select 
                                        id="gastoTipo" 
                                        name="tipo" 
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    >
                                        <option value="mensual">Mensual</option>
                                        <option value="anual">Anual</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Próxima Fecha de Pago</label>
                                    <input 
                                        type="date" 
                                        id="gastoProximaFecha" 
                                        name="proxima_fecha_pago" 
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    >
                                </div>
                                
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            id="gastoPagado" 
                                            name="pagado" 
                                            class="w-4 h-4 text-orange-500 border-slate-300 dark:border-slate-600 rounded focus:ring-orange-500"
                                        >
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Marcar como pagado</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-center gap-3 pt-2">
                                    <button 
                                        type="button" 
                                        onclick="resetGastoForm()"
                                        class="flex-1 px-4 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors"
                                    >
                                        Limpiar
                                    </button>
                                    <button 
                                        type="submit"
                                        class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors"
                                    >
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Lista de Gastos y Stats (Derecha en pantallas grandes) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Total Este Mes</p>
                                <p class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">${{ number_format($totalGastosEsteMes, 2, '.', ',') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">₡{{ number_format($totalGastosEsteMes * $tasaCambio, 2, '.', ',') }}</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Pendientes</p>
                                <p class="text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400">${{ number_format($gastosPendientes, 2, '.', ',') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">₡{{ number_format($gastosPendientes * $tasaCambio, 2, '.', ',') }}</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Pagados</p>
                                <p class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($gastosPagados, 2, '.', ',') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">₡{{ number_format($gastosPagados * $tasaCambio, 2, '.', ',') }}</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Próximos 30 días</p>
                                <p class="text-2xl sm:text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $gastosProximos }}</p>
                            </div>
                        </div>
                        
                        <!-- Lista de Gastos -->
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-md">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Lista de Gastos</h3>
                            
                            @if($gastos->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-slate-600 dark:text-slate-400 text-lg mb-2">No tienes gastos aún</p>
                                    <p class="text-slate-500 dark:text-slate-500 text-sm">Agrega tu primer gasto para comenzar</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($gastos as $gasto)
                                        @php
                                            $estaVencido = false;
                                            $venceraPronto = false;
                                            if ($gasto->proxima_fecha_pago && !$gasto->pagado) {
                                                $estaVencido = $gasto->proxima_fecha_pago->isPast();
                                                if (!$estaVencido) {
                                                    $diasRestantes = now()->diffInDays($gasto->proxima_fecha_pago, false);
                                                    $venceraPronto = $diasRestantes <= 10 && $diasRestantes >= 0;
                                                }
                                            }
                                        @endphp
                                        <div 
                                            class="gasto-item rounded-lg p-4 border transition-all {{ $gasto->pagado ? 'bg-slate-100 dark:bg-slate-800/70 border-slate-300 dark:border-slate-600 opacity-75' : ($estaVencido ? 'border-red-600 dark:border-red-500 border-2 bg-red-50/50 dark:bg-red-900/10' : ($venceraPronto ? 'border-amber-500 dark:border-amber-500 border-2 bg-amber-50/50 dark:bg-amber-900/10' : 'bg-slate-50 dark:bg-slate-700/50 border-slate-200 dark:border-slate-600 hover:border-orange-400 dark:hover:border-orange-500/30')) }}"
                                            data-gasto-id="{{ $gasto->id }}"
                                        >
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                        <h4 class="font-semibold {{ $gasto->pagado ? 'text-slate-500 dark:text-slate-400' : ($estaVencido ? 'text-red-900 dark:text-red-300' : ($venceraPronto ? 'text-amber-900 dark:text-amber-300' : 'text-slate-900 dark:text-white')) }}">{{ $gasto->nombre }}</h4>
                                                        <span class="px-2 py-0.5 text-xs font-medium rounded-md 
                                                            {{ $gasto->tipo === 'mensual' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' }}">
                                                            {{ ucfirst($gasto->tipo) }}
                                                        </span>
                                                        @if($gasto->pagado)
                                                            <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                                                Pagado
                                                            </span>
                                                        @elseif($estaVencido)
                                                            <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-red-500 text-white dark:bg-red-600 dark:text-white shadow-md animate-pulse">
                                                                Vencido
                                                            </span>
                                                        @elseif($venceraPronto)
                                                            <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-amber-500 text-white dark:bg-amber-600 dark:text-white shadow-md">
                                                                Vencerá Pronto
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($gasto->descripcion)
                                                        <p class="text-sm {{ $gasto->pagado ? 'text-slate-500 dark:text-slate-500' : 'text-slate-600 dark:text-slate-400' }} mb-2">{{ $gasto->descripcion }}</p>
                                                    @endif
                                                    
                                                    <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                                                        <span class="font-semibold {{ $gasto->pagado ? 'text-slate-500 dark:text-slate-400' : ($estaVencido ? 'text-red-700 dark:text-red-400' : ($venceraPronto ? 'text-amber-700 dark:text-amber-400' : 'text-slate-900 dark:text-white')) }}">
                                                            ${{ number_format($gasto->total, 2, '.', ',') }}
                                                            <span class="text-slate-500 dark:text-slate-500 ml-1">(₡{{ number_format($gasto->total * $tasaCambio, 2, '.', ',') }})</span>
                                                        </span>
                                                        @if($gasto->proxima_fecha_pago)
                                                            <span class="{{ $gasto->pagado ? 'text-slate-500 dark:text-slate-500' : ($estaVencido ? 'text-red-700 dark:text-red-400 font-bold' : ($venceraPronto ? 'text-amber-700 dark:text-amber-400 font-semibold' : '')) }}">
                                                                Próximo pago: {{ $gasto->proxima_fecha_pago->format('d/m/Y') }}
                                                                @if($estaVencido)
                                                                    ({{ $gasto->proxima_fecha_pago->diffForHumans() }})
                                                                @elseif($venceraPronto)
                                                                    ({{ $gasto->proxima_fecha_pago->diffForHumans() }})
                                                                @endif
                                                            </span>
                                                        @endif
                                                        @if($gasto->link)
                                                            <a href="{{ $gasto->link }}" target="_blank" class="text-orange-600 dark:text-orange-400 hover:underline flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                                </svg>
                                                                Link
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    <button 
                                                        onclick="openEditGastoModal({{ $gasto->id }})"
                                                        class="p-2 rounded-md hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors"
                                                        title="Editar"
                                                    >
                                                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button 
                                                        onclick="deleteGasto({{ $gasto->id }})"
                                                        class="p-2 rounded-md hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors"
                                                        title="Eliminar"
                                                    >
                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @include('partials.walee-world-map-clocks')
        </div>
    </div>
    
    @include('partials.walee-support-button')
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        function getSwalTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#0f172a',
                borderColor: isDark ? '#334155' : '#e2e8f0'
            };
        }
        
        function openCreateGastoModal() {
            resetGastoForm();
            document.getElementById('gastoForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        function resetGastoForm() {
            document.getElementById('gastoForm').reset();
            document.getElementById('gastoId').value = '';
            document.getElementById('gastoProximaFecha').value = '';
        }
        
        function openEditGastoModal(gastoId) {
            fetch(`/walee-gastos/${gastoId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar el gasto');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.gasto) {
                    document.getElementById('gastoId').value = data.gasto.id;
                    document.getElementById('gastoNombre').value = data.gasto.nombre || '';
                    document.getElementById('gastoDescripcion').value = data.gasto.descripcion || '';
                    document.getElementById('gastoLink').value = data.gasto.link || '';
                    document.getElementById('gastoMonto').value = data.gasto.total || '';
                    document.getElementById('gastoTipo').value = data.gasto.tipo || 'mensual';
                    document.getElementById('gastoProximaFecha').value = data.gasto.proxima_fecha_pago || '';
                    document.getElementById('gastoPagado').checked = data.gasto.pagado || false;
                    
                    document.getElementById('gastoForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo cargar el gasto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar el gasto', 'error');
            });
        }
        
        function deleteGasto(gastoId) {
            Swal.fire({
                title: '¿Eliminar gasto?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: document.documentElement.classList.contains('dark') ? 'dark-mode-swal' : 'light-mode-swal'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/walee-gastos/${gastoId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminado', 'El gasto ha sido eliminado', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo eliminar el gasto', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo eliminar el gasto', 'error');
                    });
                }
            });
        }
        
        document.getElementById('gastoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const gastoId = document.getElementById('gastoId').value;
            const url = gastoId ? `/walee-gastos/${gastoId}` : '/walee-gastos';
            const method = gastoId ? 'PUT' : 'POST';
            
            const formData = {
                nombre: document.getElementById('gastoNombre').value,
                descripcion: document.getElementById('gastoDescripcion').value,
                link: document.getElementById('gastoLink').value,
                total: document.getElementById('gastoMonto').value,
                tipo: document.getElementById('gastoTipo').value,
                proxima_fecha_pago: document.getElementById('gastoProximaFecha').value,
                pagado: document.getElementById('gastoPagado').checked
            };
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', gastoId ? 'Gasto actualizado' : 'Gasto creado', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo guardar el gasto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo guardar el gasto', 'error');
            });
        });
    </script>
</body>
</html>

