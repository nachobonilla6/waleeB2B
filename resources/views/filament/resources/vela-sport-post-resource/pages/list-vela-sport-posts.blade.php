<x-filament-panels::page>
    {{-- Filtros --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <x-filament::button 
            wire:click="setFilter('all')"
            :color="$filter === 'all' ? 'primary' : 'gray'"
            size="sm"
        >
            Todos
        </x-filament::button>
        <x-filament::button 
            wire:click="setFilter('pending')"
            :color="$filter === 'pending' ? 'warning' : 'gray'"
            size="sm"
        >
            🟡 Pendientes
        </x-filament::button>
        <x-filament::button 
            wire:click="setFilter('published')"
            :color="$filter === 'published' ? 'success' : 'gray'"
            size="sm"
        >
            🟢 Publicados
        </x-filament::button>
        <x-filament::button 
            wire:click="setFilter('rejected')"
            :color="$filter === 'rejected' ? 'danger' : 'gray'"
            size="sm"
        >
            🔴 Rechazados
        </x-filament::button>
    </div>

    {{-- Grid de Publicaciones --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @forelse($this->getPublicaciones() as $post)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-200 dark:border-gray-700">
                {{-- Imagen --}}
                <div class="relative h-32 bg-gradient-to-br from-blue-100 to-cyan-200 dark:from-blue-900/30 dark:to-cyan-800/30 overflow-hidden">
                    @if($post->imagen)
                        <a href="{{ $post->imagen }}" target="_blank" class="block w-full h-full cursor-zoom-in group">
                            <img 
                                src="{{ $post->imagen }}" 
                                alt="{{ $post->titulo }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                loading="lazy"
                                onerror="this.parentElement.style.display='none'; this.parentElement.nextElementSibling.style.display='flex';"
                            >
                        </a>
                        <div class="hidden items-center justify-center h-full text-blue-400 absolute inset-0 bg-gradient-to-br from-blue-100 to-cyan-200 dark:from-blue-900/30 dark:to-cyan-800/30">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full text-blue-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    {{-- Badge de Estado --}}
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold shadow-sm
                            @if($post->status === 'pending') bg-yellow-500 text-white
                            @elseif($post->status === 'published') bg-green-500 text-white
                            @else bg-red-500 text-white
                            @endif
                        ">
                            @if($post->status === 'pending') 🟡 Pendiente
                            @elseif($post->status === 'published') ✓ Publicado
                            @else ✗ Rechazado
                            @endif
                        </span>
                    </div>

                    {{-- Badge Vela Sport --}}
                    <div class="absolute top-2 left-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white shadow-sm">
                            🎣 Vela Sport
                        </span>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-2 mb-2 min-h-[2.5rem]">
                        {{ $post->titulo }}
                    </h3>

                    @if($post->texto)
                        <p class="text-gray-600 dark:text-gray-400 text-xs line-clamp-3 mb-3">
                            {{ $post->texto }}
                        </p>
                    @endif

                    @if($post->hashtags && count($post->hashtags) > 0)
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach(array_slice($post->hashtags, 0, 3) as $hashtag)
                                <span class="text-xs text-cyan-600 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-900/30 px-1.5 py-0.5 rounded">
                                    {{ $hashtag }}
                                </span>
                            @endforeach
                            @if(count($post->hashtags) > 3)
                                <span class="text-xs text-gray-500">+{{ count($post->hashtags) - 3 }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="text-xs text-gray-500 dark:text-gray-500 mb-3 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $post->created_at->diffForHumans() }}
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button class="flex items-center gap-1.5 px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                            </svg>
                            <span class="text-xs font-medium">Like</span>
                        </button>
                        
                        <button class="flex items-center gap-1.5 px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="text-xs font-medium">Comment</span>
                        </button>
                        
                        <x-filament::dropdown placement="top-end">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-1.5 px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="5" cy="12" r="2"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <circle cx="19" cy="12" r="2"></circle>
                                    </svg>
                                </button>
                            </x-slot>
                            
                            <x-filament::dropdown.list>
                                <x-filament::dropdown.list.item 
                                    href="{{ \App\Filament\Resources\VelaSportPostResource::getUrl('edit', ['record' => $post]) }}"
                                    tag="a"
                                    icon="heroicon-o-pencil-square"
                                >
                                    Editar
                                </x-filament::dropdown.list.item>
                                
                                <x-filament::dropdown.list.item 
                                    wire:click="deletePost({{ $post->id }})"
                                    wire:confirm="¿Eliminar esta publicación?"
                                    icon="heroicon-o-trash"
                                    color="danger"
                                >
                                    Eliminar
                                </x-filament::dropdown.list.item>
                            </x-filament::dropdown.list>
                        </x-filament::dropdown>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 mb-4 text-orange-400">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Sin publicaciones</h3>
                    <p class="text-gray-500 dark:text-gray-400">No hay publicaciones de Vela Sport.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $this->getPublicaciones()->links() }}
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>

