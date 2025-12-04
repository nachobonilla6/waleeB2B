<?php

namespace App\Filament\Resources\N8nPostResource\Pages;

use App\Filament\Resources\N8nPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditN8nPost extends EditRecord
{
    protected static string $resource = N8nPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
