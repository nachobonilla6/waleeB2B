<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee - Configuraciones</title>
    <meta name="description" content="Walee - Configuraciones del Sistema">
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
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(213, 159, 59, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(213, 159, 59, 0.5);
            }
        }
        
        .walee-gradient {
            background: linear-gradient(135deg, #D59F3B 0%, #E0C684 50%, #C78F2E 100%);
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
            <div class="absolute top-1/3 -left-20 w-60 h-60 bg-violet-400/10 dark:bg-violet-400/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/20 dark:bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8">
            @php $pageTitle = 'Configuraciones'; @endphp
            @include('partials.walee-navbar')
            
            <!-- Header -->
            <header class="mb-8 animate-fade-in-up">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">
                    Configuraciones
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-400">Herramientas de administración del sistema</p>
            </header>
            
            <!-- Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <!-- Git Pull Button -->
                <button 
                    id="gitPullBtn"
                    class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-500/10 dark:to-blue-600/5 border border-blue-200 dark:border-blue-500/20 p-8 hover:border-blue-400 dark:hover:border-blue-400/50 hover:from-blue-100 dark:hover:from-blue-500/15 hover:to-blue-200/50 dark:hover:to-blue-600/10 transition-all duration-500 shadow-sm dark:shadow-none"
                >
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 dark:bg-blue-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">
                                Git Pull
                            </h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                                Actualizar código desde el repositorio remoto
                            </p>
                            <code class="mt-2 block text-xs text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded">
                                git pull origin main
                            </code>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-500/10 items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-500/20 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </button>
                
                <!-- Migrate Button -->
                <button 
                    id="migrateBtn"
                    class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 dark:from-emerald-500/10 dark:to-emerald-600/5 border border-emerald-200 dark:border-emerald-500/20 p-8 hover:border-emerald-400 dark:hover:border-emerald-400/50 hover:from-emerald-100 dark:hover:from-emerald-500/15 hover:to-emerald-200/50 dark:hover:to-emerald-600/10 transition-all duration-500 shadow-sm dark:shadow-none"
                >
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/20 dark:bg-emerald-500/10 rounded-full blur-3xl transform translate-x-20 -translate-y-20 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">
                                Migrar Base de Datos
                            </h2>
                            <p class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">
                                Ejecutar migraciones pendientes de la base de datos
                            </p>
                            <code class="mt-2 block text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded">
                                php artisan migrate
                            </code>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 items-center justify-center group-hover:bg-emerald-200 dark:group-hover:bg-emerald-500/20 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </button>
            </div>
            
            <!-- Footer -->
            <footer class="text-center py-8 mt-8">
                <p class="text-sm text-slate-600 dark:text-slate-500">
                    <span class="text-walee-600 dark:text-walee-400 font-medium">Walee</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
</body>
</html>

