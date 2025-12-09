<x-filament-panels::page>
    <div class="h-[calc(100vh-8rem)] flex flex-col bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
        <!-- Chat Header -->
        <div class="bg-primary-600 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">W</div>
                <div class="ml-3">
                    <h1 class="text-white font-semibold text-lg">WALEE</h1>
                    <div class="text-indigo-100 text-sm">websolutions.work</div>
                    <div class="flex items-center mt-1">
                        <span class="h-2 w-2 rounded-full bg-green-400 mr-2"></span>
                        <span class="text-xs text-indigo-100">En línea</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 bg-white dark:bg-gray-800 space-y-4">
            <!-- Agent Message -->
            <div class="flex items-start">
                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-medium">
                    S
                </div>
                <div class="ml-3 max-w-xs lg:max-w-md">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 inline-block">
                        <p class="text-sm text-gray-800 dark:text-gray-200">¡Hola! Soy WALEE, tu asistente de websolutions.work. ¿En qué puedo ayudarte hoy?</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">websolutions.work - Ahora</p>
                </div>
            </div>

            <!-- User Message -->
            <div class="flex items-start justify-end">
                <div class="max-w-xs lg:max-w-md text-right">
                    <div class="bg-indigo-100 dark:bg-indigo-900 rounded-lg p-3">
                        <p class="text-sm text-gray-800 dark:text-gray-200">Hola, necesito ayuda con mi cuenta.</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tú - Ahora</p>
                </div>
                <div class="ml-3 flex-shrink-0 h-8 w-8 rounded-full bg-indigo-600 dark:bg-indigo-500 flex items-center justify-center text-white font-medium">
                    {{ \Illuminate\Support\Str::substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-4 flex-shrink-0">
            <form class="flex items-end space-x-3">
                <div class="flex-1 relative">
                    <textarea 
                        rows="2"
                        placeholder="Escribe tu mensaje..."
                        class="w-full rounded-xl border-0 py-4 px-5 pr-14 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 resize-none transition-all duration-200 min-h-[60px] max-h-48 text-base leading-relaxed overflow-y-auto"
                        style="scrollbar-width: thin;"
                    ></textarea>
                    <div class="absolute right-3 bottom-3 flex space-x-2">
                        <button type="button" class="p-1 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </button>
                        <button type="button" class="p-1 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <button 
                    type="submit" 
                    class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-primary-600 hover:bg-primary-700 text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 mb-1"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</x-filament-panels::page>

