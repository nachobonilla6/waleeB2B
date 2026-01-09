<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Soporte')</title>
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Web Solutions CR')">
    <meta property="og:description" content="@yield('description', 'Soluciones web profesionales para tu negocio')">
    <meta property="og:image" content="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg">
    <meta property="og:image:url" content="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg">
    <meta property="og:image:secure_url" content="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Web Solutions CR">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Web Solutions CR')">
    <meta name="twitter:description" content="@yield('description', 'Soluciones web profesionales para tu negocio')">
    <meta name="twitter:image" content="https://i.postimg.cc/RVw3wk3Y/wa-(Edited).jpg">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                            950: '#030712',
                        },
                    },
                },
            },
        }
    </script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Heroicons -->
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/outline/esm/index.js" type="module"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-200: #bae6fd;
            --primary-300: #7dd3fc;
            --primary-400: #38bdf8;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --primary-800: #075985;
            --primary-900: #0c4a6e;
        }
        
        .btn-primary {
            @apply inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150;
        }
        
        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150;
        }
        
        .form-input {
            @apply border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-md shadow-sm;
        }
        
        .form-label {
            @apply block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1;
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900 dark:text-white">
                            Soporte
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @if(Route::has('cases.index'))
                        <a href="{{ route('cases.index') }}" class="{{ request()->routeIs('cases.index') ? 'border-primary-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Abiertos
                            @if(isset($openCount) && $openCount > 0)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100">
                                    {{ $openCount }}
                                </span>
                            @endif
                        </a>
                        @endif
                        
                        @if(Route::has('cases.resolved'))
                        <a href="{{ route('cases.resolved') }}" class="{{ request()->routeIs('cases.resolved') ? 'border-primary-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Resueltos
                            @if(isset($resolvedCount) && $resolvedCount > 0)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                    {{ $resolvedCount }}
                                </span>
                            @endif
                        </a>
                        @endif
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:text-gray-300 dark:hover:text-white">
                        <span x-show="!darkMode">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </span>
                        <span x-show="darkMode" style="display: none;">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                    </button>
                    
                    @auth
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="user-menu" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Abrir menú de usuario</span>
                                    <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="user-menu" style="display: none;">
                                <div class="py-1" role="none">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" role="menuitem">Tu perfil</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" role="menuitem">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="ml-3 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-3 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Registrarse</a>
                        @endif
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 dark:text-gray-300 dark:hover:bg-gray-700">
                        <span class="sr-only">Abrir menú principal</span>
                        <!-- Icono de menú -->
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Icono de cerrar -->
                        <svg x-show="mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div x-show="mobileMenuOpen" class="sm:hidden" style="display: none;" @click.away="mobileMenuOpen = false">
            <div class="pt-2 pb-3 space-y-1">
                @if(Route::has('cases.index'))
                <a href="{{ route('cases.index') }}" class="{{ request()->routeIs('cases.index') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700">
                    Tickets Abiertos
                    @if(isset($openCount) && $openCount > 0)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100">
                            {{ $openCount }}
                        </span>
                    @endif
                </a>
                @endif
                
                @if(Route::has('cases.resolved'))
                <a href="{{ route('cases.resolved') }}" class="{{ request()->routeIs('cases.resolved') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium dark:text-gray-300 dark:hover:bg-gray-700">
                    Tickets Resueltos
                    @if(isset($resolvedCount) && $resolvedCount > 0)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                            {{ $resolvedCount }}
                        </span>
                    @endif
                </a>
                @endif
                
                @auth
                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Tu perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-1">
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Iniciar sesión</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Registrarse</a>
                            @endif
                        </div>
                    </div>
                @endauth
                
                <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center px-4">
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                            <span x-show="!darkMode" class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                Modo oscuro
                            </span>
                            <span x-show="darkMode" class="flex items-center" style="display: none;">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Modo claro
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 relative z-10 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Web Solutions CR</h2>
                <p class="text-gray-600 dark:text-gray-300">Soluciones digitales innovadoras para tu éxito</p>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <span class="text-gray-600 dark:text-gray-300">Español</span>
                    <span class="text-gray-400">|</span>
                    <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">English</a>
                    <span class="text-gray-400">|</span>
                    <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Français</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Trabaja con Nosotros</a>
                    <span class="text-gray-400">|</span>
                    <a href="#" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Asociarse</a>
                </div>
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-4 mb-4 md:mb-0">
                        <a href="mailto:info@websolutions.work" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                            info@websolutions.work
                        </a>
                        <span class="text-gray-400">|</span>
                        <a href="tel:+50688061829" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                            +506 8806 1829
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            &copy; {{ date('Y') }} Web Solutions CR. Todos los derechos reservados.
                        </p>
                        <span class="text-gray-400">|</span>
                        <a href="/privacidad" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
                            Política de Privacidad
                        </a>
                        <span class="text-gray-400">|</span>
                        <a href="/terminos" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
                            Términos y Condiciones
                        </a>
                    </div>
                </div>
                
                <div class="mt-4 text-center md:text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Desarrollado por <a href="https://websolutions.work" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">websolutions.work</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    @stack('modals')

    @stack('scripts')
    
    <script>
        // Inicializar Alpine.js
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                mobileMenuOpen: false,
                init() {
                    // Cargar preferencia de tema oscuro
                    if (localStorage.getItem('darkMode') === 'true' || 
                        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        this.darkMode = true;
                        document.documentElement.classList.add('dark');
                    } else {
                        this.darkMode = false;
                        document.documentElement.classList.remove('dark');
                    }
                    
                    // Escuchar cambios en la preferencia del sistema
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                        if (!localStorage.getItem('darkMode')) {
                            this.darkMode = e.matches;
                        }
                    });
                },
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                }
            }));
        });
        
        // Función para mostrar detalles del ticket
        function showCaseDetails(caseData) {
            const createdAt = new Date(caseData.created_at);
            const formattedDate = createdAt.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Construir el HTML del contenido
            let content = `
                <div class="text-left text-sm">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <p class="text-gray-500">Solicitante</p>
                            <p class="font-medium">${caseData.name}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Estado</p>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full ${caseData.status === 'open' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100'}">
                                ${caseData.status === 'open' ? 'Abierto' : 'Resuelto'}
                            </span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-gray-500">Título</p>
                        <p class="font-medium">${caseData.title}</p>
                    </div>
                    <div class="mb-2">
                        <p class="text-gray-500">Descripción</p>
                        <p class="text-gray-700 line-clamp-3">${caseData.description || 'Sin descripción'}</p>
                        <a href="#" onclick="event.preventDefault(); showFullDetails(${JSON.stringify(caseData).replace(/"/g, '&quot;')})" 
                           class="text-blue-600 hover:text-blue-800 text-xs">
                            Ver más...
                        </a>
                    </div>
            `;
            
            // Agregar imagen si existe
            if (caseData.image) {
                content += `
                    <div class="mt-2">
                        <p class="text-gray-500 text-sm mb-1">Imagen adjunta</p>
                        <img src="${caseData.image}" alt="Imagen del caso" 
                             class="max-h-32 w-auto rounded border border-gray-200 cursor-pointer"
                             onclick="showFullImage('${caseData.image}')">
                    </div>
                `;
            }
            
            content += `
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <p class="text-xs text-gray-500">Creado el ${formattedDate}</p>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Detalles del Ticket',
                html: content,
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Cerrar',
                focusConfirm: false,
                customClass: {
                    confirmButton: 'btn-primary',
                    closeButton: 'btn-secondary'
                },
                buttonsStyling: false
            });
        }
        
        // Función para mostrar detalles completos
        function showFullDetails(caseData) {
            const createdAt = new Date(caseData.created_at);
            const formattedDate = createdAt.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            let content = `
                <div class="text-left text-sm">
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div>
                            <p class="text-gray-500">Solicitante</p>
                            <p class="font-medium">${caseData.name}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Estado</p>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full ${caseData.status === 'open' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                                ${caseData.status === 'open' ? 'Abierto' : 'Resuelto'}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500">Fecha de creación</p>
                            <p class="font-medium">${formattedDate}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">ID del ticket</p>
                            <p class="font-mono text-sm">${caseData.id}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-500">Título</p>
                        <p class="font-medium text-lg">${caseData.title}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-500">Descripción</p>
                        <div class="mt-1 p-3 bg-gray-50 rounded border border-gray-200">
                            <p class="whitespace-pre-line">${caseData.description || 'Sin descripción'}</p>
                        </div>
                    </div>
            `;
            
            // Agregar imagen si existe
            if (caseData.image) {
                content += `
                    <div class="mb-4">
                        <p class="text-gray-500 mb-2">Imagen adjunta</p>
                        <img src="${caseData.image}" alt="Imagen del caso" 
                             class="max-h-48 w-auto rounded border border-gray-200 cursor-pointer"
                             onclick="showFullImage('${caseData.image}')">
                        <p class="mt-1 text-xs text-gray-500">Haz clic en la imagen para verla en tamaño completo</p>
                    </div>
                `;
            }
            
            content += `
                </div>
            `;
            
            Swal.fire({
                title: 'Detalles completos del ticket',
                html: content,
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Cerrar',
                focusConfirm: false,
                width: '600px',
                customClass: {
                    confirmButton: 'btn-primary',
                    closeButton: 'btn-secondary'
                },
                buttonsStyling: false
            });
        }
        
        // Función para mostrar imagen en tamaño completo
        function showFullImage(imageUrl) {
            Swal.fire({
                imageUrl: imageUrl,
                imageAlt: 'Imagen del ticket',
                showCloseButton: true,
                showConfirmButton: false,
                width: '90%',
                padding: '10px',
                background: 'rgba(0,0,0,0.8)',
                backdrop: `
                    rgba(0,0,0,0.8)
                    url("/images/zoom-in-out.gif")
                    center left
                    no-repeat
                `,
                customClass: {
                    closeButton: 'btn-secondary',
                    container: 'bg-transparent',
                    popup: 'bg-transparent',
                    image: 'max-h-[80vh] max-w-full object-contain'
                },
                buttonsStyling: false
            });
        }
    </script>
</body>
</html>