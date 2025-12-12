<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Pages\EmailComposer;
use App\Filament\Pages\HistorialPage;
use App\Models\Note;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    /**
     * Botones de acción.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('enviar_factura')
                ->label('Enviar Factura')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->disabled()
                ->tooltip('Próximamente'),
            Actions\Action::make('enviar_cotizacion')
                ->label('Enviar Cotización')
                ->icon('heroicon-o-currency-dollar')
                ->color('gray')
                ->disabled()
                ->tooltip('Próximamente'),
            Actions\Action::make('redactar_email')
                ->label('Redactar Email')
                ->icon('heroicon-o-envelope')
                ->color('primary')
                ->url(fn () => EmailComposer::getUrl())
                ->openUrlInNewTab(),
            Actions\Action::make('agregar_nota')
                ->label('Agregar Nota')
                ->icon('heroicon-o-pencil-square')
                ->color('primary')
                ->form([
                    Forms\Components\Textarea::make('content')
                        ->label('Contenido de la nota')
                        ->required()
                        ->rows(5)
                        ->placeholder('Escribe tu nota aquí...')
                        ->maxLength(5000),
                    Forms\Components\Select::make('type')
                        ->label('Tipo')
                        ->options([
                            'note' => 'Nota',
                            'call' => 'Llamada',
                            'meeting' => 'Reunión',
                            'email' => 'Email',
                        ])
                        ->default('note')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $client = $this->record;
                    
                    Note::create([
                        'client_id' => $client->id,
                        'content' => $data['content'],
                        'type' => $data['type'],
                        'user_id' => auth()->id(),
                    ]);

                    Notification::make()
                        ->title('Nota agregada')
                        ->body('La nota se ha guardado correctamente.')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('historial')
                ->label('Historial')
                ->icon('heroicon-o-clock')
                ->color('primary')
                ->url(fn () => HistorialPage::getUrl())
                ->openUrlInNewTab(),
        ];
    }
}
