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
    <script>
        // Cargar QRCode de forma asíncrona y esperar a que esté disponible
        (function() {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
            script.async = true;
            script.onload = function() {
                window.QRCodeLoaded = true;
                console.log('QRCode library loaded successfully');
            };
            script.onerror = function() {
                console.error('Failed to load QRCode library');
                window.QRCodeLoaded = false;
            };
            document.head.appendChild(script);
        })();
    </script>
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
        
        .section-dropdown {
            animation: slideIn 0.2s ease-out;
        }
        
        .badge-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .badge-transition:hover {
            transform: scale(1.05);
        }
        
        input, select, textarea {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        input:focus, select:focus, textarea:focus {
            transform: scale(1.01);
        }
        
        .form-section {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .form-section:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
                
                <!-- Section Field and Status Toggle -->
                <div class="mb-6">
                    <div class="flex items-center justify-between gap-4">
                        <!-- Left side: Section -->
                        <div class="flex items-center gap-4">
                            <label class="block text-xl font-semibold text-slate-700 dark:text-slate-300">Section</label>
                            <!-- Section Badge with Dropdown -->
                            <div class="relative" id="sectionBadgeContainer">
                                <span 
                                    id="sectionBadge" 
                                    onclick="toggleSectionDropdown()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300 border border-violet-200 dark:border-violet-700/50 cursor-pointer hover:bg-violet-200 dark:hover:bg-violet-900/50 transition-colors {{ !$producto->seccion ? 'opacity-50' : '' }}"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span id="sectionBadgeText">{{ $producto->seccion ?: 'Select Section' }}</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
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
                                @if($expiraPronto)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300 border border-orange-200 dark:border-orange-700/50 shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Expires Soon
                                    </span>
                                @endif
                                 @if($salePronto)
                                     <span class="badge-transition ml-2 inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-700/50 shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        Exits Soon
                                        @if($diasSale !== null)
                                            <span class="ml-1">({{ $diasSale }}d)</span>
                                        @endif
                                    </span>
                                @endif
                                <!-- Hidden select for form submission -->
                                <select 
                                    id="productoSeccion" 
                                    name="seccion" 
                                    onchange="updateSectionBadge()"
                                    class="hidden"
                                >
                                    <option value="">{{ $producto->seccion ? '' : 'selected' }}</option>
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
                                <!-- Custom Dropdown Menu -->
                                <div id="sectionDropdown" class="hidden absolute top-full left-0 mt-1 w-56 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 z-50 overflow-hidden section-dropdown">
                                    <div class="py-1 max-h-64 overflow-y-auto">
                                        <button type="button" onclick="selectSection('')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">None</button>
                                        <button type="button" onclick="selectSection('Fruits & Vegetables')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Fruits & Vegetables</button>
                                        <button type="button" onclick="selectSection('Meat & Poultry')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Meat & Poultry</button>
                                        <button type="button" onclick="selectSection('Dairy & Eggs')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Dairy & Eggs</button>
                                        <button type="button" onclick="selectSection('Bakery')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Bakery</button>
                                        <button type="button" onclick="selectSection('Beverages')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Beverages</button>
                                        <button type="button" onclick="selectSection('Snacks')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Snacks</button>
                                        <button type="button" onclick="selectSection('Canned Goods')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Canned Goods</button>
                                        <button type="button" onclick="selectSection('Frozen Foods')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Frozen Foods</button>
                                        <button type="button" onclick="selectSection('Cleaning Supplies')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Cleaning Supplies</button>
                                        <button type="button" onclick="selectSection('Personal Care')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Personal Care</button>
                                        <button type="button" onclick="selectSection('Baby Products')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Baby Products</button>
                                        <button type="button" onclick="selectSection('Pet Supplies')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Pet Supplies</button>
                                        <button type="button" onclick="selectSection('Other')" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors">Other</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right side: Status Toggle and Save Button -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
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
                            <button 
                                type="button"
                                onclick="saveProducto()"
                                class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                            >
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Codes & Images Section - Full Width -->
                <div class="form-section bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
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
                            
                            <!-- QR Code Image and Barcode (same row) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- QR Code -->
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
                                        class="mt-2 px-3 py-1.5 text-sm bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg font-medium transition-all duration-300 flex items-center justify-center gap-1.5 shadow-md shadow-purple-500/20 hover:shadow-lg hover:shadow-purple-500/30"
                                        title="Generate QR Code automatically"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        <span>Generate QR</span>
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
                                
                                <!-- Barcode -->
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
                    <div class="form-section bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
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
                    <div class="form-section bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
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
        
        function toggleSectionDropdown() {
            const dropdown = document.getElementById('sectionDropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }
        
        function selectSection(value) {
            const seccionSelect = document.getElementById('productoSeccion');
            const sectionBadge = document.getElementById('sectionBadge');
            const sectionBadgeText = document.getElementById('sectionBadgeText');
            const dropdown = document.getElementById('sectionDropdown');
            
            if (seccionSelect && sectionBadge && sectionBadgeText) {
                seccionSelect.value = value;
                
                if (value === '') {
                    sectionBadgeText.textContent = 'Select Section';
                    sectionBadge.classList.add('opacity-50');
                } else {
                    sectionBadgeText.textContent = value;
                    sectionBadge.classList.remove('opacity-50');
                }
                
                // Cerrar dropdown
                if (dropdown) {
                    dropdown.classList.add('hidden');
                }
            }
        }
        
        function updateSectionBadge() {
            const seccionSelect = document.getElementById('productoSeccion');
            const sectionBadge = document.getElementById('sectionBadge');
            const sectionBadgeText = document.getElementById('sectionBadgeText');
            
            if (seccionSelect && sectionBadge && sectionBadgeText) {
                const seccionValue = seccionSelect.value.trim();
                if (seccionValue) {
                    sectionBadgeText.textContent = seccionValue;
                    sectionBadge.classList.remove('opacity-50');
                } else {
                    sectionBadgeText.textContent = 'Select Section';
                    sectionBadge.classList.add('opacity-50');
                }
            }
        }
        
        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('sectionDropdown');
            const badge = document.getElementById('sectionBadge');
            const container = document.getElementById('sectionBadgeContainer');
            
            if (dropdown && badge && container && !container.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
        
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
                // Esperar a que la librería QRCode esté cargada
                let retries = 0;
                const maxRetries = 50; // 5 segundos máximo
                
                while ((typeof QRCode === 'undefined' || !window.QRCodeLoaded) && retries < maxRetries) {
                    await new Promise(resolve => setTimeout(resolve, 100));
                    retries++;
                }
                
                // Verificar que la librería QRCode esté cargada
                if (typeof QRCode === 'undefined') {
                    // Intentar cargar manualmente si aún no está disponible
                    if (!window.QRCodeLoading) {
                        window.QRCodeLoading = true;
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
                        script.async = true;
                        await new Promise((resolve, reject) => {
                            script.onload = () => {
                                window.QRCodeLoaded = true;
                                resolve();
                            };
                            script.onerror = () => {
                                window.QRCodeLoading = false;
                                reject(new Error('Failed to load QRCode library from CDN'));
                            };
                            document.head.appendChild(script);
                        });
                        window.QRCodeLoading = false;
                    } else {
                        // Esperar un poco más si ya se está cargando
                        let waitRetries = 0;
                        while (window.QRCodeLoading && waitRetries < 20) {
                            await new Promise(resolve => setTimeout(resolve, 100));
                            waitRetries++;
                        }
                    }
                    
                    // Verificar nuevamente después de intentar cargar
                    if (typeof QRCode === 'undefined') {
                        throw new Error('QRCode library failed to load. Please check your internet connection and refresh the page.');
                    }
                }
                
                // Verificar que QRCode.toCanvas esté disponible
                if (typeof QRCode.toCanvas !== 'function') {
                    throw new Error('QRCode.toCanvas function is not available. The library may not be fully loaded.');
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
                try {
                    await QRCode.toCanvas(canvas, qrText, {
                        width: 256,
                        margin: 2,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        },
                        errorCorrectionLevel: 'M'
                    });
                } catch (qrError) {
                    console.error('QRCode.toCanvas error:', qrError);
                    throw new Error('Failed to generate QR code image: ' + (qrError.message || 'Unknown error'));
                }
                
                // Esperar a que el canvas esté listo
                await new Promise(resolve => setTimeout(resolve, 100));
                
                // Verificar que el canvas tenga contenido
                if (!canvas || canvas.width === 0 || canvas.height === 0) {
                    throw new Error('Canvas is empty. QR code generation may have failed.');
                }
                
                // Convertir canvas a blob y crear File para el input
                return new Promise((resolve, reject) => {
                    canvas.toBlob(function(blob) {
                        if (!blob) {
                            reject(new Error('Failed to create blob from canvas. The canvas may be empty.'));
                            return;
                        }
                        
                        try {
                            const file = new File([blob], `qr-${productoId}-${Date.now()}.png`, { type: 'image/png' });
                            const dataTransfer = new DataTransfer();
                            
                            // Verificar que DataTransfer.items.add esté disponible
                            if (!dataTransfer.items || typeof dataTransfer.items.add !== 'function') {
                                throw new Error('File API not fully supported in this browser. Please use a modern browser.');
                            }
                            
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
                            console.error('Error processing QR code file:', err);
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
            formData.append('seccion', seccionSelect.value);
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
                        // Recargar la página actual para mantener en la misma página del producto
                        window.location.reload();
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

