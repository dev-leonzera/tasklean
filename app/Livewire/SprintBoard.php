<?php

namespace App\Livewire;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Sprint;
use App\Models\UserSettings;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SprintBoard extends Component
{
    public $selectedSprintId = null;
    public $selectedProjetoId = null;
    
    // Novas Sprints
    public $showCreateSprintModal = false;
    public $newSprintName = '';
    public $newSprintStart;
    public $newSprintEnd;
    public $newSprintProjetoId = null;

    // Adicionar Tarefas
    public $showAddTaskModal = false;
    public $taskToAdd = null;

    public function mount()
    {
        $this->newSprintStart = Carbon::today()->format('Y-m-d');
        $this->newSprintEnd = Carbon::today()->addWeeks(2)->format('Y-m-d');

        // Tentar encontrar o sprint ativo mais recente em projetos acessíveis
        $lastSprint = Sprint::whereHas('projeto', function($q) {
            $q->accessibleBy(Auth::user());
        })->orderBy('data_fim', 'desc')->first();

        if ($lastSprint) {
            $this->selectedSprintId = $lastSprint->id;
            $this->selectedProjetoId = $lastSprint->projeto_id;
        }
    }

    public function getProjetosProperty()
    {
        return Projeto::accessibleBy(Auth::user())->ativos()->get();
    }

    public function getSprintsProperty()
    {
        $query = Sprint::whereHas('projeto', function($q) {
            $q->accessibleBy(Auth::user());
        });

        if ($this->selectedProjetoId) {
            $query->where('projeto_id', $this->selectedProjetoId);
        }

        return $query->orderBy('data_inicio', 'desc')->get();
    }

    public function getSelectedSprintProperty()
    {
        if (!$this->selectedSprintId) return null;
        return Sprint::with('tarefas.projeto')->find($this->selectedSprintId);
    }

    public function getSprintTasksProperty()
    {
        if (!$this->selectedSprint) return collect();
        
        $query = $this->selectedSprint->tarefas();
        
        if ($this->selectedProjetoId) {
            $query->where('projeto_id', $this->selectedProjetoId);
        }

        return $query->with('projeto')->get();
    }

    public function getAvailableTasksProperty()
    {
        $query = Tarefa::accessibleBy(Auth::user())
            ->whereNull('sprint_id')
            ->where('status', '!=', 'concluida')
            ->with('projeto')
            ->orderBy('updated_at', 'desc')
            ->limit(20);

        if ($this->selectedProjetoId) {
            $query->where('projeto_id', $this->selectedProjetoId);
        }

        return $query->get();
    }

    public function getStatsProperty()
    {
        $tasks = $this->sprintTasks;
        return [
            'total' => $tasks->count(),
            'completed' => $tasks->where('status', 'concluida')->count(),
            'in_progress' => $tasks->whereIn('status', ['pendente', 'em desenvolvimento'])->count(),
            'completion_percent' => $tasks->count() > 0 
                ? round(($tasks->where('status', 'concluida')->count() / $tasks->count()) * 100)
                : 0,
        ];
    }

    public function updatedSelectedProjetoId($value)
    {
        // Se mudou o projeto, tentar achar um sprint desse projeto
        $sprint = Sprint::where('projeto_id', $value)->orderBy('data_fim', 'desc')->first();
        $this->selectedSprintId = $sprint ? $sprint->id : null;
    }

    /**
     * Verifica se o usuário pode gerenciar sprints no projeto
     */
    public function canManageSprint($projetoId)
    {
        $projeto = Projeto::find($projetoId);
        if (!$projeto) return false;

        $user = Auth::user();
        
        // Dono do projeto
        if ($projeto->user_id === $user->id) return true;

        // Se tem time, dono ou admin do time
        if ($projeto->time_id) {
            if ($projeto->time->owner_id === $user->id) return true;
            
            $membro = $projeto->time->membros()->where('user_id', $user->id)->first();
            return $membro && $membro->pivot->regra === 'admin';
        }

        return false;
    }

    public function createSprint()
    {
        if (!$this->canManageSprint($this->newSprintProjetoId)) {
            abort(403, 'Apenas gestores ou coordenadores podem criar sprints.');
        }

        $this->validate([
            'newSprintName' => 'required|string|max:255',
            'newSprintStart' => 'required|date',
            'newSprintEnd' => 'required|date|after:newSprintStart',
            'newSprintProjetoId' => 'required|exists:projetos,id',
        ]);

        $sprint = Sprint::create([
            'nome' => $this->newSprintName,
            'data_inicio' => $this->newSprintStart,
            'data_fim' => $this->newSprintEnd,
            'projeto_id' => $this->newSprintProjetoId,
            'status' => 'ativa',
        ]);

        $this->selectedSprintId = $sprint->id;
        $this->selectedProjetoId = $sprint->projeto_id;
        $this->showCreateSprintModal = false;
        
        $this->reset(['newSprintName', 'newSprintProjetoId']);
        session()->flash('success', 'Sprint criada com sucesso!');
    }

    public function openAddTaskModal($taskId)
    {
        if (!$this->selectedSprintId) {
            session()->flash('error', 'Selecione ou crie um sprint primeiro.');
            return;
        }
        $this->taskToAdd = $taskId;
        $this->showAddTaskModal = true;
    }

    public function addTaskToSprint()
    {
        $tarefa = Tarefa::accessibleBy(Auth::user())->find($this->taskToAdd);
        
        if (!$tarefa || !$this->selectedSprintId) {
            return;
        }

        if (!$this->canManageSprint($tarefa->projeto_id)) {
            abort(403, 'Apenas gestores ou coordenadores podem planejar a sprint.');
        }

        $tarefa->update([
            'sprint_id' => $this->selectedSprintId,
            'status' => 'pendente'
        ]);

        $this->showAddTaskModal = false;
        $this->taskToAdd = null;

        session()->flash('success', "Tarefa '{$tarefa->titulo}' adicionada ao sprint.");
    }

    public function removeTaskFromSprint($taskId)
    {
        $tarefa = Tarefa::accessibleBy(Auth::user())->find($taskId);
        
        if ($tarefa) {
            if (!$this->canManageSprint($tarefa->projeto_id)) {
                abort(403, 'Apenas gestores ou coordenadores podem planejar a sprint.');
            }

            $tarefa->update(['sprint_id' => null]);
            session()->flash('success', 'Tarefa removida do sprint.');
        }
    }

    public function markAsCompleted($taskId)
    {
        $tarefa = Tarefa::accessibleBy(Auth::user())->find($taskId);
        
        if ($tarefa) {
            // Verificar se o usuário tem permissão para editar a tarefa (via Policy)
            if (Auth::user()->cannot('update', $tarefa)) {
                abort(403, 'Você não tem permissão para atualizar esta tarefa.');
            }

            $tarefa->update(['status' => 'concluida']);
            session()->flash('success', 'Tarefa concluída!');
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
        return view('livewire.sprint-board', [
            'sprintTasks' => $this->sprintTasks,
            'availableTasks' => $this->availableTasks,
            'projetos' => $this->projetos,
            'sprints' => $this->sprints,
            'selectedSprint' => $this->selectedSprint,
            'stats' => $this->stats,
        ]);
    }
}
