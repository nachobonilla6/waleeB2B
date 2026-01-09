<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee B2B - Suppliers Dashboard and Logistics</title>
    <meta name="description" content="Walee B2B - Suppliers Dashboard and Logistics">
    <meta name="theme-color" content="#D59F3B">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .stat-card {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(213, 159, 59, 0.3);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(213, 159, 59, 0.5);
        }
        
        /* Estilo para backdrop opaco del modal */
        .swal2-backdrop-show {
            background-color: rgba(0, 0, 0, 0.75) !important;
            z-index: 99999 !important;
        }
        
        .swal2-container {
            backdrop-filter: blur(4px);
            z-index: 99999 !important;
        }
        
        .swal2-popup {
            z-index: 99999 !important;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        use App\Models\Client;
        use App\Models\Cliente;
        use App\Models\PublicidadEvento;
        use App\Models\Factura;
        use App\Models\ProductoSuper;
        use Carbon\Carbon;
        
        // Estadísticas generales (solo clientes con is_active = true, independiente del estado)
        $totalClientes = Client::where('is_active', true)->count();
        $clientesPending = Client::where('estado', 'pending')->count();
        $clientesPropuestaEnviada = Client::where('estado', 'propuesta_enviada')->count();
        $clientesActivos = Client::where('is_active', true)->count();
        
        // Clientes nuevos (solo con is_active = true)
        $clientesHoy = Client::where('is_active', true)
            ->whereDate('created_at', today())
            ->count();
        $clientesEsteMes = Client::where('is_active', true)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Clientes recibidos este mes
        $clientesReceivedEsteMes = Client::where('estado', 'received')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Clientes de esta semana (solo con is_active = true)
        $inicioSemana = now()->startOfWeek();
        $finSemana = now()->endOfWeek();
        
        // Estadísticas de Orders/Receipts (Facturas)
        $totalOrdersReceipts = Factura::count();
        $ordersReceiptsEsteMes = Factura::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $ordersReceiptsEstaSemana = Factura::whereBetween('created_at', [$inicioSemana, $finSemana])
            ->count();
        $ordersReceiptsHoy = Factura::whereDate('created_at', today())
            ->count();
        $clientesEstaSemana = Client::where('is_active', true)
            ->whereBetween('created_at', [$inicioSemana, $finSemana])
            ->count();
        
        // Clientes de este año (solo con is_active = true)
        $clientesEsteAno = Client::where('is_active', true)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Clientes recientes (últimos 5, solo con is_active = true)
        $clientesRecientes = Client::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Clientes en proceso recientes (últimos 5)
        $clientesEnProcesoRecientes = Client::where('estado', 'pending')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Todos los suppliers de la tabla suppliers (sin filtrar por is_active)
        $todosLosSuppliers = Client::orderBy('name', 'asc')
            ->get();
        
        // Formatear para mantener compatibilidad con el HTML existente
        $clientesConPublicaciones = $todosLosSuppliers->map(function($supplier) {
            return [
                'client' => $supplier,
                'total_publicaciones' => 0,
                'programadas' => 0,
            ];
        })->toArray();
        
        // Análisis ABC de Proveedores (Pareto)
        // Agrupar facturas por cliente y calcular totales
        $facturasPorCliente = Factura::selectRaw('cliente_id, SUM(total) as total_compras, COUNT(*) as num_facturas')
            ->whereNotNull('cliente_id')
            ->groupBy('cliente_id')
            ->orderBy('total_compras', 'desc')
            ->get();
        
        $totalComprasGlobal = $facturasPorCliente->sum('total_compras');
        $abcSuppliers = [];
        $abcData = [];
        $abcLabels = [];
        $acumulado = 0;
        $suppliers80Percent = [];
        $suppliers80PercentCount = 0;
        $suppliers80PercentTotal = 0;
        
        foreach ($facturasPorCliente as $item) {
            $cliente = Cliente::find($item->cliente_id);
            if (!$cliente) continue;
            
            $porcentaje = $totalComprasGlobal > 0 ? ($item->total_compras / $totalComprasGlobal) * 100 : 0;
            $acumulado += $porcentaje;
            
            $nombre = $cliente->nombre_empresa ?? 'Sin nombre';
            if (strlen($nombre) > 20) {
                $nombre = substr($nombre, 0, 17) . '...';
            }
            
            // Clasificar en ABC
            $categoria = 'C';
            if ($acumulado <= 80) {
                $categoria = 'A';
                $suppliers80Percent[] = [
                    'nombre' => $cliente->nombre_empresa ?? 'Sin nombre',
                    'total' => $item->total_compras,
                    'porcentaje' => $porcentaje,
                    'num_facturas' => $item->num_facturas
                ];
                $suppliers80PercentCount++;
                $suppliers80PercentTotal += $item->total_compras;
            } elseif ($acumulado <= 95) {
                $categoria = 'B';
            }
            
            $abcSuppliers[] = [
                'cliente_id' => $item->cliente_id,
                'nombre' => $nombre,
                'total' => $item->total_compras,
                'porcentaje' => $porcentaje,
                'acumulado' => $acumulado,
                'categoria' => $categoria,
                'num_facturas' => $item->num_facturas
            ];
            
            // Limitar a los primeros 10 para el gráfico
            if (count($abcLabels) < 10) {
                $abcLabels[] = $nombre;
                $abcData[] = $item->total_compras;
            }
        }
        
        // Calcular porcentaje que representan los proveedores del 80%
        $porcentaje80Percent = $totalComprasGlobal > 0 ? ($suppliers80PercentTotal / $totalComprasGlobal) * 100 : 0;
        
        // Clientes en proceso (pending y received) - Todos los tiempos
        $clientesEnProceso = Client::whereIn('estado', ['pending', 'received'])->count();
        $clientesPending = Client::where('estado', 'pending')->count();
        $clientesReceived = Client::where('estado', 'received')->count();
        // $totalClientes ya está definido arriba con solo activos
        $porcentajeClientes = $totalClientes > 0 ? (($clientesEnProceso / $totalClientes) * 100) : 0;
        
        // Datos para gráfico de barras: Clientes en proceso por día (últimos 7 días)
        $clientesEnProcesoPorDia = [];
        $clientesPendingPorDia = [];
        $clientesReceivedPorDia = [];
        $diasLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $fechaStr = $fecha->format('Y-m-d');
            $diasLabels[] = $fecha->format('d/m');
            
            // Clientes en proceso creados ese día
            $clientesEnProcesoPorDia[] = Client::whereIn('estado', ['pending', 'received'])
                ->whereDate('created_at', $fechaStr)
                ->count();
            
            // Clientes pending creados ese día
            $clientesPendingPorDia[] = Client::where('estado', 'pending')
                ->whereDate('created_at', $fechaStr)
                ->count();
            
            // Clientes received creados ese día
            $clientesReceivedPorDia[] = Client::where('estado', 'received')
                ->whereDate('created_at', $fechaStr)
                ->count();
        }
        
        // Estadísticas de Stock
        $productos = ProductoSuper::where('activo', true)->get();
        
        // REMAINING STOCK TO BE RECEIVED: Suma de (cantidad - stock) donde cantidad > stock
        $remainingStockToBeReceived = $productos->sum(function($producto) {
            $diferencia = $producto->cantidad - $producto->stock;
            return $diferencia > 0 ? $diferencia : 0;
        });
        
        // REMAINING OLD STOCK: Stock de productos con fecha_entrada mayor a 30 días
        $fechaLimiteViejo = now()->subDays(30);
        $remainingOldStock = $productos->filter(function($producto) use ($fechaLimiteViejo) {
            if (!$producto->fecha_entrada) {
                return false;
            }
            return Carbon::parse($producto->fecha_entrada)->lt($fechaLimiteViejo);
        })->sum('stock');
        
        // TOTAL NEW STOCK: Stock de productos con fecha_entrada en los últimos 30 días
        $fechaLimiteNuevo = now()->subDays(30);
        $totalNewStock = $productos->filter(function($producto) use ($fechaLimiteNuevo) {
            if (!$producto->fecha_entrada) {
                return false;
            }
            return Carbon::parse($producto->fecha_entrada)->gte($fechaLimiteNuevo);
        })->sum('stock');
        
        // Estadísticas de Conformidad
        // Global Conformity Rate: Porcentaje de facturas pagadas/completas
        $totalFacturas = Factura::count();
        $facturasPagadas = Factura::where('estado', 'pagada')->count();
        $facturasCompletas = Factura::where(function($query) {
            $query->where('estado', 'pagada')
                  ->orWhereRaw('monto_pagado >= total');
        })->count();
        $globalConformityRate = $totalFacturas > 0 ? round(($facturasCompletas / $totalFacturas) * 100, 1) : 0;
        
        // Delivery Conformity: Porcentaje de facturas entregadas a tiempo
        $facturasConFechaVencimiento = Factura::whereNotNull('fecha_vencimiento')
            ->whereNotNull('enviada_at')
            ->get();
        
        $facturasEntregadasATiempo = $facturasConFechaVencimiento->filter(function($factura) {
            $fechaVencimiento = Carbon::parse($factura->fecha_vencimiento);
            $fechaEnvio = Carbon::parse($factura->enviada_at);
            return $fechaEnvio->lte($fechaVencimiento);
        })->count();
        
        $totalFacturasConFecha = $facturasConFechaVencimiento->count();
        $deliveryConformity = $totalFacturasConFecha > 0 ? round(($facturasEntregadasATiempo / $totalFacturasConFecha) * 100, 1) : 0;
        
        // Datos para gráficos de conformidad (últimos 7 días)
        $conformityData = [];
        $deliveryConformityData = [];
        $conformityLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $fechaStr = $fecha->format('Y-m-d');
            $conformityLabels[] = $fecha->format('d/m');
            
            // Facturas del día
            $facturasDelDia = Factura::whereDate('created_at', $fechaStr)->get();
            $totalDelDia = $facturasDelDia->count();
            $completasDelDia = $facturasDelDia->filter(function($f) {
                return $f->estado == 'pagada' || ($f->monto_pagado >= $f->total);
            })->count();
            $conformityDelDia = $totalDelDia > 0 ? round(($completasDelDia / $totalDelDia) * 100, 1) : 0;
            $conformityData[] = $conformityDelDia;
            
            // Entregas del día
            $facturasEnviadasDelDia = Factura::whereDate('enviada_at', $fechaStr)
                ->whereNotNull('fecha_vencimiento')
                ->get();
            $totalEnviadasDelDia = $facturasEnviadasDelDia->count();
            $aTiempoDelDia = $facturasEnviadasDelDia->filter(function($f) {
                $fechaVencimiento = Carbon::parse($f->fecha_vencimiento);
                $fechaEnvio = Carbon::parse($f->enviada_at);
                return $fechaEnvio->lte($fechaVencimiento);
            })->count();
            $deliveryDelDia = $totalEnviadasDelDia > 0 ? round(($aTiempoDelDia / $totalEnviadasDelDia) * 100, 1) : 0;
            $deliveryConformityData[] = $deliveryDelDia;
        }
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-emerald-400/20 dark:bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Fixed Navbar -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-700/50">
            <div class="max-w-[90rem] mx-auto px-3 py-4 sm:px-4 lg:px-8">
                @php $pageTitle = 'Suppliers Dashboard and Logistics'; @endphp
                @include('partials.walee-navbar')
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8 pt-24 sm:pt-28">
            <!-- Header -->
            <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">
                        Suppliers Dashboard and Logistics
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 hidden sm:block">Total: {{ number_format($clientesActivos) }} active suppliers</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <a href="{{ route('walee.dashboard') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-walee-500 to-walee-600 hover:from-walee-600 hover:to-walee-700 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg shadow-walee-500/30 hover:shadow-xl hover:shadow-walee-500/40 transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="hidden sm:inline">General</span>
                        <span class="sm:hidden">General</span>
                    </a>
                    <a href="{{ route('walee.proveedores.activos') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Suppliers</span>
                    </a>
                    <button onclick="openCreateClientModal()" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Add Supplier</span>
                        <span class="sm:hidden">Agregar</span>
                    </button>
                    <a href="{{ route('walee.proveedores.activos') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl sm:rounded-2xl shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <span class="hidden sm:inline">View all suppliers</span>
                        <span class="sm:hidden">View all</span>
                    </a>
                    <a href="{{ route('walee.dashboard') }}" class="px-4 py-2.5 sm:px-5 sm:py-3 bg-gradient-to-r from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 hover:from-slate-300 hover:to-slate-400 dark:hover:from-slate-600 dark:hover:to-slate-700 text-slate-900 dark:text-white font-semibold rounded-xl sm:rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm transform hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 md:gap-6 lg:gap-3 mb-4 sm:mb-6 md:mb-8">
                <!-- Total Activos -->
                <a href="{{ route('walee.proveedores.activos') }}" class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Total Active Suppliers</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($clientesActivos) }}</p>
                    </div>
                </a>
                
                <!-- Total Orders Receipts -->
                <a href="{{ route('walee.facturas') }}" class="stat-card bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-blue-500/20 dark:bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Total Orders Receipts</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalOrdersReceipts) }}</p>
                    </div>
                </a>
                
                <!-- Orders Receipts This Month -->
                <a href="{{ route('walee.facturas') }}" class="stat-card bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-500/10 dark:to-violet-600/5 border border-violet-200 dark:border-violet-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-violet-500/20 dark:bg-violet-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Orders Receipts This Month</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($ordersReceiptsEsteMes) }}</p>
                    </div>
                </a>
                
                <!-- Orders Receipts This Week -->
                <a href="{{ route('walee.facturas') }}" class="stat-card bg-gradient-to-br from-walee-50 to-walee-100/50 dark:from-walee-500/10 dark:to-walee-600/5 border border-walee-200 dark:border-walee-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-walee-500/20 dark:bg-walee-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Orders Receipts This Week</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($ordersReceiptsEstaSemana) }}</p>
                    </div>
                </a>
                
                <!-- Orders Receipts Today -->
                <a href="{{ route('walee.facturas') }}" class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-emerald-500/20 dark:bg-emerald-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Orders Receipts Today</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($ordersReceiptsHoy) }}</p>
                    </div>
                </a>
                
                <!-- Remaining Stock To Be Received -->
                <a href="{{ route('walee.herramientas.inventory') }}" class="stat-card bg-gradient-to-br from-orange-50 to-orange-100/50 dark:from-orange-500/10 dark:to-orange-600/5 border border-orange-200 dark:border-orange-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-orange-500/20 dark:bg-orange-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Remaining Stock To Be Received</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($remainingStockToBeReceived) }}</p>
                    </div>
                </a>
                
                <!-- Remaining Old Stock -->
                <a href="{{ route('walee.herramientas.inventory') }}" class="stat-card bg-gradient-to-br from-red-50 to-red-100/50 dark:from-red-500/10 dark:to-red-600/5 border border-red-200 dark:border-red-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-red-500/20 dark:bg-red-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Remaining Old Stock</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($remainingOldStock) }}</p>
                    </div>
                </a>
                
                <!-- Total New Stock -->
                <a href="{{ route('walee.herramientas.inventory') }}" class="stat-card bg-gradient-to-br from-green-50 to-green-100/50 dark:from-green-500/10 dark:to-green-600/5 border border-green-200 dark:border-green-500/20 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 lg:p-4 shadow-sm dark:shadow-none hover:shadow-md dark:hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4 lg:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-10 lg:h-10 rounded-lg sm:rounded-xl bg-green-500/20 dark:bg-green-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-5 lg:h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-1 sm:mb-2">
                        <p class="text-xs sm:text-sm lg:text-xs text-slate-600 dark:text-slate-400 mb-0.5 sm:mb-1">Total New Stock</p>
                        <p class="text-xl sm:text-2xl md:text-3xl lg:text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalNewStock) }}</p>
                    </div>
                </a>
            </div>
            
            <!-- Charts and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- ABC Analysis Chart -->
                <div class="lg:col-span-1 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.5s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">ABC Supplier Analysis</h2>
                    <div class="relative w-full flex items-center justify-center" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <div class="relative" style="width: 200px; height: 200px;">
                            <canvas id="abcAnalysisChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">{{ round($porcentaje80Percent, 1) }}%</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Top Suppliers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ $suppliers80PercentCount }} suppliers represent {{ round($porcentaje80Percent, 1) }}% of purchases</p>
                    </div>
                </div>
                
                <!-- Global Conformity Rate Chart -->
                <div class="lg:col-span-1 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.6s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Global Conformity Rate</h2>
                    <div class="relative w-full flex items-center justify-center" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <div class="relative" style="width: 200px; height: 200px;">
                            <canvas id="globalConformityChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">{{ $globalConformityRate }}%</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Conformity</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ $facturasCompletas }} / {{ $totalFacturas }} invoices</p>
                    </div>
                </div>
                
                <!-- Delivery Conformity Chart -->
                <div class="lg:col-span-1 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.7s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Delivery Conformity</h2>
                    <div class="relative w-full flex items-center justify-center" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <div class="relative" style="width: 200px; height: 200px;">
                            <canvas id="deliveryConformityChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">{{ $deliveryConformity }}%</p>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">On Time</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ $facturasEntregadasATiempo }} / {{ $totalFacturasConFecha }} deliveries</p>
                    </div>
                </div>
            </div>
            
            <!-- Conformity Trends Chart -->
            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.8s">
                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white mb-2 sm:mb-3 md:mb-4">Conformity Trends - Last 7 Days</h2>
                    <div class="relative w-full" style="height: 200px; sm:height: 250px; md:height: 300px;">
                        <canvas id="conformityTrendsChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Categoria -->
            <div class="mb-4 sm:mb-6 md:mb-8">
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.7s">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <h2 class="text-sm sm:text-base md:text-lg font-semibold text-slate-900 dark:text-white">CATEGORIA</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl sm:text-3xl md:text-4xl font-bold text-violet-600 dark:text-violet-400">{{ count($clientesConPublicaciones) }}</span>
                            <span class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">suppliers</span>
                        </div>
                    </div>
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($clientesConPublicaciones as $item)
                            @php
                                $fotoPath = $item['client']->foto ?? null;
                                $fotoUrl = null;
                                if ($fotoPath) {
                                    if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                                        $fotoUrl = $fotoPath;
                                    } else {
                                        $filename = basename($fotoPath);
                                        $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                                    }
                                }
                                $telefono = $item['client']->telefono_1 ?? $item['client']->telefono_2 ?? null;
                                $telefonoLimpio = $telefono ? preg_replace('/[^0-9+]/', '', $telefono) : null;
                            @endphp
                            <div class="flex items-center gap-2 sm:gap-3 p-2 sm:p-3 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-violet-400 dark:hover:border-violet-500/30 hover:bg-violet-50/50 dark:hover:bg-violet-500/10 transition-all group">
                                <a href="{{ route('walee.supplier.detalle', $item['client']->id) }}" class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                                    @if($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="{{ $item['client']->name }}" class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg object-cover border-2 border-violet-500/30 flex-shrink-0 group-hover:scale-110 transition-transform">
                                    @else
                                        <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $item['client']->name ?: 'Supplier' }}" class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg object-cover border-2 border-violet-500/30 flex-shrink-0 group-hover:scale-110 transition-transform opacity-80">
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-xs sm:text-sm md:text-base text-slate-900 dark:text-white truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $item['client']->name ?: 'No name' }}</p>
                                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 truncate">{{ $item['client']->email ?: 'No email' }}</p>
                                        @if($telefono)
                                            <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">📞 {{ $telefono }}</p>
                                        @endif
                                        @if($item['client']->direccion || $item['client']->address)
                                            <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">📍 {{ $item['client']->direccion ?? $item['client']->address ?? '' }}</p>
                                        @endif
                                        @if($item['client']->idioma)
                                            <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">
                                                @php
                                                    $idiomas = [
                                                        'es' => '🇪🇸 Español',
                                                        'en' => '🇬🇧 English',
                                                        'fr' => '🇫🇷 Français',
                                                        'de' => '🇩🇪 Deutsch',
                                                        'it' => '🇮🇹 Italiano',
                                                        'pt' => '🇵🇹 Português'
                                                    ];
                                                    echo $idiomas[$item['client']->idioma] ?? strtoupper($item['client']->idioma);
                                                @endphp
                                            </p>
                                        @endif
                                    </div>
                                </a>
                                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                                    @if($telefonoLimpio)
                                        <button onclick="sendWhatsAppLink('{{ $telefonoLimpio }}', '{{ $item['client']->name }}', '{{ $item['client']->id }}')" 
                                                class="flex items-center justify-center p-2 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 hover:bg-emerald-200 dark:hover:bg-emerald-500/30 text-emerald-600 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-500/30 transition-all group shadow-sm hover:shadow-md"
                                                title="Enviar link por WhatsApp">
                                            <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-center py-3 sm:py-4">No suppliers found</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <section class="mb-4 sm:mb-6 md:mb-8 animate-fade-in-up">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Acciones Rápidas
                </h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('walee.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-walee-400/5 dark:hover:bg-walee-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors">Manager</span>
                    </a>
                    
                    <a href="{{ route('walee.proveedores.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Suppliers</span>
                    </a>
                    
                    <a href="{{ route('walee.facturas') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-violet-400/5 dark:hover:bg-violet-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Facturas</span>
                    </a>
                    
                    <a href="{{ route('walee.emails.dashboard') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-blue-400/5 dark:hover:bg-blue-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Emails</span>
                    </a>
                    
                    <a href="{{ route('walee.calendario') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-emerald-400/5 dark:hover:bg-emerald-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Calendario</span>
                    </a>
                    
                    <a href="{{ route('walee') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-walee-400/5 dark:hover:bg-walee-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors">Chat</span>
                    </a>
                    
                    <a href="{{ route('walee.facebook.clientes') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-blue-400/5 dark:hover:bg-blue-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Facebook</span>
                    </a>
                    
                    <a href="{{ route('walee.herramientas') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-violet-50 dark:bg-slate-900/50 border border-black dark:border-black hover:border-black dark:hover:border-black hover:bg-walee-400/5 dark:hover:bg-walee-400/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-walee-100 dark:bg-walee-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-walee-600 dark:text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-walee-600 dark:group-hover:text-walee-400 transition-colors">Herramientas</span>
                    </a>
                </div>
            </section>
            
            <!-- World Map with Clocks -->
            @include('partials.walee-world-map-clocks')
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    
    <script>
        // ABC Analysis Chart (Donut) - Pareto Analysis
        const ctxABCAnalysis = document.getElementById('abcAnalysisChart');
        if (ctxABCAnalysis) {
            const porcentaje80 = {{ round($porcentaje80Percent, 1) }};
            const porcentajeResto = 100 - porcentaje80;
            const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Colores para el gráfico
            const colors = [
                'rgb(16, 185, 129)', // Verde para los proveedores estratégicos
                isDarkMode ? 'rgba(148, 163, 184, 0.3)' : 'rgba(148, 163, 184, 0.5)' // Gris para el resto
            ];
            
            new Chart(ctxABCAnalysis, {
                type: 'doughnut',
                data: {
                    labels: ['Strategic Suppliers (80%)', 'Other Suppliers'],
                    datasets: [{
                        data: [porcentaje80, porcentajeResto],
                        backgroundColor: colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return label + ': ' + value.toFixed(1) + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Global Conformity Rate Chart (Donut)
        const ctxGlobalConformity = document.getElementById('globalConformityChart');
        if (ctxGlobalConformity) {
            const conformityRate = {{ $globalConformityRate }};
            const nonConformityRate = 100 - conformityRate;
            const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            new Chart(ctxGlobalConformity, {
                type: 'doughnut',
                data: {
                    labels: ['Conform', 'Non-conform'],
                    datasets: [{
                        data: [conformityRate, nonConformityRate],
                        backgroundColor: [
                            'rgb(16, 185, 129)',
                            isDarkMode ? 'rgba(239, 68, 68, 0.3)' : 'rgba(239, 68, 68, 0.5)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Delivery Conformity Chart (Donut)
        const ctxDeliveryConformity = document.getElementById('deliveryConformityChart');
        if (ctxDeliveryConformity) {
            const deliveryRate = {{ $deliveryConformity }};
            const nonDeliveryRate = 100 - deliveryRate;
            const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            new Chart(ctxDeliveryConformity, {
                type: 'doughnut',
                data: {
                    labels: ['On Time', 'Delayed'],
                    datasets: [{
                        data: [deliveryRate, nonDeliveryRate],
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            isDarkMode ? 'rgba(239, 68, 68, 0.3)' : 'rgba(239, 68, 68, 0.5)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Conformity Trends Chart (Line)
        const ctxConformityTrends = document.getElementById('conformityTrendsChart');
        if (ctxConformityTrends) {
            const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            new Chart(ctxConformityTrends, {
                type: 'line',
                data: {
                    labels: @json($conformityLabels),
                    datasets: [
                        {
                            label: 'Global Conformity Rate',
                            data: @json($conformityData),
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Delivery Conformity',
                            data: @json($deliveryConformityData),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: isDarkMode ? '#fff' : '#1e293b',
                                usePointStyle: true,
                                padding: 15
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            min: 0,
                            max: 100,
                            ticks: {
                                color: isDarkMode ? '#94a3b8' : '#64748b',
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                color: isDarkMode ? '#94a3b8' : '#64748b'
                            },
                            grid: {
                                color: isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }
        
        // Función para enviar link por WhatsApp
        async function sendWhatsAppLink(telefono, nombreProveedor, supplierId) {
            try {
                // Generar código de acceso aleatorio
                const response = await fetch('{{ route("walee.supplier.generate-access-code") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ supplier_id: supplierId })
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to generate access code',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
                
                const accessCode = data.access_code;
                const publicUrl = `{{ url('/walee-supplier') }}/${supplierId}/public`;
                const siteUrl = 'https://websolutions.work';
                const mensaje = `Este es el link para pedido: ${siteUrl}\n\nLink de perfil público: ${publicUrl}\n\nCódigo: ${accessCode}`;
                
                // Limpiar número de teléfono (remover espacios, guiones, etc.)
                const telefonoLimpio = telefono.replace(/[\s\-\(\)]/g, '');
                
                // Asegurar que tenga el código de país si no lo tiene
                let numeroFinal = telefonoLimpio;
                if (!numeroFinal.startsWith('+')) {
                    // Si no tiene código de país, asumir que es local (puedes ajustar según tu país)
                    // Por ahora, si no tiene +, agregar el código por defecto (ej: +506 para Costa Rica)
                    // Puedes cambiar esto según tus necesidades
                    if (numeroFinal.length <= 8) {
                        numeroFinal = '+506' + numeroFinal; // Ajusta el código de país según necesites
                    } else {
                        numeroFinal = '+' + numeroFinal;
                    }
                }
                
                // Codificar el mensaje para URL
                const mensajeCodificado = encodeURIComponent(mensaje);
                
                // Detectar si es móvil o desktop
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                
                let whatsappUrl;
                if (isMobile) {
                    // Para móviles usar wa.me
                    whatsappUrl = `https://wa.me/${numeroFinal}?text=${mensajeCodificado}`;
                } else {
                    // Para desktop usar web.whatsapp.com
                    whatsappUrl = `https://web.whatsapp.com/send?phone=${numeroFinal}&text=${mensajeCodificado}`;
                }
                
                // Abrir WhatsApp
                window.open(whatsappUrl, '_blank');
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        // Modal para crear cliente
        function openCreateClientModal() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            const html = `
                <form id="createClientForm" class="space-y-3 sm:space-y-3 md:space-y-4 text-left">
                    <div class="grid grid-cols-1 ${isDesktop ? 'md:grid-cols-2' : ''} gap-3 sm:gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Nombre *</label>
                            <input type="text" id="clientName" name="name" required
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Email</label>
                            <input type="email" id="clientEmail" name="email"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Teléfono 1</label>
                            <input type="tel" id="clientTelefono1" name="telefono_1"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Teléfono 2</label>
                            <input type="tel" id="clientTelefono2" name="telefono_2"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'md:grid-cols-2' : ''} gap-3 sm:gap-3 md:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Sitio Web</label>
                            <input type="url" id="clientWebsite" name="website"
                                   class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Estado</label>
                            <select id="clientEstado" name="estado"
                                    class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="pending">Pendiente</option>
                                <option value="propuesta_enviada">Propuesta Enviada</option>
                                <option value="activo">Activo</option>
                                <option value="accepted">Aceptado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Dirección</label>
                        <textarea id="clientAddress" name="address" rows="2"
                                  class="w-full px-3 py-2 sm:px-4 text-sm sm:text-base rounded-lg sm:rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                </form>
            `;
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '800px';
            } else if (isTablet) {
                modalWidth = '700px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            Swal.fire({
                title: 'Add Supplier',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                allowOutsideClick: false,
                allowEscapeKey: true,
                backdrop: true,
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    // Hacer el backdrop más opaco
                    const backdrop = document.querySelector('.swal2-backdrop-show');
                    if (backdrop) {
                        backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.75)';
                    }
                    // Focus en el primer campo
                    document.getElementById('clientName')?.focus();
                },
                preConfirm: () => {
                    const form = document.getElementById('createClientForm');
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData);
                    
                    // Validar nombre requerido
                    if (!data.name || data.name.trim() === '') {
                        Swal.showValidationMessage('Name is required');
                        return false;
                    }
                    
                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    createClient(result.value);
                }
            });
        }
        
        async function createClient(data) {
            try {
                const response = await fetch('{{ route("walee.proveedores.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Supplier created!',
                        text: 'The supplier has been added successfully',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    let errorMessage = 'Error creating supplier';
                    if (result.message) {
                        errorMessage = result.message;
                    } else if (result.errors) {
                        const errors = Object.values(result.errors).flat();
                        errorMessage = errors.join(', ');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta nuevamente.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        // Estilos para SweetAlert dark/light mode
        const style = document.createElement('style');
        style.textContent = `
            .dark-swal {
                background: #1e293b !important;
                color: #e2e8f0 !important;
            }
            .light-swal {
                background: #ffffff !important;
                color: #1e293b !important;
            }
            .dark-swal-title {
                color: #f1f5f9 !important;
            }
            .light-swal-title {
                color: #0f172a !important;
            }
            .dark-swal-html {
                color: #cbd5e1 !important;
            }
            .light-swal-html {
                color: #334155 !important;
            }
            @media (min-width: 1024px) {
                .swal2-popup {
                    max-height: 90vh !important;
                    overflow-y: auto !important;
                }
                .swal2-html-container {
                    max-height: calc(90vh - 150px) !important;
                    overflow-y: auto !important;
                }
            }
            
            @media (max-width: 640px) {
                .swal2-popup {
                    width: 95% !important;
                    margin: 0.5rem !important;
                    padding: 1rem !important;
                }
                .swal2-title {
                    font-size: 1.125rem !important;
                    margin-bottom: 0.75rem !important;
                }
                .swal2-html-container {
                    margin: 0.5rem 0 !important;
                    font-size: 0.875rem !important;
                }
                .swal2-confirm,
                .swal2-cancel {
                    font-size: 0.875rem !important;
                    padding: 0.5rem 1rem !important;
                }
                .swal2-actions {
                    margin-top: 1rem !important;
                    gap: 0.5rem !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    @include('partials.walee-support-button')
</body>
</html>

