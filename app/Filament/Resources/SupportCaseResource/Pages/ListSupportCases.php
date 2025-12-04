<?php

namespace App\Filament\Resources\SupportCaseResource\Pages;

use App\Filament\Resources\SupportCaseResource;
use App\Filament\Resources\SupportCaseResource\Widgets\SupportCaseStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportCases extends ListRecords
{
    protected static string $resource = SupportCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Ticket'),
        ];
    }

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }


}
