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
    public $projetos = [];
    public $tarefasBacklog = [];
    public $tarefasPendentes = [];
    public $tarefasEmDesenvolvimento = [];
    public $tarefasConcluidas = [];

    public function mount()
    {
        $this->projetos = Projeto::where('user_id', Auth::id())->ativos()->get();
        $this->carregarTarefas();
    }

    public function carregarTarefas()
    {
        $query = Tarefa::where('user_id', Auth::id())->with(['projeto', 'responsavel']);
        
        if ($this->projetoId) {
            $query->where('projeto_id', $this->projetoId);
        }
        
        $tarefas = $query->get();
        
        $this->tarefasBacklog = $tarefas->where('status', 'backlog')->values()->toArray();
        $this->tarefasPendentes = $tarefas->where('status', 'pendente')->values()->toArray();
        $this->tarefasEmDesenvolvimento = $tarefas->where('status', 'em desenvolvimento')->values()->toArray();
        $this->tarefasConcluidas = $tarefas->where('status', 'concluida')->values()->toArray();
    }

    public function filtrarPorProjeto($projetoId)
    {
        $this->projetoId = $projetoId;
        $this->carregarTarefas();
    }

    public function limparFiltro()
    {
        $this->projetoId = null;
        $this->carregarTarefas();
    }

    #[On('tarefa-movida')]
    public function moverTarefa($tarefaId, $novoStatus)
    {
        $tarefa = Tarefa::where('user_id', Auth::id())->find($tarefaId);
        
        if ($tarefa) {
            $tarefa->update(['status' => $novoStatus]);
            $this->carregarTarefas();
            
            // Emitir evento para notificar sobre a mudança
            $this->dispatch('tarefa-atualizada', [
                'tarefa_id' => $tarefaId,
                'novo_status' => $novoStatus,
                'titulo' => $tarefa->titulo
            ]);
        }
    }

    public function render()
    {
        return view('livewire.kanban-board');
    }
}
