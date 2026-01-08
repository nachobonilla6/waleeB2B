<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Edit Product</title>
    <meta name="description" content="Edit Product - Inventory Management">
    <meta name="theme-color" content="#D59F3B">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
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
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
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
                @php $pageTitle = 'Edit Product'; @endphp
                @include('partials.walee-navbar')
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8 pt-24 sm:pt-28">
            <!-- Header -->
            <div class="mb-6 sm:mb-8 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Inventory</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $producto->nombre }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('walee.herramientas.inventory') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors shadow-sm"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Form -->
            <form id="productoForm" class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                
                <!-- Status Section - Above all sections -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </h2>
                    <div class="space-y-4">
                        <!-- Section Field -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Section</label>
                            <select 
                                id="productoSeccion" 
                                name="seccion" 
                                onchange="updateSectionBadge()"
                                class="w-48 max-w-xs px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent cursor-pointer"
                            >
                                <option value="">Select Section</option>
                                <option value="Fruits & Vegetables" {{ $producto->seccion == 'Fruits & Vegetables' ? 'selected' : '' }}>Fruits & Vegetables</option>
                                <option value="Meat & Poultry" {{ $producto->seccion == 'Meat & Poultry' ? 'selected' : '' }}>Meat & Poultry</option>
                                <option value="Dairy & Eggs" {{ $producto->seccion == 'Dairy & Eggs' ? 'selected' : '' }}>Dairy & Eggs</option>
                                <option value="Bakery" {{ $producto->seccion == 'Bakery' ? 'selected' : '' }}>Bakery</option>
                                <option value="Beverages" {{ $producto->seccion == 'Beverages' ? 'selected' : '' }}>Beverages</option>
                                <option value="Snacks" {{ $producto->seccion == 'Snacks' ? 'selected' : '' }}>Snacks</option>
                                <option value="Canned Goods" {{ $producto->seccion == 'Canned Goods' ? 'selected' : '' }}>Canned Goods</option>
                                <option value="Frozen Foods" {{ $producto->seccion == 'Frozen Foods' ? 'selected' : '' }}>Frozen Foods</option>
                                <option value="Cleaning Supplies" {{ $producto->seccion == 'Cleaning Supplies' ? 'selected' : '' }}>Cleaning Supplies</option>
                                <option value="Personal Care" {{ $producto->seccion == 'Personal Care' ? 'selected' : '' }}>Personal Care</option>
                                <option value="Baby Products" {{ $producto->seccion == 'Baby Products' ? 'selected' : '' }}>Baby Products</option>
                                <option value="Pet Supplies" {{ $producto->seccion == 'Pet Supplies' ? 'selected' : '' }}>Pet Supplies</option>
                                <option value="Other" {{ $producto->seccion && !in_array($producto->seccion, ['Fruits & Vegetables', 'Meat & Poultry', 'Dairy & Eggs', 'Bakery', 'Beverages', 'Snacks', 'Canned Goods', 'Frozen Foods', 'Cleaning Supplies', 'Personal Care', 'Baby Products', 'Pet Supplies']) ? 'selected' : '' }}>Other</option>
                            </select>
                            @if($producto->seccion && !in_array($producto->seccion, ['Fruits & Vegetables', 'Meat & Poultry', 'Dairy & Eggs', 'Bakery', 'Beverages', 'Snacks', 'Canned Goods', 'Frozen Foods', 'Cleaning Supplies', 'Personal Care', 'Baby Products', 'Pet Supplies']))
                                <input 
                                    type="text" 
                                    id="productoSeccionCustom" 
                                    value="{{ $producto->seccion }}"
                                    oninput="updateSectionBadge()"
                                    placeholder="Custom section"
                                    class="mt-2 w-48 max-w-xs px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent"
                                >
                            @endif
                            <!-- Section Badge -->
                            <div class="mt-2" id="sectionBadgeContainer">
                                <span id="sectionBadge" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300 border border-violet-200 dark:border-violet-700/50 {{ !$producto->seccion ? 'hidden' : '' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span id="sectionBadgeText">Section: {{ $producto->seccion ?: 'No section' }}</span>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Status Toggle -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Product Status</label>
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input 
                                    type="checkbox" 
                                    id="productoActivo" 
                                    name="activo" 
                                    value="1"
                                    {{ $producto->activo ? 'checked' : '' }}
                                    class="sr-only peer"
                                    onchange="updateStatusText()"
                                >
                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                                <span id="statusText" class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $producto->activo ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Codes & Images Section - Full Width -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Codes & Images
                    </h2>
                    <div class="space-y-4">
                        <!-- Desktop: Foto y QR en la misma línea -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- Product Image -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Product Image</label>
                                <input 
                                    type="file" 
                                    id="productoImagen" 
                                    name="imagen" 
                                    accept="image/*"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300"
                                >
                                @if($producto->imagen)
                                    @php
                                        $imagenPath = trim($producto->imagen);
                                        if (str_starts_with($imagenPath, 'http://') || str_starts_with($imagenPath, 'https://')) {
                                            $imagenUrl = $imagenPath;
                                        } else {
                                            $filename = basename($imagenPath);
                                            if (strpos($imagenPath, 'productos-super/') === 0) {
                                                $imagenUrl = asset('storage/' . $imagenPath);
                                            } else {
                                                $imagenUrl = route('storage.productos-super', ['filename' => $filename]);
                                            }
                                        }
                                    @endphp
                                    <div class="mt-2 relative inline-block">
                                        <img src="{{ $imagenUrl }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600" id="currentImagenPreview">
                                        <button 
                                            type="button"
                                            onclick="removeImagen()"
                                            class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors"
                                            title="Remove image"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" id="removeImagen" name="remove_imagen" value="0">
                                @endif
                            </div>
                            
                            <!-- QR Code Image and Barcode (same column) -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">QR Code Image</label>
                                    <input 
                                        type="file" 
                                        id="productoFotoQr" 
                                        name="foto_qr" 
                                        accept="image/*"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900/30 dark:file:text-purple-300"
                                    >
                                    <button 
                                        type="button"
                                        onclick="generateQRCode()"
                                        class="mt-2 w-full px-4 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg font-medium transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transform hover:scale-105 active:scale-95"
                                        title="Generate QR Code automatically"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        <span>Generate QR Code</span>
                                    </button>
                                    <div id="qrCodeCanvas" class="mt-2 hidden"></div>
                                    @if($producto->foto_qr)
                                        @php
                                            $qrPath = trim($producto->foto_qr);
                                            if (str_starts_with($qrPath, 'http://') || str_starts_with($qrPath, 'https://')) {
                                                $qrUrl = $qrPath;
                                            } else {
                                                $filename = basename($qrPath);
                                                $qrUrl = route('storage.productos-super.qr', ['filename' => $filename]);
                                            }
                                        @endphp
                                        <div class="mt-2 relative inline-block">
                                            <img src="{{ $qrUrl }}" alt="Current QR" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600" id="currentQrPreview">
                                            <button 
                                                type="button"
                                                onclick="removeFotoQr()"
                                                class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors"
                                                title="Remove QR image"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <input type="hidden" id="removeFotoQr" name="remove_foto_qr" value="0">
                                    @endif
                                </div>
                                
                                <!-- Barcode debajo del QR en la misma columna -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Barcode</label>
                                    <input 
                                        type="text" 
                                        id="productoCodigoBarras" 
                                        name="codigo_barras" 
                                        value="{{ $producto->codigo_barras }}"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                                        placeholder="Barcode"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Basic Info and Dates - Two Columns -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Basic Information -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Basic Information
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Product Name *</label>
                                <input 
                                    type="text" 
                                    id="productoNombre" 
                                    name="nombre" 
                                    value="{{ $producto->nombre }}"
                                    required
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Product name"
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                                <textarea 
                                    id="productoDescripcion" 
                                    name="descripcion" 
                                    rows="3"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    placeholder="Product description..."
                                >{{ $producto->descripcion }}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Price (₡) *</label>
                                    <input 
                                        type="number" 
                                        id="productoPrecio" 
                                        name="precio" 
                                        step="0.01"
                                        min="0"
                                        value="{{ $producto->precio }}"
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Category</label>
                                    <input 
                                        type="text" 
                                        id="productoCategoria" 
                                        name="categoria" 
                                        value="{{ $producto->categoria }}"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Category"
                                    >
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Stock *</label>
                                    <input 
                                        type="number" 
                                        id="productoStock" 
                                        name="stock" 
                                        min="0"
                                        value="{{ $producto->stock }}"
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Quantity</label>
                                    <input 
                                        type="number" 
                                        id="productoCantidad" 
                                        name="cantidad" 
                                        min="0"
                                        value="{{ $producto->cantidad ?? 0 }}"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Dates -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Dates & Expiration
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Entry Date</label>
                                <input 
                                    type="date" 
                                    id="productoFechaEntrada" 
                                    name="fecha_entrada" 
                                    value="{{ $producto->fecha_entrada ? \Carbon\Carbon::parse($producto->fecha_entrada)->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sale Limit Date</label>
                                <input 
                                    type="date" 
                                    id="productoFechaLimiteVenta" 
                                    name="fecha_limite_venta" 
                                    value="{{ $producto->fecha_limite_venta ? \Carbon\Carbon::parse($producto->fecha_limite_venta)->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Expiration Date</label>
                                <input 
                                    type="date" 
                                    id="productoFechaExpiracion" 
                                    name="fecha_expiracion" 
                                    value="{{ $producto->fecha_expiracion ? \Carbon\Carbon::parse($producto->fecha_expiracion)->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Exit Date</label>
                                <input 
                                    type="date" 
                                    id="productoFechaSalida" 
                                    name="fecha_salida" 
                                    value="{{ $producto->fecha_salida ? \Carbon\Carbon::parse($producto->fecha_salida)->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Save Button - Below -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <button 
                        type="button"
                        onclick="saveProducto()"
                        class="w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @include('partials.walee-support-button')
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const productoId = {{ $producto->id }};
        
        function updateStatusText() {
            const checkbox = document.getElementById('productoActivo');
            const statusText = document.getElementById('statusText');
            statusText.textContent = checkbox.checked ? 'Active' : 'Inactive';
        }
        
        function updateSectionBadge() {
            const seccionSelect = document.getElementById('productoSeccion');
            const seccionCustomInput = document.getElementById('productoSeccionCustom');
            const sectionBadge = document.getElementById('sectionBadge');
            const sectionBadgeText = document.getElementById('sectionBadgeText');
            
            if (seccionSelect && sectionBadge && sectionBadgeText) {
                let seccionValue = '';
                
                if (seccionSelect.value === 'Other' && seccionCustomInput) {
                    seccionValue = seccionCustomInput.value.trim();
                    // Mostrar/ocultar input personalizado
                    if (!seccionCustomInput.parentElement) {
                        const container = seccionSelect.parentElement;
                        const customInput = document.createElement('input');
                        customInput.type = 'text';
                        customInput.id = 'productoSeccionCustom';
                        customInput.placeholder = 'Custom section';
                        customInput.className = 'mt-2 w-48 max-w-xs px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent';
                        customInput.oninput = updateSectionBadge;
                        container.appendChild(customInput);
                    }
                    if (seccionCustomInput) {
                        seccionCustomInput.style.display = 'block';
                    }
                } else {
                    seccionValue = seccionSelect.value.trim();
                    // Ocultar input personalizado si no es "Other"
                    if (seccionCustomInput) {
                        seccionCustomInput.style.display = 'none';
                    }
                }
                
                if (seccionValue) {
                    sectionBadgeText.textContent = `Section: ${seccionValue}`;
                    sectionBadge.classList.remove('hidden');
                } else {
                    sectionBadge.classList.add('hidden');
                }
            }
        }
        
        // Inicializar el texto del status al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            updateStatusText();
            updateSectionBadge();
            
            // Hacer scroll hacia arriba si se guardó recientemente
            if (sessionStorage.getItem('scrollToTop') === 'true') {
                sessionStorage.removeItem('scrollToTop');
                setTimeout(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 100);
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
        
        function removeImagen() {
            Swal.fire({
                ...getSwalTheme(),
                title: 'Remove Image?',
                text: 'Are you sure you want to remove this image?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const removeImagenInput = document.getElementById('removeImagen');
                    if (removeImagenInput) {
                        removeImagenInput.value = '1';
                    }
                    const preview = document.getElementById('currentImagenPreview');
                    if (preview) {
                        preview.style.opacity = '0.5';
                        preview.style.filter = 'grayscale(100%)';
                    }
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'success',
                        title: 'Image marked for removal',
                        text: 'The image will be removed when you save the changes',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
        
        function removeFotoQr() {
            Swal.fire({
                ...getSwalTheme(),
                title: 'Remove QR Image?',
                text: 'Are you sure you want to remove this QR code image?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, remove it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const removeFotoQrInput = document.getElementById('removeFotoQr');
                    if (removeFotoQrInput) {
                        removeFotoQrInput.value = '1';
                    }
                    const preview = document.getElementById('currentQrPreview');
                    if (preview) {
                        preview.style.opacity = '0.5';
                        preview.style.filter = 'grayscale(100%)';
                    }
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'success',
                        title: 'QR image marked for removal',
                        text: 'The QR image will be removed when you save the changes',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
        
        async function generateQRCode() {
            try {
                // Verificar que la librería QRCode esté cargada
                if (typeof QRCode === 'undefined') {
                    throw new Error('QRCode library not loaded. Please refresh the page.');
                }
                
                // Obtener datos del producto para el QR
                const codigoBarras = document.getElementById('productoCodigoBarras');
                const nombre = document.getElementById('productoNombre');
                const productoId = {{ $producto->id }};
                
                if (!codigoBarras || !nombre) {
                    throw new Error('Required fields not found');
                }
                
                // Crear texto para el QR (usar código de barras si existe, sino usar ID y nombre)
                let qrText = '';
                const codigoBarrasValue = codigoBarras.value ? codigoBarras.value.trim() : '';
                const nombreValue = nombre.value ? nombre.value.trim() : 'Product';
                
                if (codigoBarrasValue !== '') {
                    qrText = codigoBarrasValue;
                } else {
                    qrText = `PROD-${productoId}-${nombreValue}`;
                }
                
                if (!qrText || qrText.trim() === '') {
                    throw new Error('QR text cannot be empty');
                }
                
                // Limpiar canvas anterior si existe
                const qrCanvas = document.getElementById('qrCodeCanvas');
                if (!qrCanvas) {
                    throw new Error('QR canvas element not found');
                }
                
                qrCanvas.innerHTML = '';
                qrCanvas.classList.remove('hidden');
                
                // Crear canvas para el QR
                const canvas = document.createElement('canvas');
                canvas.id = 'generatedQRCanvas';
                qrCanvas.appendChild(canvas);
                
                // Generar QR code
                await QRCode.toCanvas(canvas, qrText, {
                    width: 256,
                    margin: 2,
                    color: {
                        dark: '#000000',
                        light: '#FFFFFF'
                    },
                    errorCorrectionLevel: 'M'
                });
                
                // Esperar a que el canvas esté listo
                await new Promise(resolve => setTimeout(resolve, 100));
                
                // Convertir canvas a blob y crear File para el input
                return new Promise((resolve, reject) => {
                    canvas.toBlob(function(blob) {
                        if (!blob) {
                            reject(new Error('Failed to create blob from canvas'));
                            return;
                        }
                        
                        try {
                            const file = new File([blob], `qr-${productoId}-${Date.now()}.png`, { type: 'image/png' });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            const fileInput = document.getElementById('productoFotoQr');
                            
                            if (!fileInput) {
                                reject(new Error('File input not found'));
                                return;
                            }
                            
                            fileInput.files = dataTransfer.files;
                            
                            // Mostrar preview del QR generado
                            const preview = document.getElementById('currentQrPreview');
                            const canvasDataUrl = canvas.toDataURL('image/png');
                            
                            if (preview) {
                                preview.src = canvasDataUrl;
                                preview.style.opacity = '1';
                                preview.style.filter = 'none';
                            } else {
                                // Crear preview si no existe
                                const previewContainer = fileInput.parentElement;
                                const existingPreview = previewContainer.querySelector('.mt-2.relative');
                                if (existingPreview) {
                                    existingPreview.remove();
                                }
                                const newPreview = document.createElement('div');
                                newPreview.className = 'mt-2 relative inline-block';
                                newPreview.innerHTML = `
                                    <img src="${canvasDataUrl}" alt="Generated QR" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600" id="currentQrPreview">
                                    <button 
                                        type="button"
                                        onclick="removeFotoQr()"
                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors"
                                        title="Remove QR image"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                `;
                                fileInput.parentElement.appendChild(newPreview);
                            }
                            
                            // Resetear flag de remover si estaba marcado
                            const removeFotoQrInput = document.getElementById('removeFotoQr');
                            if (removeFotoQrInput) {
                                removeFotoQrInput.value = '0';
                            }
                            
                            Swal.fire({
                                ...getSwalTheme(),
                                icon: 'success',
                                title: 'QR Code Generated!',
                                text: 'The QR code has been generated successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            resolve();
                        } catch (err) {
                            reject(err);
                        }
                    }, 'image/png');
                });
                
            } catch (error) {
                console.error('Error generating QR code:', error);
                Swal.fire({
                    ...getSwalTheme(),
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to generate QR code. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
        
        async function saveProducto() {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('nombre', document.getElementById('productoNombre').value);
            formData.append('descripcion', document.getElementById('productoDescripcion').value);
            formData.append('precio', document.getElementById('productoPrecio').value);
            formData.append('categoria', document.getElementById('productoCategoria').value);
            const seccionSelect = document.getElementById('productoSeccion');
            const seccionCustomInput = document.getElementById('productoSeccionCustom');
            let seccionValue = seccionSelect.value;
            if (seccionValue === 'Other' && seccionCustomInput) {
                seccionValue = seccionCustomInput.value.trim() || '';
            }
            formData.append('seccion', seccionValue);
            formData.append('stock', document.getElementById('productoStock').value);
            formData.append('cantidad', document.getElementById('productoCantidad').value);
            formData.append('fecha_entrada', document.getElementById('productoFechaEntrada').value);
            formData.append('fecha_limite_venta', document.getElementById('productoFechaLimiteVenta').value);
            formData.append('fecha_expiracion', document.getElementById('productoFechaExpiracion').value);
            formData.append('fecha_salida', document.getElementById('productoFechaSalida').value);
            formData.append('codigo_barras', document.getElementById('productoCodigoBarras').value);
            formData.append('activo', document.getElementById('productoActivo').checked ? '1' : '0');
            
            const imagenFile = document.getElementById('productoImagen').files[0];
            if (imagenFile) {
                formData.append('imagen', imagenFile);
            }
            
            const fotoQrFile = document.getElementById('productoFotoQr').files[0];
            if (fotoQrFile) {
                formData.append('foto_qr', fotoQrFile);
            }
            
            // Agregar flags para remover fotos
            const removeImagenInput = document.getElementById('removeImagen');
            if (removeImagenInput) {
                formData.append('remove_imagen', removeImagenInput.value);
            }
            
            const removeFotoQrInput = document.getElementById('removeFotoQr');
            if (removeFotoQrInput) {
                formData.append('remove_foto_qr', removeFotoQrInput.value);
            }
            
            try {
                const response = await fetch(`/walee-herramientas/inventory/producto/${productoId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirigir a la página de inventory
                        window.location.href = '{{ route("walee.herramientas.inventory") }}';
                    });
                } else {
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error updating product'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    ...getSwalTheme(),
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating product'
                });
            }
        }
        
        // Preview de imágenes
        document.getElementById('productoImagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.mt-2 img[alt="Current image"]');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const container = document.getElementById('productoImagen').parentElement;
                        const div = document.createElement('div');
                        div.className = 'mt-2';
                        div.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600">`;
                        container.appendChild(div);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
        
        document.getElementById('productoFotoQr').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.mt-2 img[alt="Current QR"]');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const container = document.getElementById('productoFotoQr').parentElement;
                        const div = document.createElement('div');
                        div.className = 'mt-2';
                        div.innerHTML = `<img src="${e.target.result}" alt="Preview QR" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600">`;
                        container.appendChild(div);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>

