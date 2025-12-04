<?php

namespace App\Filament\Resources\OpenSupportCaseResource\Pages;

use App\Filament\Resources\OpenSupportCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOpenSupportCase extends ViewRecord
{
    protected static string $resource = OpenSupportCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
