<?php

namespace App\Livewire;

use App\Models\SupportCase;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class SupportCaseList extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $perPage = 10;
    
    #[Url]
    public $sortField = 'created_at';
    
    #[Url]
    public $sortDirection = 'desc';
    
    #[Url]
    public $activeTab = 'open';
    
    public $updatingStatus = [];
    public $openCount = 0;
    public $resolvedCount = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'open'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount($activeTab = 'open')
    {
        $this->activeTab = $activeTab;
        $this->updateCounts();
    }

    public function updatedActiveTab()
    {
        $this->resetPage();
    }

    protected function updateCounts()
    {
        $this->openCount = SupportCase::where('status', 'open')->count();
        $this->resolvedCount = SupportCase::where('status', 'resolved')->count();
    }

    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'notify'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getCasesProperty()
    {
        $query = SupportCase::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            });
            
        if ($this->activeTab === 'resolved') {
            $query->where('status', 'resolved');
        } else {
            $this->activeTab = 'open';
            $query->where('status', 'open');
        }
        
        return $query->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function changeStatus($caseId, $status)
    {
        try {
            $this->updatingStatus[$caseId] = true;
            
            $case = SupportCase::findOrFail($caseId);
            $newStatus = $status === 'open' ? 'resolved' : 'open';
            $case->status = $newStatus;
            
            if ($newStatus === 'resolved') {
                $case->resolved_at = now();
            } else {
                $case->resolved_at = null;
            }
            
            $case->save();
            
            $this->updateCounts();
            $this->dispatch('ticketUpdated');
            
            // Mostrar notificación de éxito
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'El ticket ha sido marcado como ' . ($newStatus === 'resolved' ? 'resuelto' : 'abierto') . ' correctamente.'
            ]);
            
            // Cambiar de pestaña si es necesario
            if (($newStatus === 'resolved' && $this->activeTab === 'open') || 
                ($newStatus === 'open' && $this->activeTab === 'resolved')) {
                $this->activeTab = $newStatus;
            }
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar el estado del ticket: ' . $e->getMessage()
            ]);
        } finally {
            $this->updatingStatus[$caseId] = false;
        }
    }
    
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.support-case-list', [
            'cases' => $this->cases,
        ]);
    }
}
