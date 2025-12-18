<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - {{ $cliente->name }}</title>
    <meta name="description" content="Detalle del cliente">
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
    @php
        $phone = $cliente->phone ?: $cliente->telefono_1 ?: $cliente->telefono_2;
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
    @endphp

    <div class="min-h-screen relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-2xl mx-auto px-4 py-6">
            <!-- Header -->
            <header class="flex items-center justify-between mb-8 animate-fade-in-up">
                <a href="{{ route('walee.clientes.activos') }}" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                
                <a href="{{ route('walee.cliente.editar', $cliente->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="text-sm font-medium">Editar</span>
                </a>
            </header>
            
            <!-- Profile Card -->
            <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="rounded-3xl bg-slate-900/60 border border-slate-800 p-6 mb-6">
                    <!-- Avatar & Name -->
                    <div class="text-center mb-6">
                        @if($cliente->foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($cliente->foto) }}" alt="{{ $cliente->name }}" class="w-24 h-24 rounded-2xl object-cover border-3 border-emerald-500/30 mx-auto mb-4">
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 border-2 border-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl font-bold text-emerald-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <h1 class="text-2xl font-bold text-white">{{ $cliente->name }}</h1>
                        <span class="inline-block mt-2 px-3 py-1 text-xs font-medium bg-emerald-500/20 text-emerald-400 rounded-full border border-emerald-500/30">
                            {{ ucfirst($cliente->estado) }}
                        </span>
                    </div>
                    
                    <!-- WhatsApp Button -->
                    @if($whatsappLink)
                        <a href="{{ $whatsappLink }}" target="_blank" class="flex items-center justify-center gap-3 w-full px-6 py-4 rounded-2xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/30 transition-all mb-6">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span class="text-lg font-medium">Enviar WhatsApp</span>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Info Cards -->
            <div class="space-y-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                @if($cliente->email)
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Email</p>
                                <p class="text-white truncate">{{ $cliente->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($phone)
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Teléfono</p>
                                <p class="text-white">{{ $phone }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($cliente->website)
                    <a href="{{ $cliente->website }}" target="_blank" class="block rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-slate-700 p-4 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Sitio Web</p>
                                <p class="text-white truncate">{{ $cliente->website }}</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                    </a>
                @endif
                
                @if($cliente->address)
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Dirección</p>
                                <p class="text-white">{{ $cliente->address }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($cliente->feedback)
                    <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Feedback</p>
                                <p class="text-white text-sm">{{ $cliente->feedback }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
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

