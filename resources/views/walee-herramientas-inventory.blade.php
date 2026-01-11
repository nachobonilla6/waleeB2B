<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Inventory Management</title>
    <meta name="description" content="Inventory management - DLC DLV">
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
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .producto-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .producto-row:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .badge-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .badge-transition:hover {
            transform: scale(1.05);
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(213, 159, 59, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(213, 159, 59, 0.5); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    @php
        use App\Models\ProductoSuper;
        
        $productos = ProductoSuper::orderBy('nombre', 'asc')->get();
        
        // Total stock - suma de todos los stocks
        $totalStock = $productos->sum('stock');
        
        // Active stock - suma de stock de productos activos
        $activeStock = $productos->where('activo', true)->sum('stock');
        
        // Productos que expiran pronto (7 días o menos) - usar fecha_limite_venta o fecha_expiracion
        $productosExpiranPronto = $productos->filter(function($producto) {
            $fechaLimite = $producto->fecha_limite_venta ?? $producto->fecha_expiracion;
            if (!$fechaLimite || !$producto->activo) {
                return false;
            }
            $fechaLimiteCarbon = \Carbon\Carbon::parse($fechaLimite);
            if ($fechaLimiteCarbon->isPast()) {
                return false; // Ya expirados no cuentan
            }
            $diasRestantes = now()->diffInDays($fechaLimiteCarbon, false);
            return $diasRestantes <= 7 && $diasRestantes >= 0;
        })->count();
        
        // Productos expirados - usar fecha_limite_venta o fecha_expiracion
        $productosExpirados = $productos->filter(function($producto) {
            $fechaLimite = $producto->fecha_limite_venta ?? $producto->fecha_expiracion;
            if (!$fechaLimite || !$producto->activo) {
                return false;
            }
            return \Carbon\Carbon::parse($fechaLimite)->isPast();
        })->count();
    @endphp
    
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-blue-400/20 dark:bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Fixed Navbar -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-700/50">
            <div class="max-w-[90rem] mx-auto px-4 py-4 sm:px-6 lg:px-8">
                @php $pageTitle = 'Inventory'; @endphp
                @include('partials.walee-navbar')
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8 pt-28 sm:pt-32">
            @include('partials.walee-back-button')
            <!-- Header -->
            <div class="mb-6 sm:mb-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Inventory Management</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-400">DLC DLV</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('walee.herramientas') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors shadow-sm"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back</span>
                        </a>
                        <button 
                            onclick="openCreateProductoModal()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>New Product</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Subtle Stats Widgets -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 animate-fade-in-up" style="animation-delay: 0.12s;">
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Total Stock</div>
                    <div class="text-lg font-semibold text-slate-700 dark:text-slate-300">{{ number_format($totalStock) }}</div>
                </div>
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Active Stock</div>
                    <div class="text-lg font-semibold text-slate-700 dark:text-slate-300">{{ number_format($activeStock) }}</div>
                </div>
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Expire Soon</div>
                    <div class="text-lg font-semibold text-orange-600 dark:text-orange-400">{{ $productosExpiranPronto }}</div>
                </div>
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Expired</div>
                    <div class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $productosExpirados }}</div>
                </div>
            </div>
            
            <!-- Search and Filters -->
            <div class="bg-white dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700/50 shadow-sm mb-6 animate-fade-in-up" style="animation-delay: 0.15s;">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Search product..."
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    <select 
                        id="filterCategoria"
                        class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">All categories</option>
                        @foreach($productos->pluck('categoria')->filter()->unique()->sort() as $categoria)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                    </select>
                    <select 
                        id="filterStock"
                        class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="all">All stock</option>
                        <option value="low">Low stock (≤10)</option>
                        <option value="zero">Out of stock</option>
                        <option value="active">Active only</option>
                    </select>
                    <select 
                        id="filterExpire"
                        class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="all">All products</option>
                        <option value="expire-soon">Expire Soon (≤7 days)</option>
                    </select>
                </div>
            </div>
            
            <!-- Products Table -->
            <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-100 dark:bg-slate-900/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Brand</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Stock</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Entry Date</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">DLC</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">DLV</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Exit Date</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">QR Code</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">QR Code Conso</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Barcode</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productosTableBody" class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($productos as $producto)
                                @php
                                    // Verificar si expira pronto para el atributo data
                                    $expiraProntoData = false;
                                    $fechaLimite = $producto->fecha_limite_venta ?? $producto->fecha_expiracion;
                                    if ($fechaLimite && $producto->activo) {
                                        try {
                                            $fechaLimiteCarbon = \Carbon\Carbon::parse($fechaLimite);
                                            if (!$fechaLimiteCarbon->isPast()) {
                                                $diasRestantes = now()->diffInDays($fechaLimiteCarbon, false);
                                                $expiraProntoData = $diasRestantes <= 7 && $diasRestantes >= 0;
                                            }
                                        } catch (\Exception $e) {
                                            // Ignorar errores de parsing
                                        }
                                    }
                                @endphp
                                <tr class="producto-row hover:bg-slate-50 dark:hover:bg-slate-800/50" 
                                    data-nombre="{{ strtolower($producto->nombre) }}"
                                    data-categoria="{{ strtolower($producto->categoria ?? '') }}"
                                    data-stock="{{ $producto->stock }}"
                                    data-activo="{{ $producto->activo ? '1' : '0' }}"
                                    data-expira-pronto="{{ $expiraProntoData ? '1' : '0' }}"
                                >
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col gap-2">
                                            @if($producto->imagen)
                                                @php
                                                    $imagenPath = trim($producto->imagen);
                                                    
                                                    // Si ya es una URL completa, usarla
                                                    if (str_starts_with($imagenPath, 'http://') || str_starts_with($imagenPath, 'https://')) {
                                                        $imagenUrl = $imagenPath;
                                                    } else {
                                                        // El path almacenado puede ser "productos-super/imagen.jpg" o solo "imagen.jpg"
                                                        // Extraer solo el nombre del archivo
                                                        $filename = basename($imagenPath);
                                                        
                                                        // Verificar si el archivo existe físicamente
                                                        $fullPath = storage_path('app/public/productos-super/' . $filename);
                                                        $fileExists = file_exists($fullPath);
                                                        
                                                        // Si no existe con el nombre directo, intentar con el path completo
                                                        if (!$fileExists && strpos($imagenPath, 'productos-super/') !== false) {
                                                            // El path ya incluye el directorio, verificar directamente
                                                            $fullPathAlt = storage_path('app/public/' . $imagenPath);
                                                            if (file_exists($fullPathAlt)) {
                                                                $fileExists = true;
                                                            }
                                                        }
                                                        
                                                        // Construir URL - intentar primero con la ruta definida
                                                        // Si el path incluye el directorio completo, usar asset() directamente
                                                        if (strpos($imagenPath, 'productos-super/') === 0) {
                                                            // El path ya incluye el directorio, usar asset() directamente
                                                            $imagenUrl = asset('storage/' . $imagenPath);
                                                        } else {
                                                            // Usar la ruta definida con solo el nombre del archivo
                                                            $imagenUrl = route('storage.productos-super', ['filename' => $filename]);
                                                        }
                                                    }
                                                @endphp
                                                <img 
                                                    src="{{ $imagenUrl }}" 
                                                    alt="{{ $producto->nombre }}" 
                                                    class="w-16 h-16 object-cover rounded-lg border border-slate-300 dark:border-slate-600"
                                                    loading="lazy"
                                                    onerror="console.error('Error cargando imagen:', '{{ $producto->nombre }}', 'Path DB:', '{{ $imagenPath }}', 'Filename:', '{{ $filename ?? 'N/A' }}', 'URL:', this.src, 'File exists:', {{ $fileExists ?? false ? 'true' : 'false' }}); this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                >
                                                <div class="w-16 h-16 bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center hidden">
                                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-16 h-16 bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            @if($producto->brand)
                                                <div class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-1">{{ $producto->brand }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col gap-2">
                                            <span class="font-medium text-slate-900 dark:text-white">{{ $producto->nombre }}</span>
                                            <div class="flex flex-wrap items-center gap-2">
                                                @php
                                                    // Verificar si expira pronto (7 días o menos)
                                                    $expiraPronto = false;
                                                    $diasExpira = null;
                                                    $fechaLimite = $producto->fecha_limite_venta ?? $producto->fecha_expiracion;
                                                    if ($fechaLimite && $producto->activo) {
                                                        try {
                                                            $fechaLimiteCarbon = \Carbon\Carbon::parse($fechaLimite);
                                                            if (!$fechaLimiteCarbon->isPast()) {
                                                                $diasRestantes = now()->diffInDays($fechaLimiteCarbon, false);
                                                                $diasExpira = $diasRestantes;
                                                                $expiraPronto = $diasRestantes <= 7 && $diasRestantes >= 0;
                                                            }
                                                        } catch (\Exception $e) {
                                                            // Ignorar errores de parsing
                                                        }
                                                    }
                                                    
                                                    // Verificar si sale pronto (7 días o menos)
                                                    $salePronto = false;
                                                    $diasSale = null;
                                                    if ($producto->fecha_salida && $producto->activo) {
                                                        try {
                                                            $fechaSalidaCarbon = \Carbon\Carbon::parse($producto->fecha_salida);
                                                            if (!$fechaSalidaCarbon->isPast()) {
                                                                $diasRestantes = now()->diffInDays($fechaSalidaCarbon, false);
                                                                $diasSale = $diasRestantes;
                                                                $salePronto = $diasRestantes <= 7 && $diasRestantes >= 0;
                                                            }
                                                        } catch (\Exception $e) {
                                                            // Ignorar errores de parsing
                                                        }
                                                    }
                                                @endphp
                                                @if($producto->seccion)
                                                    <span class="badge-transition inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300 border border-violet-200 dark:border-violet-700/50">
                                                        {{ $producto->seccion }}
                                                    </span>
                                                @endif
                                                @if($expiraPronto)
                                                    <span class="badge-transition inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300 border border-orange-200 dark:border-orange-700/50 shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Expires Soon
                                                    </span>
                                                @endif
                                                @if($salePronto)
                                                    <span class="badge-transition inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-700/50 shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                        </svg>
                                                        Exits Soon
                                                        @if($diasSale !== null)
                                                            <span class="ml-1">({{ $diasSale }}d)</span>
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $producto->cantidad ?? 0 }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $producto->stock }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-slate-700 dark:text-slate-300">
                                            {{ $producto->fecha_entrada ? \Carbon\Carbon::parse($producto->fecha_entrada)->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-slate-700 dark:text-slate-300">
                                            {{ $producto->dlc ? \Carbon\Carbon::parse($producto->dlc)->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-slate-700 dark:text-slate-300">
                                            {{ $producto->fecha_limite_venta ? \Carbon\Carbon::parse($producto->fecha_limite_venta)->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-slate-700 dark:text-slate-300">
                                            {{ $producto->fecha_salida ? \Carbon\Carbon::parse($producto->fecha_salida)->format('d/m/Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($producto->foto_qr)
                                            @php
                                                $qrPath = trim($producto->foto_qr);
                                                if (str_starts_with($qrPath, 'http://') || str_starts_with($qrPath, 'https://')) {
                                                    $qrUrl = $qrPath;
                                                } else {
                                                    // Extraer solo el nombre del archivo
                                                    $filename = basename($qrPath);
                                                    // Usar la ruta definida para QR
                                                    $qrUrl = route('storage.productos-super.qr', ['filename' => $filename]);
                                                }
                                            @endphp
                                            <img 
                                                src="{{ $qrUrl }}" 
                                                alt="QR Code" 
                                                class="w-12 h-12 object-cover rounded border border-slate-300 dark:border-slate-600 mx-auto cursor-pointer"
                                                onclick="showQRModal('{{ $qrUrl }}')"
                                                onerror="this.style.display='none';"
                                            >
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($producto->foto_qr_super)
                                            @php
                                                $qrSuperPath = trim($producto->foto_qr_super);
                                                if (str_starts_with($qrSuperPath, 'http://') || str_starts_with($qrSuperPath, 'https://')) {
                                                    $qrSuperUrl = $qrSuperPath;
                                                } else {
                                                    // Extraer solo el nombre del archivo
                                                    $filename = basename($qrSuperPath);
                                                    // Usar la ruta definida para QR Super
                                                    $qrSuperUrl = route('storage.productos-super.qr-super', ['filename' => $filename]);
                                                }
                                            @endphp
                                            <img 
                                                src="{{ $qrSuperUrl }}" 
                                                alt="QR Code Conso" 
                                                class="w-12 h-12 object-cover rounded border-8 border-pink-300 dark:border-pink-600 mx-auto cursor-pointer"
                                                onclick="showQRModal('{{ $qrSuperUrl }}')"
                                                onerror="this.style.display='none';"
                                            >
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs font-mono text-slate-700 dark:text-slate-300">
                                            {{ $producto->codigo_barras ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <button 
                                                onclick="editProducto({{ $producto->id }})"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-300 hover:scale-110 hover:shadow-md"
                                                title="Edit"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="deleteProducto({{ $producto->id }})"
                                                class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-300 hover:scale-110 hover:shadow-md"
                                                title="Delete"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.walee-support-button')
    
    <!-- QR Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95" id="qrModalContent">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">QR Code</h3>
                <button onclick="closeQRModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <img id="qrModalImage" src="" alt="QR Code" class="w-full h-auto rounded-lg">
        </div>
    </div>
    
    <script>
        // Detect dark mode and configure SweetAlert
        function getSwalConfig() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                colorScheme: isDark ? 'dark' : 'light',
                customClass: {
                    popup: isDark ? 'dark:bg-slate-800 dark:text-white' : 'bg-white text-slate-900',
                    title: isDark ? 'dark:text-white' : 'text-slate-900',
                    htmlContainer: isDark ? 'dark:text-slate-300' : 'text-slate-700',
                    confirmButton: isDark ? 'dark:bg-blue-500 dark:hover:bg-blue-600' : 'bg-blue-500 hover:bg-blue-600',
                    cancelButton: isDark ? 'dark:bg-slate-600 dark:hover:bg-slate-700' : 'bg-slate-600 hover:bg-slate-700',
                    input: isDark ? 'dark:bg-slate-700 dark:text-white dark:border-slate-600' : 'bg-white text-slate-900 border-slate-300'
                }
            };
        }
        
        // Search and Filter
        document.getElementById('searchInput').addEventListener('input', filterProductos);
        document.getElementById('filterCategoria').addEventListener('change', filterProductos);
        document.getElementById('filterStock').addEventListener('change', filterProductos);
        document.getElementById('filterExpire').addEventListener('change', filterProductos);
        
        function filterProductos() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const categoria = document.getElementById('filterCategoria').value.toLowerCase();
            const stockFilter = document.getElementById('filterStock').value;
            const expireFilter = document.getElementById('filterExpire').value;
            const rows = document.querySelectorAll('.producto-row');
            
            rows.forEach(row => {
                const nombre = row.dataset.nombre;
                const rowCategoria = row.dataset.categoria;
                const stock = parseInt(row.dataset.stock);
                const activo = row.dataset.activo === '1';
                const expiraPronto = row.dataset.expiraPronto === '1';
                
                let show = true;
                
                if (search && !nombre.includes(search)) show = false;
                if (categoria && rowCategoria !== categoria) show = false;
                if (stockFilter === 'low' && (stock > 10 || stock === 0)) show = false;
                if (stockFilter === 'zero' && stock !== 0) show = false;
                if (stockFilter === 'active' && !activo) show = false;
                if (expireFilter === 'expire-soon' && !expiraPronto) show = false;
                
                row.style.display = show ? '' : 'none';
            });
        }
        
        // QR Modal
        function showQRModal(qrUrl) {
            document.getElementById('qrModalImage').src = qrUrl;
            document.getElementById('qrModal').classList.remove('hidden');
        }
        
        function closeQRModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }
        
        // Close modal on background click
        document.getElementById('qrModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQRModal();
            }
        });
        
        // Edit Producto - redirect to inventory edit page
        function editProducto(id) {
            window.location.href = `/walee-herramientas/inventory/producto/${id}/edit`;
        }
        
        // Create Producto - redirect to create page
        function openCreateProductoModal() {
            window.location.href = '{{ route("walee.herramientas.inventory.create") }}';
        }
        
        // Delete Producto
        async function deleteProducto(id) {
            const result = await Swal.fire({
                ...getSwalConfig(),
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const response = await fetch(`/walee-productos-super/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        ...getSwalConfig(),
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Product has been deleted.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        ...getSwalConfig(),
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting product: ' + (data.message || 'Unknown error')
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    ...getSwalConfig(),
                    icon: 'error',
                    title: 'Error',
                    text: 'Error deleting product'
                });
            }
        }
    </script>
</body>
</html>

