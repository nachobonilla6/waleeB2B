@extends('layouts.app')

@section('title', 'Tickets de Soporte')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate dark:text-white">
                    Tickets de Soporte
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('cases.create') }}" class="btn-primary flex items-center">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Ticket
                </a>
            </div>
        </div>

        <!-- Pestañas de navegación -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="#" class="border-indigo-500 text-indigo-600 dark:text-indigo-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center" aria-current="page">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Abiertos
                    @if($openCount > 0)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                            {{ $openCount }}
                        </span>
                    @endif
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Resueltos
                    @if($resolvedCount > 0)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                            {{ $resolvedCount }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        @livewire('support-case-list', ['activeTab' => 'open'])
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Manejar el cambio de pestañas
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('[role="tab"]');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Actualizar pestaña activa
                document.querySelectorAll('[role="tab"]').forEach(t => {
                    t.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                    t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-200');
                });
                
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-200');
                this.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                
                // Aquí puedes agregar la lógica para cargar los tickets según la pestaña seleccionada
                // Por ejemplo, podrías usar Livewire para actualizar la lista
            });
        });
    });

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
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full ${caseData.status === 'open' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
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
            
            content += '</div>';
            
            // Configuración del diálogo para el popup pequeño
            Swal.fire({
                title: 'Detalles del Caso',
                html: content,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#3b82f6',
                width: '400px',
                showCloseButton: true,
                showDenyButton: caseData.status === 'open',
                denyButtonText: 'Cerrar Ticket / Marcar como resuelto',
                denyButtonColor: '#10b981',
                showLoaderOnDeny: true,
                allowOutsideClick: false,
                preDeny: () => {
                    return fetch(`/support-cases/${caseData.id}/close`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            console.error('Server response:', data);
                            throw new Error(data.message || 'Error al actualizar el estado del ticket');
                        }
                        return data;
                    })
                    .then(data => {
                        // Refresh the page to reflect the changes
                        window.location.reload();
                        return data;
                    })
                    .catch(error => {
                        console.error('Error updating ticket:', error);
                        Swal.showValidationMessage(error.message || 'Error al actualizar el ticket. Por favor, intente de nuevo.');
                        return false;
                    });
                }
            }).then((result) => {
                if (result.isDenied) {
                    // Mostrar mensaje de éxito y recargar
                    Swal.fire({
                        title: '¡Listo!',
                        text: 'El ticket ha sido marcado como resuelto',
                        icon: 'success',
                        confirmButtonColor: '#3b82f6',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.reload();
                    });
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
            
            let content = `
                <div class="text-left text-sm">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-gray-500">Solicitante</p>
                            <p class="font-medium">${caseData.name}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Email</p>
                            <p class="font-medium">${caseData.email}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Estado</p>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full ${caseData.status === 'open' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                                ${caseData.status === 'open' ? 'Abierto' : 'Resuelto'}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500">Fecha</p>
                            <p class="font-medium">${formattedDate}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-500">Título</p>
                        <p class="font-medium">${caseData.title}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-500">Descripción</p>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded">${caseData.description || 'Sin descripción'}</p>
                    </div>
            `;
            
            if (caseData.image) {
                content += `
                    <div class="mt-2">
                        <p class="text-gray-500 text-sm mb-1">Imagen adjunta</p>
                        <img src="${caseData.image}" alt="Imagen del caso" 
                             class="max-h-64 w-auto rounded border border-gray-200 cursor-pointer"
                             onclick="showFullImage('${caseData.image}')">
                    </div>
                `;
            }
            
            content += '</div>';
            
            // Configuración del diálogo grande
            Swal.fire({
                title: 'Detalles Completos',
                html: content,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#3b82f6',
                width: '600px',
                showCloseButton: true,
                showDenyButton: caseData.status === 'open',
                denyButtonText: 'Cerrar Ticket / Marcar como resuelto',
                denyButtonColor: '#10b981',
                showLoaderOnDeny: true,
                allowOutsideClick: false,
                preDeny: () => {
                    return fetch(`/support-cases/${caseData.id}/close`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            console.error('Server response:', data);
                            throw new Error(data.message || 'Error al actualizar el estado del ticket');
                        }
                        return data;
                    })
                    .then(data => {
                        // Refresh the page to reflect the changes
                        window.location.reload();
                        return data;
                    })
                    .catch(error => {
                        console.error('Error updating ticket:', error);
                        Swal.showValidationMessage(error.message || 'Error al actualizar el ticket. Por favor, intente de nuevo.');
                        return false;
                    });
                }
            }).then((result) => {
                if (result.isDenied) {
                    // Mostrar mensaje de éxito y recargar
                    Swal.fire({
                        title: '¡Listo!',
                        text: 'El ticket ha sido marcado como resuelto',
                        icon: 'success',
                        confirmButtonColor: '#3b82f6',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }
        
        function showFullImage(imageUrl) {
            Swal.fire({
                imageUrl: imageUrl,
                imageAlt: 'Imagen del caso',
                showConfirmButton: false,
                showCloseButton: true,
                width: '80%',
                padding: '0',
                background: 'rgba(0,0,0,0.8)'
            });
        }
        
        // Hacer la función global para que esté disponible en el Livewire
        window.showCaseDetails = showCaseDetails;
    </script>
    
    <style>
        :root {
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-700: #374151;
            --gray-900: #111827;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            @apply bg-gray-900 text-white transition-colors duration-200;
        }
        
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-200;
        }
        
        .btn-outline {
            @apply border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors duration-200;
        }
        
        .card {
            @apply bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden;
        }
        
        /* Estilos para la tabla */
        table {
            @apply bg-white text-gray-900;
        }
        
        th {
            @apply bg-gray-100 text-gray-700;
        }
        
        td {
            @apply border-t border-gray-200;
        }
        
        .badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        
        .badge-open {
            @apply bg-yellow-100 text-yellow-800;
        }
        
        .badge-resolved {
            @apply bg-green-100 text-green-800;
        }
    </style>
    
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">Soporte Técnico</span>
                    </div>
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <a href="{{ route('cases.index') }}" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <svg class="h-5 w-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Tickets
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <svg class="h-5 w-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Dashboard
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <svg class="h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu">
                                <div class="py-1" role="none">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline">
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1 py-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-end items-center py-2">
                @auth
                    <a href="#" class="btn-primary flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nuevo Ticket
                    </a>
                @endauth
            </div>
            
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="container">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="{{ route('cases.index') }}" class="border-primary-500 text-gray-900 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
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
                        <a href="{{ route('cases.resolved') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
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
                    </nav>
                </div>
                
                <div class="tab-content p-3 border border-top-0 rounded-bottom">
                    <div class="tab-pane fade show active" id="open" role="tabpanel">
                        @livewire('support-case-list', ['activeTab' => 'open'])
                    </div>
                </div>
            </div>
        </div>
    </main>


    @livewireScripts
</body>
</html>
