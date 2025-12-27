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
        
        $phone = $cliente->phone ?: $cliente->telefono_1 ?: $cliente->telefono_2;
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
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
        
        <div class="relative max-w-[90rem] mx-auto px-4 py-6">
            @php $pageTitle = $cliente->name; @endphp
            @include('partials.walee-navbar')
            
            <div class="flex items-center justify-end gap-2 mb-6">
                <a href="{{ route('walee.cliente.editar', $cliente->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="text-sm font-medium">Editar</span>
                </a>
            </div>
            
            <!-- Profile Card -->
            <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-6 mb-6">
                    <!-- Avatar & Name -->
                    <div class="text-center mb-6">
                        @if($cliente->foto)
                            <img src="/storage/{{ $cliente->foto }}" alt="{{ $cliente->name }}" class="w-24 h-24 rounded-2xl object-cover border-3 border-emerald-500/30 mx-auto mb-4">
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 border-2 border-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                                <span class="text-3xl font-bold text-emerald-400">{{ strtoupper(substr($cliente->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $cliente->name }}</h1>
                        <div class="flex items-center justify-center gap-2 mt-2">
                            <span class="inline-block px-3 py-1 text-xs font-medium bg-emerald-500/20 text-emerald-400 rounded-full border border-emerald-500/30">
                                {{ $cliente->estado === 'accepted' ? 'Activo' : ucfirst($cliente->estado) }}
                            </span>
                            @if($emailsEnviados > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium {{ $emailsBg }} {{ $emailsColor }} rounded-full border {{ $emailsBorder }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $emailsEnviados }} {{ $emailsEnviados == 1 ? 'email' : 'emails' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <!-- Email with AI Button -->
                        <a href="{{ route('walee.emails.crear') }}?cliente_id={{ $cliente->id }}" class="flex items-center justify-center gap-3 w-full px-6 py-4 rounded-2xl bg-walee-500/20 hover:bg-walee-500/30 text-walee-400 border border-walee-500/30 hover:border-walee-400/50 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span class="text-lg font-medium">Crear Email con AI</span>
                        </a>
                        
                        <!-- WhatsApp Button -->
                        @if($whatsappLink)
                            <a href="{{ $whatsappLink }}" target="_blank" class="flex items-center justify-center gap-3 w-full px-6 py-4 rounded-2xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/30 transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span class="text-lg font-medium">Enviar WhatsApp</span>
                            </a>
                        @endif
                        
                        <!-- Facebook Button -->
                        <a href="{{ route('walee.cliente.settings', $cliente->id) }}" class="flex items-center justify-center gap-3 w-full px-6 py-4 rounded-2xl bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 border border-blue-500/30 transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="text-lg font-medium">Manejar Facebook</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Info Cards -->
            <div class="space-y-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                @if($cliente->email)
                    <div class="rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-600 dark:text-slate-500 mb-1">Email</p>
                                <p class="text-slate-800 dark:text-white truncate">{{ $cliente->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($phone)
                    <div class="rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Teléfono</p>
                                <p class="text-slate-800 dark:text-white">{{ $phone }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($cliente->website)
                    <a href="{{ $cliente->website }}" target="_blank" class="block rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 p-4 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Sitio Web</p>
                                <p class="text-slate-800 dark:text-white truncate">{{ $cliente->website }}</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                    </a>
                @endif
                
                @if($cliente->address)
                    <div class="rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Dirección</p>
                                <p class="text-slate-800 dark:text-white">{{ $cliente->address }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($cliente->feedback)
                    <div class="rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-4">
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
                
                <!-- Emails Enviados -->
                <div class="rounded-2xl bg-gradient-to-br from-violet-500/10 to-violet-600/5 border border-violet-500/20 p-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-violet-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs text-slate-600 dark:text-slate-400">Emails enviados</p>
                                @if($emailsEnviados >= 3)
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-red-500/20 text-red-400 rounded-full border border-red-500/30">
                                        ⚠️ Múltiples envíos
                                    </span>
                                @elseif($emailsEnviados >= 1)
                                    <span class="px-2 py-0.5 text-[10px] font-medium bg-amber-500/20 text-amber-400 rounded-full border border-amber-500/30">
                                        ✓ Contactado
                                    </span>
                                @endif
                            </div>
                            @if($emailsEnviados > 0)
                                <p class="text-2xl font-bold {{ $emailsColor }} mb-1">{{ $emailsEnviados }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-500">
                                    {{ $emailsEnviados == 1 ? 'email enviado' : 'emails enviados' }} con propuestas
                                </p>
                            @else
                                <p class="text-white text-sm">Sin emails enviados</p>
                                <p class="text-xs text-slate-500 mt-1">Este cliente aún no ha recibido propuestas</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Documentos Section -->
            <div class="mt-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="rounded-3xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 p-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Documentos
                    </h2>
                    
                    <!-- Tabs -->
                    <div class="flex gap-2 mb-6 border-b border-slate-200 dark:border-slate-700">
                        <button onclick="showTab('contratos')" id="tab-contratos" class="tab-button active px-4 py-2 text-sm font-medium text-walee-400 border-b-2 border-walee-400">
                            Contratos ({{ $contratos->count() }})
                        </button>
                        <button onclick="showTab('cotizaciones')" id="tab-cotizaciones" class="tab-button px-4 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-walee-400">
                            Cotizaciones ({{ $cotizaciones->count() }})
                        </button>
                        <button onclick="showTab('facturas')" id="tab-facturas" class="tab-button px-4 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-walee-400">
                            Facturas ({{ $facturas->count() }})
                        </button>
                    </div>
                    
                    <!-- Contratos Tab -->
                    <div id="content-contratos" class="tab-content">
                        @if($contratos->count() > 0)
                            <div class="space-y-3">
                                @foreach($contratos as $contrato)
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-walee-400/50 transition-all">
                                        <div class="flex items-center gap-4 flex-1">
                                            <div class="w-10 h-10 rounded-lg bg-walee-400/20 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                                    Contrato - {{ implode(', ', array_map(function($s) { return ucfirst(str_replace('_', ' ', $s)); }, $contrato->servicios ?? [])) }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                    {{ number_format($contrato->precio, 2, ',', '.') }} CRC · {{ strtoupper($contrato->idioma) }} · {{ $contrato->enviada_at->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        @if($contrato->pdf_path)
                                            <a href="/storage/{{ $contrato->pdf_path }}" target="_blank" class="px-4 py-2 rounded-lg bg-walee-400/20 hover:bg-walee-400/30 text-walee-400 border border-walee-400/30 transition-all text-sm font-medium">
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
                            <div class="space-y-3">
                                @foreach($cotizaciones as $cotizacion)
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-400/50 transition-all">
                                        <div class="flex items-center gap-4 flex-1">
                                            <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $cotizacion->numero_cotizacion ?? 'Cotización #' . $cotizacion->id }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                    {{ number_format($cotizacion->monto, 2, ',', '.') }} CRC · {{ $cotizacion->tipo_servicio ?? 'N/A' }} · {{ $cotizacion->fecha ? $cotizacion->fecha->format('d/m/Y') : $cotizacion->created_at->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($cotizacion->enlace || $cotizacion->pdf_path ?? false)
                                                <a href="{{ $cotizacion->enlace ?? '/storage/' . ($cotizacion->pdf_path ?? '') }}" target="_blank" class="px-3 py-1 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 border border-blue-500/30 transition-all text-xs font-medium">
                                                    Ver PDF
                                                </a>
                                            @endif
                                            <span class="px-3 py-1 rounded-lg text-xs font-medium {{ $cotizacion->estado == 'enviada' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-slate-500/20 text-slate-400 border border-slate-500/30' }}">
                                                {{ ucfirst($cotizacion->estado ?? 'pendiente') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-slate-500 dark:text-slate-400">No hay cotizaciones</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Facturas Tab -->
                    <div id="content-facturas" class="tab-content hidden">
                        @if($facturas->count() > 0)
                            <div class="space-y-3">
                                @foreach($facturas as $factura)
                                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-red-400/50 transition-all">
                                        <div class="flex items-center gap-4 flex-1">
                                            <div class="w-10 h-10 rounded-lg bg-red-500/20 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                                    {{ $factura->numero_factura ?? 'Factura #' . $factura->id }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                    {{ number_format($factura->total, 2, ',', '.') }} CRC · {{ $factura->fecha_emision ? $factura->fecha_emision->format('d/m/Y') : $factura->created_at->format('d/m/Y') }}
                                                    @if($factura->fecha_vencimiento)
                                                        · Vence: {{ $factura->fecha_vencimiento->format('d/m/Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($factura->enlace || $factura->pdf_path ?? false)
                                                <a href="{{ $factura->enlace ?? '/storage/' . ($factura->pdf_path ?? '') }}" target="_blank" class="px-3 py-1 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 transition-all text-xs font-medium">
                                                    Ver PDF
                                                </a>
                                            @endif
                                            <span class="px-3 py-1 rounded-lg text-xs font-medium {{ $factura->estado == 'pagada' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : ($factura->estado == 'pendiente' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30') }}">
                                                {{ ucfirst($factura->estado ?? 'pendiente') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-slate-500 dark:text-slate-400">No hay facturas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-6">
                <p class="text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
    
    <script>
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
    </script>
</body>
</html>

