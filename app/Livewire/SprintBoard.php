<?php

namespace App\Livewire;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Sprint;
use App\Models\UserSettings;
use Livewire\Component;
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

        // Tentar encontrar o sprint ativo mais recente
        $lastSprint = Sprint::whereHas('projeto', function($q) {
            $q->where('user_id', Auth::id());
        })->orderBy('data_fim', 'desc')->first();

        if ($lastSprint) {
            $this->selectedSprintId = $lastSprint->id;
            $this->selectedProjetoId = $lastSprint->projeto_id;
        }
    }

    public function getProjetosProperty()
    {
        return Projeto::where('user_id', Auth::id())->ativos()->get();
    }

    public function getSprintsProperty()
    {
        $query = Sprint::whereHas('projeto', function($q) {
            $q->where('user_id', Auth::id());
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
        $query = Tarefa::where('user_id', Auth::id())
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

    public function createSprint()
    {
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
        $tarefa = Tarefa::where('user_id', Auth::id())->find($this->taskToAdd);
        
        if (!$tarefa || !$this->selectedSprintId) {
            return;
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
        $tarefa = Tarefa::where('user_id', Auth::id())->find($taskId);
        
        if ($tarefa) {
            $tarefa->update(['sprint_id' => null]);
            session()->flash('success', 'Tarefa removida do sprint.');
        }
    }

    public function markAsCompleted($taskId)
    {
        $tarefa = Tarefa::where('user_id', Auth::id())->find($taskId);
        if ($tarefa) {
            $tarefa->update(['status' => 'concluida']);
            session()->flash('success', 'Tarefa concluída!');
        }
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
