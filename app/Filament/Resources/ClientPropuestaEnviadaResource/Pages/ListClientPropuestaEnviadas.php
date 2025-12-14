<?php

namespace App\Filament\Resources\ClientPropuestaEnviadaResource\Pages;

use App\Filament\Resources\ClientPropuestaEnviadaResource;
use App\Models\Client;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Mail;

class ListClientPropuestaEnviadas extends ListRecords
{
    protected static string $resource = ClientPropuestaEnviadaResource::class;
    
    protected static string $view = 'filament.resources.client-propuesta-enviada-resource.pages.list-client-propuesta-enviadas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('propuesta_personalizada')
                ->label('Propuesta Personalizada')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->modalHeading('📧 Enviar Propuesta Personalizada')
                ->modalWidth('2xl')
                ->form([
                    Forms\Components\Select::make('cliente_id')
                        ->label('Cliente')
                        ->options(Client::orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, $state) {
                            if ($state) {
                                $client = Client::find($state);
                                if ($client?->email) {
                                    $set('email', $client->email);
                                }
                            }
                        }),
                    Forms\Components\TextInput::make('email')
                        ->label('📧 Correo Electrónico')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('subject')
                        ->label('Asunto')
                        ->required()
                        ->maxLength(255)
                        ->default('Propuesta Personalizada'),
                    Forms\Components\Textarea::make('body')
                        ->label('Mensaje')
                        ->required()
                        ->rows(10)
                        ->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    try {
                        $client = Client::find($data['cliente_id']);
                        
                        Mail::raw($data['body'], function ($message) use ($data, $client) {
                            $message->to($data['email'])
                                    ->subject($data['subject']);
                        });
                        
                        Notification::make()
                            ->title('✅ Email enviado')
                            ->body('La propuesta personalizada ha sido enviada a ' . $data['email'])
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('❌ Error')
                            ->body('Error al enviar el email: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}







