<?php

namespace App\Filament\Resources\SitioResource\Pages;

use App\Filament\Resources\SitioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSitios extends ListRecords
{
    protected static string $resource = SitioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()?->latest();
    }
    
    protected function getTableRecordsPerPageSelectOptions(): array 
    {
        return [5 => 5, 10, 25, 50];
    }
    
    public function getDefaultTableRecordsPerPageSelectOption(): int 
    {
        return 5;
    }
}
