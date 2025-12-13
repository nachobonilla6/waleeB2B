<?php

namespace App\Filament\Pages;
use App\Models\WorkflowRun;
use App\Models\Client;
use App\Filament\Resources\ClienteEnProcesoResource;
use App\Filament\Resources\ClientesGoogleEnviadasResource;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ListClientesGoogleCopias extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.resources.clientes-google-copia-resource.pages.list-clientes-google-copias';
    
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Site Scraper';
    protected static ?string $title = 'Site Scraper';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'list-clientes-google-copias';

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::Full;
    }

    public function getTitle(): string
    {
        return 'Site Scraper';
    }

    public function getHeading(): string
    {
        return 'Site Scraper';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(WorkflowRun::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('data.nombre_lugar')
                    ->label('Lugar')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->getStateUsing(fn ($record) => $record->data['nombre_lugar'] ?? null),
                Tables\Columns\TextColumn::make('data.industria')
                    ->label('Industria')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'tienda_ropa' => '👕 Tienda de Ropa',
                        'pizzeria' => '🍕 Pizzería',
                        'restaurante' => '🍽️ Restaurante',
                        'cafeteria' => '☕ Cafetería',
                        'farmacia' => '💊 Farmacia',
                        'supermercado' => '🛒 Supermercado',
                        'peluqueria' => '✂️ Peluquería / Salón de Belleza',
                        'gimnasio' => '💪 Gimnasio',
                        'veterinaria' => '🐾 Veterinaria',
                        'taller_mecanico' => '🔧 Taller Mecánico',
                        'otro' => '📝 Otro',
                        default => $state ?? 'N/A',
                    })
                    ->getStateUsing(fn ($record) => $record->data['industria'] ?? null),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progreso')
                    ->formatStateUsing(fn ($state, $record) => 
                        $record && $record->status === 'completed' ? '100%' : 
                        ($state . '%')
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('data.message')
                    ->label('Mensaje')
                    ->searchable()
                    ->wrap()
                    ->placeholder('N/A')
                    ->getStateUsing(fn ($record) => $record->data['message'] ?? null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'running' => 'Ejecutando',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_result')
                    ->label('Ver Resultado')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn ($record) => $record && $record->result !== null)
                    ->modalHeading('Resultado del Workflow')
                    ->modalContent(fn ($record) => $record ? view('filament.pages.workflow-result', [
                        'result' => $record->result,
                        'data' => $record->data,
                    ]) : 'No hay datos disponibles')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Tables\Actions\Action::make('view_error')
                    ->label('Ver Error')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn ($record) => $record && $record->status === 'failed')
                    ->modalHeading('Detalles del Error')
                    ->modalContent(fn ($record) => view('filament.pages.workflow-error', [
                        'record' => $record,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Tables\Actions\Action::make('retry')
                    ->label('Reintentar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => $record && $record->status === 'failed')
                    ->requiresConfirmation()
                    ->modalHeading('Reintentar Workflow')
                    ->modalDescription('¿Estás seguro de que deseas reintentar este workflow? Se creará un nuevo registro.')
                    ->action(function ($record) {
                        try {
                            $jobId = Str::uuid();
                            
                            // Obtener datos originales si existen
                            $originalData = $record->data ?? [];
                            
                            // Crear nuevo registro
                            $newWorkflowRun = WorkflowRun::create([
                                'job_id' => $jobId,
                                'status' => 'pending',
                                'progress' => 0,
                                'step' => 'En cola',
                                'workflow_name' => $record->workflow_name,
                                'data' => $originalData,
                            ]);

                            Notification::make()
                                ->title('Workflow en cola para reintento')
                                ->body('Se ha creado un nuevo registro. Debes iniciar el workflow manualmente con el mismo webhook.')
                                ->warning()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al reintentar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->recordClasses(fn ($record) => match($record->status) {
                'failed' => 'border-l-4 border-l-danger-500 bg-danger-50/50 dark:bg-danger-900/10',
                'running' => 'border-l-4 border-l-primary-500 bg-primary-50/50 dark:bg-primary-900/10',
                'completed' => 'border-l-4 border-l-success-500 bg-success-50/50 dark:bg-success-900/10',
                'pending' => 'border-l-4 border-l-warning-500 bg-warning-50/50 dark:bg-warning-900/10',
                default => '',
            })
            ->poll('3s')
            ->defaultSort('created_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        $clientesGoogleUrl = ClienteEnProcesoResource::getUrl('index');
        $listosUrl = ClienteEnProcesoResource::getUrl('listos');
        $propuestasUrl = ClientesGoogleEnviadasResource::getUrl('index');
        $extraerUrl = url('/admin/list-clientes-google-copias');
        $currentUrl = url()->current();

        // Contar clientes pendientes
        $pendingCount = Client::where('estado', 'pending')->count();
        $listosCount = Client::where('estado', 'listo_para_enviar')->count();
        $propuestasCount = Client::where('estado', 'propuesta_enviada')->count();

        return [
            Action::make('start_search')
                ->label('Iniciar Búsqueda')
                ->icon('heroicon-o-magnifying-glass')
                ->color('primary')
                ->form([
                    Forms\Components\TextInput::make('nombre_lugar')
                        ->label('Lugar')
                        ->placeholder('Ej: Heredia, San José, etc.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('industria')
                        ->label('Tipo de Negocio')
                        ->options([
                            'tienda_ropa' => '👕 Tienda de Ropa',
                            'pizzeria' => '🍕 Pizzería',
                            'restaurante' => '🍽️ Restaurante',
                            'cafeteria' => '☕ Cafetería',
                            'farmacia' => '💊 Farmacia',
                            'supermercado' => '🛒 Supermercado',
                            'peluqueria' => '✂️ Peluquería / Salón de Belleza',
                            'gimnasio' => '💪 Gimnasio',
                            'veterinaria' => '🐾 Veterinaria',
                            'taller_mecanico' => '🔧 Taller Mecánico',
                            'otro' => '📝 Otro',
                        ])
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data) {
                    try {
                        $jobId = Str::uuid();
                        $webhookUrl = 'https://n8n.srv1137974.hstgr.cloud/webhook-test/0c01d9a1-788c-44d2-9c1b-9457901d0a3c';

                        // Crear el registro del workflow
                        $workflowRun = WorkflowRun::create([
                            'job_id' => $jobId,
                            'status' => 'pending',
                            'progress' => 0,
                            'step' => 'En cola',
                            'workflow_name' => 'Búsqueda: ' . ($data['nombre_lugar'] ?? 'Sin nombre'),
                            'data' => [
                                'nombre_lugar' => $data['nombre_lugar'],
                                'industria' => $data['industria'],
                            ],
                        ]);

                        // Preparar payload para n8n
                        $payload = [
                            'job_id' => $jobId,
                            'progress_url' => url('/api/n8n/progress'),
                            'nombre_lugar' => $data['nombre_lugar'],
                            'industria' => $data['industria'],
                        ];

                        // Llamar al webhook de n8n
                        $response = Http::timeout(120)->post($webhookUrl, $payload);

                        if ($response->successful()) {
                            $workflowRun->update([
                                'status' => 'running',
                                'step' => 'Iniciado - Buscando lugares',
                                'started_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Búsqueda iniciada')
                                ->body('La búsqueda se ha iniciado correctamente. ID: ' . substr($jobId, 0, 8))
                                ->success()
                                ->send();
                        } else {
                            $workflowRun->update([
                                'status' => 'failed',
                                'step' => 'Error al iniciar búsqueda',
                                'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                                'completed_at' => null,
                            ]);

                            Notification::make()
                                ->title('Error al iniciar búsqueda')
                                ->body('El webhook respondió con error: ' . $response->status())
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        if (isset($workflowRun)) {
                            $workflowRun->update([
                                'status' => 'failed',
                                'step' => 'Error al iniciar',
                                'error_message' => $e->getMessage(),
                                'completed_at' => null,
                            ]);
                        }

                        Notification::make()
                            ->title('Error')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('extraer_nuevos_clientes')
                ->label('Extraer Nuevos Clientes')
                ->url($extraerUrl)
                ->color($currentUrl === $extraerUrl ? 'primary' : 'gray'),
            Action::make('clientes_google')
                ->label('Clientes Google')
                ->url($clientesGoogleUrl)
                ->color($currentUrl === $clientesGoogleUrl ? 'primary' : 'gray')
                ->badge($pendingCount > 0 ? (string) $pendingCount : null)
                ->badgeColor('warning'),
            Action::make('listos_para_enviar')
                ->label('Listos para Enviar')
                ->url($listosUrl)
                ->color($currentUrl === $listosUrl ? 'primary' : 'gray')
                ->badge($listosCount > 0 ? (string) $listosCount : null)
                ->badgeColor('info'),
            Action::make('propuestas_enviadas')
                ->label('Propuestas Enviadas')
                ->url($propuestasUrl)
                ->color($currentUrl === $propuestasUrl ? 'primary' : 'gray')
                ->badge($propuestasCount > 0 ? (string) $propuestasCount : null)
                ->badgeColor('success'),
        ];
    }
}

