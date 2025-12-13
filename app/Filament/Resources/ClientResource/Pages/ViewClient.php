<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Pages\EmailComposer;
use App\Filament\Pages\HistorialPage;
use App\Models\Note;
use App\Models\Client;
use App\Models\Factura;
use App\Models\Cotizacion;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\DB;

class ViewClient extends ViewRecord implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ClientResource::class;
    
    protected static string $view = 'filament.resources.client-resource.pages.view-client';

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->record->load('notes.user');
    }
    
    public function table(Table $table): Table
    {
        $clientId = $this->record->id;
        
        // Query para notas del cliente
        $notesQuery = Note::query()
            ->where('client_id', $clientId)
            ->select([
                'notes.id',
                'notes.client_id',
                DB::raw("NULL as cliente_id"),
                'notes.content',
                'notes.type',
                'notes.user_id',
                'notes.created_at',
                'notes.updated_at',
                DB::raw("'note' as record_type"),
                DB::raw("NULL as propuesta"),
                DB::raw("NULL as name"),
                DB::raw("NULL as enlace"),
            ]);
        
        // Envolver en una subquery para poder ordenar
        $unifiedQuery = Note::query()
            ->fromSub($notesQuery, 'unified')
            ->orderBy('created_at', 'desc')
            ->select('unified.*');

        return $table
            ->query($unifiedQuery)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Creado por')
                    ->placeholder('Sistema')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        if ($state) {
                            $user = \App\Models\User::find($state);
                            return $user?->name ?? 'Sistema';
                        }
                        return 'Sistema';
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'note' => 'gray',
                        'call' => 'primary',
                        'meeting' => 'info',
                        'email' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'note' => 'Nota',
                        'call' => 'Llamada',
                        'meeting' => 'Reunión',
                        'email' => 'Email',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->label('Detalles')
                    ->wrap()
                    ->searchable()
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        $getValue = function($key) use ($record) {
                            if (is_array($record)) {
                                return $record[$key] ?? null;
                            }
                            return $record->$key ?? null;
                        };
                        
                        $recordId = $getValue('id');
                        $recordType = $getValue('record_type');
                        
                        // Nota - enlace al view de la nota
                        if ($recordType === 'note' && $recordId) {
                            $url = \App\Filament\Resources\NoteResource::getUrl('view', ['record' => $recordId]);
                            $contentHtml = '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>';
                            return '<a href="' . $url . '" class="text-primary-600 dark:text-primary-400 hover:underline">' . $contentHtml . '</a>';
                        }
                        
                        return '<div class="whitespace-pre-wrap">' . nl2br(e($state)) . '</div>';
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No hay actividades registradas')
            ->emptyStateDescription('Las actividades de este cliente aparecerán aquí.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

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

                    // Recargar la relación de notas
                    $this->record->refresh();
                    $this->record->load('notes.user');

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
