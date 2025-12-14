<div
    class="flex items-center gap-2"
    x-data
    x-on:reload-page.window="window.location.reload()"
>
    {{ $this->chatAction }}
    {{ $this->extraerClientesAction }}

    <x-filament-actions::modals />
</div>
