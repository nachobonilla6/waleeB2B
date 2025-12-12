<x-filament-panels::page>
    <div class="flex items-center justify-center h-full">
        <div class="text-center">
            <p class="text-gray-500 dark:text-gray-400">Redirigiendo...</p>
        </div>
    </div>
</x-filament-panels::page>

<script>
    window.location.href = '{{ \App\Filament\Resources\ClienteEnProcesoResource::getUrl('listos') }}';
</script>
