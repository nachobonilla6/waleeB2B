<?php

namespace App\Filament\Resources\VelaSportPostResource\Pages;

use App\Filament\Resources\VelaSportPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVelaSportPost extends EditRecord
{
    protected static string $resource = VelaSportPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

