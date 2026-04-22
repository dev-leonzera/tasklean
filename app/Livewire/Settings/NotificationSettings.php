<?php

namespace App\Livewire\Settings;

use App\Models\UserSettings;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationSettings extends Component
{
    // Configurações de Notificações
    public bool $notifications_enabled = true;
    public bool $email_notifications = true;
    public bool $task_due_today = true;
    public bool $task_overdue = true;
    public bool $task_long_development = true;
    public int $notification_frequency = 60;

    public function mount(): void
    {
        $settings = UserSettings::getForUser(Auth::id());
        
        $this->notifications_enabled = $settings->notifications_enabled;
        $this->email_notifications = $settings->email_notifications;
        $this->task_due_today = $settings->task_due_today;
        $this->task_overdue = $settings->task_overdue;
        $this->task_long_development = $settings->task_long_development;
        $this->notification_frequency = $settings->notification_frequency;
    }

    public function save(): void
    {
        $this->validate([
            'notifications_enabled' => 'boolean',
            'email_notifications' => 'boolean',
            'task_due_today' => 'boolean',
            'task_overdue' => 'boolean',
            'task_long_development' => 'boolean',
            'notification_frequency' => 'required|integer|min:5|max:1440',
        ]);

        $settings = UserSettings::getForUser(Auth::id());
        $settings->update([
            'notifications_enabled' => $this->notifications_enabled,
            'email_notifications' => $this->email_notifications,
            'task_due_today' => $this->task_due_today,
            'task_overdue' => $this->task_overdue,
            'task_long_development' => $this->task_long_development,
            'notification_frequency' => $this->notification_frequency,
        ]);

        session()->flash('message', 'Configurações de notificações salvas com sucesso!');
        
        $this->dispatch('settings-saved');
    }

    public function render()
    {
        return view('livewire.settings.notification-settings');
    }
}
