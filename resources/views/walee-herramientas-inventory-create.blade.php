<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Create Product</title>
    <meta name="description" content="Create Product - Inventory Management">
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
                @php $pageTitle = 'Create Product'; @endphp
                @include('partials.walee-navbar')
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8 pt-24 sm:pt-28">
            <!-- Header -->
            <div class="mb-6 sm:mb-8 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Create New Product</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Add a new product to inventory</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            onclick="openAIModal()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span>AI Assistant</span>
                        </button>
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
            
            <!-- Form Layout - Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <!-- Left Column - Basic Fields -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Basic Information
                        </h2>
                        <form id="productoForm" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Product Name *</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="productoNombre" 
                                        name="nombre" 
                                        required
                                        class="w-full px-4 py-2 pr-20 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Product name"
                                    >
                                    <button 
                                        type="button"
                                        onclick="startVoiceRecognition('nombre')"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-slate-400 hover:text-blue-500 rounded-lg transition-colors"
                                        title="Voice input"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                                <div class="relative">
                                    <textarea 
                                        id="productoDescripcion" 
                                        name="descripcion" 
                                        rows="3"
                                        class="w-full px-4 py-2 pr-20 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                        placeholder="Product description..."
                                    ></textarea>
                                    <button 
                                        type="button"
                                        onclick="startVoiceRecognition('descripcion')"
                                        class="absolute right-2 top-2 p-2 text-slate-400 hover:text-blue-500 rounded-lg transition-colors"
                                        title="Voice input"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                        </svg>
                                    </button>
                                </div>
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
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Category"
                                    >
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Section</label>
                                <input 
                                    type="text" 
                                    id="productoSeccion" 
                                    name="seccion" 
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Section"
                                >
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Stock *</label>
                                    <input 
                                        type="number" 
                                        id="productoStock" 
                                        name="stock" 
                                        min="0"
                                        value="0"
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
                                        value="0"
                                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Right Column - Advanced Settings -->
                <div class="space-y-6">
                    <!-- Codes & Images Section -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Codes & Images
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Barcode</label>
                                <input 
                                    type="text" 
                                    id="productoCodigoBarras" 
                                    name="codigo_barras" 
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                                    placeholder="Barcode"
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Product Image</label>
                                <input 
                                    type="file" 
                                    id="productoImagen" 
                                    name="imagen" 
                                    accept="image/*"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300"
                                >
                                <div id="imagenPreview" class="mt-2 hidden">
                                    <img id="imagenPreviewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">QR Code Image</label>
                                <input 
                                    type="file" 
                                    id="productoFotoQr" 
                                    name="foto_qr" 
                                    accept="image/*"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900/30 dark:file:text-purple-300"
                                >
                                <div id="qrPreview" class="mt-2 hidden">
                                    <img id="qrPreviewImg" src="" alt="Preview QR" class="w-32 h-32 object-cover rounded-lg border border-slate-300 dark:border-slate-600">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Toggle -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status
                        </h2>
                        <div class="space-y-4">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input 
                                    type="checkbox" 
                                    id="productoActivo" 
                                    name="activo" 
                                    value="1"
                                    checked
                                    class="sr-only peer"
                                    onchange="updateStatusText()"
                                >
                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-slate-600 peer-checked:bg-blue-600"></div>
                                <span id="statusText" class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    Active
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Dates Section -->
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
                                    value="{{ now()->format('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sale Limit Date</label>
                                <input 
                                    type="date" 
                                    id="productoFechaLimiteVenta" 
                                    name="fecha_limite_venta" 
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Expiration Date</label>
                                <input 
                                    type="date" 
                                    id="productoFechaExpiracion" 
                                    name="fecha_expiracion" 
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Exit Date</label>
                                <input 
                                    type="date" 
                                    id="productoFechaSalida" 
                                    name="fecha_salida" 
                                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Save Button -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <button 
                            type="button"
                            onclick="saveProducto()"
                            class="w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                        >
                            Create Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.walee-support-button')
    
    <!-- AI Modal -->
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
                        Describe el producto que quieres crear:
                    </label>
                    <div class="relative">
                        <textarea 
                            id="aiPrompt"
                            rows="4"
                            placeholder="E.g: Create a product called 'Whole milk' in the 'Dairy' category, section 'Refrigerated', price 2500 colones, stock 50 units, quantity 50, expires in 30 days..."
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
        
        function updateStatusText() {
            const checkbox = document.getElementById('productoActivo');
            const statusText = document.getElementById('statusText');
            statusText.textContent = checkbox.checked ? 'Active' : 'Inactive';
        }
        
        // Inicializar el texto del status al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            updateStatusText();
            
            // Establecer fecha de entrada por defecto si está vacía (año 2026)
            const fechaEntrada = document.getElementById('productoFechaEntrada');
            if (!fechaEntrada.value) {
                const today = new Date();
                today.setFullYear(2026);
                fechaEntrada.value = today.toISOString().split('T')[0];
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
        
        // Speech Recognition
        let recognition = null;
        let isListening = false;
        let currentField = null;
        
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.continuous = false;
            recognition.interimResults = false;
            
            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                if (currentField) {
                    const field = document.getElementById('producto' + currentField.charAt(0).toUpperCase() + currentField.slice(1));
                    if (field) {
                        field.value = field.value + ' ' + transcript;
                    }
                } else {
                    const promptTextarea = document.getElementById('aiPrompt');
                    if (promptTextarea) {
                        promptTextarea.value = promptTextarea.value + ' ' + transcript;
                    }
                }
                stopVoiceRecognition();
            };
            
            recognition.onerror = function(event) {
                console.error('Error en reconocimiento de voz:', event.error);
                stopVoiceRecognition();
            };
            
            recognition.onend = function() {
                stopVoiceRecognition();
            };
        }
        
        function startVoiceRecognition(field) {
            currentField = field;
            if (!recognition) {
                Swal.fire({
                    ...getSwalTheme(),
                    icon: 'error',
                    title: 'Not Supported',
                    text: 'Speech recognition is not supported in your browser'
                });
                return;
            }
            
            if (isListening) {
                recognition.stop();
                isListening = false;
                return;
            }
            
            try {
                recognition.start();
                isListening = true;
                const fieldElement = document.getElementById('producto' + field.charAt(0).toUpperCase() + field.slice(1));
                if (fieldElement) {
                    fieldElement.style.borderColor = '#ef4444';
                }
            } catch (error) {
                console.error('Error al iniciar reconocimiento:', error);
            }
        }
        
        function stopVoiceRecognition() {
            if (recognition && isListening) {
                recognition.stop();
            }
            isListening = false;
            currentField = null;
            if (recognition) {
                const fields = ['productoNombre', 'productoDescripcion'];
                fields.forEach(id => {
                    const field = document.getElementById(id);
                    if (field) {
                        field.style.borderColor = '';
                    }
                });
            }
        }
        
        // AI Modal Functions
        function openAIModal() {
            document.getElementById('aiModal').classList.remove('hidden');
            document.getElementById('aiPrompt').focus();
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
                document.getElementById('voiceBtn').classList.remove('bg-red-100', 'dark:bg-red-900/30');
                document.getElementById('voiceBtn').classList.add('bg-violet-100', 'dark:bg-violet-900/30');
                document.getElementById('voiceIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>';
                document.getElementById('voiceStatus').classList.add('hidden');
            } else {
                if (!recognition) {
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'error',
                        title: 'Not Supported',
                        text: 'Speech recognition is not supported in your browser'
                    });
                    return;
                }
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
        }
        
        function processAIPrompt() {
            const prompt = document.getElementById('aiPrompt').value.trim();
            if (!prompt) {
                Swal.fire({
                    ...getSwalTheme(),
                    icon: 'warning',
                    title: 'Error',
                    text: 'Por favor, describe el producto que quieres crear'
                });
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
                    
                    // Cerrar modal después de un momento
                    setTimeout(() => {
                        closeAIModal();
                    }, 2000);
                } else {
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo generar el producto'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                processBtn.disabled = false;
                processText.classList.remove('hidden');
                processLoading.classList.add('hidden');
                Swal.fire({
                    ...getSwalTheme(),
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo procesar la solicitud'
                });
            });
        }
        
        function fillFormWithAI(producto) {
            if (producto.nombre) document.getElementById('productoNombre').value = producto.nombre;
            if (producto.descripcion) document.getElementById('productoDescripcion').value = producto.descripcion;
            if (producto.precio) document.getElementById('productoPrecio').value = producto.precio;
            if (producto.categoria) document.getElementById('productoCategoria').value = producto.categoria;
            if (producto.seccion) document.getElementById('productoSeccion').value = producto.seccion;
            if (producto.stock !== undefined) document.getElementById('productoStock').value = producto.stock;
            if (producto.cantidad !== undefined) document.getElementById('productoCantidad').value = producto.cantidad;
            if (producto.fecha_expiracion) document.getElementById('productoFechaExpiracion').value = producto.fecha_expiracion;
            
            // Fecha de entrada: usar la del producto o establecer fecha actual de 2026
            const fechaEntradaInput = document.getElementById('productoFechaEntrada');
            if (producto.fecha_entrada) {
                fechaEntradaInput.value = producto.fecha_entrada;
            } else if (!fechaEntradaInput.value) {
                const today = new Date();
                today.setFullYear(2026);
                fechaEntradaInput.value = today.toISOString().split('T')[0];
            }
            
            if (producto.fecha_limite_venta) document.getElementById('productoFechaLimiteVenta').value = producto.fecha_limite_venta;
            if (producto.fecha_salida) document.getElementById('productoFechaSalida').value = producto.fecha_salida;
            if (producto.codigo_barras) document.getElementById('productoCodigoBarras').value = producto.codigo_barras;
            if (producto.activo !== undefined) document.getElementById('productoActivo').checked = producto.activo;
            updateStatusText();
        }
        
        async function saveProducto() {
            const formData = new FormData();
            formData.append('nombre', document.getElementById('productoNombre').value);
            formData.append('descripcion', document.getElementById('productoDescripcion').value);
            formData.append('precio', document.getElementById('productoPrecio').value);
            formData.append('categoria', document.getElementById('productoCategoria').value);
            formData.append('seccion', document.getElementById('productoSeccion').value);
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
            
            try {
                const response = await fetch('/walee-herramientas/inventory/producto', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Guardar indicador para hacer scroll después de recargar
                    sessionStorage.setItem('scrollToTop', 'true');
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product created successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirigir a la página de edición del producto creado
                        if (data.producto_id) {
                            window.location.href = `/walee-herramientas/inventory/producto/${data.producto_id}/edit`;
                        } else {
                            window.location.href = '{{ route("walee.herramientas.inventory") }}';
                        }
                    });
                } else {
                    Swal.fire({
                        ...getSwalTheme(),
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error creating product'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    ...getSwalTheme(),
                    icon: 'error',
                    title: 'Error',
                    text: 'Error creating product'
                });
            }
        }
        
        // Preview de imágenes
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
        
        document.getElementById('productoFotoQr').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('qrPreviewImg').src = e.target.result;
                    document.getElementById('qrPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('qrPreview').classList.add('hidden');
            }
        });
        
        // Close modal on background click
        document.getElementById('aiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAIModal();
            }
        });
    </script>
</body>
</html>

