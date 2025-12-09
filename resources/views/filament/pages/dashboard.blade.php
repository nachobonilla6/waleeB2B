<x-filament-panels::page>
    <!-- Enlaces rápidos arriba de los widgets -->
    <div class="mb-6 flex flex-wrap gap-3">
        <a 
            href="{{ route('chat') }}" 
            target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2 bg-success-600 hover:bg-success-700 text-white rounded-lg font-medium transition-colors shadow-sm"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            Chat
        </a>
    </div>

    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="$this->getColumns()"
    />
</x-filament-panels::page>
