<div class="space-y-4">
    {{-- Imagen --}}
    @if($record->imagen)
        <div class="rounded-lg overflow-hidden">
            <img 
                src="{{ $record->imagen }}" 
                alt="{{ $record->titulo }}"
                class="w-full max-h-64 object-cover"
                onerror="this.style.display='none'"
            >
        </div>
    @endif

    {{-- Texto --}}
    @if($record->texto)
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $record->texto }}</p>
        </div>
    @endif

    {{-- Hashtags --}}
    @if($record->hashtags && count($record->hashtags) > 0)
        <div class="flex flex-wrap gap-2">
            @foreach($record->hashtags as $hashtag)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                    {{ $hashtag }}
                </span>
            @endforeach
        </div>
    @endif

    {{-- Footer --}}
    @if($record->footer)
        <div class="border-t dark:border-gray-700 pt-3">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->footer }}</p>
        </div>
    @endif

    {{-- Info --}}
    <div class="border-t dark:border-gray-700 pt-3 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
        <span>Recibido: {{ $record->created_at->format('d/m/Y H:i') }}</span>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
            @if($record->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
            @elseif($record->status === 'published') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
            @elseif($record->status === 'scheduled') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
            @else bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
            @endif
        ">
            {{ ucfirst($record->status) }}
        </span>
    </div>
</div>



