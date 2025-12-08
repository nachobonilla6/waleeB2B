<?php

namespace App\Filament\Resources\PublicacionVelaResource\Pages;

use App\Filament\Resources\PublicacionVelaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\MaxWidth;

class EditPublicacionVela extends EditRecord
{
    protected static string $resource = PublicacionVelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::FourExtraLarge;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
