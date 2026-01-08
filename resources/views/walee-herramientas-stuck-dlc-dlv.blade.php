<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Stock Management</title>
    <meta name="description" content="Product stock management">
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
    @php
        use App\Models\ProductoSuper;
        
        $productos = ProductoSuper::orderBy('nombre', 'asc')->get();
        
        $totalProductos = $productos->count();
        $productosActivos = $productos->where('activo', true)->count();
        $stockTotal = $productos->where('activo', true)->sum('stock');
        $stockBajo = $productos->where('activo', true)->filter(function($p) {
            return $p->stock <= 10 && $p->stock > 0;
        })->count();
        $sinStock = $productos->where('activo', true)->where('stock', 0)->count();
    @endphp
    
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-400/20 dark:bg-orange-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-orange-400/20 dark:bg-orange-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Fixed Navbar -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-slate-50/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-700/50">
            <div class="max-w-[90rem] mx-auto px-4 py-4 sm:px-6 lg:px-8">
                @php $pageTitle = 'Stuck DLC + DLV'; @endphp
                @include('partials.walee-navbar')
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8 pt-24 sm:pt-28">
            <!-- Header -->
            <div class="mb-6 sm:mb-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Stock Management</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Manage product inventory</p>
                    </div>
                    <button 
                        onclick="openCreateProductoModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors shadow-lg"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>New Product</span>
                    </button>
                </div>
            </div>
            
            <!-- Subtle Stats Widgets -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 animate-fade-in-up" style="animation-delay: 0.12s;">
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Total</div>
                    <div class="text-lg font-semibold text-slate-700 dark:text-slate-300">{{ $totalProductos }}</div>
                </div>
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Active</div>
                    <div class="text-lg font-semibold text-slate-700 dark:text-slate-300">{{ $productosActivos }}</div>
                </div>
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Low Stock</div>
                    <div class="text-lg font-semibold text-orange-600 dark:text-orange-400">{{ $stockBajo }}</div>
                </div>
                <div class="bg-white/60 dark:bg-slate-800/30 backdrop-blur-sm rounded-lg p-3 border border-slate-200/50 dark:border-slate-700/30">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">Out of Stock</div>
                    <div class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $sinStock }}</div>
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
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>
                    <select 
                        id="filterCategoria"
                        class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    >
                        <option value="">All categories</option>
                        @foreach($productos->pluck('categoria')->filter()->unique()->sort() as $categoria)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                    </select>
                    <select 
                        id="filterStock"
                        class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    >
                        <option value="all">All stock</option>
                        <option value="low">Low stock (≤10)</option>
                        <option value="zero">Out of stock</option>
                        <option value="active">Active only</option>
                    </select>
                </div>
            </div>
            
            <!-- Products Table -->
            <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700/50 shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-100 dark:bg-slate-900/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Stock</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productosTableBody" class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($productos as $producto)
                                <tr class="producto-row hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors" 
                                    data-nombre="{{ strtolower($producto->nombre) }}"
                                    data-categoria="{{ strtolower($producto->categoria ?? '') }}"
                                    data-stock="{{ $producto->stock }}"
                                    data-activo="{{ $producto->activo ? '1' : '0' }}"
                                >
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $producto->nombre }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $producto->categoria ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">₡{{ number_format($producto->precio, 2) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <input 
                                                type="number" 
                                                value="{{ $producto->stock }}" 
                                                min="0"
                                                data-producto-id="{{ $producto->id }}"
                                                class="w-20 px-2 py-1 text-center border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent stock-input"
                                                onchange="updateStock({{ $producto->id }}, this.value)"
                                            >
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($producto->activo)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">
                                                Inactive
                                            </span>
                                        @endif
                                        @if($producto->stock <= 10 && $producto->stock > 0)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                Low
                                            </span>
                                        @elseif($producto->stock == 0)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                Out of stock
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <button 
                                                onclick="editProducto({{ $producto->id }})"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="Edit"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                onclick="deleteProducto({{ $producto->id }})"
                                                class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
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
                    confirmButton: isDark ? 'dark:bg-orange-500 dark:hover:bg-orange-600' : 'bg-orange-500 hover:bg-orange-600',
                    cancelButton: isDark ? 'dark:bg-slate-600 dark:hover:bg-slate-700' : 'bg-slate-600 hover:bg-slate-700',
                    input: isDark ? 'dark:bg-slate-700 dark:text-white dark:border-slate-600' : 'bg-white text-slate-900 border-slate-300'
                }
            };
        }
        
        // Search and Filter
        document.getElementById('searchInput').addEventListener('input', filterProductos);
        document.getElementById('filterCategoria').addEventListener('change', filterProductos);
        document.getElementById('filterStock').addEventListener('change', filterProductos);
        
        function filterProductos() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const categoria = document.getElementById('filterCategoria').value.toLowerCase();
            const stockFilter = document.getElementById('filterStock').value;
            const rows = document.querySelectorAll('.producto-row');
            
            rows.forEach(row => {
                const nombre = row.dataset.nombre;
                const rowCategoria = row.dataset.categoria;
                const stock = parseInt(row.dataset.stock);
                const activo = row.dataset.activo === '1';
                
                let show = true;
                
                if (search && !nombre.includes(search)) show = false;
                if (categoria && rowCategoria !== categoria) show = false;
                if (stockFilter === 'low' && (stock > 10 || stock === 0)) show = false;
                if (stockFilter === 'zero' && stock !== 0) show = false;
                if (stockFilter === 'active' && !activo) show = false;
                
                row.style.display = show ? '' : 'none';
            });
        }
        
        // Update Stock
        async function updateStock(productoId, nuevoStock) {
            try {
                const response = await fetch(`/walee-productos-super/${productoId}/stock`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ stock: parseInt(nuevoStock) })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        ...getSwalConfig(),
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Stock updated successfully',
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
                        text: 'Error updating stock: ' + (data.message || 'Unknown error')
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    ...getSwalConfig(),
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating stock'
                });
            }
        }
        
        // AI Prompt Modal
        function openAIPrompt(field) {
            const fieldName = field === 'nombre' ? 'name' : 'description';
            Swal.fire({
                ...getSwalConfig(),
                title: 'AI Prompt',
                input: 'textarea',
                inputPlaceholder: `Describe the product ${fieldName} you want to generate...`,
                inputAttributes: {
                    'aria-label': 'Enter your prompt'
                },
                showCancelButton: true,
                confirmButtonText: 'Generate',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#9333ea',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter a prompt!';
                    }
                },
                preConfirm: (prompt) => {
                    if (prompt) {
                        generateWithAI(prompt, field);
                    }
                }
            });
        }
        
        // Speech Recognition
        let recognition = null;
        let isListening = false;
        
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';
        }
        
        function startVoiceRecognition(targetField) {
            if (!recognition) {
                Swal.fire({
                    ...getSwalConfig(),
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
            
            isListening = true;
            recognition.start();
            
            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                const activeElement = document.activeElement;
                if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA')) {
                    activeElement.value = activeElement.value + ' ' + transcript;
                }
                isListening = false;
            };
            
            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
                isListening = false;
            };
            
            recognition.onend = function() {
                isListening = false;
            };
        }
        
        // AI Prompt Function
        async function generateWithAI(prompt, targetField) {
            try {
                Swal.fire({
                    ...getSwalConfig(),
                    title: 'Generating with AI...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Simulate AI generation (replace with actual AI API call)
                // For demo purposes, we'll use a simple text generation
                // In production, replace this with actual AI API call
                await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API delay
                
                Swal.close();
                
                // Generate text based on prompt (demo mode)
                let generatedText = '';
                if (targetField === 'nombre') {
                    generatedText = prompt.split(' ').map(word => 
                        word.charAt(0).toUpperCase() + word.slice(1)
                    ).join(' ');
                } else {
                    generatedText = `This is a ${prompt}. High quality product with excellent features and customer satisfaction.`;
                }
                
                // Find the active field in the SweetAlert modal
                const swalContainer = document.querySelector('.swal2-popup');
                if (swalContainer) {
                    const field = swalContainer.querySelector(`#producto${targetField.charAt(0).toUpperCase() + targetField.slice(1)}`);
                    if (field) {
                        field.value = generatedText;
                        field.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
                
                Swal.fire({
                    ...getSwalConfig(),
                    icon: 'success',
                    title: 'Generated!',
                    text: 'Content has been generated and filled in',
                    timer: 1500,
                    showConfirmButton: false
                });
            } catch (error) {
                console.error('AI generation error:', error);
                Swal.fire({
                    ...getSwalConfig(),
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not generate content. Please try again or enter manually.'
                });
            }
        }
        
        // Modal Functions with SweetAlert
        function openCreateProductoModal() {
            showProductModal();
        }
        
        async function editProducto(id) {
            // Redirigir a la página de edición de inventory
            window.location.href = `/walee-herramientas/inventory/producto/${id}/edit`;
        }
        
        function showProductModal(producto = null) {
            const isEdit = producto !== null;
            const title = isEdit ? 'Edit Product' : 'New Product';
            
            const htmlContent = `
                <form id="productoForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="productoId" name="producto_id" value="${producto?.id || ''}">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Name *</label>
                        <input type="text" id="productoNombre" name="nombre" value="${producto?.nombre || ''}" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <div class="flex gap-2">
                            <textarea id="productoDescripcion" name="descripcion" rows="3" class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none">${producto?.descripcion || ''}</textarea>
                            <div class="flex flex-col gap-2">
                                <button type="button" onclick="startVoiceRecognition('descripcion')" class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors" title="Voice Input">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                    </svg>
                                </button>
                                <button type="button" onclick="openAIPrompt('descripcion')" class="px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors" title="AI Generate">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Price (₡) *</label>
                            <input type="number" id="productoPrecio" name="precio" step="0.01" min="0" value="${producto?.precio || ''}" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Stock *</label>
                            <input type="number" id="productoStock" name="stock" min="0" value="${producto?.stock || ''}" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Category</label>
                        <input type="text" id="productoCategoria" name="categoria" value="${producto?.categoria || ''}" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="productoActivo" name="activo" ${producto?.activo ? 'checked' : ''} class="w-4 h-4 text-orange-500 border-slate-300 rounded focus:ring-orange-500">
                        <label for="productoActivo" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Active product</label>
                    </div>
                </form>
            `;
            
            Swal.fire({
                ...getSwalConfig(),
                title: title,
                html: htmlContent,
                width: '700px',
                heightAuto: false,
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#64748b',
                didOpen: () => {
                    // Attach form submit handler
                    const form = document.getElementById('productoForm');
                    if (form) {
                        form.addEventListener('submit', handleFormSubmit);
                    }
                },
                preConfirm: () => {
                    return handleFormSubmit();
                }
            });
        }
        
        async function handleFormSubmit(e) {
            if (e) e.preventDefault();
            
            const productoId = document.getElementById('productoId')?.value;
            const nombre = document.getElementById('productoNombre')?.value;
            const descripcion = document.getElementById('productoDescripcion')?.value;
            const precio = document.getElementById('productoPrecio')?.value;
            const stock = document.getElementById('productoStock')?.value;
            const categoria = document.getElementById('productoCategoria')?.value;
            const activo = document.getElementById('productoActivo')?.checked;
            
            if (!nombre || !precio || !stock) {
                Swal.showValidationMessage('Please fill in all required fields');
                return false;
            }
            
            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion || '');
            formData.append('precio', precio);
            formData.append('stock', stock);
            formData.append('categoria', categoria || '');
            formData.append('activo', activo ? '1' : '0');
            
            if (productoId) {
                formData.append('_method', 'PUT');
            }
            
            const url = productoId 
                ? `/walee-productos-super/${productoId}`
                : '/walee-productos-super';
            const method = productoId ? 'PUT' : 'POST';
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        ...getSwalConfig(),
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.showValidationMessage('Error: ' + (data.message || 'Unknown error'));
                    return false;
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.showValidationMessage('Error saving product');
                return false;
            }
        }
        
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
