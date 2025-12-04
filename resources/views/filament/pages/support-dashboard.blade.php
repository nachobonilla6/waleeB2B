<x-filament::page>
    <div class="space-y-6">
        @foreach ($this->getHeaderWidgets() as $widget)
            {{ \Filament\Facades\Filament::renderHook('widgets::before') }}
            {{ $widget }}
            {{ \Filament\Facades\Filament::renderHook('widgets::after') }}
        @endforeach
    </div>
</x-filament::page>
