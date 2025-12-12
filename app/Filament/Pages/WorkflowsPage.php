<?php

namespace App\Filament\Pages;

use App\Models\WorkflowRun;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;

class WorkflowsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Workflows';
    protected static ?string $title = 'Workflows';
    protected static ?string $navigationGroup = 'Soporte';
    protected static ?int $navigationSort = 101;

    protected static string $view = 'filament.pages.workflows-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(WorkflowRun::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('job_id')
                    ->label('ID del Trabajo')
                    ->copyable()
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('workflow_name')
                    ->label('Workflow')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Sin nombre'),
                Tables\Columns\TextColumn::make('step')
                    ->label('Paso Actual')
                    ->placeholder('En cola')
                    ->wrap(),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progreso')
                    ->formatStateUsing(fn ($state, $record) => 
                        $record && $record->status === 'completed' ? '100%' : 
                        ($state . '%')
                    )
                    ->sortable(),
                Tables\Columns\ViewColumn::make('progress')
                    ->label('Barra de Progreso')
                    ->view('filament.tables.columns.progress-bar'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'running' => 'primary',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'running' => 'Ejecutando',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(50)
                    ->visible(fn ($record) => $record && $record->status === 'failed')
                    ->wrap(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Iniciado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
                Tables\Actions\Action::make('view_payload')
                    ->label('Ver Payload')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->visible(fn ($record) => $record && $record->data !== null)
                    ->modalHeading('Payload del Workflow')
                    ->modalContent(fn ($record) => view('filament.pages.workflow-payload', [
                        'data' => $record->data,
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
            ->poll('3s') // Auto-refresh cada 3 segundos
            ->defaultSort('created_at', 'desc');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('start_workflow')
                ->label('Iniciar Workflow')
                ->icon('heroicon-o-play')
                ->color('success')
                ->form([
                    Forms\Components\TextInput::make('workflow_name')
                        ->label('Nombre del Workflow')
                        ->placeholder('Ej: Extracción de clientes')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('webhook_url')
                        ->label('URL del Webhook de n8n')
                        ->placeholder('https://n8n.srv1137974.hstgr.cloud/webhook/...')
                        ->url()
                        ->required()
                        ->helperText('URL del webhook de n8n que iniciará el workflow'),
                    Forms\Components\Textarea::make('data')
                        ->label('Datos Adicionales (JSON)')
                        ->placeholder('{"query": "valor", "param": "valor"}')
                        ->helperText('Datos adicionales a enviar al workflow (opcional)')
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    try {
                        $jobId = Str::uuid();

                        // Crear el registro del workflow
                        $workflowRun = WorkflowRun::create([
                            'job_id' => $jobId,
                            'status' => 'pending',
                            'progress' => 0,
                            'step' => 'En cola',
                            'workflow_name' => $data['workflow_name'] ?? null,
                        ]);

                        // Preparar datos para enviar a n8n
                        $payload = [
                            'job_id' => $jobId,
                            'progress_url' => url('/api/n8n/progress'), // URL para que n8n reporte progreso
                        ];

                        // Agregar datos adicionales si se proporcionaron
                        if (!empty($data['data'])) {
                            $parsedData = json_decode($data['data'], true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $payload = array_merge($payload, $parsedData);
                            }
                        }

                        // Llamar al webhook de n8n
                        $response = Http::timeout(120)->post($data['webhook_url'], $payload);

                        if ($response->successful()) {
                            $workflowRun->update([
                                'status' => 'running',
                                'step' => 'Iniciado',
                                'started_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Workflow iniciado')
                                ->body('El workflow se ha iniciado correctamente. ID: ' . substr($jobId, 0, 8))
                                ->success()
                                ->send();
                        } else {
                            $workflowRun->update([
                                'status' => 'failed',
                                'step' => 'Error al iniciar workflow',
                                'error_message' => 'Error al iniciar workflow: ' . $response->status(),
                                'completed_at' => null, // No marcar como completado si falla
                            ]);

                            Notification::make()
                                ->title('Error al iniciar workflow')
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
                                'completed_at' => null, // No marcar como completado si falla
                            ]);
                        }

                        Notification::make()
                            ->title('Error')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
