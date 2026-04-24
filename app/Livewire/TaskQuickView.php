<?php

namespace App\Livewire;

use App\Models\Tarefa;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class TaskQuickView extends Component
{
    public $tarefaId;
    public $isOpen = false;

    #[On('openTaskQuickView')]
    public function open($id)
    {
        $this->tarefaId = $id;
        $this->isOpen = true;
        
        // Disparar evento para re-inicializar qualquer JS se necessário
        $this->dispatch('quick-view-opened');
    }

    public function close()
    {
        $this->isOpen = false;
        $this->tarefaId = null;
    }

    public function getTarefaProperty()
    {
        if (!$this->tarefaId) return null;
        
        return Tarefa::with(['projeto', 'responsavel'])->find($this->tarefaId);
    }

    public function render()
    {
        return view('livewire.task-quick-view', [
            'tarefa' => $this->tarefa,
        ]);
    }
}
