<x-filament-panels::page>
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-3 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Site Scraper</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Extrae información de negocios automáticamente</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Configuración de Búsqueda</h3>
            </div>
            
            <form wire:submit="submit" class="p-6">
                <div class="space-y-6">
                    {{ $this->form }}
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Los datos se procesarán automáticamente
                        </div>
                        @if($isSuccess)
                            <x-filament::button 
                                wire:click="resetForm"
                                color="pink"
                                size="lg"
                                class="shadow-lg hover:shadow-xl transition-all duration-200"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Iniciar Otra Búsqueda
                                </span>
                            </x-filament::button>
                        @else
                            <x-filament::button 
                                type="submit" 
                                wire:loading.attr="disabled"
                                wire:target="submit"
                                color="primary"
                                size="lg"
                                class="shadow-lg hover:shadow-xl transition-all duration-200"
                                wire:loading.class="opacity-70 cursor-wait"
                            >
                                <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Iniciar Búsqueda
                                </span>
                                <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                    <x-filament::loading-indicator class="w-4 h-4" />
                                    Procesando...
                                </span>
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Ubicación</p>
                        <p class="text-xs text-blue-700 dark:text-blue-300">Búsqueda inteligente</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-900 dark:text-green-100">Tipo de Negocio</p>
                        <p class="text-xs text-green-700 dark:text-green-300">Categorización automática</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-purple-900 dark:text-purple-100">Procesamiento</p>
                        <p class="text-xs text-purple-700 dark:text-purple-300">Resultados instantáneos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // Cargar Google Maps API de forma asíncrona con loading=async
            function loadGoogleMaps() {
                if (window.google && window.google.maps && window.google.maps.places) {
                    initAutocomplete();
                    return;
                }
                
                const script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbUykR7Bo4&libraries=places&loading=async';
                script.async = true;
                script.defer = true;
                script.onload = function() {
                    setTimeout(initAutocomplete, 200);
                };
                script.onerror = function() {
                    console.warn('No se pudo cargar Google Maps API');
                };
                document.head.appendChild(script);
            }
            
            function initAutocomplete() {
                try {
                    const input = document.getElementById('location-autocomplete');
                    if (!input) {
                        setTimeout(initAutocomplete, 200);
                        return;
                    }
                    
                    // Verificar que sea un input válido
                    if (!(input instanceof HTMLInputElement)) {
                        console.warn('El elemento location-autocomplete no es un HTMLInputElement válido');
                        return;
                    }
                    
                    if (window.google && window.google.maps && window.google.maps.places) {
                        // Usar el método legacy (aún funciona, solo muestra advertencia)
                        const autocomplete = new google.maps.places.Autocomplete(input, {
                            types: ['geocode', 'establishment'],
                            componentRestrictions: { country: ['cr', 'us', 'mx', 'co', 'ar', 'cl', 'pe', 'ec', 'gt', 'pa', 'hn', 'ni', 'sv', 'do', 'bo', 'py', 'uy', 've'] }
                        });
                        
                        autocomplete.addListener('place_changed', function() {
                            const place = autocomplete.getPlace();
                            if (place.formatted_address && window.Livewire) {
                                input.value = place.formatted_address;
                                @this.set('data.location', place.formatted_address);
                            }
                        });
                    }
                } catch (e) {
                    console.warn('Error inicializando autocomplete:', e);
                }
            }
            
            // Inicializar cuando el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', loadGoogleMaps);
            } else {
                loadGoogleMaps();
            }
            
            // Reinicializar después de actualizaciones de Livewire
            if (window.Livewire) {
                Livewire.hook('morph.updated', () => {
                    setTimeout(initAutocomplete, 200);
                });
            }
        })();
    </script>
    <style>
        .pac-container {
            z-index: 10000 !important;
        }
    </style>
</x-filament-panels::page>
