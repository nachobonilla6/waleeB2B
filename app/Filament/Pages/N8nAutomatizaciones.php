<?php

namespace App\Filament\Pages;

use App\Services\N8nService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class N8nAutomatizaciones extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Automatizaciones n8n';
    protected static ?string $title = 'Automatizaciones n8n';
    protected static ?string $navigationGroup = 'Automatizaciones';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.n8n-automatizaciones';

    protected N8nService $n8nService;
    public Collection $workflows;
    public ?string $filterActive = null;
    public string $search = '';

    public function mount(): void
    {
        $this->n8nService = new N8nService();
        $this->loadWorkflows();
    }

    protected function loadWorkflows(): void
    {
        $workflows = $this->n8nService->getWorkflows();
        // La API de n8n puede devolver los datos en diferentes formatos
        if (isset($workflows['data']) && is_array($workflows['data'])) {
            $this->workflows = collect($workflows['data']);
        } elseif (is_array($workflows)) {
            $this->workflows = collect($workflows);
        } else {
            $this->workflows = collect([]);
        }
    }

    public function updatedSearch(): void
    {
        // El filtro se aplica en la vista
    }

    public function updatedFilterActive(): void
    {
        // El filtro se aplica en la vista
    }

    public function getFilteredWorkflows(): Collection
    {
        $workflows = $this->workflows;

        // Aplicar búsqueda
        if (!empty($this->search)) {
            $workflows = $workflows->filter(function ($workflow) {
                return stripos($workflow['name'] ?? '', $this->search) !== false ||
                       stripos($workflow['description'] ?? '', $this->search) !== false;
            });
        }

        // Aplicar filtro de estado
        if ($this->filterActive !== null) {
            $workflows = $workflows->filter(function ($workflow) {
                return $workflow['active'] == ($this->filterActive === 'true');
            });
        }

        // Ordenar por fecha de actualización (puede venir como updatedAt o updated_at)
        return $workflows->sortByDesc(function ($workflow) {
            return $workflow['updatedAt'] ?? $workflow['updated_at'] ?? $workflow['createdAt'] ?? $workflow['created_at'] ?? 0;
        });
    }

    public function toggleWorkflow(string $workflowId, bool $active): void
    {
        $this->n8nService = new N8nService();
        $success = $this->n8nService->toggleWorkflow($workflowId, $active);
        
        if ($success) {
            $workflow = $this->workflows->firstWhere('id', $workflowId);
            Notification::make()
                ->title('Workflow actualizado')
                ->body("El workflow '{$workflow['name']}' ha sido " . ($active ? 'activado' : 'desactivado'))
                ->success()
                ->send();
            
            $this->loadWorkflows();
        } else {
            Notification::make()
                ->title('Error')
                ->body('No se pudo actualizar el workflow')
                ->danger()
                ->send();
        }
    }

    public function executeWorkflow(string $workflowId): void
    {
        $this->n8nService = new N8nService();
        $result = $this->n8nService->executeWorkflow($workflowId);
        
        $workflow = $this->workflows->firstWhere('id', $workflowId);
        
        if ($result) {
            Notification::make()
                ->title('Workflow ejecutado')
                ->body("El workflow '{$workflow['name']}' se está ejecutando")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Error')
                ->body('No se pudo ejecutar el workflow')
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
                ->action(function () {
                    $this->loadWorkflows();
                    Notification::make()
                        ->title('Workflows actualizados')
                        ->success()
                        ->send();
                }),
            
            Action::make('open_n8n')
                ->label('Abrir n8n')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url('https://n8n.srv1137974.hstgr.cloud')
                ->openUrlInNewTab(),
        ];
    }
}
