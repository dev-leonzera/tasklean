<?php

namespace App\Policies;

use App\Models\Time;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimePolicy
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
    public function view(User $user, Time $time): bool
    {
        return $user->id === $time->owner_id || 
               $time->membros()->where('user_id', $user->id)->exists();
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
    public function update(User $user, Time $time): bool
    {
        if ($user->id === $time->owner_id) {
            return true;
        }

        $membro = $time->membros()->where('user_id', $user->id)->first();
        
        return $membro && $membro->pivot->regra === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Time $time): bool
    {
        return $user->id === $time->owner_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Time $time): bool
    {
        return $user->id === $time->owner_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Time $time): bool
    {
        return $user->id === $time->owner_id;
    }
}
