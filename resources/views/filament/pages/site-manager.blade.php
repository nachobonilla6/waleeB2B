<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($this->getSitios() as $sitio)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col h-full">
                    @if($sitio->imagen)
                        <div class="h-52 bg-gray-100 dark:bg-gray-700 overflow-hidden relative">
                            <img src="{{ asset('storage/' . $sitio->imagen) }}" alt="{{ $sitio->nombre }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    @else
                        <div class="h-52 bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 flex items-center justify-center">
                            <svg class="w-20 h-20 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-6 flex flex-col h-full">
                        <!-- Header con título y estado -->
                        <div class="mb-4">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">{{ $sitio->nombre }}</h3>
                                @if($sitio->en_linea)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-success-50 text-success-700 dark:bg-success-900/30 dark:text-success-400 border border-success-200 dark:border-success-800">
                                        <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                        En línea
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                        Offline
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Descripción -->
                        @if($sitio->descripcion)
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 leading-relaxed flex-grow">
                                {{ $sitio->descripcion }}
                            </p>
                        @endif
                        
                        <!-- Video link -->
                        @if($sitio->video_url)
                            <div class="mb-4">
                                <a href="{{ $sitio->video_url }}" target="_blank" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                    </svg>
                                    Ver video
                                </a>
                            </div>
                        @endif
                        
                        <!-- Botones de acción -->
                        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                @if($sitio->enlace)
                                    <a href="{{ $sitio->enlace }}" target="_blank" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Visitar
                                    </a>
                                @endif
                                <a href="{{ \App\Filament\Resources\SitioResource::getUrl('edit', ['record' => $sitio]) }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver
                                </a>
                                <a href="{{ \App\Filament\Resources\SitioResource::getUrl('edit', ['record' => $sitio]) }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            </div>
        @endif
    </div>
</x-filament-panels::page>
