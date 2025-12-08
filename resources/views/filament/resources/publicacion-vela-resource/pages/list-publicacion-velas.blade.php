<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
            @foreach($this->getRecords() as $publicacion)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    @if($publicacion->foto)
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($publicacion->foto) }}" 
                                 alt="Publicación" 
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <p class="text-gray-900 dark:text-white text-sm mb-3 line-clamp-4">
                            {{ $publicacion->texto }}
                        </p>
                        
                        @if($publicacion->hashtags)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach(explode(' ', $publicacion->hashtags) as $hashtag)
                                    @if(!empty(trim($hashtag)))
                                        <span class="text-primary-600 dark:text-primary-400 text-xs font-medium">
                                            {{ trim($hashtag) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>
                                {{ $publicacion->fecha_publicacion?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                            </span>
                            <div class="flex gap-2">
                                <a href="{{ \App\Filament\Resources\PublicacionVelaResource::getUrl('edit', ['record' => $publicacion]) }}" 
                                   class="text-primary-600 dark:text-primary-400 hover:underline">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($this->getRecords()->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No hay publicaciones aún.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>

