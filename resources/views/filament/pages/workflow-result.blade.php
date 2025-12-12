<div class="space-y-4">
    @if(isset($result))
        <div>
            <h3 class="text-lg font-semibold mb-2">Resultado:</h3>
            <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96 text-sm">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif

    @if(isset($data) && !empty($data))
        <div>
            <h3 class="text-lg font-semibold mb-2">Datos Adicionales:</h3>
            <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96 text-sm">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif

    @if(!isset($result) && (!isset($data) || empty($data)))
        <p class="text-gray-500 dark:text-gray-400">No hay resultados disponibles.</p>
    @endif
</div>
