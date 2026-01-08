<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee B2B - Super Products</title>
    <meta name="description" content="Supermarket Products Management">
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
        $productos = \App\Models\ProductoSuper::orderBy('nombre', 'asc')->get();
        
        $totalProductos = $productos->count();
        $productosActivos = $productos->where('activo', true)->count();
        $productosInactivos = $productos->where('activo', false)->count();
        $valorTotalInventario = $productos->where('activo', true)->sum(function($p) {
            return $p->precio * $p->stock;
        });
        $categorias = $productos->pluck('categoria')->filter()->unique()->sort()->values();
        
        // Productos que vencen pronto (7 días o menos)
        $productosVencenPronto = $productos->filter(function($producto) {
            if (!$producto->fecha_expiracion || !$producto->activo) {
                return false;
            }
            $fechaExpiracion = \Carbon\Carbon::parse($producto->fecha_expiracion);
            if ($fechaExpiracion->isPast()) {
                return false; // Ya vencidos no cuentan
            }
            $diasRestantes = now()->diffInDays($fechaExpiracion, false);
            return $diasRestantes <= 7 && $diasRestantes >= 0;
        })->values();
        
        $productosVencidos = $productos->filter(function($producto) {
            if (!$producto->fecha_expiracion || !$producto->activo) {
                return false;
            }
            return \Carbon\Carbon::parse($producto->fecha_expiracion)->isPast();
        })->values();
    @endphp
    
    <div class="min-h-screen relative overflow-hidden">
        <div class="relative max-w-[140rem] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            @include('partials.walee-navbar')
            
            <div class="mt-6 sm:mt-8 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Super Products</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Manage supermarket products</p>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <a 
                            href="{{ route('walee.herramientas') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors shadow-sm"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span class="hidden sm:inline">Volver</span>
                        </a>
                        <button 
                            onclick="openAIModal()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span class="hidden sm:inline">IA</span>
                        </button>
                        <button 
                            onclick="openCreateProductoModal()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">Nuevo Producto</span>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Formulario de Agregar Producto (Izquierda en pantallas grandes) -->
                    <div class="lg:col-span-1">
                        <!-- Botón para mostrar/ocultar formulario en móvil -->
                        <button 
                            onclick="toggleFormulario()"
                            class="lg:hidden w-full mb-4 px-4 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors shadow-md flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Add Product</span>
                            <svg id="formToggleIcon" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div id="formularioProducto" class="bg-white dark:bg-slate-800 rounded-xl p-4 sm:p-6 border border-slate-200 dark:border-slate-700 shadow-md sticky top-4 hidden lg:block">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-4">Add Product</h3>
                            <form id="productoForm" class="space-y-4" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="productoId" name="producto_id">
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre</label>
                                    <input 
                                        type="text" 
                                        id="productoNombre" 
                                        name="nombre" 
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        placeholder="Ej: Leche, Pan, etc."
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción</label>
                                    <textarea 
                                        id="productoDescripcion" 
                                        name="descripcion" 
                                        rows="3"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                                        placeholder="Descripción del producto..."
                                    ></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Price (₡)</label>
                                    <input 
                                        type="number" 
                                        id="productoPrecio" 
                                        name="precio" 
                                        step="0.01"
                                        min="0"
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        placeholder="0.00"
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Category</label>
                                    <input 
                                        type="text" 
                                        id="productoCategoria" 
                                        name="categoria" 
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        placeholder="Ej: Lácteos, Panadería, etc."
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Stock</label>
                                    <input 
                                        type="number" 
                                        id="productoStock" 
                                        name="stock" 
                                        min="0"
                                        value="0"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fecha de Expiración</label>
                                    <input 
                                        type="date" 
                                        id="productoFechaExpiracion" 
                                        name="fecha_expiracion" 
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Código de Barras</label>
                                    <input 
                                        type="text" 
                                        id="productoCodigoBarras" 
                                        name="codigo_barras" 
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        placeholder="Opcional"
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Imagen del Producto</label>
                                    <input 
                                        type="file" 
                                        id="productoImagen" 
                                        name="imagen" 
                                        accept="image/*"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-300"
                                    >
                                    <div id="imagenPreview" class="mt-2 hidden">
                                        <img id="imagenPreviewImg" src="" alt="Vista previa" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            id="productoActivo" 
                                            name="activo" 
                                            checked
                                            class="w-4 h-4 text-emerald-500 border-slate-300 dark:border-slate-600 rounded focus:ring-emerald-500"
                                        >
                                        <span class="text-sm text-slate-700 dark:text-slate-300">Producto activo</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-center gap-3 pt-2">
                                    <button 
                                        type="button" 
                                        onclick="resetProductoForm()"
                                        class="flex-1 px-4 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors"
                                    >
                                        Limpiar
                                    </button>
                                    <button 
                                        type="submit"
                                        class="flex-1 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors"
                                    >
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Lista de Productos (Derecha en pantallas grandes) -->
                    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                        <!-- Search Bar con Filtros Integrados -->
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-3 sm:p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                            <div class="space-y-3">
                                <!-- Barra de búsqueda con botón de alerta -->
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <input 
                                            type="text" 
                                            id="searchProductos"
                                            placeholder="Search products by name, category..."
                                            class="w-full px-4 py-2.5 pl-10 pr-10 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm"
                                            onkeyup="buscarProductos()"
                                        >
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <button 
                                            id="clearSearchBtn"
                                            onclick="clearSearch()"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors hidden"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    @if($productosVencenPronto->count() > 0)
                                        <button 
                                            id="vencenProntoBtn"
                                            onclick="filtrarVencenPronto()"
                                            class="relative inline-flex items-center gap-2 px-3 sm:px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition-colors shadow-md whitespace-nowrap"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span class="hidden sm:inline">Vencen Pronto</span>
                                            <span class="inline-flex items-center justify-center min-w-[24px] h-6 px-1.5 text-xs font-bold bg-white text-amber-600 rounded-full">
                                                {{ $productosVencenPronto->count() }}
                                            </span>
                                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                            </span>
                                        </button>
                                    @endif
                                </div>
                                
                                <!-- Filtros integrados -->
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <select 
                                        id="filtroCategoria" 
                                        onchange="filtrarProductos()"
                                        class="flex-1 px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                        <option value="">All categories</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                                        @endforeach
                                    </select>
                                    <select 
                                        id="filtroActivo" 
                                        onchange="filtrarProductos()"
                                        class="flex-1 sm:flex-none sm:w-40 px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    >
                                        <option value="">Todos</option>
                                        <option value="1">Solo Activos</option>
                                        <option value="0">Solo Inactivos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lista de Productos -->
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-3 sm:p-4 md:p-6 border border-slate-200 dark:border-slate-700 shadow-md">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Products</h3>
                                <span id="productosCount" class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">{{ $productos->count() }} products</span>
                            </div>
                            
                            @if($productos->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-slate-600 dark:text-slate-400 text-lg mb-2">You don't have any products yet</p>
                                    <p class="text-slate-500 dark:text-slate-500 text-sm">Agrega tu primer producto para comenzar</p>
                                </div>
                            @else
                                <div class="space-y-3" id="productosList">
                                    @foreach($productos as $producto)
                                        @php
                                            $estaVencido = false;
                                            $venceraPronto = false;
                                            if ($producto->fecha_expiracion) {
                                                $estaVencido = \Carbon\Carbon::parse($producto->fecha_expiracion)->isPast();
                                                if (!$estaVencido) {
                                                    $diasRestantes = now()->diffInDays(\Carbon\Carbon::parse($producto->fecha_expiracion), false);
                                                    $venceraPronto = $diasRestantes <= 7 && $diasRestantes >= 0;
                                                }
                                            }
                                        @endphp
                                        <div 
                                            class="producto-item rounded-lg p-3 sm:p-4 border transition-all {{ $producto->activo ? ($estaVencido ? 'border-red-600 dark:border-red-500 border-2 bg-red-50/50 dark:bg-red-900/10' : ($venceraPronto ? 'border-amber-500 dark:border-amber-500 border-2 bg-amber-50/50 dark:bg-amber-900/10' : 'bg-slate-50 dark:bg-slate-700/50 border-slate-200 dark:border-slate-600 hover:border-emerald-400 dark:hover:border-emerald-500/30')) : 'bg-slate-100 dark:bg-slate-800/70 border-slate-300 dark:border-slate-600 opacity-75' }}"
                                            data-producto-id="{{ $producto->id }}"
                                            data-categoria="{{ $producto->categoria ?? '' }}"
                                            data-activo="{{ $producto->activo ? '1' : '0' }}"
                                            data-nombre="{{ strtolower($producto->nombre) }}"
                                            data-vencen-pronto="{{ $venceraPronto ? '1' : '0' }}"
                                        >
                                            <div class="flex items-start gap-2 sm:gap-4">
                                                @if($producto->imagen && !empty(trim($producto->imagen)))
                                                    @php
                                                        // Construir URL de la imagen usando la ruta definida
                                                        $imagenPath = trim($producto->imagen);
                                                        
                                                        // Si ya es una URL completa, usarla
                                                        if (str_starts_with($imagenPath, 'http://') || str_starts_with($imagenPath, 'https://')) {
                                                            $imagenUrl = $imagenPath;
                                                        } else {
                                                            // Extraer solo el nombre del archivo
                                                            $filename = basename($imagenPath);
                                                            // Usar la ruta definida en routes/web.php
                                                            $imagenUrl = route('storage.productos-super', ['filename' => $filename]);
                                                        }
                                                        
                                                        // Verificar si el archivo existe físicamente
                                                        $fullPath = storage_path('app/public/productos-super/' . basename($imagenPath));
                                                        $fileExists = file_exists($fullPath);
                                                    @endphp
                                                    <div class="flex-shrink-0">
                                                        @if(!$fileExists)
                                                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg border border-red-300 dark:border-red-600 bg-red-50 dark:bg-red-900/20 flex items-center justify-center" title="Archivo no encontrado: {{ $imagenPath }}">
                                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <img 
                                                                src="{{ $imagenUrl }}" 
                                                                alt="{{ $producto->nombre }}" 
                                                                class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg border border-slate-300 dark:border-slate-600 shadow-sm"
                                                                loading="lazy"
                                                                onerror="console.error('Error cargando imagen:', '{{ $producto->nombre }}', 'Ruta DB:', '{{ $imagenPath }}', 'URL:', this.src, 'Archivo existe:', {{ $fileExists ? 'true' : 'false' }}); this.onerror=null; this.style.display='none';"
                                                            >
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-1.5 sm:mb-2">
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="text-sm sm:text-base font-semibold {{ $producto->activo ? ($estaVencido ? 'text-red-900 dark:text-red-300' : ($venceraPronto ? 'text-amber-900 dark:text-amber-300' : 'text-slate-900 dark:text-white')) : 'text-slate-500 dark:text-slate-400' }} truncate">{{ $producto->nombre }}</h4>
                                                            <div class="flex items-center gap-1.5 sm:gap-2 mt-1 flex-wrap">
                                                                @if($producto->categoria)
                                                                    <span class="px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-medium rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                                                        {{ $producto->categoria }}
                                                                    </span>
                                                                @endif
                                                                @if($estaVencido)
                                                                    <span class="px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-bold rounded-md bg-red-500 text-white dark:bg-red-600 shadow-md animate-pulse">
                                                                        Vencido
                                                                    </span>
                                                                @elseif($venceraPronto)
                                                                    <span class="px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-bold rounded-md bg-amber-500 text-white dark:bg-amber-600 shadow-md">
                                                                        Vence Pronto
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Toggles y botones - Móvil optimizado -->
                                                        <div class="flex flex-col sm:flex-row items-end sm:items-start gap-1.5 sm:gap-2 flex-shrink-0">
                                                            <!-- Toggles compactos en móvil -->
                                                            <div class="flex gap-1.5 sm:flex-col sm:gap-2">
                                                                <!-- Toggle Activo/Inactivo -->
                                                                <label class="relative inline-flex items-center cursor-pointer group">
                                                                    <input 
                                                                        type="checkbox" 
                                                                        class="sr-only peer" 
                                                                        {{ $producto->activo ? 'checked' : '' }}
                                                                        disabled
                                                                    >
                                                                    <div class="w-7 h-4 sm:w-9 sm:h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 sm:after:h-4 sm:after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500"></div>
                                                                    <span class="ml-1.5 sm:ml-2 text-[10px] sm:text-xs font-medium text-slate-700 dark:text-slate-300 hidden sm:inline min-w-[60px]">
                                                                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                                                                    </span>
                                                                </label>
                                                                
                                                                <!-- Toggle Facebook -->
                                                                <label class="relative inline-flex items-center cursor-pointer group">
                                                                    <input 
                                                                        type="checkbox" 
                                                                        class="sr-only peer" 
                                                                        disabled
                                                                    >
                                                                    <div class="w-7 h-4 sm:w-9 sm:h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3 after:w-3 sm:after:h-4 sm:after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-blue-500"></div>
                                                                    <span class="ml-1.5 sm:ml-2 text-[10px] sm:text-xs font-medium text-slate-700 dark:text-slate-300 hidden sm:flex sm:items-center gap-1 min-w-[80px]">
                                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                                        </svg>
                                                                        <span>FB</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            
                                                            <!-- Botones de acción -->
                                                            <div class="flex items-center gap-1 sm:gap-2">
                                                                <button 
                                                                    onclick="openEditProductoModal({{ $producto->id }})"
                                                                    class="p-1.5 sm:p-2 rounded-md hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors"
                                                                    title="Editar"
                                                                >
                                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                    </svg>
                                                                </button>
                                                                <button 
                                                                    onclick="deleteProducto({{ $producto->id }})"
                                                                    class="p-1.5 sm:p-2 rounded-md hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors"
                                                                    title="Eliminar"
                                                                >
                                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($producto->descripcion)
                                                        <p class="text-xs sm:text-sm {{ $producto->activo ? 'text-slate-600 dark:text-slate-400' : 'text-slate-500 dark:text-slate-500' }} mb-1.5 sm:mb-2 line-clamp-2">{{ $producto->descripcion }}</p>
                                                    @endif
                                                    
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4 text-[10px] sm:text-xs text-slate-500 dark:text-slate-400">
                                                        <span class="font-semibold {{ $producto->activo ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                                                            ₡{{ number_format($producto->precio, 2, '.', ',') }}
                                                        </span>
                                                        <span class="{{ $producto->activo ? 'text-slate-700 dark:text-slate-300' : 'text-slate-500 dark:text-slate-500' }}">
                                                            Stock: {{ $producto->stock }}
                                                        </span>
                                                        @if($producto->fecha_expiracion)
                                                            <span class="{{ $estaVencido ? 'text-red-700 dark:text-red-400 font-bold' : ($venceraPronto ? 'text-amber-700 dark:text-amber-400 font-semibold' : '') }}">
                                                                Exp: {{ \Carbon\Carbon::parse($producto->fecha_expiracion)->format('d/m/Y') }}
                                                                @if($estaVencido || $venceraPronto)
                                                                    <span class="hidden sm:inline">({{ \Carbon\Carbon::parse($producto->fecha_expiracion)->diffForHumans() }})</span>
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </div>
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
        </div>
    </div>
    
    @include('partials.walee-support-button')
    
    <!-- Modal de IA -->
    <div id="aiModal" class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        AI Assistant for Products
                    </h3>
                    <button onclick="closeAIModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Describe el producto que quieres crear o editar:
                    </label>
                    <div class="relative">
                        <textarea 
                            id="aiPrompt"
                            rows="4"
                            placeholder="E.g: Create a product called 'Whole milk' in the 'Dairy' category, price 2500 colones, stock 50 units, expires in 30 days..."
                            class="w-full px-4 py-3 pr-20 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all resize-none"
                        ></textarea>
                        <button 
                            id="voiceBtn"
                            onclick="toggleVoiceRecognition()"
                            class="absolute bottom-3 right-3 p-2 rounded-lg bg-violet-100 dark:bg-violet-900/30 hover:bg-violet-200 dark:hover:bg-violet-900/50 text-violet-600 dark:text-violet-400 transition-colors"
                            title="Grabar con voz"
                        >
                            <svg id="voiceIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                        </button>
                    </div>
                    <p id="voiceStatus" class="text-xs text-slate-500 dark:text-slate-400 mt-2 hidden"></p>
                </div>
                
                <div id="aiResponse" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Respuesta de la IA:
                    </label>
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <p id="aiResponseText" class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap"></p>
                    </div>
                </div>
            </div>
            
            <div class="p-4 sm:p-6 border-t border-slate-200 dark:border-slate-700 flex items-center gap-3">
                <button 
                    onclick="closeAIModal()"
                    class="flex-1 px-4 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    onclick="processAIPrompt()"
                    id="processAIBtn"
                    class="flex-1 px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span id="processAIText">Generar Producto</span>
                    <span id="processAILoading" class="hidden">Procesando...</span>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Cargar producto para editar si hay parámetro en la URL
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                openEditProductoModal(editId);
                // Limpiar el parámetro de la URL sin recargar la página
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
        
        function getSwalTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#0f172a',
                borderColor: isDark ? '#334155' : '#e2e8f0'
            };
        }
        
        function openCreateProductoModal() {
            resetProductoForm();
            // Mostrar formulario en móvil si está oculto
            const formulario = document.getElementById('formularioProducto');
            if (formulario.classList.contains('hidden')) {
                formulario.classList.remove('hidden');
                formulario.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                document.getElementById('productoForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
        
        function toggleFormulario() {
            const formulario = document.getElementById('formularioProducto');
            const icon = document.getElementById('formToggleIcon');
            formulario.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
        
        function resetProductoForm() {
            document.getElementById('productoForm').reset();
            document.getElementById('productoId').value = '';
            document.getElementById('productoActivo').checked = true;
            document.getElementById('imagenPreview').classList.add('hidden');
            document.getElementById('imagenPreviewImg').src = '';
        }
        
        // Preview de imagen
        document.getElementById('productoImagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagenPreviewImg').src = e.target.result;
                    document.getElementById('imagenPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagenPreview').classList.add('hidden');
            }
        });
        
        function filtrarProductos() {
            aplicarFiltros();
        }
        
        function buscarProductos() {
            aplicarFiltros();
            const searchInput = document.getElementById('searchProductos');
            const clearBtn = document.getElementById('clearSearchBtn');
            
            if (searchInput.value.trim() !== '') {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }
        
        function clearSearch() {
            document.getElementById('searchProductos').value = '';
            document.getElementById('clearSearchBtn').classList.add('hidden');
            aplicarFiltros();
        }
        
        let filtroVencenPronto = false;
        
        function filtrarVencenPronto() {
            filtroVencenPronto = !filtroVencenPronto;
            const btn = document.getElementById('vencenProntoBtn');
            
            if (filtroVencenPronto) {
                btn.classList.add('ring-2', 'ring-amber-300', 'ring-offset-2');
                btn.classList.remove('bg-amber-500', 'hover:bg-amber-600');
                btn.classList.add('bg-amber-600');
            } else {
                btn.classList.remove('ring-2', 'ring-amber-300', 'ring-offset-2');
                btn.classList.remove('bg-amber-600');
                btn.classList.add('bg-amber-500', 'hover:bg-amber-600');
            }
            
            aplicarFiltros();
            
            // Scroll a la lista
            document.getElementById('productosList').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        function aplicarFiltros() {
            const categoria = document.getElementById('filtroCategoria').value;
            const activo = document.getElementById('filtroActivo').value;
            const searchTerm = document.getElementById('searchProductos').value.toLowerCase().trim();
            const items = document.querySelectorAll('.producto-item');
            let visibleCount = 0;
            
            items.forEach(item => {
                const itemCategoria = item.getAttribute('data-categoria') || '';
                const itemActivo = item.getAttribute('data-activo');
                const itemNombre = item.getAttribute('data-nombre') || '';
                const itemVencenPronto = item.getAttribute('data-vencen-pronto');
                
                let show = true;
                
                // Filtro por vencen pronto
                if (filtroVencenPronto && itemVencenPronto !== '1') {
                    show = false;
                }
                
                // Filtro por categoría
                if (show && categoria && itemCategoria !== categoria) {
                    show = false;
                }
                
                // Filtro por activo/inactivo
                if (show && activo !== '' && itemActivo !== activo) {
                    show = false;
                }
                
                // Búsqueda por nombre o categoría
                if (show && searchTerm !== '') {
                    const categoriaLower = itemCategoria.toLowerCase();
                    if (!itemNombre.includes(searchTerm) && !categoriaLower.includes(searchTerm)) {
                        show = false;
                    }
                }
                
                if (show) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Actualizar contador
            const countElement = document.getElementById('productosCount');
            if (countElement) {
                countElement.textContent = visibleCount + ' producto' + (visibleCount !== 1 ? 's' : '');
            }
        }
        
        function openEditProductoModal(productoId) {
            fetch(`/walee-productos-super/${productoId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar el producto');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.producto) {
                    document.getElementById('productoId').value = data.producto.id;
                    document.getElementById('productoNombre').value = data.producto.nombre || '';
                    document.getElementById('productoDescripcion').value = data.producto.descripcion || '';
                    document.getElementById('productoPrecio').value = data.producto.precio || '';
                    document.getElementById('productoCategoria').value = data.producto.categoria || '';
                    document.getElementById('productoStock').value = data.producto.stock || 0;
                    document.getElementById('productoFechaExpiracion').value = data.producto.fecha_expiracion || '';
                    document.getElementById('productoCodigoBarras').value = data.producto.codigo_barras || '';
                    document.getElementById('productoActivo').checked = data.producto.activo || false;
                    
                    // Mostrar imagen actual si existe
                    if (data.producto.imagen_url) {
                        document.getElementById('imagenPreviewImg').src = data.producto.imagen_url;
                        document.getElementById('imagenPreview').classList.remove('hidden');
                    } else {
                        document.getElementById('imagenPreview').classList.add('hidden');
                    }
                    
                    // En móvil, mostrar el formulario si está oculto
                    const formulario = document.getElementById('formularioProducto');
                    const icon = document.getElementById('formToggleIcon');
                    const isMobile = window.innerWidth < 1024; // lg breakpoint
                    
                    if (isMobile && formulario.classList.contains('hidden')) {
                        formulario.classList.remove('hidden');
                        if (icon) {
                            icon.classList.add('rotate-180');
                        }
                    }
                    
                    // Scroll al formulario
                    setTimeout(() => {
                        document.getElementById('productoForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, isMobile ? 100 : 0);
                } else {
                    Swal.fire('Error', data.message || 'No se pudo cargar el producto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar el producto', 'error');
            });
        }
        
        function deleteProducto(productoId) {
            Swal.fire({
                title: '¿Eliminar producto?',
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
                    fetch(`/walee-productos-super/${productoId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminado', 'El producto ha sido eliminado', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo eliminar el producto', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo eliminar el producto', 'error');
                    });
                }
            });
        }
        
        document.getElementById('productoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productoId = document.getElementById('productoId').value;
            const url = productoId ? `/walee-productos-super/${productoId}` : '/walee-productos-super';
            const method = productoId ? 'POST' : 'POST'; // Usar POST para PUT con FormData
            
            const formDataObj = new FormData();
            formDataObj.append('nombre', document.getElementById('productoNombre').value);
            formDataObj.append('descripcion', document.getElementById('productoDescripcion').value);
            formDataObj.append('precio', document.getElementById('productoPrecio').value);
            formDataObj.append('categoria', document.getElementById('productoCategoria').value);
            formDataObj.append('stock', document.getElementById('productoStock').value);
            formDataObj.append('fecha_expiracion', document.getElementById('productoFechaExpiracion').value);
            formDataObj.append('codigo_barras', document.getElementById('productoCodigoBarras').value);
            formDataObj.append('activo', document.getElementById('productoActivo').checked ? '1' : '0');
            
            const imagenFile = document.getElementById('productoImagen').files[0];
            if (imagenFile) {
                formDataObj.append('imagen', imagenFile);
            }
            
            if (productoId) {
                formDataObj.append('_method', 'PUT');
            }
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formDataObj
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', productoId ? 'Producto actualizado' : 'Producto creado', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'No se pudo guardar el producto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo guardar el producto', 'error');
            });
        });
        
        // Funciones de IA
        let recognition = null;
        let isListening = false;
        
        function openAIModal() {
            document.getElementById('aiModal').classList.remove('hidden');
            document.getElementById('aiPrompt').focus();
            
            // Inicializar reconocimiento de voz si está disponible
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();
                recognition.lang = 'es-ES';
                recognition.continuous = false;
                recognition.interimResults = false;
                
                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    const promptTextarea = document.getElementById('aiPrompt');
                    promptTextarea.value = transcript;
                    stopVoiceRecognition();
                };
                
                recognition.onerror = function(event) {
                    console.error('Error en reconocimiento de voz:', event.error);
                    document.getElementById('voiceStatus').textContent = 'Error: ' + event.error;
                    stopVoiceRecognition();
                };
                
                recognition.onend = function() {
                    stopVoiceRecognition();
                };
            } else {
                document.getElementById('voiceBtn').style.display = 'none';
            }
        }
        
        function closeAIModal() {
            document.getElementById('aiModal').classList.add('hidden');
            document.getElementById('aiPrompt').value = '';
            document.getElementById('aiResponse').classList.add('hidden');
            if (isListening) {
                stopVoiceRecognition();
            }
        }
        
        function toggleVoiceRecognition() {
            if (isListening) {
                stopVoiceRecognition();
            } else {
                startVoiceRecognition();
            }
        }
        
        function startVoiceRecognition() {
            if (!recognition) return;
            
            try {
                recognition.start();
                isListening = true;
                document.getElementById('voiceBtn').classList.add('bg-red-100', 'dark:bg-red-900/30');
                document.getElementById('voiceBtn').classList.remove('bg-violet-100', 'dark:bg-violet-900/30');
                document.getElementById('voiceIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>';
                document.getElementById('voiceStatus').textContent = 'Escuchando... Habla ahora.';
                document.getElementById('voiceStatus').classList.remove('hidden');
            } catch (error) {
                console.error('Error al iniciar reconocimiento:', error);
                document.getElementById('voiceStatus').textContent = 'Error al iniciar el micrófono';
                document.getElementById('voiceStatus').classList.remove('hidden');
            }
        }
        
        function stopVoiceRecognition() {
            if (recognition && isListening) {
                recognition.stop();
            }
            isListening = false;
            document.getElementById('voiceBtn').classList.remove('bg-red-100', 'dark:bg-red-900/30');
            document.getElementById('voiceBtn').classList.add('bg-violet-100', 'dark:bg-violet-900/30');
            document.getElementById('voiceIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>';
            document.getElementById('voiceStatus').classList.add('hidden');
        }
        
        function processAIPrompt() {
            const prompt = document.getElementById('aiPrompt').value.trim();
            if (!prompt) {
                Swal.fire('Error', 'Por favor, describe el producto que quieres crear', 'warning');
                return;
            }
            
            const processBtn = document.getElementById('processAIBtn');
            const processText = document.getElementById('processAIText');
            const processLoading = document.getElementById('processAILoading');
            
            processBtn.disabled = true;
            processText.classList.add('hidden');
            processLoading.classList.remove('hidden');
            
            fetch('/walee-productos-super/ai/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(response => response.json())
            .then(data => {
                processBtn.disabled = false;
                processText.classList.remove('hidden');
                processLoading.classList.add('hidden');
                
                if (data.success && data.producto) {
                    // Mostrar respuesta
                    document.getElementById('aiResponseText').textContent = data.response || 'Producto generado correctamente';
                    document.getElementById('aiResponse').classList.remove('hidden');
                    
                    // Rellenar formulario
                    fillFormWithAI(data.producto);
                    
                    // Mostrar formulario en móvil si está oculto
                    const formulario = document.getElementById('formularioProducto');
                    const isMobile = window.innerWidth < 1024;
                    if (isMobile && formulario.classList.contains('hidden')) {
                        formulario.classList.remove('hidden');
                        const icon = document.getElementById('formToggleIcon');
                        if (icon) icon.classList.add('rotate-180');
                    }
                    
                    // Scroll al formulario
                    setTimeout(() => {
                        document.getElementById('productoForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, isMobile ? 100 : 0);
                    
                    // Cerrar modal después de un momento
                    setTimeout(() => {
                        closeAIModal();
                    }, 2000);
                } else {
                    Swal.fire('Error', data.message || 'No se pudo generar el producto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                processBtn.disabled = false;
                processText.classList.remove('hidden');
                processLoading.classList.add('hidden');
                Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
            });
        }
        
        function fillFormWithAI(producto) {
            if (producto.nombre) document.getElementById('productoNombre').value = producto.nombre;
            if (producto.descripcion) document.getElementById('productoDescripcion').value = producto.descripcion;
            if (producto.precio) document.getElementById('productoPrecio').value = producto.precio;
            if (producto.categoria) document.getElementById('productoCategoria').value = producto.categoria;
            if (producto.stock !== undefined) document.getElementById('productoStock').value = producto.stock;
            if (producto.fecha_expiracion) document.getElementById('productoFechaExpiracion').value = producto.fecha_expiracion;
            if (producto.codigo_barras) document.getElementById('productoCodigoBarras').value = producto.codigo_barras;
            if (producto.activo !== undefined) document.getElementById('productoActivo').checked = producto.activo;
            
            // Limpiar ID si es nuevo producto
            document.getElementById('productoId').value = '';
        }
        
    </script>
</body>
</html>

