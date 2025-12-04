<?php

namespace App\Filament\Resources\OpenSupportCaseResource\Pages;

use App\Filament\Resources\OpenSupportCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOpenSupportCase extends CreateRecord
{
    protected static string $resource = OpenSupportCaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
