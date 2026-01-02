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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        // Modal functions with SweetAlert
        function openCreateModal() {
            selectedPhotos = [];
            showProductModal();
        }
        
        function showProductModal(product = null) {
            const isEdit = product !== null;
            const html = `
                <form id="productForm" class="space-y-4 text-left">
                    <input type="hidden" id="productId" name="id" value="${product?.id || ''}">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre *</label>
                        <input type="text" id="productNombre" name="nombre" required
                               value="${product?.nombre || ''}"
                               class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descripción</label>
                        <textarea id="productDescripcion" name="descripcion" rows="3"
                                  class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">${product?.descripcion || ''}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Estado *</label>
                            <select id="productEstado" name="estado" required
                                    class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                <option value="activo" ${product?.estado === 'activo' ? 'selected' : ''}>Activo</option>
                                <option value="inactivo" ${product?.estado === 'inactivo' ? 'selected' : ''}>Inactivo</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo *</label>
                            <div class="flex gap-2">
                                <select id="productTipo" name="tipo" required
                                        class="flex-1 px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500">
                                    <option value="bot" ${product?.tipo === 'bot' ? 'selected' : ''}>Bot</option>
                                    <option value="sitio" ${product?.tipo === 'sitio' ? 'selected' : ''}>Sitio</option>
                                    <option value="servicio" ${product?.tipo === 'servicio' ? 'selected' : ''}>Servicio</option>
                                </select>
                                <input type="text" id="productTipoCustom" name="tipo_custom" placeholder="Otro tipo"
                                       value="${product?.tipo && !['bot', 'sitio', 'servicio'].includes(product.tipo) ? product.tipo : ''}"
                                       class="flex-1 px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-500 ${product?.tipo && !['bot', 'sitio', 'servicio'].includes(product.tipo) ? '' : 'hidden'}">
                            </div>
                            <button type="button" onclick="toggleCustomTypeSwal()" class="mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                Crear tipo personalizado
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fotos (máximo 10)</label>
                        <div class="grid grid-cols-5 gap-2 mb-2 max-h-32 overflow-y-auto" id="photosPreviewSwal"></div>
                        <input type="file" id="productFotosSwal" name="fotos[]" multiple accept="image/*" onchange="previewPhotosSwal(this)" class="hidden">
                        <label for="productFotosSwal" class="inline-block px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white font-medium rounded-xl cursor-pointer transition-all text-sm">
                            Seleccionar fotos
                        </label>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Puedes seleccionar hasta 10 imágenes</p>
                    </div>
                </form>
            `;
            
            Swal.fire({
                title: isEdit ? 'Editar Producto' : 'Nuevo Producto',
                html: html,
                width: '600px',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#D59F3B',
                cancelButtonColor: '#6b7280',
                didOpen: () => {
                    // Cargar fotos existentes si es edición
                    if (isEdit && product.fotos && product.fotos.length > 0) {
                        const preview = document.getElementById('photosPreviewSwal');
                        preview.innerHTML = '';
                        const fotosPaths = product.fotos_paths || [];
                        product.fotos.forEach((fotoUrl, index) => {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            let fotoPath = fotosPaths[index];
                            if (!fotoPath && fotoUrl) {
                                const match = fotoUrl.match(/\/storage\/(.+)$/);
                                fotoPath = match ? match[1] : fotoUrl;
                            }
                            div.innerHTML = `
                                <img src="${fotoUrl}" class="w-full h-16 object-cover rounded-lg">
                                <button type="button" onclick="removeExistingPhotoSwal(this)" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="existing_fotos[]" value="${fotoPath || fotoUrl}">
                            `;
                            preview.appendChild(div);
                        });
                    }
                },
                preConfirm: () => {
                    return saveProductSwal(isEdit ? product.id : null);
                }
            });
        }
        
        function toggleCustomTypeSwal() {
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
        
        function previewPhotosSwal(input) {
            const files = Array.from(input.files).slice(0, 10);
            selectedPhotos = files;
            const preview = document.getElementById('photosPreviewSwal');
            preview.innerHTML = '';
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-16 object-cover rounded-lg">
                        <button type="button" onclick="removePhotoSwal(${index})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
        
        function removePhotoSwal(index) {
            selectedPhotos.splice(index, 1);
            const input = document.getElementById('productFotosSwal');
            const dt = new DataTransfer();
            selectedPhotos.forEach(file => dt.items.add(file));
            input.files = dt.files;
            previewPhotosSwal(input);
        }
        
        function removeExistingPhotoSwal(btn) {
            btn.closest('div').remove();
        }
        
        async function saveProductSwal(productId) {
            const formData = new FormData();
            formData.append('nombre', document.getElementById('productNombre').value);
            formData.append('descripcion', document.getElementById('productDescripcion').value);
            formData.append('estado', document.getElementById('productEstado').value);
            
            const tipoSelect = document.getElementById('productTipo');
            const tipoCustom = document.getElementById('productTipoCustom');
            const tipo = tipoCustom.classList.contains('hidden') ? tipoSelect.value : tipoCustom.value;
            formData.append('tipo', tipo);
            
            if (productId) {
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
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const responseText = await response.text();
                let data;
                
                try {
                    data = JSON.parse(responseText);
                } catch (parseError) {
                    throw new Error('El servidor devolvió una respuesta inválida');
                }
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message || 'Producto guardado correctamente',
                        confirmButtonColor: '#D59F3B'
                    }).then(() => {
                        location.reload();
                    });
                    return true;
                } else {
                    let errorMessage = data.message || 'No se pudo guardar el producto';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join(', ');
                        errorMessage += ': ' + errorList;
                    }
                    Swal.showValidationMessage(errorMessage);
                    return false;
                }
            } catch (error) {
                Swal.showValidationMessage('Error: ' + error.message);
                return false;
            }
        }
        
        
        async function editProduct(id) {
            try {
                const response = await fetch(`/walee-productos/${id}`);
                const product = await response.json();
                selectedPhotos = [];
                showProductModal(product);
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar el producto',
                    confirmButtonColor: '#D59F3B'
                });
            }
        }
        
        async function deleteProduct(id) {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });
            
            if (!result.isConfirmed) return;
            
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
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El producto ha sido eliminado',
                        confirmButtonColor: '#D59F3B'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo eliminar el producto',
                        confirmButtonColor: '#D59F3B'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al eliminar el producto',
                    confirmButtonColor: '#D59F3B'
                });
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
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Estado actualizado',
                            text: 'El estado ha sido cambiado a ' + (data.estado === 'activo' ? 'Activo' : 'Inactivo'),
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        // Revertir el checkbox
                        checkbox.checked = estadoOriginal;
                        const toggleCheckbox = document.getElementById(`toggle-checkbox-${id}`);
                        if (toggleCheckbox) {
                            toggleCheckbox.checked = estadoOriginal;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo actualizar el estado',
                            confirmButtonColor: '#D59F3B'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // Revertir el checkbox
                    checkbox.checked = estadoOriginal;
                    const toggleCheckbox = document.getElementById(`toggle-checkbox-${id}`);
                    if (toggleCheckbox) {
                        toggleCheckbox.checked = estadoOriginal;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cambiar el estado. Por favor, recarga la página.',
                        confirmButtonColor: '#D59F3B'
                    });
                }
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

