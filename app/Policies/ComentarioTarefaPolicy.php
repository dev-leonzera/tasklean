<?php

namespace App\Policies;

use App\Models\ComentarioTarefa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComentarioTarefaPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ComentarioTarefa $comentario): bool
    {
        // O autor do comentário sempre pode deletar
        if ($user->id === $comentario->user_id) {
            return true;
        }

        // Dono da tarefa ou responsável pela tarefa também podem deletar
        $tarefa = $comentario->tarefa;
        if ($user->id === $tarefa->user_id || $user->id === $tarefa->responsavel_id) {
            return true;
        }

        // Se a tarefa pertence a um projeto de time, dono ou admin do time podem deletar
        if ($tarefa->projeto->time_id) {
            if ($tarefa->projeto->time->owner_id === $user->id) {
                return true;
            }

            $membro = $tarefa->projeto->time->membros()->where('user_id', $user->id)->first();
            return $membro && $membro->pivot->regra === 'admin';
        }

        return false;
    }
}
