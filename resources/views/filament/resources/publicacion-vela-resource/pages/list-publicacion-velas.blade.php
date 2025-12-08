<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex justify-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 max-w-7xl">
                @foreach($this->getRecords() as $publicacion)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 max-w-sm">
                        @if($publicacion->foto)
                            <div class="w-full h-40 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($publicacion->foto) }}" 
                                     alt="Publicación" 
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-full h-40 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="p-3">
                            <p class="text-gray-900 dark:text-white text-xs mb-2 line-clamp-3">
                                {{ $publicacion->texto }}
                            </p>
                            
                            @if($publicacion->hashtags)
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach(explode(' ', $publicacion->hashtags) as $hashtag)
                                        @if(!empty(trim($hashtag)))
                                            <span class="text-primary-600 dark:text-primary-400 text-xs font-medium">
                                                {{ trim($hashtag) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                                <div class="flex items-center justify-between mb-2">
                                    <button type="button" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="text-xs font-medium">Like</span>
                                    </button>
                                    <button type="button" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span class="text-xs font-medium">Comment</span>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>
                                    {{ $publicacion->fecha_publicacion?->format('d/m/Y') ?? 'Sin fecha' }}
                                </span>
                                <a href="{{ \App\Filament\Resources\PublicacionVelaResource::getUrl('edit', ['record' => $publicacion]) }}" 
                                   class="text-primary-600 dark:text-primary-400 hover:underline text-xs">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        @if($this->getRecords()->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No hay publicaciones aún.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>

