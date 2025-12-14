<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->cliente_nombre ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->email }}</p>
        </div>
    </div>
    
    <div>
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Asunto</label>
        <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $record->subject }}</p>
    </div>
    
    @if($record->ai_prompt)
    <div>
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Prompt usado</label>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 italic">{{ $record->ai_prompt }}</p>
    </div>
    @endif
    
    <div>
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mensaje</label>
        <div class="mt-1 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $record->body }}</div>
        </div>
    </div>
    
    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Enviado por</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->user->name ?? 'Sistema' }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de envío</label>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>
