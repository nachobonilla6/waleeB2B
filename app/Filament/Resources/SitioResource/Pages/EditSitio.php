<?php

namespace App\Filament\Resources\SitioResource\Pages;

use App\Filament\Resources\SitioResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class EditSitio extends EditRecord
{
    protected static string $resource = SitioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('deleteImage')
                ->label('Eliminar imagen')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->action(function () {
                    $record = $this->record;
                    
                    // Delete the file from storage
                    if ($record->imagen) {
                        Storage::disk('public')->delete($record->imagen);
                    }
                    
                    // Clear the field
                    $record->imagen = null;
                    $record->save();
                    
                    // Show success notification
                    Notification::make()
                        ->title('Imagen eliminada')
                        ->success()
                        ->send();
                        
                    // Refresh the form
                    $this->fillForm();
                })
                ->visible(fn () => !empty($this->record?->imagen)),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
