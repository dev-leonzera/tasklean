<?php

namespace App\Livewire;

use App\Models\Tarefa;
use App\Models\ComentarioTarefa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ComentariosTarefa extends Component
{
    public $tarefaId;
    public $novoComentario = '';

    public function mount($tarefaId)
    {
        $this->tarefaId = $tarefaId;
    }

    public function getComentariosProperty()
    {
        return ComentarioTarefa::where('tarefa_id', $this->tarefaId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function adicionarComentario()
    {
        $this->validate([
            'novoComentario' => 'required|string|min:2',
        ]);

        ComentarioTarefa::create([
            'conteudo' => $this->novoComentario,
            'tarefa_id' => $this->tarefaId,
            'user_id' => Auth::id(),
        ]);

        $this->novoComentario = '';
        session()->flash('success', 'Comentário adicionado!');
    }

    public function render()
    {
        return view('livewire.comentarios-tarefa', [
            'comentarios' => $this->comentarios,
        ]);
    }
}
