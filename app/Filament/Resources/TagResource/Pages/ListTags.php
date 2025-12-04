<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    protected function getTableQuery(): ?Builder
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
