<?php

namespace App\Livewire\Settings;

use App\Models\UserSettings;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectSettings extends Component
{
    // Configurações de Projeto
    public string $default_task_status = 'backlog';
    public int $default_sprint_duration = 2;
    public array $working_days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    public string $timezone = 'America/Sao_Paulo';
    public bool $require_task_assignee = false;
    public bool $require_task_due_date = false;

    public function mount(): void
    {
        $settings = UserSettings::getForUser(Auth::id());
        
        $this->default_task_status = $settings->default_task_status;
        $this->default_sprint_duration = $settings->default_sprint_duration;
        $this->working_days = $settings->working_days;
        $this->timezone = $settings->timezone;
        $this->require_task_assignee = $settings->require_task_assignee;
        $this->require_task_due_date = $settings->require_task_due_date;
    }

    public function save(): void
    {
        $this->validate([
            'default_task_status' => 'required|in:backlog,pendente,em desenvolvimento,concluida',
            'default_sprint_duration' => 'required|integer|min:1|max:12',
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'timezone' => 'required|string',
            'require_task_assignee' => 'boolean',
            'require_task_due_date' => 'boolean',
        ]);

        $settings = UserSettings::getForUser(Auth::id());
        $settings->update([
            'default_task_status' => $this->default_task_status,
            'default_sprint_duration' => $this->default_sprint_duration,
            'working_days' => $this->working_days,
            'timezone' => $this->timezone,
            'require_task_assignee' => $this->require_task_assignee,
            'require_task_due_date' => $this->require_task_due_date,
        ]);

        session()->flash('message', 'Configurações de projeto salvas com sucesso!');
        
        $this->dispatch('settings-saved');
    }

    public function toggleWorkingDay($day): void
    {
        if (in_array($day, $this->working_days)) {
            $this->working_days = array_filter($this->working_days, fn($d) => $d !== $day);
        } else {
            $this->working_days[] = $day;
        }
    }

    public function render()
    {
        return view('livewire.settings.project-settings');
    }
}
