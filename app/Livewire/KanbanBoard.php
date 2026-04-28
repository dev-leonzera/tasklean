<?php

namespace App\Livewire;

use App\Models\Tarefa;
use App\Models\Projeto;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class KanbanBoard extends Component
{
    public $projetoId = null;

    public function mount()
    {
        $this->projetoId = request('projeto_id');
    }

    public function filtrarPorProjeto($projetoId)
    {
        $this->projetoId = $projetoId;
    }

    public function limparFiltro()
    {
        $this->projetoId = null;
    }

    #[On('tarefa-movida')]
    public function moverTarefa($tarefaId, $novoStatus)
    {
        $tarefa = Tarefa::accessibleBy(Auth::user())->find($tarefaId);
        
        if ($tarefa) {
            $tarefa->update(['status' => $novoStatus]);
            
            // Emitir evento local para notificar sobre a mudança
            $this->dispatch('tarefa-atualizada', [
                'tarefa_id' => $tarefaId,
                'novo_status' => $novoStatus,
                'titulo' => $tarefa->titulo
            ]);
        }
    }

    #[On('tarefa-criada-realtime')]
    #[On('tarefa-atualizada-realtime')]
    #[On('tarefa-excluida-realtime')]
    public function refreshTarefas()
    {
        // Apenas recarrega a renderização
    }

    public function render()
    {
        $projetos = Projeto::accessibleBy(Auth::user())->ativos()->get();
        
        $query = Tarefa::accessibleBy(Auth::user())->with(['projeto', 'responsavel']);
        
        if ($this->projetoId) {
            $query->where('projeto_id', $this->projetoId);
        }
        
        $tarefas = $query->get();

        return view('livewire.kanban-board', [
            'projetos' => $projetos,
            'tarefasBacklog' => $tarefas->where('status', 'backlog'),
            'tarefasPendentes' => $tarefas->where('status', 'pendente'),
            'tarefasEmDesenvolvimento' => $tarefas->where('status', 'em desenvolvimento'),
            'tarefasConcluidas' => $tarefas->where('status', 'concluida'),
        ]);
    }
}
