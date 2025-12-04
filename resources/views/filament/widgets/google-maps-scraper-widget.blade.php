<x-filament-widgets::widget class="fi-wi-chart">
    <x-filament::section>
        <div class="space-y-6">
            <div class="flex items-center space-x-2 mb-6">
                <x-filament::icon
                    icon="heroicon-o-map"
                    class="h-5 w-5 text-primary-500"
                />
                <h2 class="text-xl font-bold tracking-tight">Google Maps Scraper</h2>
            </div>

            <form wire:submit.prevent="submit" class="space-y-6">
                {{ $this->form }}

                <div class="flex justify-end">
                    <x-filament::button type="submit">
                        <span wire:loading.remove>Start Scraping</span>
                        <span wire:loading wire:target="submit" class="flex items-center">
                            <x-filament::loading-indicator class="h-4 w-4 me-2" />
                            Processing...
                        </span>
                    </x-filament::button>
                </div>
            </form>

            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">n8n Setup Instructions:</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li>Create a new workflow in n8n</li>
                    <li>Add a Webhook trigger node</li>
                    <li>Copy the webhook URL and paste it above</li>
                    <li>Add your Google Maps API key in the n8n workflow</li>
                    <li>Process the data as needed (save to database, send email, etc.)</li>
                </ol>
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/30 rounded text-sm text-blue-700 dark:text-blue-300">
                    <strong>Note:</strong> Make sure your n8n instance is accessible from the internet or use a service like ngrok for local development.
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
