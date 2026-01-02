<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Productos</title>
    <meta name="description" content="Walee - Gestión de Productos">
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
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Productos'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        Productos
                    </h1>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Gestiona tus productos y servicios</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="openCreateModal()" class="px-4 py-2 bg-walee-500 hover:bg-walee-600 text-white font-medium rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Nuevo Producto</span>
                    </button>
                    <a href="{{ route('walee.herramientas') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                    </a>
                    @include('partials.walee-dark-mode-toggle')
                </div>
            </header>
            
            <!-- Filters -->
            <div class="mb-6 flex flex-wrap gap-4 animate-fade-in-up">
                <select id="filterEstado" onchange="filterProducts()" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
                <select id="filterTipo" onchange="filterProducts()" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <option value="">Todos los tipos</option>
                    <option value="bot">Bot</option>
                    <option value="sitio">Sitio</option>
                    <option value="servicio">Servicio</option>
                </select>
            </div>
            
            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="productsGrid">
                @forelse($productos as $producto)
                    <div class="product-card bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm dark:shadow-none animate-fade-in-up hover:shadow-lg dark:hover:shadow-none transition-all"
                         data-estado="{{ $producto->estado }}"
                         data-tipo="{{ $producto->tipo }}"
                         data-product-id="{{ $producto->id }}">
                        <!-- Images Carousel -->
                        <div class="relative h-48 bg-slate-100 dark:bg-slate-700 overflow-hidden">
                            @if($producto->fotos && count($producto->fotos) > 0)
                                <div class="carousel-container relative h-full">
                                    @foreach($producto->fotos as $index => $foto)
                                        <img src="{{ asset('storage/' . $foto) }}" 
                                             alt="{{ $producto->nombre }}"
                                             class="carousel-image absolute inset-0 w-full h-full object-cover {{ $index === 0 ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-300"
                                             data-index="{{ $index }}">
                                    @endforeach
                                    @if(count($producto->fotos) > 1)
                                        <button onclick="prevImage(this)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button onclick="nextImage(this)" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                                            @foreach($producto->fotos as $index => $foto)
                                                <div class="carousel-dot w-2 h-2 rounded-full bg-white/50 {{ $index === 0 ? 'bg-white' : '' }}" data-index="{{ $index }}"></div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="flex items-center justify-center h-full text-slate-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $producto->nombre }}</h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <label class="relative inline-flex items-center cursor-pointer" id="toggle-{{ $producto->id }}">
                                            <input type="checkbox" 
                                                   id="toggle-checkbox-{{ $producto->id }}"
                                                   class="sr-only peer" 
                                                   {{ $producto->estado === 'activo' ? 'checked' : '' }}
                                                   onchange="toggleEstado({{ $producto->id }}, this.checked, this)">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-walee-300 dark:peer-focus:ring-walee-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-green-500"></div>
                                            <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300" id="estado-text-{{ $producto->id }}">
                                                {{ $producto->estado === 'activo' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </label>
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                            {{ ucfirst($producto->tipo) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($producto->descripcion)
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 line-clamp-3">{{ $producto->descripcion }}</p>
                            @endif
                            
                            <div class="flex items-center gap-2">
                                <button onclick="editProduct({{ $producto->id }})" class="flex-1 px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-lg transition-all text-sm">
                                    Editar
                                </button>
                                <button onclick="deleteProduct({{ $producto->id }})" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-all text-sm">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400">No hay productos registrados</p>
                        <button onclick="openCreateModal()" class="mt-4 px-6 py-2 bg-walee-500 hover:bg-walee-600 text-white font-medium rounded-xl transition-all">
                            Crear primer producto
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Create/Edit Modal -->
    <div id="productModal" class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white" id="modalTitle">Nuevo Producto</h3>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <form id="productForm" onsubmit="saveProduct(event)" class="p-6 space-y-6">
                <input type="hidden" id="productId" name="id">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre *</label>
                    <input type="text" id="productNombre" name="nombre" required
                           class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción</label>
                    <textarea id="productDescripcion" name="descripcion" rows="4"
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado *</label>
                        <select id="productEstado" name="estado" required
                                class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo *</label>
                        <div class="flex gap-2">
                            <select id="productTipo" name="tipo" required
                                    class="flex-1 px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                <option value="bot">Bot</option>
                                <option value="sitio">Sitio</option>
                                <option value="servicio">Servicio</option>
                            </select>
                            <input type="text" id="productTipoCustom" name="tipo_custom" placeholder="Otro tipo"
                                   class="flex-1 px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500 hidden">
                        </div>
                        <button type="button" onclick="toggleCustomType()" class="mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            Crear tipo personalizado
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fotos (máximo 10)</label>
                    <div class="grid grid-cols-5 gap-4 mb-4" id="photosPreview"></div>
                    <input type="file" id="productFotos" name="fotos[]" multiple accept="image/*" onchange="previewPhotos(this)" class="hidden">
                    <label for="productFotos" class="inline-block px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-xl cursor-pointer transition-all">
                        Seleccionar fotos
                    </label>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Puedes seleccionar hasta 10 imágenes</p>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <button type="button" onclick="closeModal()" class="px-6 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-xl transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2 bg-walee-500 hover:bg-walee-600 text-white font-medium rounded-xl transition-all">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentImageIndex = {};
        let selectedPhotos = [];
        
        // Carousel functions
        function nextImage(btn) {
            const container = btn.closest('.carousel-container');
            const images = container.querySelectorAll('.carousel-image');
            const dots = container.querySelectorAll('.carousel-dot');
            let currentIndex = Array.from(images).findIndex(img => img.classList.contains('opacity-100'));
            let nextIndex = (currentIndex + 1) % images.length;
            
            images[currentIndex].classList.remove('opacity-100');
            images[currentIndex].classList.add('opacity-0');
            images[nextIndex].classList.remove('opacity-0');
            images[nextIndex].classList.add('opacity-100');
            
            dots[currentIndex].classList.remove('bg-white');
            dots[currentIndex].classList.add('bg-white/50');
            dots[nextIndex].classList.remove('bg-white/50');
            dots[nextIndex].classList.add('bg-white');
        }
        
        function prevImage(btn) {
            const container = btn.closest('.carousel-container');
            const images = container.querySelectorAll('.carousel-image');
            const dots = container.querySelectorAll('.carousel-dot');
            let currentIndex = Array.from(images).findIndex(img => img.classList.contains('opacity-100'));
            let prevIndex = (currentIndex - 1 + images.length) % images.length;
            
            images[currentIndex].classList.remove('opacity-100');
            images[currentIndex].classList.add('opacity-0');
            images[prevIndex].classList.remove('opacity-0');
            images[prevIndex].classList.add('opacity-100');
            
            dots[currentIndex].classList.remove('bg-white');
            dots[currentIndex].classList.add('bg-white/50');
            dots[prevIndex].classList.remove('bg-white/50');
            dots[prevIndex].classList.add('bg-white');
        }
        
        // Filter products
        function filterProducts() {
            const estado = document.getElementById('filterEstado').value;
            const tipo = document.getElementById('filterTipo').value;
            const cards = document.querySelectorAll('.product-card');
            
            cards.forEach(card => {
                const cardEstado = card.dataset.estado;
                const cardTipo = card.dataset.tipo;
                
                const matchEstado = !estado || cardEstado === estado;
                const matchTipo = !tipo || cardTipo === tipo;
                
                if (matchEstado && matchTipo) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Modal functions
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Nuevo Producto';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('photosPreview').innerHTML = '';
            selectedPhotos = [];
            document.getElementById('productModal').classList.remove('hidden');
            document.getElementById('productModal').classList.add('flex');
        }
        
        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productModal').classList.remove('flex');
        }
        
        function toggleCustomType() {
            const select = document.getElementById('productTipo');
            const custom = document.getElementById('productTipoCustom');
            
            if (custom.classList.contains('hidden')) {
                custom.classList.remove('hidden');
                select.classList.add('hidden');
                custom.required = true;
                select.required = false;
            } else {
                custom.classList.add('hidden');
                select.classList.remove('hidden');
                custom.required = false;
                select.required = true;
            }
        }
        
        function previewPhotos(input) {
            const files = Array.from(input.files).slice(0, 10);
            selectedPhotos = files;
            const preview = document.getElementById('photosPreview');
            preview.innerHTML = '';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                        <button type="button" onclick="removePhoto(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
        
        function removePhoto(index) {
            selectedPhotos.splice(index, 1);
            const input = document.getElementById('productFotos');
            const dt = new DataTransfer();
            selectedPhotos.forEach(file => dt.items.add(file));
            input.files = dt.files;
            previewPhotos(input);
        }
        
        function removeExistingPhoto(btn) {
            if (confirm('¿Eliminar esta foto?')) {
                btn.closest('div').remove();
            }
        }
        
        async function saveProduct(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('nombre', document.getElementById('productNombre').value);
            formData.append('descripcion', document.getElementById('productDescripcion').value);
            formData.append('estado', document.getElementById('productEstado').value);
            
            const tipoSelect = document.getElementById('productTipo');
            const tipoCustom = document.getElementById('productTipoCustom');
            const tipo = tipoCustom.classList.contains('hidden') ? tipoSelect.value : tipoCustom.value;
            formData.append('tipo', tipo);
            
            const productId = document.getElementById('productId').value;
            if (productId) {
                formData.append('_method', 'PUT');
                
                // Capturar fotos existentes
                const existingFotosInputs = document.querySelectorAll('input[name="existing_fotos[]"]');
                existingFotosInputs.forEach((input, index) => {
                    formData.append(`existing_fotos[${index}]`, input.value);
                });
            }
            
            // Agregar nuevas fotos seleccionadas
            selectedPhotos.forEach((photo, index) => {
                formData.append(`fotos[${index}]`, photo);
            });
            
            try {
                const url = productId 
                    ? `/walee-productos/${productId}`
                    : '/walee-productos';
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                
                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Respuesta no JSON:', text);
                    throw new Error('Error en la respuesta del servidor');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    closeModal();
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo guardar el producto'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar el producto: ' + error.message);
            }
        }
        
        async function editProduct(id) {
            try {
                const response = await fetch(`/walee-productos/${id}`);
                const product = await response.json();
                
                document.getElementById('modalTitle').textContent = 'Editar Producto';
                document.getElementById('productId').value = product.id;
                document.getElementById('productNombre').value = product.nombre;
                document.getElementById('productDescripcion').value = product.descripcion || '';
                document.getElementById('productEstado').value = product.estado;
                
                // Check if tipo is in select options
                const tipoSelect = document.getElementById('productTipo');
                if (['bot', 'sitio', 'servicio'].includes(product.tipo)) {
                    tipoSelect.value = product.tipo;
                    tipoSelect.classList.remove('hidden');
                    document.getElementById('productTipoCustom').classList.add('hidden');
                } else {
                    document.getElementById('productTipoCustom').value = product.tipo;
                    document.getElementById('productTipoCustom').classList.remove('hidden');
                    tipoSelect.classList.add('hidden');
                }
                
                // Load existing photos
                const preview = document.getElementById('photosPreview');
                preview.innerHTML = '';
                if (product.fotos && product.fotos.length > 0) {
                    // Usar fotos_paths si está disponible, sino extraer de las URLs
                    const fotosPaths = product.fotos_paths || [];
                    product.fotos.forEach((fotoUrl, index) => {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        // Si tenemos fotos_paths, usarlo, sino extraer de la URL
                        let fotoPath = fotosPaths[index];
                        if (!fotoPath && fotoUrl) {
                            // Extraer la ruta relativa de la URL completa
                            const match = fotoUrl.match(/\/storage\/(.+)$/);
                            fotoPath = match ? match[1] : fotoUrl;
                        }
                        div.innerHTML = `
                            <img src="${fotoUrl}" class="w-full h-24 object-cover rounded-lg">
                            <button type="button" onclick="removeExistingPhoto(this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <input type="hidden" name="existing_fotos[]" value="${fotoPath || fotoUrl}">
                        `;
                        preview.appendChild(div);
                    });
                }
                
                selectedPhotos = [];
                document.getElementById('productModal').classList.remove('hidden');
                document.getElementById('productModal').classList.add('flex');
            } catch (error) {
                console.error('Error:', error);
                alert('Error al cargar el producto');
            }
        }
        
        async function deleteProduct(id) {
            if (!confirm('¿Estás seguro de eliminar este producto?')) return;
            
            try {
                const response = await fetch(`/walee-productos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar el producto');
            }
        }
        
        async function toggleEstado(id, activo, checkbox) {
            // Guardar el estado original para revertir si falla
            const estadoOriginal = !activo;
            
            try {
                const response = await fetch(`/walee-productos/${id}/toggle-estado`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Actualizar el card
                    const card = document.querySelector(`[data-product-id="${id}"]`);
                    if (card) {
                        card.dataset.estado = data.estado;
                    }
                    
                    // Actualizar el texto del estado
                    const estadoText = document.getElementById(`estado-text-${id}`);
                    if (estadoText) {
                        estadoText.textContent = data.estado === 'activo' ? 'Activo' : 'Inactivo';
                    }
                    
                    // El checkbox ya está en el estado correcto por el onchange
                    // Solo nos aseguramos de que esté sincronizado
                    const toggleCheckbox = document.getElementById(`toggle-checkbox-${id}`);
                    if (toggleCheckbox && toggleCheckbox.checked !== (data.estado === 'activo')) {
                        toggleCheckbox.checked = data.estado === 'activo';
                    }
                } else {
                    // Revertir el checkbox
                    checkbox.checked = estadoOriginal;
                    const toggleCheckbox = document.getElementById(`toggle-checkbox-${id}`);
                    if (toggleCheckbox) {
                        toggleCheckbox.checked = estadoOriginal;
                    }
                    console.error('Error:', data.message);
                    alert('Error: ' + (data.message || 'No se pudo actualizar el estado'));
                }
            } catch (error) {
                console.error('Error:', error);
                // Revertir el checkbox
                checkbox.checked = estadoOriginal;
                const toggleCheckbox = document.getElementById(`toggle-checkbox-${id}`);
                if (toggleCheckbox) {
                    toggleCheckbox.checked = estadoOriginal;
                }
                alert('Error al cambiar el estado. Por favor, recarga la página.');
            }
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

