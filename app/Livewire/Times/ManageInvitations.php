<?php

namespace App\Livewire\Times;

use App\Models\Time;
use App\Models\ConviteTime;
use Illuminate\Support\Str;
use Livewire\Component;

class ManageInvitations extends Component
{
    public Time $time;
    public $regra = 'membro';
    public $max_usos = 1;
    public $expires_in = 7; // days

    protected $rules = [
        'regra' => 'required|in:admin,membro',
        'max_usos' => 'required|integer|min:1',
        'expires_in' => 'required|integer|min:1',
    ];

    public function mount(Time $time)
    {
        $this->time = $time;
    }

    public function gerarLink()
    {
        $this->validate();

        ConviteTime::create([
            'time_id' => $this->time->id,
            'token' => Str::random(32),
            'regra' => $this->regra,
            'max_usos' => $this->max_usos,
            'expires_at' => now()->addDays($this->expires_in),
        ]);

        $this->dispatch('toast-show', message: 'Link de convite gerado com sucesso!', title: 'Sucesso', type: 'success');
    }

    public function revogar($id)
    {
        $convite = ConviteTime::where('time_id', $this->time->id)->findOrFail($id);
        $convite->delete();

        $this->dispatch('toast-show', message: 'Convite revogado.', title: 'Info', type: 'info');
    }

    public function render()
    {
        return view('livewire.times.manage-invitations', [
            'convites' => $this->time->convites()->latest()->get()
        ]);
    }
}
