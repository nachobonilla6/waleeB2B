<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\N8nService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class N8nWorkflows extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'N8N Automatizaciones';
    protected static ?string $title = 'N8N Automatizaciones';
    protected static ?string $navigationGroup = 'Soporte';
    protected static ?int $navigationSort = 11;
    
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected static string $view = 'filament.pages.n8n-workflows';

    public array $workflows = [];
    public bool $isLoading = false;
    public ?string $error = null;

    public function mount(): void
    {
        $this->loadWorkflows();
    }

    public function loadWorkflows(): void
    {
        $this->isLoading = true;
        $this->error = null;

        try {
            $n8nService = new N8nService();
            $this->workflows = $n8nService->getWorkflows();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            $this->workflows = [];
        } finally {
            $this->isLoading = false;
        }
    }

    public function syncWorkflowToBots(string $workflowId): void
    {
        try {
            $n8nService = new N8nService();
            $workflow = $n8nService->getWorkflow($workflowId);

            if (!$workflow) {
                Notification::make()
                    ->title('Error')
                    ->body('No se pudo obtener la información del workflow.')
                    ->danger()
                    ->send();
                return;
            }

            // Buscar si ya existe un bot con este workflow_id
            $bot = \App\Models\N8nBot::where('workflow_id', $workflowId)->first();

            if ($bot) {
                // Actualizar bot existente
                $bot->update([
                    'name' => $workflow['name'] ?? $bot->name,
                    'settings' => array_merge($bot->settings ?? [], [
                        'active' => $workflow['active'] ?? false,
                        'nodes' => count($workflow['nodes'] ?? []),
                        'updated_at_n8n' => now()->toDateTimeString(),
                    ]),
                ]);

                Notification::make()
                    ->title('Bot actualizado')
                    ->body("El bot '{$bot->name}' se ha actualizado con la información de n8n.")
                    ->success()
                    ->send();
            } else {
                // Crear nuevo bot
                $bot = \App\Models\N8nBot::create([
                    'name' => $workflow['name'] ?? 'Workflow sin nombre',
                    'workflow_id' => $workflowId,
                    'trigger_type' => 'manual',
                    'settings' => [
                        'active' => $workflow['active'] ?? false,
                        'nodes' => count($workflow['nodes'] ?? []),
                        'created_from_n8n' => true,
                        'created_at_n8n' => now()->toDateTimeString(),
                    ],
                ]);

                Notification::make()
                    ->title('Bot creado')
                    ->body("Se ha creado un nuevo bot '{$bot->name}' desde n8n.")
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Actualizar')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->loadWorkflows()),
            Action::make('sync_all')
                ->label('Sincronizar Todos')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Sincronizar Todos los Workflows')
                ->modalDescription('¿Deseas sincronizar todos los workflows de n8n a la base de datos de bots?')
                ->action(function () {
                    $n8nService = new N8nService();
                    $workflows = $n8nService->getWorkflows();
                    $synced = 0;

                    foreach ($workflows as $workflow) {
                        try {
                            $workflowId = $workflow['id'] ?? null;
                            if ($workflowId) {
                                $this->syncWorkflowToBots($workflowId);
                                $synced++;
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }

                    Notification::make()
                        ->title('Sincronización completada')
                        ->body("Se sincronizaron {$synced} workflows.")
                        ->success()
                        ->send();

                    $this->loadWorkflows();
                }),
        ];
    }
}

