<?php

namespace App\Filament\Resources\SupportCaseResource\Pages;

use App\Filament\Resources\SupportCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportCase extends EditRecord
{
    protected static string $resource = SupportCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
