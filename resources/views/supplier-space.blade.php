<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supplier Space - Walee</title>
    <meta name="description" content="Supplier Space - Manage your profile and products">
    <meta name="theme-color" content="#D59F3B">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
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
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/10 dark:bg-emerald-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            @if(!session('supplier_space_authenticated'))
                <!-- Login Form -->
                <div class="max-w-md mx-auto mt-20 animate-fade-in-up">
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-8 shadow-lg dark:shadow-none">
                        <div class="text-center mb-8">
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Supplier Space</h1>
                            <p class="text-slate-600 dark:text-slate-400">Enter access code to continue</p>
                        </div>
                        
                        <form id="accessCodeForm" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Access Code</label>
                                <input type="password" id="accessCode" name="code" required
                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500 focus:border-transparent text-center text-2xl tracking-widest"
                                       placeholder="••••" maxlength="4" autocomplete="off">
                            </div>
                            
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-walee-500 to-walee-600 hover:from-walee-600 hover:to-walee-700 text-white font-semibold rounded-xl shadow-lg shadow-walee-500/30 hover:shadow-xl hover:shadow-walee-500/40 transition-all duration-300 transform hover:scale-105 active:scale-95">
                                Access Supplier Space
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Supplier Dashboard -->
                @php
                    $supplier = session('supplier_space_supplier');
                    $productos = $supplier ? \App\Models\Rproducto::where('cliente_id', $supplier->id)->orderBy('created_at', 'desc')->get() : collect();
                @endphp
                
                <div class="space-y-6 animate-fade-in-up">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Supplier Space</h1>
                            <p class="text-slate-600 dark:text-slate-400 mt-1">Manage your profile and products</p>
                        </div>
                        <form method="POST" action="{{ route('supplier-space.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-medium transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                    
                    @if(!$supplier)
                        <!-- Search Supplier Form -->
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Find Your Supplier Account</h2>
                            <form id="searchSupplierForm" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email or Name</label>
                                    <input type="text" id="supplierSearch" name="search" required
                                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500 focus:border-transparent"
                                           placeholder="Enter your email or supplier name">
                                </div>
                                <button type="submit" 
                                        class="w-full px-4 py-3 bg-gradient-to-r from-walee-500 to-walee-600 hover:from-walee-600 hover:to-walee-700 text-white font-semibold rounded-xl shadow-lg shadow-walee-500/30 hover:shadow-xl hover:shadow-walee-500/40 transition-all duration-300">
                                    Search Supplier
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Supplier Profile -->
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Supplier Profile</h2>
                            <form id="updateProfileForm" class="space-y-4">
                                @csrf
                                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Name</label>
                                        <input type="text" name="name" value="{{ $supplier->name }}" required
                                               class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                        <input type="email" name="email" value="{{ $supplier->email }}"
                                               class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Phone 1</label>
                                        <input type="tel" name="telefono_1" value="{{ $supplier->telefono_1 }}"
                                               class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Phone 2</label>
                                        <input type="tel" name="telefono_2" value="{{ $supplier->telefono_2 }}"
                                               class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Address</label>
                                        <input type="text" name="direccion" value="{{ $supplier->direccion ?? $supplier->address }}"
                                               class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        class="px-6 py-2 bg-gradient-to-r from-walee-500 to-walee-600 hover:from-walee-600 hover:to-walee-700 text-white font-semibold rounded-lg shadow-lg shadow-walee-500/30 hover:shadow-xl transition-all duration-300">
                                    Update Profile
                                </button>
                            </form>
                        </div>
                        
                        <!-- Products Section -->
                        <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Products ({{ $productos->count() }})</h2>
                            </div>
                            
                            <div class="space-y-4">
                                @forelse($productos as $producto)
                                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                        <form class="product-form" data-product-id="{{ $producto->id }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $producto->id }}">
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Product Name</label>
                                                    <input type="text" name="nombre" value="{{ $producto->nombre }}" required
                                                           class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Type</label>
                                                    <input type="text" name="tipo" value="{{ $producto->tipo }}"
                                                           class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                                </div>
                                                
                                                <div class="md:col-span-2">
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                                                    <textarea name="descripcion" rows="3"
                                                              class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">{{ $producto->descripcion }}</textarea>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                                                    <select name="estado"
                                                            class="w-full px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                                        <option value="activo" {{ $producto->estado == 'activo' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactivo" {{ $producto->estado == 'inactivo' ? 'selected' : '' }}>Inactive</option>
                                                        <option value="pendiente" {{ $producto->estado == 'pendiente' ? 'selected' : '' }}>Pending</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" 
                                                    class="mt-4 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg transition-colors">
                                                Update Product
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-slate-600 dark:text-slate-400 text-center py-8">No products found</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    <script>
        // Access Code Form
        document.getElementById('accessCodeForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const code = document.getElementById('accessCode').value;
            
            try {
                const response = await fetch('{{ route("supplier-space.authenticate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ code: code })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Code',
                        text: data.message || 'The access code is incorrect',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        });
        
        // Search Supplier Form
        document.getElementById('searchSupplierForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const search = document.getElementById('supplierSearch').value;
            
            try {
                const response = await fetch('{{ route("supplier-space.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ search: search })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Supplier Not Found',
                        text: data.message || 'No supplier found with that email or name',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        });
        
        // Update Profile Form
        document.getElementById('updateProfileForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("supplier-space.update-profile") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile Updated',
                        text: 'Your profile has been updated successfully',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error updating profile',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            }
        });
        
        // Update Product Forms
        document.querySelectorAll('.product-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const productId = this.dataset.productId;
                
                try {
                    const response = await fetch('{{ route("supplier-space.update-product") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Product Updated',
                            text: 'Product has been updated successfully',
                            confirmButtonColor: '#10b981',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error updating product',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            });
        });
    </script>
</body>
</html>

