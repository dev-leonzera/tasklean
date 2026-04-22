<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de Notificações
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os intervalos e tipos de notificações
    | do sistema ProTask.
    |
    */

    'intervals' => [
        'check_notifications' => env('NOTIFICATION_CHECK_INTERVAL', 60), // minutos (1 hora)
        'task_reminder_hours' => env('TASK_REMINDER_HOURS', 24), // horas antes do vencimento
        'overdue_check_hours' => env('OVERDUE_CHECK_HOURS', 1), // horas para verificar atrasos
    ],

    'types' => [
        'task_due_today' => [
            'enabled' => true,
            'title' => 'Tarefa vence hoje',
            'icon' => 'bi-calendar-day',
            'color' => 'warning',
        ],
        'task_due_tomorrow' => [
            'enabled' => true,
            'title' => 'Tarefa vence amanhã',
            'icon' => 'bi-calendar-check',
            'color' => 'info',
        ],
        'task_overdue' => [
            'enabled' => true,
            'title' => 'Tarefa atrasada',
            'icon' => 'bi-exclamation-triangle',
            'color' => 'danger',
        ],
        'project_no_tasks' => [
            'enabled' => false, // Desabilitado por enquanto
            'title' => 'Projeto sem tarefas',
            'icon' => 'bi-folder-x',
            'color' => 'secondary',
        ],
        'task_long_development' => [
            'enabled' => true,
            'title' => 'Tarefa em desenvolvimento há muito tempo',
            'icon' => 'bi-hourglass-split',
            'color' => 'warning',
        ],
    ],

    'scheduler' => [
        'enabled' => env('NOTIFICATION_SCHEDULER_ENABLED', true),
        'timezone' => env('APP_TIMEZONE', 'America/Fortaleza'),
    ],
];
