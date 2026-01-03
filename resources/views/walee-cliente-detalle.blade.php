<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - {{ $cliente->name }}</title>
    <meta name="description" content="Detalle del cliente">
    <meta name="theme-color" content="#D59F3B">
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    @php
        use App\Models\PropuestaPersonalizada;
        
        // Obtener teléfono para WhatsApp (intentar múltiples campos)
        $phone = $cliente->telefono_1 ?: $cliente->telefono_2 ?: $cliente->phone;
        $cleanPhone = $phone ? preg_replace('/[^0-9]/', '', $phone) : null;
        // Si el teléfono no empieza con código de país, agregar código por defecto (ej: 52 para México, 1 para USA)
        if ($cleanPhone && strlen($cleanPhone) == 10 && !str_starts_with($cleanPhone, '1') && !str_starts_with($cleanPhone, '52')) {
            // Asumir código de país por defecto si es necesario
            // $cleanPhone = '52' . $cleanPhone; // Descomentar y ajustar según necesidad
        }
        $whatsappLink = $cleanPhone ? "https://wa.me/{$cleanPhone}" : null;
        
        // Obtener contador de emails enviados
        $emailsEnviados = PropuestaPersonalizada::where('cliente_id', $cliente->id)->count();
        $emailsColor = $emailsEnviados >= 3 ? 'text-red-400' : ($emailsEnviados >= 1 ? 'text-amber-400' : 'text-slate-500');
        $emailsBg = $emailsEnviados >= 3 ? 'bg-red-500/20' : ($emailsEnviados >= 1 ? 'bg-amber-500/20' : 'bg-slate-800/50');
        $emailsBorder = $emailsEnviados >= 3 ? 'border-red-500/30' : ($emailsEnviados >= 1 ? 'border-amber-500/30' : 'border-slate-700');
    @endphp

    <div class="min-h-screen relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-[90rem] mx-auto px-2.5 py-2.5 sm:px-4 sm:py-6">
            @php $pageTitle = $cliente->name; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header Compacto -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 mb-3 sm:mb-6 animate-fade-in-up">
                <div class="flex items-center gap-2 sm:gap-4">
                    @php
                        $fotoPath = $cliente->foto ?? null;
                        $fotoUrl = null;
                        
                        if ($fotoPath) {
                            if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                                // Si es una URL completa, usarla directamente
                                $fotoUrl = $fotoPath;
                            } else {
                                // Extraer el nombre del archivo de la ruta
                                $filename = basename($fotoPath);
                                // Usar la ruta pública para acceder a la foto
                                $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                            }
                        }
                    @endphp
                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="{{ $cliente->name }}" class="w-10 h-10 sm:w-16 sm:h-16 rounded-lg sm:rounded-2xl object-cover border-2 border-emerald-500/30 flex-shrink-0">
                    @else
                        <img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="{{ $cliente->name }}" class="w-10 h-10 sm:w-16 sm:h-16 rounded-lg sm:rounded-2xl object-cover border-2 border-emerald-500/30 flex-shrink-0 opacity-80">
                    @endif
                    <div class="flex-1 min-w-0">
                        <h1 class="text-base sm:text-2xl md:text-3xl font-bold text-slate-900 dark:text-white truncate">{{ $cliente->name }}</h1>
                        <div class="flex items-center gap-1.5 sm:gap-2 mt-0.5 sm:mt-1 flex-wrap">
                            <span class="inline-block px-1.5 py-0.5 sm:px-2.5 sm:py-1 text-[10px] sm:text-xs font-medium bg-emerald-500/20 text-emerald-400 rounded-full border border-emerald-500/30">
                                {{ $cliente->estado === 'accepted' ? 'Activo' : ucfirst($cliente->estado) }}
                            </span>
                            @if($emailsEnviados > 0)
                                <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2.5 sm:py-1 text-[10px] sm:text-xs font-medium {{ $emailsBg }} {{ $emailsColor }} rounded-full border {{ $emailsBorder }}">
                                    <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $emailsEnviados }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <button onclick="openEditClientModal()" class="inline-flex items-center gap-1 sm:gap-2 px-2.5 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all text-[10px] sm:text-sm font-medium">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">Editar</span>
                    </button>
                </div>
            </div>
            
            <!-- Action Buttons Grid -->
            <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-3 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <!-- Email with AI Button -->
                <a href="{{ route('walee.emails.crear') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-center gap-1.5 sm:gap-3 px-2.5 sm:px-4 py-2 sm:py-3.5 rounded-lg sm:rounded-2xl bg-walee-500/20 hover:bg-walee-500/30 text-walee-400 border border-walee-500/30 hover:border-walee-400/50 transition-all group">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 flex-shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span class="text-xs sm:text-base font-medium truncate hidden xs:inline">Email con AI</span>
                    <span class="text-xs font-medium xs:hidden">Email</span>
                </a>
                
                <!-- WhatsApp Button -->
                <a href="{{ $whatsappLink ?: '#' }}" 
                   @if($whatsappLink) target="_blank" @else onclick="event.preventDefault(); showWhatsAppError(); return false;" @endif
                   class="flex items-center justify-center gap-1.5 sm:gap-3 px-2.5 sm:px-4 py-2 sm:py-3.5 rounded-lg sm:rounded-2xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/30 transition-all group {{ !$whatsappLink ? 'opacity-60 cursor-not-allowed' : '' }}">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 flex-shrink-0 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span class="text-xs sm:text-base font-medium truncate hidden xs:inline">WhatsApp</span>
                    <span class="text-xs font-medium xs:hidden">WA</span>
                </a>
            </div>
            
            <!-- Info Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 mb-3 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                @if($cliente->email)
                    <div class="rounded-lg sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-2 sm:p-4 hover:border-blue-400/50 dark:hover:border-blue-500/30 transition-all">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-500 mb-0.5">Email</p>
                                <p class="text-xs sm:text-base text-slate-800 dark:text-white truncate">{{ $cliente->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($phone)
                    <div class="rounded-lg sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-2 sm:p-4 hover:border-green-400/50 dark:hover:border-green-500/30 transition-all">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-500 mb-0.5">Teléfono</p>
                                <p class="text-xs sm:text-base text-slate-800 dark:text-white truncate">{{ $phone }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($cliente->website)
                    <a href="{{ $cliente->website }}" target="_blank" class="block rounded-lg sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-purple-400/50 dark:hover:border-purple-500/30 p-2 sm:p-4 transition-all group">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-500 mb-0.5">Sitio Web</p>
                                <p class="text-xs sm:text-base text-slate-800 dark:text-white truncate group-hover:text-purple-400 transition-colors">{{ $cliente->website }}</p>
                            </div>
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-slate-400 group-hover:text-purple-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                    </a>
                @endif
                
                @if($cliente->address)
                    <div class="rounded-lg sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-2 sm:p-4 hover:border-orange-400/50 dark:hover:border-orange-500/30 transition-all">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-500 mb-0.5">Dirección</p>
                                <p class="text-xs sm:text-base text-slate-800 dark:text-white line-clamp-2">{{ $cliente->address }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($cliente->feedback)
                    <div class="rounded-lg sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-2 sm:p-4 hover:border-cyan-400/50 dark:hover:border-cyan-500/30 transition-all sm:col-span-2 lg:col-span-1">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-cyan-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-500 mb-0.5">Feedback</p>
                                <p class="text-xs sm:text-base text-slate-800 dark:text-white line-clamp-3">{{ $cliente->feedback }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Emails Enviados -->
                <div class="rounded-lg sm:rounded-2xl bg-gradient-to-br from-violet-500/10 to-violet-600/5 border border-violet-500/20 p-2 sm:p-4 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-violet-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1 sm:mb-2">
                                <p class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-400">Emails enviados</p>
                                @if($emailsEnviados >= 3)
                                    <span class="px-1 py-0.5 text-[9px] sm:text-[10px] font-bold bg-red-500/20 text-red-400 rounded-full border border-red-500/30">
                                        ⚠️
                                    </span>
                                @elseif($emailsEnviados >= 1)
                                    <span class="px-1 py-0.5 text-[9px] sm:text-[10px] font-medium bg-amber-500/20 text-amber-400 rounded-full border border-amber-500/30">
                                        ✓
                                    </span>
                                @endif
                            </div>
                            @if($emailsEnviados > 0)
                                <p class="text-lg sm:text-2xl font-bold {{ $emailsColor }} mb-0.5">{{ $emailsEnviados }}</p>
                                <p class="text-[10px] sm:text-xs text-slate-600 dark:text-slate-500">
                                    {{ $emailsEnviados == 1 ? 'email' : 'emails' }} con propuestas
                                </p>
                            @else
                                <p class="text-xs sm:text-base text-slate-800 dark:text-white">Sin emails</p>
                                <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    @if($cliente->estado == 'pending')
                                        Enviados
                                    @elseif($cliente->estado == 'received')
                                        Emails pendientes
                                    @else
                                        Aún no ha recibido propuestas
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Publicaciones Section - Mobile Visible -->
            <div class="mb-3 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.25s;">
                <div class="rounded-lg sm:rounded-2xl bg-gradient-to-br from-violet-500/10 to-violet-600/5 border border-violet-500/20 p-2.5 sm:p-4">
                    <h2 class="text-sm sm:text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-4 flex items-center gap-1.5 sm:gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Publicaciones
                    </h2>
                    <div class="space-y-2 sm:space-y-2.5">
                        @if($clientePlaneadorId)
                            @if($publicacionesProgramadas > 0)
                                <a href="{{ route('walee.planeador.publicidad', $clientePlaneadorId) }}" class="flex items-center justify-between p-2 sm:p-2.5 rounded-lg bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 transition-all group">
                                    <div class="flex items-center gap-2 sm:gap-2.5">
                                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-violet-500/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-violet-400 transition-colors">Programadas</p>
                                            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400">Publicaciones futuras</p>
                                        </div>
                                    </div>
                                    <span class="text-base sm:text-lg font-bold text-violet-600 dark:text-violet-400">{{ $publicacionesProgramadas }}</span>
                                </a>
                            @endif
                            @if($publicacionesPublicadas > 0)
                                <a href="{{ route('walee.planeador.publicidad', $clientePlaneadorId) }}" class="flex items-center justify-between p-2 sm:p-2.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 transition-all group">
                                    <div class="flex items-center gap-2 sm:gap-2.5">
                                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-emerald-400 transition-colors">Publicadas</p>
                                            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400">Ya publicadas</p>
                                        </div>
                                    </div>
                                    <span class="text-base sm:text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $publicacionesPublicadas }}</span>
                                </a>
                            @endif
                            @if($publicacionesProgramadas == 0 && $publicacionesPublicadas == 0)
                                <div class="text-center py-4 sm:py-6">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs sm:text-sm text-slate-800 dark:text-white">Sin publicaciones</p>
                                    <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1">Aún no hay publicaciones programadas</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4 sm:py-6">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-slate-800 dark:text-white">Sin publicaciones</p>
                                <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1">Aún no hay publicaciones programadas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Citas Section -->
            <div class="mb-3 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="rounded-lg sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-2.5 sm:p-4 md:p-6">
                    <h2 class="text-sm sm:text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-4 md:mb-6 flex items-center gap-1.5 sm:gap-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Citas
                    </h2>
                    
                    <!-- Tabs -->
                    <div class="flex gap-1 sm:gap-2 mb-2 sm:mb-4 md:mb-6 border-b border-slate-200 dark:border-slate-700">
                        <button onclick="showCitasTab('pendientes')" id="tab-citas-pendientes" class="tab-button-citas active px-2 py-1 sm:px-4 sm:py-2 text-[10px] sm:text-sm font-medium text-walee-400 border-b-2 border-walee-400">
                            Pendientes ({{ $citasPendientes->count() }})
                        </button>
                        <button onclick="showCitasTab('pasadas')" id="tab-citas-pasadas" class="tab-button-citas px-2 py-1 sm:px-4 sm:py-2 text-[10px] sm:text-sm font-medium text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-walee-400">
                            Pasadas ({{ $citasPasadas->count() }})
                        </button>
                    </div>
                    
                    <!-- Citas Pendientes Tab -->
                    <div id="content-citas-pendientes" class="tab-content-citas">
                        @if($citasPendientes->count() > 0)
                            <div class="space-y-2 sm:space-y-3">
                                @foreach($citasPendientes as $cita)
                                    <div class="flex items-center justify-between p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-walee-400/50 transition-all">
                                        <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-1 min-w-0">
                                            <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-walee-400/20 flex items-center justify-center flex-shrink-0" style="background-color: {{ $cita->color ?? '#8b5cf6' }}20;">
                                                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" style="color: {{ $cita->color ?? '#8b5cf6' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-slate-900 dark:text-white truncate">
                                                    {{ $cita->titulo }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 sm:mt-1">
                                                    @if($cita->fecha_inicio)
                                                        {{ $cita->fecha_inicio->format('d/m/Y H:i') }}
                                                        @if($cita->fecha_fin)
                                                            - {{ $cita->fecha_fin->format('H:i') }}
                                                        @endif
                                                    @endif
                                                    @if($cita->ubicacion)
                                                        · <span class="truncate">{{ $cita->ubicacion }}</span>
                                                    @endif
                                                </p>
                                                @if($cita->descripcion)
                                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 sm:mt-1 line-clamp-1">
                                                        {{ $cita->descripcion }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0 ml-2 sm:ml-4">
                                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-xs font-medium {{ $cita->estado == 'completada' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : ($cita->estado == 'cancelada' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-walee-500/20 text-walee-400 border border-walee-500/30') }}">
                                                {{ ucfirst($cita->estado ?? 'programada') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 sm:py-8">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-400 mx-auto mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">No hay citas pendientes</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Citas Pasadas Tab -->
                    <div id="content-citas-pasadas" class="tab-content-citas hidden">
                        @if($citasPasadas->count() > 0)
                            <div class="space-y-2 sm:space-y-3">
                                @foreach($citasPasadas as $cita)
                                    <div class="flex items-center justify-between p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-slate-400/50 transition-all opacity-75">
                                        <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-1 min-w-0">
                                            <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-slate-400/20 flex items-center justify-center flex-shrink-0" style="background-color: {{ $cita->color ?? '#8b5cf6' }}20;">
                                                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-slate-400" style="color: {{ $cita->color ?? '#8b5cf6' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 truncate">
                                                    {{ $cita->titulo }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 sm:mt-1">
                                                    @if($cita->fecha_inicio)
                                                        {{ $cita->fecha_inicio->format('d/m/Y H:i') }}
                                                        @if($cita->fecha_fin)
                                                            - {{ $cita->fecha_fin->format('H:i') }}
                                                        @endif
                                                    @endif
                                                    @if($cita->ubicacion)
                                                        · <span class="truncate">{{ $cita->ubicacion }}</span>
                                                    @endif
                                                </p>
                                                @if($cita->descripcion)
                                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 sm:mt-1 line-clamp-1">
                                                        {{ $cita->descripcion }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0 ml-2 sm:ml-4">
                                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-xs font-medium {{ $cita->estado == 'completada' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : ($cita->estado == 'cancelada' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-slate-500/20 text-slate-400 border border-slate-500/30') }}">
                                                {{ ucfirst($cita->estado ?? 'programada') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 sm:py-8">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-400 mx-auto mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">No hay citas pasadas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Documentos Section -->
            <div class="mb-3 sm:mb-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="rounded-lg sm:rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-2.5 sm:p-4 md:p-6">
                    <h2 class="text-sm sm:text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-4 md:mb-6 flex items-center gap-1.5 sm:gap-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Documentos
                    </h2>
                    
                    <!-- Tabs -->
                    <div class="flex gap-1 sm:gap-2 mb-2 sm:mb-4 md:mb-6 border-b border-slate-200 dark:border-slate-700 overflow-x-auto">
                        <button onclick="showTab('contratos')" id="tab-contratos" class="tab-button active px-2 py-1 sm:px-4 sm:py-2 text-[10px] sm:text-sm font-medium text-walee-400 border-b-2 border-walee-400 whitespace-nowrap">
                            Contratos ({{ $contratos->count() }})
                        </button>
                        <button onclick="showTab('cotizaciones')" id="tab-cotizaciones" class="tab-button px-2 py-1 sm:px-4 sm:py-2 text-[10px] sm:text-sm font-medium text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-walee-400 whitespace-nowrap">
                            Cotizaciones ({{ $cotizaciones->count() }})
                        </button>
                        <button onclick="showTab('facturas')" id="tab-facturas" class="tab-button px-2 py-1 sm:px-4 sm:py-2 text-[10px] sm:text-sm font-medium text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-walee-400 whitespace-nowrap">
                            Facturas ({{ $facturas->count() }})
                        </button>
                    </div>
                    
                    <!-- Contratos Tab -->
                    <div id="content-contratos" class="tab-content">
                        @if($contratos->count() > 0)
                            <div class="space-y-2 sm:space-y-3">
                                @foreach($contratos as $contrato)
                                    <div class="flex items-center justify-between p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-walee-400/50 transition-all">
                                        <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-1 min-w-0">
                                            <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-walee-400/20 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-slate-900 dark:text-white truncate">
                                                    Contrato - {{ implode(', ', array_map(function($s) { return ucfirst(str_replace('_', ' ', $s)); }, $contrato->servicios ?? [])) }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 sm:mt-1">
                                                    {{ number_format($contrato->precio, 2, ',', '.') }} CRC · {{ strtoupper($contrato->idioma) }} · {{ $contrato->enviada_at->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        @if($contrato->pdf_path)
                                            <a href="/storage/{{ $contrato->pdf_path }}" target="_blank" rel="noopener noreferrer" class="ml-2 sm:ml-4 px-2.5 py-1.5 sm:px-3 sm:py-2 md:px-4 rounded-lg bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all text-xs sm:text-sm font-medium cursor-pointer flex-shrink-0 relative z-10" style="pointer-events: auto; display: inline-block;">
                                                Ver PDF
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-slate-500 dark:text-slate-400">No hay contratos enviados</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Cotizaciones Tab -->
                    <div id="content-cotizaciones" class="tab-content hidden">
                        @if($cotizaciones->count() > 0)
                            <div class="space-y-2 sm:space-y-3">
                                @foreach($cotizaciones as $cotizacion)
                                    <div class="flex items-center justify-between p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400/50 transition-all">
                                        <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-1 min-w-0">
                                            <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-slate-900 dark:text-white truncate">
                                                    {{ $cotizacion->numero_cotizacion ?? 'Cotización #' . $cotizacion->id }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 sm:mt-1">
                                                    {{ number_format($cotizacion->monto, 2, ',', '.') }} CRC · {{ $cotizacion->tipo_servicio ?? 'N/A' }} · {{ $cotizacion->fecha ? $cotizacion->fecha->format('d/m/Y') : $cotizacion->created_at->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0 ml-2 sm:ml-4 relative z-10" style="pointer-events: auto;">
                                            @if($cotizacion->enlace || ($cotizacion->pdf_path ?? false))
                                                <a href="{{ $cotizacion->enlace ?? '/storage/' . ($cotizacion->pdf_path ?? '') }}" target="_blank" rel="noopener noreferrer" class="px-2.5 py-1.5 sm:px-3 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 border border-blue-500/30 transition-all text-xs font-medium cursor-pointer relative z-10" style="pointer-events: auto; display: inline-block;">
                                                    Ver PDF
                                                </a>
                                            @endif
                                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-xs font-medium {{ $cotizacion->estado == 'enviada' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-slate-500/20 text-slate-400 border border-slate-500/30' }}">
                                                {{ ucfirst($cotizacion->estado ?? 'pendiente') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 sm:py-8">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-400 mx-auto mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">No hay cotizaciones</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Facturas Tab -->
                    <div id="content-facturas" class="tab-content hidden">
                        @if($facturas->count() > 0)
                            <div class="space-y-2 sm:space-y-3">
                                @foreach($facturas as $factura)
                                    <div class="flex items-center justify-between p-2.5 sm:p-3 md:p-4 rounded-lg sm:rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-red-400/50 transition-all">
                                        <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-1 min-w-0">
                                            <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-slate-900 dark:text-white truncate">
                                                    {{ $factura->numero_factura ?? 'Factura #' . $factura->id }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 sm:mt-1">
                                                    {{ number_format($factura->total, 2, ',', '.') }} CRC · {{ $factura->fecha_emision ? $factura->fecha_emision->format('d/m/Y') : $factura->created_at->format('d/m/Y') }}
                                                    @if($factura->fecha_vencimiento)
                                                        · Vence: {{ $factura->fecha_vencimiento->format('d/m/Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0 ml-2 sm:ml-4 relative z-10" style="pointer-events: auto;">
                                            @if($factura->enlace || ($factura->pdf_path ?? false))
                                                <a href="{{ $factura->enlace ?? '/storage/' . ($factura->pdf_path ?? '') }}" target="_blank" rel="noopener noreferrer" class="px-2.5 py-1.5 sm:px-3 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 transition-all text-xs font-medium cursor-pointer relative z-10" style="pointer-events: auto; display: inline-block;">
                                                    Ver PDF
                                                </a>
                                            @endif
                                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-xs font-medium {{ $factura->estado == 'pagada' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : ($factura->estado == 'pendiente' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30') }}">
                                                {{ ucfirst($factura->estado ?? 'pendiente') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 sm:py-8">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-400 mx-auto mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">No hay facturas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-3 sm:py-6 md:py-8 mt-3 sm:mt-6">
                <p class="text-[10px] sm:text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
    
    <script>
        function showWhatsAppError() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            Swal.fire({
                icon: 'info',
                title: 'Número no disponible',
                text: 'Este cliente no tiene un número de teléfono registrado. Por favor, edita el cliente para agregar un número de teléfono.',
                confirmButtonText: 'Editar Cliente',
                confirmButtonColor: '#10b981',
                cancelButtonText: 'Cerrar',
                showCancelButton: true,
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    openEditClientModal();
                }
            });
        }
        
        function showCitasTab(tabName) {
            // Hide all citas tab contents
            document.querySelectorAll('.tab-content-citas').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all citas tabs
            document.querySelectorAll('.tab-button-citas').forEach(button => {
                button.classList.remove('active', 'text-walee-400', 'border-walee-400');
                button.classList.add('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            });
            
            // Show selected tab content
            document.getElementById('content-citas-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            const selectedTab = document.getElementById('tab-citas-' + tabName);
            if (selectedTab) {
                selectedTab.classList.add('active', 'text-walee-400', 'border-walee-400');
                selectedTab.classList.remove('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            }
        }
        
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'text-walee-400', 'border-walee-400');
                button.classList.add('text-slate-500', 'dark:text-slate-400', 'border-transparent');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.add('active', 'text-walee-400', 'border-walee-400');
            activeTab.classList.remove('text-slate-500', 'dark:text-slate-400', 'border-transparent');
        }
        
        // Modal para editar cliente
        function openEditClientModal() {
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            const isDesktop = window.innerWidth >= 1024;
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Datos del cliente
            @php
                $fotoPath = $cliente->foto ?? null;
                $fotoUrl = null;
                if ($fotoPath) {
                    if (\Illuminate\Support\Str::startsWith($fotoPath, ['http://', 'https://'])) {
                        $fotoUrl = $fotoPath;
                    } else {
                        $filename = basename($fotoPath);
                        $fotoUrl = route('storage.clientes', ['filename' => $filename]);
                    }
                }
            @endphp
            
            const clienteData = {
                fotoUrl: @json($fotoUrl),
                name: @json($cliente->name ?? ''),
                email: @json($cliente->email ?? ''),
                telefono_1: @json($cliente->telefono_1 ?? ''),
                telefono_2: @json($cliente->telefono_2 ?? ''),
                website: @json($cliente->website ?? ''),
                estado: @json($cliente->estado ?? 'pending'),
                address: @json($cliente->address ?? ''),
                feedback: @json($cliente->feedback ?? ''),
                inicial: @json(strtoupper(substr($cliente->name, 0, 1)))
            };
            
            const html = `
                <form id="editClientForm" class="space-y-2.5 sm:space-y-3 text-left">
                    <!-- Foto -->
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Foto del Cliente</label>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="flex-shrink-0 relative">
                                <div id="fotoPreviewContainer" class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg sm:rounded-xl overflow-hidden border-2 border-emerald-500/30">
                                    ${clienteData.fotoUrl ? 
                                        `<img src="${clienteData.fotoUrl}" alt="Foto" id="fotoPreview" class="w-full h-full object-cover">` :
                                        `<img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="Foto" id="fotoPreview" class="w-full h-full object-cover opacity-80">`
                                    }
                                </div>
                                ${clienteData.fotoUrl ? `
                                    <button type="button" onclick="deleteClientPhoto()" class="absolute -top-1 -right-1 w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all shadow-lg" title="Eliminar foto">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                ` : ''}
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row gap-1.5 sm:gap-2">
                                    <label for="foto_file" class="cursor-pointer inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all text-xs sm:text-sm font-medium">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Cambiar foto</span>
                                    </label>
                                    ${clienteData.fotoUrl ? `
                                        <button type="button" onclick="deleteClientPhoto()" class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 transition-all text-xs sm:text-sm font-medium">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Eliminar</span>
                                        </button>
                                    ` : ''}
                                </div>
                                <input type="file" name="foto_file" id="foto_file" accept="image/*" class="hidden" onchange="previewClientImage(this)">
                                <input type="hidden" name="delete_foto" id="delete_foto" value="0">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">JPG, PNG o GIF. Máx 2MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'sm:grid-cols-2' : ''} gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Nombre *</label>
                            <input type="text" id="clientName" name="name" required value="${clienteData.name}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Email</label>
                            <input type="email" id="clientEmail" name="email" value="${clienteData.email}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Teléfono 1</label>
                            <input type="tel" id="clientTelefono1" name="telefono_1" value="${clienteData.telefono_1}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Teléfono 2</label>
                            <input type="tel" id="clientTelefono2" name="telefono_2" value="${clienteData.telefono_2}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 ${isDesktop ? 'sm:grid-cols-2' : ''} gap-2.5 sm:gap-3">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Sitio Web</label>
                            <input type="url" id="clientWebsite" name="website" value="${clienteData.website}"
                                   class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Estado</label>
                            <select id="clientEstado" name="estado"
                                    class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                                <option value="pending" ${clienteData.estado === 'pending' ? 'selected' : ''}>Pendiente</option>
                                <option value="contactado" ${clienteData.estado === 'contactado' ? 'selected' : ''}>Contactado</option>
                                <option value="propuesta_enviada" ${clienteData.estado === 'propuesta_enviada' ? 'selected' : ''}>Propuesta Enviada</option>
                                <option value="accepted" ${clienteData.estado === 'accepted' ? 'selected' : ''}>Aceptado</option>
                                <option value="activo" ${clienteData.estado === 'activo' ? 'selected' : ''}>Activo</option>
                                <option value="rechazado" ${clienteData.estado === 'rechazado' ? 'selected' : ''}>Rechazado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Dirección</label>
                        <input type="text" id="clientAddress" name="address" value="${clienteData.address}"
                               class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400">
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Feedback / Notas</label>
                        <textarea id="clientFeedback" name="feedback" rows="3"
                                  class="w-full px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-walee-400 resize-none">${clienteData.feedback}</textarea>
                    </div>
                </form>
            `;
            
            let modalWidth = '95%';
            if (isDesktop) {
                modalWidth = '700px';
            } else if (isTablet) {
                modalWidth = '600px';
            } else if (isMobile) {
                modalWidth = '95%';
            }
            
            Swal.fire({
                title: 'Editar Cliente',
                html: html,
                width: modalWidth,
                padding: isMobile ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#10b981',
                cancelButtonColor: isDarkMode ? '#475569' : '#6b7280',
                background: isDarkMode ? '#1e293b' : '#ffffff',
                color: isDarkMode ? '#e2e8f0' : '#1e293b',
                customClass: {
                    popup: isDarkMode ? 'dark-swal' : 'light-swal',
                    title: isDarkMode ? 'dark-swal-title' : 'light-swal-title',
                    htmlContainer: isDarkMode ? 'dark-swal-html' : 'light-swal-html',
                    confirmButton: isDarkMode ? 'dark-swal-confirm' : 'light-swal-confirm',
                    cancelButton: isDarkMode ? 'dark-swal-cancel' : 'light-swal-cancel'
                },
                didOpen: () => {
                    document.getElementById('clientName')?.focus();
                },
                preConfirm: () => {
                    const form = document.getElementById('editClientForm');
                    const formData = new FormData(form);
                    
                    // Validar nombre requerido
                    if (!formData.get('name') || formData.get('name').trim() === '') {
                        Swal.showValidationMessage('El nombre es requerido');
                        return false;
                    }
                    
                    return formData;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    updateClient(result.value);
                }
            });
        }
        
        function previewClientImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.getElementById('fotoPreviewContainer');
                    container.innerHTML = `<img src="${e.target.result}" alt="Preview" id="fotoPreview" class="w-full h-full object-cover">`;
                    
                    // Resetear el flag de eliminación si se sube una nueva foto
                    const deleteFotoInput = document.getElementById('delete_foto');
                    if (deleteFotoInput) {
                        deleteFotoInput.value = '0';
                    }
                    
                    // Ocultar botones de eliminar si existen
                    const deleteButtons = container.parentElement.querySelectorAll('button[onclick="deleteClientPhoto()"]');
                    deleteButtons.forEach(btn => btn.style.display = 'none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function deleteClientPhoto() {
            const container = document.getElementById('fotoPreviewContainer');
            const deleteFotoInput = document.getElementById('delete_foto');
            const fotoFileInput = document.getElementById('foto_file');
            
            // Mostrar imagen genérica
            container.innerHTML = `<img src="https://images.icon-icons.com/1188/PNG/512/1490201150-client_82317.png" alt="Foto" id="fotoPreview" class="w-full h-full object-cover opacity-80">`;
            
            // Marcar para eliminar
            if (deleteFotoInput) {
                deleteFotoInput.value = '1';
            }
            
            // Limpiar input de archivo
            if (fotoFileInput) {
                fotoFileInput.value = '';
            }
            
            // Ocultar botones de eliminar
            const deleteButtons = container.parentElement.querySelectorAll('button[onclick="deleteClientPhoto()"]');
            deleteButtons.forEach(btn => btn.style.display = 'none');
        }
        
        async function updateClient(formData) {
            try {
                const response = await fetch('{{ route("walee.cliente.actualizar", $cliente->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente actualizado!',
                        text: 'Los cambios se han guardado correctamente',
                        confirmButtonColor: '#10b981',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Error al actualizar el cliente',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión. Por favor, intenta de nuevo.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    </script>
</body>
</html>

