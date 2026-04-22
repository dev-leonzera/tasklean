<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendar verificação de notificações (menos frequente)
Schedule::command('notifications:check')
    ->hourly() // A cada hora (menos frequente)
    ->withoutOverlapping() // Evitar sobreposição
    ->runInBackground(); // Executar em background
