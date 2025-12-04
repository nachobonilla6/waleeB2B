<?php

namespace App\Filament\Resources\OpenSupportCaseResource\Pages;

use App\Filament\Resources\OpenSupportCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOpenSupportCase extends EditRecord
{
    protected static string $resource = OpenSupportCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
