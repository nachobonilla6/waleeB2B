<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Botón de crear sitio -->
        <div class="flex justify-end">
            <a href="{{ \App\Filament\Resources\SitioResource::getUrl('create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Crear Sitio
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($this->getSitios() as $sitio)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col h-full">
                    @if($sitio->imagen)
                        <div class="h-32 bg-gray-100 dark:bg-gray-700 overflow-hidden relative">
                            <img src="{{ asset('storage/' . $sitio->imagen) }}" alt="{{ $sitio->nombre }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    @else
                        <div class="h-32 bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-3 flex flex-col h-full">
                        <!-- Header con título y estado -->
                        <div class="mb-2">
                            <div class="flex items-start justify-between mb-1">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white leading-tight line-clamp-2">{{ $sitio->nombre }}</h3>
                            </div>
                            @if($sitio->en_linea)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700 dark:bg-success-900/30 dark:text-success-400 border border-success-200 dark:border-success-800">
                                    <span class="w-1 h-1 bg-success-500 rounded-full mr-1"></span>
                                    En línea
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                    <span class="w-1 h-1 bg-gray-400 rounded-full mr-1"></span>
                                    Offline
                                </span>
                            @endif
                        </div>
                        
                        <!-- Descripción -->
                        @if($sitio->descripcion)
                            <p class="text-xs text-gray-600 dark:text-gray-300 mb-2 line-clamp-2 leading-relaxed flex-grow">
                                {{ strip_tags($sitio->descripcion) }}
                            </p>
                        @endif
                        
                        <!-- Botones de acción -->
                        <div class="mt-auto pt-2 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col gap-1.5">
                                @if($sitio->enlace)
                                    <a href="{{ $sitio->enlace }}" target="_blank" class="w-full inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-white bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 rounded transition-all duration-200">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Visitar
                                    </a>
                                @endif
                                <a href="{{ \App\Filament\Resources\SitioResource::getUrl('edit', ['record' => $sitio]) }}" class="w-full inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded transition-all duration-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($this->getSitios()->count() === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay sitios</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza agregando un nuevo sitio.</p>
                <a href="{{ \App\Filament\Resources\SitioResource::getUrl('create') }}" class="mt-4 inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Crear Sitio
                </a>
            </div>
        @endif
    </div>
</x-filament-panels::page>
