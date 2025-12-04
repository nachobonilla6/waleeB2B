<?php

namespace App\Filament\Resources\N8nErrorResource\Pages;

use App\Filament\Resources\N8nErrorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditN8nError extends EditRecord
{
    protected static string $resource = N8nErrorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
