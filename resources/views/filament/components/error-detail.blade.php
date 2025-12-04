<div class="space-y-4">
    <div>
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mensaje de Error</h4>
        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->error_message ?: 'Sin mensaje' }}</p>
    </div>

    @if($record->error_stack)
    <div>
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stack Trace</h4>
        <pre class="mt-1 text-xs bg-gray-100 dark:bg-gray-800 p-3 rounded-lg overflow-x-auto text-gray-900 dark:text-gray-100">{{ $record->error_stack }}</pre>
    </div>
    @endif

    <div class="grid grid-cols-2 gap-4">
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Workflow</h4>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->workflow_name ?: '-' }}</p>
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nodo</h4>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->last_node_executed ?: '-' }}</p>
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Modo</h4>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->mode ?: '-' }}</p>
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha</h4>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    @if($record->execution_url)
    <div>
        <a href="{{ $record->execution_url }}" target="_blank" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-500">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Ver ejecución en n8n
        </a>
    </div>
    @endif
</div>




