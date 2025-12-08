<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Enviar Datos (Site Scraper)</h3>
    <form wire:submit="enviar" class="space-y-4">
        {{ $this->form }}
        <div class="flex justify-end">
            <x-filament::button
                type="submit"
                color="success"
                size="sm"
            >
                Enviar a Webhook
            </x-filament::button>
        </div>
    </form>
</div>
