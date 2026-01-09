<!DOCTYPE html>
<html lang="en" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee B2B - All Tools</title>
    <meta name="description" content="All Walee B2B tools">
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
            <div class="absolute bottom-20 right-1/4 w-40 h-40 bg-walee-400/10 dark:bg-walee-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Main Content -->
        <div class="relative max-w-[90rem] mx-auto px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
            @php $pageTitle = 'Tools'; @endphp
            @include('partials.walee-navbar')
            @include('partials.walee-back-button')
            
            <!-- Header -->
            <div class="mb-4 sm:mb-6 md:mb-8 animate-fade-in-up" style="animation-delay: 0.1s;">
                <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-1 sm:mb-2">All Tools</h1>
                <p class="text-xs sm:text-sm md:text-base text-slate-600 dark:text-slate-400 hidden sm:block">Access all available tools in Walee B2B</p>
            </div>
            
            <!-- Herramientas Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-4 md:gap-6">
                <!-- Facturas -->
                <a href="{{ route('walee.facturas') }}" class="group flex flex-col items-center gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-violet-500 dark:hover:border-violet-500 hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-all duration-300 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.15s;">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-violet-100 dark:bg-violet-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm md:text-base font-semibold text-slate-700 dark:text-slate-300 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors text-center">Invoices</span>
                </a>
                
                <!-- Emails -->
                <a href="{{ route('walee.emails') }}" class="group flex flex-col items-center gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all duration-300 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm md:text-base font-semibold text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors text-center">Emails</span>
                </a>
                
                <!-- Tareas -->
                <a href="{{ route('walee.tareas') }}" class="group flex flex-col items-center gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-violet-500 dark:hover:border-violet-500 hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-all duration-300 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.25s;">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-violet-100 dark:bg-violet-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm md:text-base font-semibold text-slate-700 dark:text-slate-300 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors text-center">Tasks</span>
                </a>
                
                <!-- Contratos -->
                <a href="{{ route('walee.herramientas.enviar-contrato') }}" class="group flex flex-col items-center gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-purple-500 dark:hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition-all duration-300 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.5s;">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-purple-100 dark:bg-purple-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm md:text-base font-semibold text-slate-700 dark:text-slate-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors text-center">Contracts</span>
                </a>
                
                <!-- Stuck DLC + DLV -->
                <a href="{{ route('walee.herramientas.stuck-dlc-dlv') }}" class="group flex flex-col items-center gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-orange-500 dark:hover:border-orange-500 hover:bg-orange-50 dark:hover:bg-orange-500/10 transition-all duration-300 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.55s;">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-orange-100 dark:bg-orange-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm md:text-base font-semibold text-slate-700 dark:text-slate-300 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors text-center">Stock Management</span>
                </a>
                
                <!-- Inventory -->
                <a href="{{ route('walee.herramientas.inventory') }}" class="group flex flex-col items-center gap-2 sm:gap-3 p-3 sm:p-4 md:p-6 rounded-xl sm:rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all duration-300 shadow-sm dark:shadow-none animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-blue-100 dark:bg-blue-400/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm md:text-base font-semibold text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors text-center">Inventory</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors text-center">DLC DLV</span>
                </a>
            </div>
            
            <!-- World Map with Clocks -->
            @include('partials.walee-world-map-clocks')
            
            <!-- Footer -->
            <footer class="text-center py-4 sm:py-6 md:py-8 mt-6 sm:mt-8 md:mt-12">
                <p class="text-xs sm:text-sm text-slate-500">
                    <span class="text-walee-400 font-medium">Walee B2B</span> · websolutions.work
                </p>
            </footer>
        </div>
    </div>
    @include('partials.walee-support-button')
</body>
</html>

