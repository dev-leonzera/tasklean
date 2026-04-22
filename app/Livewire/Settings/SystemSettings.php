<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class SystemSettings extends Component
{
    public string $activeTab = 'interface';

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.settings.system-settings');
    }
}
