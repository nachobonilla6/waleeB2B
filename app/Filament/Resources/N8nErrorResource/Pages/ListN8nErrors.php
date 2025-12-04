<?php

namespace App\Filament\Resources\N8nErrorResource\Pages;

use App\Filament\Resources\N8nErrorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListN8nErrors extends ListRecords
{
    protected static string $resource = N8nErrorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('open_n8n')
                ->label('Abrir n8n')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url('https://n8n.srv1137974.hstgr.cloud')
                ->openUrlInNewTab()
                ->color('info'),
            Actions\Action::make('mark_all_resolved')
                ->label('Resolver todos')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Resolver todos los errores')
                ->modalDescription('¿Estás seguro de marcar todos los errores nuevos como resueltos?')
                ->action(fn () => \App\Models\N8nError::where('status', 'new')->update(['status' => 'resolved']))
                ->visible(fn () => \App\Models\N8nError::where('status', 'new')->exists()),
        ];
    }
}
