<?php

namespace App\Livewire\Settings;

use App\Models\UserSettings;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardSettings extends Component
{
    // Configurações de Dashboard
    public bool $show_projects_metric = true;
    public bool $show_completed_tasks = true;
    public bool $show_backlog_metric = true;
    public bool $show_overdue_tasks = true;
    public int $recent_activities_limit = 5;
    public int $auto_refresh_interval = 30;

    public function mount(): void
    {
        $settings = UserSettings::getForUser(Auth::id());
        
        $this->show_projects_metric = $settings->show_projects_metric;
        $this->show_completed_tasks = $settings->show_completed_tasks;
        $this->show_backlog_metric = $settings->show_backlog_metric;
        $this->show_overdue_tasks = $settings->show_overdue_tasks;
        $this->recent_activities_limit = $settings->recent_activities_limit;
        $this->auto_refresh_interval = $settings->auto_refresh_interval;
    }

    public function save(): void
    {
        $this->validate([
            'show_projects_metric' => 'boolean',
            'show_completed_tasks' => 'boolean',
            'show_backlog_metric' => 'boolean',
            'show_overdue_tasks' => 'boolean',
            'recent_activities_limit' => 'required|integer|min:1|max:20',
            'auto_refresh_interval' => 'required|integer|min:10|max:300',
        ]);

        $settings = UserSettings::getForUser(Auth::id());
        $settings->update([
            'show_projects_metric' => $this->show_projects_metric,
            'show_completed_tasks' => $this->show_completed_tasks,
            'show_backlog_metric' => $this->show_backlog_metric,
            'show_overdue_tasks' => $this->show_overdue_tasks,
            'recent_activities_limit' => $this->recent_activities_limit,
            'auto_refresh_interval' => $this->auto_refresh_interval,
        ]);

        session()->flash('message', 'Configurações do dashboard salvas com sucesso!');
        
        $this->dispatch('settings-saved');
    }

    public function render()
    {
        return view('livewire.settings.dashboard-settings');
    }
}
