@php
    $state = $getState();
    $record = $getRecord();
    $imageUrl = null;
    $alt = 'Vista previa de la imagen';
    
    // Si hay un state, usamos ese (para cuando se sube una nueva imagen)
    if ($state) {
        if (is_array($state)) {
            $imagePath = $state[0] ?? null;
        } else {
            $imagePath = $state;
        }
        
        if (is_string($imagePath)) {
            $imagePath = str_replace('public/', '', $imagePath);
            $imageUrl = str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . ltrim($imagePath, '/'));
        } elseif (is_object($imagePath) && method_exists($imagePath, 'temporaryUrl')) {
            try {
                $imageUrl = $imagePath->temporaryUrl();
            } catch (\Exception $e) {
                // Si falla, no hacemos nada
            }
        }
    }
    // Si no hay state pero hay un registro con imagen
    elseif ($record && $record->imagen) {
        $imagePath = is_array($record->imagen) ? ($record->imagen[0] ?? null) : $record->imagen;
        if ($imagePath) {
            $imagePath = str_replace('public/', '', $imagePath);
            $imageUrl = str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . ltrim($imagePath, '/'));
        }
    }
@endphp

@if($imageUrl)
    <div class="flex justify-center mt-4">
        <div class="relative group w-full max-w-2xl">
            <div 
                x-data="{ open: false }"
                @click="open = true"
                class="relative overflow-hidden rounded-lg shadow-lg border-2 border-gray-200 dark:border-gray-700 transition-all duration-300 transform hover:shadow-xl hover:-translate-y-1 cursor-pointer"
            >
                <div 
                    x-data="{}" 
                    @click="const input = document.querySelector('input[type=\'file\'][name*=\'imagen\']'); if (input) input.click();"
                    class="relative group cursor-pointer"
                >
                    <img 
                        src="{{ $imageUrl }}" 
                        alt="{{ $alt }}" 
                        class="block w-full h-auto max-h-60 mx-auto object-contain transition-opacity duration-200 group-hover:opacity-80"
                        onerror="this.style.display='none'"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <div class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs flex items-center space-x-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Editar</span>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                    <span class="opacity-0 group-hover:opacity-100 text-white bg-primary-500 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Editar imagen</span>
                    </span>
                </div>
            </div>
            <div class="mt-2 text-xs text-center text-gray-500 dark:text-gray-400">
                Haz clic en la imagen para editar
            </div>
            
            <div 
                x-show="open" 
                @click.away="open = false"
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4"
                style="display: none;"
            >
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-auto">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Editor de imagen</h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="{{ $alt }}" 
                            class="w-full h-auto max-h-[70vh] object-contain"
                            onerror="this.style.display='none'"
                        />
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <button 
                                type="button"
                                x-on:click="
                                    if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) { 
                                        const fileInput = document.querySelector('input[type=\'file\'][name*=\'imagen\']');
                                        if (fileInput) {
                                            // Create a new file input to replace the current one
                                            const newInput = document.createElement('input');
                                            newInput.type = 'file';
                                            newInput.name = fileInput.name;
                                            newInput.hidden = true;
                                            
                                            // Replace the old input with the new one
                                            fileInput.parentNode.insertBefore(newInput, fileInput);
                                            fileInput.remove();
                                            
                                            // Trigger change event on the form
                                            const form = newInput.closest('form');
                                            if (form) {
                                                const event = new Event('change', { bubbles: true });
                                                newInput.dispatchEvent(event);
                                            }
                                            
                                            // Close the modal and reload to see changes
                                            open = false;
                                            setTimeout(() => window.location.reload(), 300);
                                        }
                                    }
                                "
                                class="px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 flex items-center space-x-1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Eliminar imagen</span>
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                @click="open = false" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                Cerrar
                            </button>
                            <button 
                                @click="document.querySelector('input[type=\'file\']').click()" 
                                class="px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                            >
                                Cambiar imagen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-center p-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800/50">
        <div class="flex flex-col items-center justify-center space-y-2">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">No hay imagen cargada</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Sube una imagen para ver la vista previa</p>
        </div>
    </div>
@endif