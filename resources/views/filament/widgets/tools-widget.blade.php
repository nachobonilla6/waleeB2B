<x-filament-widgets::widget class="filament-tools-widget mb-6 w-full">
    <div class="flex w-full gap-3">
        @foreach($tools as $tool)
            @php
                $color = match($tool['color'] ?? 'gray') {
                    'blue' => 'primary',
                    'green' => 'success',
                    'yellow' => 'warning',
                    'red' => 'danger',
                    'purple' => 'violet',
                    default => 'gray'
                };
            @endphp
            
            <a 
                href="{{ $tool['url'] }}" 
                @if($tool['external'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                class="flex-1 p-3 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow group border border-gray-200 dark:border-gray-700 text-center"
            >
                <div class="flex flex-col items-center space-y-2">
                    <div class="bg-{{ $color }}-50 dark:bg-gray-700 p-2 rounded-lg group-hover:bg-{{ $color }}-100 dark:group-hover:bg-gray-600 transition-colors">
                        <x-dynamic-component 
                            :component="$tool['icon']"
                            class="h-5 w-5 text-{{ $color }}-600 dark:text-{{ $color }}-400"
                        />
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-{{ $color }}-600 dark:group-hover:text-{{ $color }}-400 truncate w-full">
                            {{ $tool['label'] }}
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate w-full">
                            {{ $tool['description'] }}
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-{{ $color }}-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</x-filament-widgets::widget>
