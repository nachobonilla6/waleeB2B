<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Editar {{ $cliente->name }}</title>
    <meta name="description" content="Editar cliente">
    <meta name="theme-color" content="#D59F3B">
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
                            400: '#D59F3B',
                            500: '#C78F2E',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 min-h-screen">
    <div class="min-h-screen relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-[90rem] mx-auto px-4 py-6">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 flex items-center justify-center transition-all">
                        <svg class="w-5 h-5 text-slate-700 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-slate-800 dark:text-white">Editar Cliente</h1>
                </div>
                <div class="flex items-center gap-2">
                    @include('partials.walee-dark-mode-toggle')
                </div>
            </header>
            
            <!-- Form -->
            <form action="{{ route('walee.cliente.actualizar', $cliente->id) }}" method="POST" enctype="multipart/form-data" class="animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Foto -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-3">Foto del Cliente</label>
                        <div class="flex items-center gap-4">
                            <!-- Foto actual -->
                            <div class="flex-shrink-0">
                                @if($cliente->foto)
                                    <img src="/storage/{{ $cliente->foto }}" alt="{{ $cliente->name }}" id="fotoPreview" class="w-20 h-20 rounded-xl object-cover border-2 border-emerald-500/30">
                                @else
                                    <div id="fotoPreview" class="w-20 h-20 rounded-xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 border-2 border-emerald-500/20 flex items-center justify-center">
                                        <span class="text-2xl font-bold text-emerald-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Upload button -->
                            <div class="flex-1">
                                <label for="foto_file" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Cambiar foto</span>
                                </label>
                                <input type="file" name="foto_file" id="foto_file" accept="image/*" class="hidden" onchange="previewImage(this)">
                                <p class="text-xs text-slate-500 mt-2">JPG, PNG o GIF. Máx 2MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nombre -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name', $cliente->name) }}" required
                            class="w-full bg-transparent text-white text-lg border-none outline-none placeholder-slate-600"
                            placeholder="Nombre del cliente">
                    </div>
                    
                    <!-- Email -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $cliente->email) }}"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600"
                            placeholder="correo@ejemplo.com">
                    </div>
                    
                    <!-- Teléfono 1 -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Teléfono 1</label>
                        <input type="text" name="telefono_1" value="{{ old('telefono_1', $cliente->telefono_1) }}"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600"
                            placeholder="+506 8888-8888">
                    </div>
                    
                    <!-- Teléfono 2 -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Teléfono 2</label>
                        <input type="text" name="telefono_2" value="{{ old('telefono_2', $cliente->telefono_2) }}"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600"
                            placeholder="+506 8888-8888">
                    </div>
                    
                    <!-- Website -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Sitio Web</label>
                        <input type="url" name="website" value="{{ old('website', $cliente->website) }}"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600"
                            placeholder="https://ejemplo.com">
                    </div>
                    
                    <!-- Dirección -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Dirección</label>
                        <input type="text" name="address" value="{{ old('address', $cliente->address) }}"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600"
                            placeholder="Dirección del cliente">
                    </div>
                    
                    <!-- Estado -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Estado</label>
                        <select name="estado" class="w-full bg-transparent text-white border-none outline-none">
                            <option value="nuevo" {{ $cliente->estado == 'nuevo' ? 'selected' : '' }} class="bg-slate-900">Nuevo</option>
                            <option value="contactado" {{ $cliente->estado == 'contactado' ? 'selected' : '' }} class="bg-slate-900">Contactado</option>
                            <option value="propuesta_enviada" {{ $cliente->estado == 'propuesta_enviada' ? 'selected' : '' }} class="bg-slate-900">Propuesta Enviada</option>
                            <option value="accepted" {{ $cliente->estado == 'accepted' ? 'selected' : '' }} class="bg-slate-900">Aceptado</option>
                            <option value="rechazado" {{ $cliente->estado == 'rechazado' ? 'selected' : '' }} class="bg-slate-900">Rechazado</option>
                        </select>
                    </div>
                    
                    <!-- Feedback -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Feedback / Notas</label>
                        <textarea name="feedback" rows="3"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600 resize-none"
                            placeholder="Notas sobre el cliente...">{{ old('feedback', $cliente->feedback) }}</textarea>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="flex-1 px-6 py-4 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-center font-medium transition-all">
                        Cancelar
                    </a>
                    <button type="submit" class="flex-1 px-6 py-4 rounded-2xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/30 font-medium transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-6">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · wesolutions.work
                </p>
            </footer>
        </div>
    </div>
<script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('fotoPreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.id = 'fotoPreview';
                        img.className = 'w-20 h-20 rounded-xl object-cover border-2 border-emerald-500/30';
                        img.alt = 'Preview';
                        preview.parentNode.replaceChild(img, preview);
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @include('partials.walee-support-button')
</body>
</html>

