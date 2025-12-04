<?php

namespace App\Filament\Resources\N8nBotResource\Pages;

use App\Filament\Resources\N8nBotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListN8nBots extends ListRecords
{
    protected static string $resource = N8nBotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('n8n_dashboard')
                ->label('Abrir n8n')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url('https://n8n.srv1137974.hstgr.cloud')
                ->openUrlInNewTab()
                ->color('gray'),
            Actions\CreateAction::make(),
        ];
    }
}
