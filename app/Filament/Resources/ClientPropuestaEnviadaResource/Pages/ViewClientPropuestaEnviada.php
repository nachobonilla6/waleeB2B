<?php

namespace App\Filament\Resources\ClientPropuestaEnviadaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ClientPropuestaEnviadaResource;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class ViewClientPropuestaEnviada extends ViewRecord
{
    protected static string $resource = ClientPropuestaEnviadaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Información del Cliente')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nombre'),
                        TextEntry::make('email')
                            ->label('Correo Electrónico')
                            ->url(fn ($record) => 'mailto:' . $record->email)
                            ->openUrlInNewTab(),
                        TextEntry::make('address')
                            ->label('Dirección')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->address)),
                        TextEntry::make('telefono_1')
                            ->label('Teléfono 1')
                            ->url(fn ($record) => $record->telefono_1 ? 'tel:' . $record->telefono_1 : null)
                            ->default('-'),
                        TextEntry::make('telefono_2')
                            ->label('Teléfono 2')
                            ->url(fn ($record) => $record->telefono_2 ? 'tel:' . $record->telefono_2 : null)
                            ->default('-'),
                        TextEntry::make('website')
                            ->label('Sitio Web')
                            ->url(fn ($record) => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : 'https://' . $record->website) : null)
                            ->openUrlInNewTab()
                            ->visible(fn ($record) => !empty($record->website)),
                        TextEntry::make('proposed_site')
                            ->label('Sitio Propuesto')
                            ->url(fn ($record) => $record->proposed_site ? (str_starts_with($record->proposed_site, 'http') ? $record->proposed_site : 'https://' . $record->proposed_site) : null)
                            ->openUrlInNewTab()
                            ->visible(fn ($record) => !empty($record->proposed_site)),
                        \Filament\Infolists\Components\IconEntry::make('propuesta_enviada')
                            ->label('Propuesta Enviada')
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        TextEntry::make('feedback')
                            ->label('Feedback')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->feedback)),
                        TextEntry::make('propuesta')
                            ->label('Propuesta')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->propuesta)),
                        TextEntry::make('created_at')
                            ->label('Fecha de Registro')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2)
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

