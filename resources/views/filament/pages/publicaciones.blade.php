<x-filament-panels::page>
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .post-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .post-card:hover {
            transform: translateY(-4px);
        }
        .image-overlay {
            background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.4) 100%);
            transition: opacity 0.3s ease;
        }
        .post-card:hover .image-overlay {
            opacity: 0.8;
        }
        .image-container {
            position: relative;
            overflow: hidden;
        }
        .image-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.3) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .post-card:hover .image-container::after {
            opacity: 1;
        }
    </style>

    <!-- Header con estadísticas -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Publicaciones</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Gestiona y visualiza todas tus publicaciones</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPosts()->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de publicaciones -->
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($this->getPosts() as $post)
                <div class="post-card bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700 flex flex-col group">
                    <!-- Imagen con overlay -->
                    <div class="image-container w-full h-64 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex-shrink-0 relative">
                        <img 
                            src="{{ $post->image_url }}" 
                            alt="{{ $post->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                            onerror="this.src='https://via.placeholder.com/800x600?text=Imagen+No+Disponible'"
                        >
                        <!-- Badge de fecha en la imagen -->
                        <div class="absolute top-3 right-3">
                            <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-lg px-3 py-1.5 shadow-lg">
                                <div class="text-xs font-semibold text-gray-900 dark:text-white">
                                    {{ $post->created_at->format('d M') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="p-6 flex-grow flex flex-col">
                        <!-- Categoría/Tag -->
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                Publicación
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 leading-tight group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                            {{ $post->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed mb-4 flex-grow">
                            {{ $post->content }}
                        </p>
                    </div>
                    
                    <!-- Footer con acción -->
                    <div class="px-6 pb-6 pt-0 mt-auto border-t border-gray-100 dark:border-gray-700">
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 dark:bg-gray-700 hover:bg-primary-50 dark:hover:bg-primary-900/20 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg font-medium text-sm transition-all duration-200 group/btn">
                            <span>Leer más</span>
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($this->getPosts()->isEmpty())
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No hay publicaciones</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ejecuta el seeder para crear publicaciones de ejemplo.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
