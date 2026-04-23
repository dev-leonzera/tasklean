<?php

namespace App\Livewire;

use App\Models\Tarefa;
use App\Models\Projeto;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CommandMenu extends Component
{
    public $query = '';
    public $results = [];
    public $isOpen = false;
    public $selectedIndex = 0;

    protected $listeners = ['toggleCommandMenu' => 'toggle'];

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->query = '';
            $this->results = [];
            $this->selectedIndex = 0;
        }
    }

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $projetos = Projeto::where('user_id', Auth::id())
            ->where('titulo', 'like', '%' . $this->query . '%')
            ->limit(5)
            ->get()
            ->map(function ($p) {
                return [
                    'type' => 'projeto',
                    'id' => $p->id,
                    'title' => $p->titulo,
                    'url' => route('projetos.show', $p->id),
                    'icon' => 'bi-folder'
                ];
            });

        $tarefas = Tarefa::where('user_id', Auth::id())
            ->where('titulo', 'like', '%' . $this->query . '%')
            ->limit(5)
            ->get()
            ->map(function ($t) {
                return [
                    'type' => 'tarefa',
                    'id' => $t->id,
                    'title' => $t->titulo,
                    'url' => route('tarefas.show', $t->id),
                    'icon' => 'bi-list-task'
                ];
            });

        $this->results = $projetos->concat($tarefas)->toArray();
        $this->selectedIndex = 0;
    }

    public function selectItem($index)
    {
        if (isset($this->results[$index])) {
            return redirect($this->results[$index]['url']);
        }
    }

    public function render()
    {
        return view('livewire.command-menu');
    }
}
