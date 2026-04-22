<?php

namespace App\Livewire\Settings;

use App\Models\UserSettings;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InterfaceSettings extends Component
{
    // Configurações de Interface
    public bool $enable_sprints = false;
    public bool $enable_kanban = true;

    public function mount(): void
    {
        $settings = UserSettings::getForUser(Auth::id());
        
        $this->enable_sprints = $settings->enable_sprints;
        $this->enable_kanban = $settings->enable_kanban;
    }

    public function save(): void
    {
        $this->validate([
            'enable_sprints' => 'boolean',
            'enable_kanban' => 'boolean',
        ]);

        $settings = UserSettings::getForUser(Auth::id());
        $settings->update([
            'enable_sprints' => $this->enable_sprints,
            'enable_kanban' => $this->enable_kanban,
        ]);

        session()->flash('message', 'Configurações de interface salvas com sucesso!');
        
        $this->dispatch('settings-saved');
    }

    public function render()
    {
        return view('livewire.settings.interface-settings');
    }
}
