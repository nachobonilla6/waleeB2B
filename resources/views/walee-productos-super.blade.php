<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Productos Super</title>
    <meta name="description" content="Gestión de Productos del Supermercado">
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
    @endphp
    
    <div class="min-h-screen relative overflow-hidden">
        <div class="relative max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            @include('partials.walee-navbar')
            
            <div class="mt-6 sm:mt-8 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Productos Super</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Gestiona los productos del supermercado</p>
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
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Formulario de Agregar Producto (Izquierda en pantallas grandes) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-md sticky top-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Agregar Producto</h3>
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
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Precio (₡)</label>
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
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Categoría</label>
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
                    
                    <!-- Lista de Productos y Stats (Derecha en pantallas grandes) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Total Productos</p>
                                <p class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">{{ $totalProductos }}</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Activos</p>
                                <p class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $productosActivos }}</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Inactivos</p>
                                <p class="text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400">{{ $productosInactivos }}</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Valor Inventario</p>
                                <p class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">₡{{ number_format($valorTotalInventario, 2, '.', ',') }}</p>
                            </div>
                        </div>
                        
                        <!-- Filtros -->
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700 shadow-md">
                            <div class="flex flex-wrap items-center gap-3">
                                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Filtrar por categoría:</label>
                                <select 
                                    id="filtroCategoria" 
                                    onchange="filtrarProductos()"
                                    class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                >
                                    <option value="">Todas</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria }}">{{ $categoria }}</option>
                                    @endforeach
                                </select>
                                <select 
                                    id="filtroActivo" 
                                    onchange="filtrarProductos()"
                                    class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                >
                                    <option value="">Todos</option>
                                    <option value="1">Solo Activos</option>
                                    <option value="0">Solo Inactivos</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Lista de Productos -->
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-md">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Lista de Productos</h3>
                            
                            @if($productos->isEmpty())
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-slate-600 dark:text-slate-400 text-lg mb-2">No tienes productos aún</p>
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
                                            class="producto-item rounded-lg p-4 border transition-all {{ $producto->activo ? ($estaVencido ? 'border-red-600 dark:border-red-500 border-2 bg-red-50/50 dark:bg-red-900/10' : ($venceraPronto ? 'border-amber-500 dark:border-amber-500 border-2 bg-amber-50/50 dark:bg-amber-900/10' : 'bg-slate-50 dark:bg-slate-700/50 border-slate-200 dark:border-slate-600 hover:border-emerald-400 dark:hover:border-emerald-500/30')) : 'bg-slate-100 dark:bg-slate-800/70 border-slate-300 dark:border-slate-600 opacity-75' }}"
                                            data-producto-id="{{ $producto->id }}"
                                            data-categoria="{{ $producto->categoria ?? '' }}"
                                            data-activo="{{ $producto->activo ? '1' : '0' }}"
                                        >
                                            <div class="flex items-start justify-between gap-4">
                                                @if($producto->imagen && !empty(trim($producto->imagen)))
                                                    @php
                                                        // Construir URL de la imagen directamente
                                                        $imagenPath = trim($producto->imagen);
                                                        // Verificar si el archivo existe físicamente
                                                        $fullPath = storage_path('app/public/' . $imagenPath);
                                                        $fileExists = file_exists($fullPath);
                                                        
                                                        // Si ya es una URL completa, usarla
                                                        if (str_starts_with($imagenPath, 'http://') || str_starts_with($imagenPath, 'https://')) {
                                                            $imagenUrl = $imagenPath;
                                                        } else {
                                                            // Construir URL usando asset con storage/
                                                            $imagenUrl = asset('storage/' . $imagenPath);
                                                        }
                                                    @endphp
                                                    <div class="flex-shrink-0">
                                                        @if(!$fileExists)
                                                            <div class="w-20 h-20 rounded-lg border border-red-300 dark:border-red-600 bg-red-50 dark:bg-red-900/20 flex items-center justify-center" title="Archivo no encontrado: {{ $imagenPath }}">
                                                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <img 
                                                                src="{{ $imagenUrl }}" 
                                                                alt="{{ $producto->nombre }}" 
                                                                class="w-20 h-20 object-cover rounded-lg border border-slate-300 dark:border-slate-600 shadow-sm"
                                                                loading="lazy"
                                                                onerror="console.error('Error cargando imagen:', '{{ $producto->nombre }}', 'Ruta DB:', '{{ $imagenPath }}', 'URL:', this.src, 'Archivo existe:', {{ $fileExists ? 'true' : 'false' }}); this.onerror=null; this.style.display='none';"
                                                            >
                                                        @endif
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                                        <h4 class="font-semibold {{ $producto->activo ? ($estaVencido ? 'text-red-900 dark:text-red-300' : ($venceraPronto ? 'text-amber-900 dark:text-amber-300' : 'text-slate-900 dark:text-white')) : 'text-slate-500 dark:text-slate-400' }}">{{ $producto->nombre }}</h4>
                                                        @if($producto->categoria)
                                                            <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                                                {{ $producto->categoria }}
                                                            </span>
                                                        @endif
                                                        @if($producto->activo)
                                                            <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                                                Activo
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                                                Inactivo
                                                            </span>
                                                        @endif
                                                        @if($estaVencido)
                                                            <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-red-500 text-white dark:bg-red-600 dark:text-white shadow-md animate-pulse">
                                                                Vencido
                                                            </span>
                                                        @elseif($venceraPronto)
                                                            <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-amber-500 text-white dark:bg-amber-600 dark:text-white shadow-md">
                                                                Vence Pronto
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($producto->descripcion)
                                                        <p class="text-sm {{ $producto->activo ? 'text-slate-600 dark:text-slate-400' : 'text-slate-500 dark:text-slate-500' }} mb-2">{{ $producto->descripcion }}</p>
                                                    @endif
                                                    
                                                    <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                                                        <span class="font-semibold {{ $producto->activo ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">
                                                            Precio: ₡{{ number_format($producto->precio, 2, '.', ',') }}
                                                        </span>
                                                        <span class="{{ $producto->activo ? 'text-slate-700 dark:text-slate-300' : 'text-slate-500 dark:text-slate-500' }}">
                                                            Stock: {{ $producto->stock }}
                                                        </span>
                                                        @if($producto->fecha_expiracion)
                                                            <span class="{{ $estaVencido ? 'text-red-700 dark:text-red-400 font-bold' : ($venceraPronto ? 'text-amber-700 dark:text-amber-400 font-semibold' : '') }}">
                                                                Expira: {{ \Carbon\Carbon::parse($producto->fecha_expiracion)->format('d/m/Y') }}
                                                                @if($estaVencido || $venceraPronto)
                                                                    ({{ \Carbon\Carbon::parse($producto->fecha_expiracion)->diffForHumans() }})
                                                                @endif
                                                            </span>
                                                        @endif
                                                        @if($producto->codigo_barras)
                                                            <span class="text-slate-500 dark:text-slate-500">
                                                                Código: {{ $producto->codigo_barras }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    <button 
                                                        onclick="openEditProductoModal({{ $producto->id }})"
                                                        class="p-2 rounded-md hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors"
                                                        title="Editar"
                                                    >
                                                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button 
                                                        onclick="deleteProducto({{ $producto->id }})"
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
        
        function openCreateProductoModal() {
            resetProductoForm();
            document.getElementById('productoForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
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
            const categoria = document.getElementById('filtroCategoria').value;
            const activo = document.getElementById('filtroActivo').value;
            const items = document.querySelectorAll('.producto-item');
            
            items.forEach(item => {
                const itemCategoria = item.getAttribute('data-categoria') || '';
                const itemActivo = item.getAttribute('data-activo');
                
                let show = true;
                
                if (categoria && itemCategoria !== categoria) {
                    show = false;
                }
                
                if (activo !== '' && itemActivo !== activo) {
                    show = false;
                }
                
                item.style.display = show ? 'block' : 'none';
            });
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
                    
                    document.getElementById('productoForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
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
    </script>
</body>
</html>

