<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Contract Builder</title>
    <meta name="description" content="Contract Builder">
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
        
        /* Custom select styling for better dark mode support */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25em 1.25em;
        }
        
        select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239f7aea'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        }
        
        .dark select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        }
        
        .dark select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23a78bfa'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        }
        
        /* Option styling for better dark mode */
        option {
            background-color: white;
            color: #0f172a;
        }
        
        .dark option {
            background-color: #1e293b;
            color: #f1f5f9;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-violet-400/20 dark:bg-violet-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Contract Builder'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Notifications -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-xl text-emerald-800 dark:text-emerald-200 animate-fade-in-up">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-xl text-red-800 dark:text-red-200 animate-fade-in-up">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            
            <!-- Form -->
            <form method="POST" action="{{ route('walee.herramientas.enviar-contrato.post') }}" enctype="multipart/form-data" class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Contract Builder</h1>
                </div>
                
                <!-- Grid de campos: 1 columna en móvil, 2 columnas en md y lg -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Supplier Section -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Supplier
                        </h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Supplier <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="proveedor_id" name="proveedor_id" required class="appearance-none w-full px-4 py-3 pr-10 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-violet-500 dark:focus:border-violet-400 focus:ring-2 focus:ring-violet-500/20 dark:focus:ring-violet-400/20 focus:outline-none transition-all hover:border-slate-400 dark:hover:border-slate-500 cursor-pointer @error('proveedor_id') border-red-500 dark:border-red-500 @enderror">
                                    <option value="" class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white">Select supplier...</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" data-email="{{ $proveedor->email }}" class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white py-2" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->name }}
                                            @if($proveedor->idioma)
                                                @php
                                                    $idiomas = [
                                                        'es' => '🇪🇸',
                                                        'en' => '🇬🇧',
                                                        'fr' => '🇫🇷',
                                                        'de' => '🇩🇪',
                                                        'it' => '🇮🇹',
                                                        'pt' => '🇵🇹'
                                                    ];
                                                    echo ' ' . ($idiomas[$proveedor->idioma] ?? strtoupper($proveedor->idioma));
                                                @endphp
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('proveedor_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            
                            <!-- Email del proveedor seleccionado -->
                            <div id="proveedor-email-display" class="mt-3 p-3 rounded-lg bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-800 hidden">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Email:</span>
                                    <span id="proveedor-email-text" class="text-sm font-medium text-violet-700 dark:text-violet-300"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Services Section -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Services
                        </h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Select Services <span class="text-red-500">*</span></label>
                            <div class="space-y-3" id="services-list">
                                @foreach($servicios as $servicio)
                                    <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/50 cursor-pointer transition-all">
                                        <input type="checkbox" name="servicios[]" value="{{ $servicio->codigo }}" {{ in_array($servicio->codigo, old('servicios', [])) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500 focus:ring-2">
                                        <div class="flex-1">
                                            <span class="text-slate-900 dark:text-white">{{ $servicio->nombre }}</span>
                                            @if($servicio->descripcion)
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ Str::limit($servicio->descripcion, 80) }}</p>
                                            @endif
                                        </div>
                                        @if($servicio->tipo === 'personalizado')
                                            <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded">Custom</span>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                            
                            <!-- Custom Services Section -->
                            <div id="custom-services-container" class="mt-4 space-y-3"></div>
                            
                            <!-- Add Custom Service Button -->
                            <button type="button" id="add-custom-service" class="mt-3 w-full px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-700 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                + Add Custom Service
                            </button>
                            
                            @error('servicios')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('servicios.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('servicios_personalizados.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Price Section -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Price
                        </h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Price (USD) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 dark:text-slate-400">$</span>
                                <input type="number" id="precio" name="precio" step="0.01" min="0" value="{{ old('precio') }}" required placeholder="0.00" class="w-full pl-8 pr-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-all @error('precio') border-red-500 @enderror">
                            </div>
                            @error('precio')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Language Section -->
                    <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            Contract Language
                        </h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Language <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="idioma" name="idioma" required class="appearance-none w-full px-4 py-3 pr-10 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-amber-500 dark:focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 dark:focus:ring-amber-400/20 focus:outline-none transition-all hover:border-slate-400 dark:hover:border-slate-500 cursor-pointer @error('idioma') border-red-500 dark:border-red-500 @enderror">
                                    <option value="" class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white">Select language...</option>
                                    <option value="es" class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white py-2" {{ old('idioma') == 'es' ? 'selected' : '' }}>🇪🇸 Spanish</option>
                                    <option value="en" class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white py-2" {{ old('idioma') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                    <option value="fr" class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white py-2" {{ old('idioma') == 'fr' ? 'selected' : '' }}>🇫🇷 French</option>
                                    <option value="zh" class="bg-white dark:bg-slate-800 text-slate-900 dark:text-white py-2" {{ old('idioma') == 'zh' ? 'selected' : '' }}>🇨🇳 Chinese (Mandarin)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('idioma')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Attachments Section -->
                <div class="bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm dark:shadow-none">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        Attachments (Optional)
                    </h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Select Files</label>
                        <input type="file" id="archivos" name="archivos[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all @error('archivos') border-red-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Allowed formats: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, ZIP, RAR</p>
                        @error('archivos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('archivos.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        
                        <!-- Lista de archivos seleccionados -->
                        <div id="archivos-lista" class="mt-3 space-y-2 hidden">
                            <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Selected files:</p>
                            <div id="archivos-nombres" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4">
                    <a href="{{ route('walee.dashboard') }}" class="flex-1 px-6 py-3 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-medium hover:bg-slate-300 dark:hover:bg-slate-600 transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-walee-500 hover:bg-walee-600 text-white rounded-xl font-medium transition-all shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                        Send Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- World Map with Clocks -->
    @include('partials.walee-world-map-clocks')
    
    @include('partials.walee-support-button')
    
    <script>
        let customServiceIndex = 0;
        
        // Mostrar email del proveedor seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            const proveedorSelect = document.getElementById('proveedor_id');
            const emailDisplay = document.getElementById('proveedor-email-display');
            const emailText = document.getElementById('proveedor-email-text');
            
            function updateEmailDisplay() {
                const selectedOption = proveedorSelect.options[proveedorSelect.selectedIndex];
                const email = selectedOption.getAttribute('data-email');
                
                if (email && proveedorSelect.value) {
                    emailText.textContent = email;
                    emailDisplay.classList.remove('hidden');
                } else {
                    emailDisplay.classList.add('hidden');
                }
            }
            
            // Mostrar email si hay un valor seleccionado (por ejemplo, después de un error de validación)
            updateEmailDisplay();
            
            // Actualizar cuando cambie la selección
            proveedorSelect.addEventListener('change', updateEmailDisplay);
            
            // Add Custom Service functionality
            const addCustomServiceBtn = document.getElementById('add-custom-service');
            const customServicesContainer = document.getElementById('custom-services-container');
            
            addCustomServiceBtn.addEventListener('click', function() {
                const serviceId = 'custom_service_' + customServiceIndex++;
                const serviceDiv = document.createElement('div');
                serviceDiv.className = 'flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg';
                serviceDiv.innerHTML = `
                    <input type="checkbox" name="servicios[]" value="${serviceId}" checked class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500 focus:ring-2">
                    <input type="text" name="servicios_personalizados[]" placeholder="Enter custom service name" required class="flex-1 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                    <button type="button" class="remove-custom-service p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                customServicesContainer.appendChild(serviceDiv);
                
                // Add remove functionality
                serviceDiv.querySelector('.remove-custom-service').addEventListener('click', function() {
                    serviceDiv.remove();
                });
            });
            
            // Mostrar lista de archivos seleccionados
            const archivosInput = document.getElementById('archivos');
            const archivosLista = document.getElementById('archivos-lista');
            const archivosNombres = document.getElementById('archivos-nombres');
            
            archivosInput.addEventListener('change', function() {
                const files = Array.from(this.files);
                
                if (files.length > 0) {
                    archivosNombres.innerHTML = '';
                    files.forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center gap-2 p-2 rounded-lg bg-slate-100 dark:bg-slate-800/50 text-xs';
                        fileItem.innerHTML = `
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="flex-1 text-slate-700 dark:text-slate-300">${file.name}</span>
                            <span class="text-slate-500 dark:text-slate-400">${(file.size / 1024).toFixed(2)} KB</span>
                        `;
                        archivosNombres.appendChild(fileItem);
                    });
                    archivosLista.classList.remove('hidden');
                } else {
                    archivosLista.classList.add('hidden');
                }
            });
        });
        
        // Dark/Light Mode Toggle
        function initDarkMode() {
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                html.classList.add('dark');
                updateIcons(true);
            } else {
                html.classList.remove('dark');
                updateIcons(false);
            }
        }
        
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                updateIcons(false);
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                updateIcons(true);
            }
        }
        
        function updateIcons(isDark) {
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            
            if (isDark) {
                sunIcon?.classList.add('hidden');
                moonIcon?.classList.remove('hidden');
            } else {
                sunIcon?.classList.remove('hidden');
                moonIcon?.classList.add('hidden');
            }
        }
        
        // Initialize on page load
        initDarkMode();
    </script>
</body>
</html>
