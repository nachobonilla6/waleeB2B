<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Searchbar -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="reportes-search"
                        placeholder="Buscar en reportes..." 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                </div>
                <button 
                    type="button"
                    onclick="document.getElementById('reportes-search').value = ''; filterReportes('');"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                >
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Cards de Entradas y Salidas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="reportes-cards">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 reportes-card" data-keywords="entradas facturas pagadas ingresos recibidos">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Entradas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Facturas pagadas - Ingresos recibidos
                </p>
                <a href="{{ \App\Filament\Pages\Entradas::getUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                    Ver Entradas
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 reportes-card" data-keywords="salidas facturas pendientes vencidas pagos pendientes">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Salidas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Facturas pendientes y vencidas - Pagos pendientes
                </p>
                <a href="{{ \App\Filament\Pages\Salidas::getUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                    Ver Salidas
                </a>
            </div>
        </div>
    </div>

    <script>
        function filterReportes(searchTerm) {
            const cards = document.querySelectorAll('.reportes-card');
            const term = searchTerm.toLowerCase().trim();
            
            cards.forEach(card => {
                const keywords = card.getAttribute('data-keywords').toLowerCase();
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                if (term === '' || keywords.includes(term) || title.includes(term) || description.includes(term)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('reportes-search');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    filterReportes(e.target.value);
                });
            }
        });
    </script>
</x-filament-panels::page>

