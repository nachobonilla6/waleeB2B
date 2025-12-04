<?php

namespace App\Filament\Resources\N8nBotResource\Pages;

use App\Filament\Resources\N8nBotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditN8nBot extends EditRecord
{
    protected static string $resource = N8nBotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
