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
        
    @endphp
    
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-400/20 dark:bg-orange-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-orange-400/20 dark:bg-orange-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Stuck DLC + DLV'; @endphp
            @include('partials.walee-navbar')
            
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
    
    <!-- Create/Edit Producto Modal -->
    <div id="productoModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" id="modalTitle">New Product</h3>
                <button onclick="closeProductoModal()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="productoForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="productoId" name="producto_id">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Name *</label>
                    <input type="text" id="productoNombre" name="nombre" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                    <textarea id="productoDescripcion" name="descripcion" rows="3" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Price (₡) *</label>
                        <input type="number" id="productoPrecio" name="precio" step="0.01" min="0" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Stock *</label>
                        <input type="number" id="productoStock" name="stock" min="0" required class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Category</label>
                    <input type="text" id="productoCategoria" name="categoria" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="productoActivo" name="activo" checked class="w-4 h-4 text-orange-500 border-slate-300 rounded focus:ring-orange-500">
                    <label for="productoActivo" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Active product</label>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeProductoModal()" class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @include('partials.walee-support-button')
    
    <script>
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
                    location.reload();
                } else {
                    alert('Error updating stock: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating stock');
            }
        }
        
        // Modal Functions
        function openCreateProductoModal() {
            document.getElementById('modalTitle').textContent = 'New Product';
            document.getElementById('productoForm').reset();
            document.getElementById('productoId').value = '';
            document.getElementById('productoModal').classList.remove('hidden');
            document.getElementById('productoModal').classList.add('flex');
        }
        
        function closeProductoModal() {
            document.getElementById('productoModal').classList.add('hidden');
            document.getElementById('productoModal').classList.remove('flex');
        }
        
        async function editProducto(id) {
            try {
                const response = await fetch(`/walee-productos-super/${id}`);
                const data = await response.json();
                
                if (!data.success) {
                    alert('Error loading product: ' + (data.message || 'Unknown error'));
                    return;
                }
                
                const producto = data.producto;
                
                document.getElementById('modalTitle').textContent = 'Edit Product';
                document.getElementById('productoId').value = producto.id;
                document.getElementById('productoNombre').value = producto.nombre;
                document.getElementById('productoDescripcion').value = producto.descripcion || '';
                document.getElementById('productoPrecio').value = producto.precio;
                document.getElementById('productoStock').value = producto.stock;
                document.getElementById('productoCategoria').value = producto.categoria || '';
                document.getElementById('productoActivo').checked = producto.activo;
                
                document.getElementById('productoModal').classList.remove('hidden');
                document.getElementById('productoModal').classList.add('flex');
            } catch (error) {
                console.error('Error:', error);
                alert('Error loading product');
            }
        }
        
        async function deleteProducto(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;
            
            try {
                const response = await fetch(`/walee-productos-super/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting product: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting product');
            }
        }
        
        // Form Submit
        document.getElementById('productoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productoId = document.getElementById('productoId').value;
            const url = productoId 
                ? `/walee-productos-super/${productoId}`
                : '/walee-productos-super';
            const method = productoId ? 'PUT' : 'POST';
            
            // Agregar activo como checkbox
            formData.append('activo', document.getElementById('productoActivo').checked ? '1' : '0');
            
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
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error saving product');
            }
        });
        
        // Close modal on outside click
        document.getElementById('productoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductoModal();
            }
        });
    </script>
</body>
</html>
