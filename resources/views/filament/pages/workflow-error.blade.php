<div class="space-y-4">
    <div class="bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-danger-900 dark:text-danger-100 mb-2">
            <span class="inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Error en el Workflow
            </span>
        </h3>
        
        @if($record->error_message)
            <div class="mt-3">
                <p class="text-sm font-medium text-danger-800 dark:text-danger-200 mb-1">Mensaje de error:</p>
                <p class="text-sm text-danger-700 dark:text-danger-300 bg-white dark:bg-gray-800 p-3 rounded border border-danger-200 dark:border-danger-700">
                    {{ $record->error_message }}
                </p>
            </div>
        @endif
        
        @if($record->step)
            <div class="mt-3">
                <p class="text-sm font-medium text-danger-800 dark:text-danger-200 mb-1">Paso donde falló:</p>
                <p class="text-sm text-danger-700 dark:text-danger-300">
                    {{ $record->step }}
                </p>
            </div>
        @endif
    </div>

    <div>
        <h3 class="text-lg font-semibold mb-2">Información del Trabajo</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="font-medium text-gray-600 dark:text-gray-400">ID del Trabajo:</span>
                <span class="font-mono text-gray-900 dark:text-gray-100">{{ $record->job_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600 dark:text-gray-400">Estado:</span>
                <span class="px-2 py-1 rounded text-xs font-semibold bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200">
                    Fallido
                </span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600 dark:text-gray-400">Progreso:</span>
                <span class="text-gray-900 dark:text-gray-100">{{ $record->progress }}%</span>
            </div>
            @if($record->started_at)
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600 dark:text-gray-400">Iniciado:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $record->started_at->format('d/m/Y H:i:s') }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span class="font-medium text-gray-600 dark:text-gray-400">Creado:</span>
                <span class="text-gray-900 dark:text-gray-100">{{ $record->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>
    </div>

    @if($record->data)
        <div>
            <h3 class="text-lg font-semibold mb-2">Datos del Workflow</h3>
            <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96 text-sm">{{ json_encode($record->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    @endif
</div>
