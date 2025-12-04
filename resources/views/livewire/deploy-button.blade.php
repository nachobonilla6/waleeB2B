<div
    class="flex items-center"
    x-data
    x-on:reload-page.window="window.location.reload()"
>
    {{ $this->deployAction }}

    <x-filament-actions::modals />
</div>
