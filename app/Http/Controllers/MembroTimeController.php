<?php

namespace App\Http\Controllers;

use App\Models\Time;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MembroTimeController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Time $time)
    {
        $this->authorize('update', $time);

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'regra' => 'required|in:admin,membro'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($time->membros()->where('user_id', $user->id)->exists() || $time->owner_id === $user->id) {
            return back()->with('error', 'Usuário já faz parte do time!');
        }

        $time->membros()->attach($user->id, ['regra' => $request->regra]);

        return redirect()->route('times.show', $time->slug)->with('success', 'Membro adicionado com sucesso!');
    }

    public function updateRegra(Request $request, Time $time, User $user)
    {
        $this->authorize('update', $time);

        $request->validate([
            'regra' => 'required|in:admin,membro'
        ]);

        $time->membros()->updateExistingPivot($user->id, ['regra' => $request->regra]);

        return redirect()->route('times.show', $time->slug)->with('success', 'Regra atualizada com sucesso!');
    }

    public function destroy(Time $time, User $user)
    {
        $this->authorize('update', $time);

        $time->membros()->detach($user->id);

        return redirect()->route('times.show', $time->slug)->with('success', 'Membro removido com sucesso!');
    }
}
