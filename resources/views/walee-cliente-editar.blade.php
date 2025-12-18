<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Editar {{ $cliente->name }}</title>
    <meta name="description" content="Editar cliente">
    <meta name="theme-color" content="#D59F3B">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
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
<body class="bg-slate-950 text-white min-h-screen">
    <div class="min-h-screen relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-emerald-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-2xl mx-auto px-4 py-6">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <div class="flex items-center gap-4">
                    <a href="{{ route('walee.cliente.detalle', $cliente->id) }}" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center transition-all">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-white">Editar Cliente</h1>
                </div>
            </header>
            
            <!-- Form -->
            <form action="{{ route('walee.cliente.actualizar', $cliente->id) }}" method="POST" class="animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
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
                    
                    <!-- Teléfono -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $cliente->phone) }}"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600"
                            placeholder="+506 8888-8888">
                    </div>
                    
                    <!-- Teléfono 2 -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">Teléfono Alternativo</label>
                        <input type="text" name="telefono_1" value="{{ old('telefono_1', $cliente->telefono_1) }}"
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
                    
                    <!-- Foto URL -->
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <label class="block text-xs text-slate-500 mb-2">URL de Foto</label>
                        <input type="url" name="foto" value="{{ old('foto', $cliente->foto) }}"
                            class="w-full bg-transparent text-white border-none outline-none placeholder-slate-600"
                            placeholder="https://ejemplo.com/foto.jpg">
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
</body>
</html>

