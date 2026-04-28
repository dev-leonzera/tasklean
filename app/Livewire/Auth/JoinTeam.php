<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\ConviteTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class JoinTeam extends Component
{
    public $token;
    public $convite;
    public $time;

    // Campos de Registro
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount($token)
    {
        $this->token = $token;
        $this->convite = ConviteTime::where('token', $token)->first();

        if (!$this->convite || !$this->convite->isValid()) {
            abort(404, 'Convite inválido ou expirado.');
        }

        $this->time = $this->convite->time;
        
        // Se já estiver logado e já for membro, redireciona
        if (Auth::check()) {
            if ($this->time->membros()->where('user_id', Auth::id())->exists() || $this->time->owner_id === Auth::id()) {
                session()->flash('info', 'Você já faz parte deste time!');
                return $this->redirect(route('dashboard'), navigate: true);
            }
        }
    }

    public function registerAndJoin()
    {
        if (Auth::check()) {
            $this->join(Auth::user());
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        event(new Registered($user));
        Auth::login($user);

        $this->join($user);
    }

    public function join(User $user)
    {
        // Verifica novamente se o convite ainda é válido
        if (!$this->convite->isValid()) {
            $this->dispatch('toast-show', message: 'Este convite expirou ou atingiu o limite de usos.', title: 'Erro', type: 'error');
            return;
        }

        // Adiciona ao time
        $this->time->membros()->attach($user->id, ['regra' => $this->convite->regra]);

        // Incrementa usos
        $this->convite->increment('usos');

        session()->flash('success', "Bem-vindo ao time {$this->time->nome}!");
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.join-team');
    }
}
