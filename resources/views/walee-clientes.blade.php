<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Clientes</title>
    <meta name="description" content="Walee - Gestión de Clientes">
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
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(213, 159, 59, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(213, 159, 59, 0.5);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .option-card {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .option-card:nth-child(1) { animation-delay: 0.1s; }
        .option-card:nth-child(2) { animation-delay: 0.2s; }
        .option-card:nth-child(3) { animation-delay: 0.3s; }
        
        .walee-gradient {
            background: linear-gradient(135deg, #D59F3B 0%, #E0C684 50%, #C78F2E 100%);
        }
        
        /* Scrollbar styling */
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen transition-colors duration-200">
    @php
        use App\Models\Client;
        
        $clientesActivos = Client::where('estado', 'accepted')->count();
        $clientesEnProceso = Client::where('estado', 'propuesta_enviada')->count();
        $clientesTotales = Client::count();
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-walee-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-emerald-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Clientes'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Options Grid -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Clientes Activos -->
                <a href="{{ route('walee.clientes.activos') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 border border-emerald-500/20 p-8 hover:border-emerald-400/50 hover:from-emerald-500/15 hover:to-emerald-600/10 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-emerald-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition-colors">Clientes Activos</h2>
                                <span class="px-3 py-1 text-sm font-bold bg-emerald-500/30 text-emerald-300 rounded-full border border-emerald-500/40">{{ $clientesActivos }}</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Ver y gestionar clientes con proyectos activos y en curso</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-emerald-500/10 items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                            <svg class="w-6 h-6 text-emerald-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                
                <!-- Crear Contrato -->
                <a href="{{ route('walee.herramientas.enviar-contrato') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-violet-500/10 to-violet-600/5 border border-violet-500/20 p-8 hover:border-violet-400/50 hover:from-violet-500/15 hover:to-violet-600/10 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-violet-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2 group-hover:text-violet-600 dark:group-hover:text-violet-300 transition-colors">Crear Contrato</h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Generar y enviar contrato de servicios digitales a clientes</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-violet-500/10 items-center justify-center group-hover:bg-violet-500/20 transition-colors">
                            <svg class="w-6 h-6 text-violet-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                
                <!-- Clientes en Proceso -->
                <a href="{{ route('walee.clientes.proceso') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-500/10 to-amber-600/5 border border-amber-500/20 p-8 hover:border-amber-400/50 hover:from-amber-500/15 hover:to-amber-600/10 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-amber-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-300 transition-colors">Clientes en Proceso</h2>
                                <span class="px-3 py-1 text-sm font-bold bg-amber-500/30 text-amber-600 dark:text-amber-300 rounded-full border border-amber-500/40">{{ $clientesEnProceso }}</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Clientes con propuesta enviada en seguimiento</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-amber-500/10 items-center justify-center group-hover:bg-amber-500/20 transition-colors">
                            <svg class="w-6 h-6 text-amber-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                
                <!-- Extraer Clientes -->
                <a href="{{ route('walee.extraer.clientes') }}" class="option-card group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500/10 to-blue-600/5 border border-blue-500/20 p-8 hover:border-blue-400/50 hover:from-blue-500/15 hover:to-blue-600/10 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors">Extraer Clientes</h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Importar nuevos clientes desde Google Maps y otras fuentes</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-blue-500/10 items-center justify-center group-hover:bg-blue-500/20 transition-colors">
                            <svg class="w-6 h-6 text-blue-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
</body>
</html>

