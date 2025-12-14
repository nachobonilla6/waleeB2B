<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\SupportCase;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class DeployButton extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function ticketsPreviewAction(): Action
    {
        return Action::make('tickets_preview')
            ->label('Preview Tickets')
            ->icon('heroicon-o-ticket')
            ->color('primary')
            ->size('sm')
            ->modalHeading('📋 Lista de Tickets')
            ->modalWidth('6xl')
            ->modalContent(fn () => view('livewire.tickets-preview', ['component' => $this]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(SupportCase::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Asunto')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn (SupportCase $record): string => $record->title),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'in_progress' => 'info',
                        'resolved' => 'success',
                        'closed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => 
                        match ($state) {
                            'open' => 'Abierto',
                            'in_progress' => 'En Progreso',
                            'resolved' => 'Resuelto',
                            'closed' => 'Cerrado',
                            default => $state,
                        }
                    ),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'in_progress' => 'En Progreso',
                        'resolved' => 'Resuelto',
                        'closed' => 'Cerrado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SupportCase $record) => \App\Filament\Resources\SupportCaseResource::getUrl('view', ['record' => $record])),
            ])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([5, 10, 25, 50]);
    }

    public function chatAction(): Action
    {
        return Action::make('chat')
            ->label('Walee Chat')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->color('info')
            ->size('sm')
            ->url('/walee');
    }

    public function extraerClientesAction(): Action
    {
        return Action::make('extraer_clientes')
            ->label('Extraer Clientes')
            ->icon('heroicon-o-magnifying-glass')
            ->color('warning')
            ->size('sm')
            ->url('/admin/clientes-google-copias');
    }

    public function render()
    {
        return view('livewire.deploy-button');
    }
}
