<?php

namespace App\Policies;

use App\Models\Projeto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjetoPolicy
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
    public function view(User $user, Projeto $projeto): bool
    {
        // Dono do projeto
        if ($user->id === $projeto->user_id) {
            return true;
        }

        // Se o projeto pertence a um time, membros do time podem ver
        if ($projeto->time_id) {
            return $projeto->time->membros()->where('user_id', $user->id)->exists() ||
                   $projeto->time->owner_id === $user->id;
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
    public function update(User $user, Projeto $projeto): bool
    {
        // Dono do projeto
        if ($user->id === $projeto->user_id) {
            return true;
        }

        // Se o projeto pertence a um time, admins do time ou dono do time podem editar
        if ($projeto->time_id) {
            if ($projeto->time->owner_id === $user->id) {
                return true;
            }

            $membro = $projeto->time->membros()->where('user_id', $user->id)->first();
            return $membro && $membro->pivot->regra === 'admin';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Projeto $projeto): bool
    {
        // Apenas o dono do projeto ou dono do time pode deletar
        if ($user->id === $projeto->user_id) {
            return true;
        }

        if ($projeto->time_id) {
            return $projeto->time->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Projeto $projeto): bool
    {
        return $user->id === $projeto->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Projeto $projeto): bool
    {
        return $user->id === $projeto->user_id;
    }
}
