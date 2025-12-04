@extends('layouts.app')

@section('title', 'Tickets Resueltos')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                    Tickets Resueltos
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('cases.index') }}" class="btn-secondary flex items-center">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Ver abiertos
                </a>
            </div>
        </div>

        @livewire('support-case-list', ['activeTab' => 'resolved'])
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showCaseDetails(caseData) {
        const createdAt = new Date(caseData.created_at);
        const formattedDate = createdAt.toLocaleDateString('es-MX', {
            day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            let statusText = caseData.status === 'open' ? 'Abierto' : 'Resuelto';
            let statusClass = caseData.status === 'open' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
            
            Swal.fire({
                title: caseData.title,
                html: `
                    <div class="text-left">
                        <p class="mb-2"><strong>ID:</strong> ${caseData.id}</p>
                        <p class="mb-2"><strong>Descripción:</strong> ${caseData.description}</p>
                        <p class="mb-2"><strong>Estado:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">${statusText}</span></p>
                        <p class="mb-2"><strong>Fecha de creación:</strong> ${formattedDate}</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Cerrar Ticket',
                cancelButtonText: 'Cerrar',
                showDenyButton: caseData.status === 'open',
                denyButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6b7280',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lógica para cerrar el ticket
                    Livewire.dispatch('updateStatus', { caseId: caseData.id, status: caseData.status });
                }
            });
        }
        
        function showFullDetails(caseData) {
            const createdAt = new Date(caseData.created_at);
            const formattedDate = createdAt.toLocaleDateString('es-MX', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            let statusText = caseData.status === 'open' ? 'Abierto' : 'Resuelto';
            let statusClass = caseData.status === 'open' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
            
            // Construir el HTML para las imágenes si existen
            let imagesHtml = '';
            if (caseData.images && caseData.images.length > 0) {
                imagesHtml = `
                    <div class="mt-4">
                        <p class="font-semibold mb-2">Imágenes adjuntas:</p>
                        <div class="grid grid-cols-2 gap-2">
                            ${caseData.images.map(image => 
                                `<img 
                                    src="${image}" 
                                    alt="Imagen del ticket" 
                                    class="h-32 w-full object-cover rounded cursor-pointer"
                                    onclick="showFullImage('${image}')"
                                >`
                            ).join('')}
                        </div>
                    </div>
                `;
            }
            
            Swal.fire({
                title: caseData.title,
                html: `
                    <div class="text-left">
                        <p class="mb-2"><strong>ID:</strong> ${caseData.id}</p>
                        <p class="mb-2"><strong>Descripción:</strong> ${caseData.description}</p>
                        <p class="mb-2"><strong>Estado:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">${statusText}</span></p>
                        <p class="mb-2"><strong>Fecha de creación:</strong> ${formattedDate}</p>
                        ${imagesHtml}
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Cerrar Ticket',
                cancelButtonText: 'Cerrar',
                showDenyButton: caseData.status === 'open',
                denyButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6b7280',
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lógica para cerrar el ticket
                    Livewire.dispatch('updateStatus', { caseId: caseData.id, status: caseData.status });
                }
            });
        }
        
        function showFullImage(imageUrl) {
            Swal.fire({
                imageUrl: imageUrl,
                imageAlt: 'Imagen del ticket',
                showConfirmButton: false,
                showCloseButton: true,
                background: 'none',
                backdrop: `
                    rgba(0,0,0,0.8)
                `
            });
        }
    </script>
    
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
            --primary-950: #082f49;
        }
        
        .btn-primary {
            @apply bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200;
        }
        
        .btn-secondary {
            @apply bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600;
        }
        
        .card {
            @apply bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800 dark:border dark:border-gray-700;
        }
        
        .badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        
        .badge-success {
            @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100;
        }
        
        .badge-warning {
            @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-primary-600 dark:text-primary-400">
                            Soporte Técnico
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('cases.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium dark:text-gray-300 dark:hover:text-white">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Abiertos
                            @if($openCount > 0)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100">
                                    {{ $openCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('cases.resolved') }}" class="border-primary-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium dark:text-white">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Resueltos
                            @if($resolvedCount > 0)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                    {{ $resolvedCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <button type="button" @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-full text-gray-400 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg x-show="!darkMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    @auth
                        <div class="ml-3 relative">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200 mr-2">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white">Iniciar sesión</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tickets Resueltos</h1>
                <a href="{{ route('cases.index') }}" class="btn-secondary flex items-center">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Volver a abiertos
                </a>
            </div>
            
            <!-- Livewire Component -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg dark:bg-gray-800 dark:border dark:border-gray-700">
                @livewire('support-case-list', ['activeTab' => 'resolved'], key('resolved-cases'))
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 dark:bg-gray-900 dark:border-gray-800">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} Soporte Técnico. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
