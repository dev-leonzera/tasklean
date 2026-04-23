<?php

namespace App\Policies;

use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TarefaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tarefa $tarefa): bool
    {
        // Dono da tarefa ou responsável
        if ($user->id === $tarefa->user_id || $user->id === $tarefa->responsavel_id) {
            return true;
        }

        // Se a tarefa pertence a um projeto de time
        if ($tarefa->projeto->time_id) {
            return $tarefa->projeto->time->membros()->where('user_id', $user->id)->exists() ||
                   $tarefa->projeto->time->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tarefa $tarefa): bool
    {
        // Dono da tarefa ou responsável
        if ($user->id === $tarefa->user_id || $user->id === $tarefa->responsavel_id) {
            return true;
        }

        // Admin do time ou dono do time
        if ($tarefa->projeto->time_id) {
            if ($tarefa->projeto->time->owner_id === $user->id) {
                return true;
            }

            $membro = $tarefa->projeto->time->membros()->where('user_id', $user->id)->first();
            return $membro && $membro->pivot->regra === 'admin';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tarefa $tarefa): bool
    {
        // Apenas o dono da tarefa ou dono/admin do time
        if ($user->id === $tarefa->user_id) {
            return true;
        }

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
