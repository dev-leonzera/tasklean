<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        // Interface
        'enable_sprints',
        'enable_kanban',
        // Notificações
        'notifications_enabled',
        'email_notifications',
        'task_due_today',
        'task_overdue',
        'task_long_development',
        'notification_frequency',
        // Dashboard
        'show_projects_metric',
        'show_completed_tasks',
        'show_backlog_metric',
        'show_overdue_tasks',
        'recent_activities_limit',
        'auto_refresh_interval',
        // Projeto
        'default_task_status',
        'default_sprint_duration',
        'working_days',
        'timezone',
        'require_task_assignee',
        'require_task_due_date',
    ];

    protected $casts = [
        'enable_sprints' => 'boolean',
        'enable_kanban' => 'boolean',
        'notifications_enabled' => 'boolean',
        'email_notifications' => 'boolean',
        'task_due_today' => 'boolean',
        'task_overdue' => 'boolean',
        'task_long_development' => 'boolean',
        'show_projects_metric' => 'boolean',
        'show_completed_tasks' => 'boolean',
        'show_backlog_metric' => 'boolean',
        'show_overdue_tasks' => 'boolean',
        'require_task_assignee' => 'boolean',
        'require_task_due_date' => 'boolean',
        'working_days' => 'array',
    ];

    /**
     * Relacionamento: Configurações pertencem a um usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém ou cria configurações para um usuário
     */
    public static function getForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            self::getDefaults()
        );
    }

    /**
     * Retorna os valores padrão das configurações
     */
    public static function getDefaults(): array
    {
        return [
            'enable_sprints' => false,
            'enable_kanban' => true,
            'notifications_enabled' => true,
            'email_notifications' => true,
            'task_due_today' => true,
            'task_overdue' => true,
            'task_long_development' => true,
            'notification_frequency' => 60,
            'show_projects_metric' => true,
            'show_completed_tasks' => true,
            'show_backlog_metric' => true,
            'show_overdue_tasks' => true,
            'recent_activities_limit' => 5,
            'auto_refresh_interval' => 30,
            'default_task_status' => 'backlog',
            'default_sprint_duration' => 2,
            'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'timezone' => 'America/Sao_Paulo',
            'require_task_assignee' => false,
            'require_task_due_date' => false,
        ];
    }
}
