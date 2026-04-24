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

    public function getTarefaProperty()
    {
        return Tarefa::find($this->tarefaId);
    }

    public function adicionarComentario()
    {
        $this->authorize('view', $this->tarefa);

        $this->validate([
            'novoComentario' => 'required|string|min:2|max:1000',
        ]);

        $comentario = ComentarioTarefa::create([
            'conteudo' => $this->novoComentario,
            'tarefa_id' => $this->tarefaId,
            'user_id' => Auth::id(),
        ]);

        // Notificar o responsável se não for o autor do comentário
        if ($this->tarefa->responsavel_id && $this->tarefa->responsavel_id !== Auth::id()) {
            // Como não temos sistema de banco de dados para notificações cross-user ainda, 
            // vamos apenas disparar um evento que pode ser capturado se o usuário estiver online
            $this->dispatch('notificacao-enviada', [
                'user_id' => $this->tarefa->responsavel_id,
                'title' => 'Novo comentário',
                'message' => Auth::user()->name . " comentou na tarefa: " . $this->tarefa->titulo
            ]);
        }

        $this->novoComentario = '';
        $this->dispatch('comentario-adicionado');
    }

    public function removerComentario($id)
    {
        $comentario = ComentarioTarefa::findOrFail($id);
        
        $this->authorize('delete', $comentario);

        $comentario->delete();
        $this->dispatch('comentario-removido');
    }

    public function render()
    {
        return view('livewire.comentarios-tarefa', [
            'comentarios' => $this->comentarios,
        ]);
    }
}
