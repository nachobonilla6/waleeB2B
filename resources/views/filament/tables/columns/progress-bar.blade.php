@php
    $record = $getRecord();
    $progress = $record->progress ?? 0;
    $status = $record->status ?? 'pending';
    
    // Determinar color según estado
    $colorClass = match($status) {
        'pending' => 'bg-gray-400',
        'running' => 'bg-primary-600',
        'completed' => 'bg-success-600',
        'failed' => 'bg-danger-600',
        default => 'bg-gray-400',
    };
    
    // Asegurar que completed muestre 100%
    if ($status === 'completed') {
        $progress = 100;
    }
@endphp

<div class="w-full">
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
        <div 
            class="{{ $colorClass }} h-2.5 rounded-full transition-all duration-300"
            style="width: {{ $progress }}%"
        ></div>
    </div>
</div>
