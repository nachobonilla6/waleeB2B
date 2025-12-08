<?php

namespace App\Filament\Resources\PublicacionVelaResource\Pages;

use App\Filament\Resources\PublicacionVelaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;

class CreatePublicacionVela extends CreateRecord
{
    protected static string $resource = PublicacionVelaResource::class;

    protected static bool $canCreateAnother = false;

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::FourExtraLarge;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
