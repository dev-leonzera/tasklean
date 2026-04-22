<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\CompromissoController;
use App\Http\Controllers\RelatorioController;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile as SettingsProfile;
use App\Livewire\Profile;
use App\Models\Tarefa;
use App\Models\UserSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

// Rota para redirecionar usuários autenticados para o dashboard
Route::get('/home', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', ConfirmPassword::class)
        ->name('password.confirm');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/check-notifications', [App\Http\Controllers\DashboardController::class, 'checkNotifications'])->name('dashboard.check-notifications');
    Route::post('/dashboard/mark-all-read', [App\Http\Controllers\DashboardController::class, 'markAllAsRead'])->name('dashboard.mark-all-read');
    Route::post('/dashboard/dismiss-notification', [App\Http\Controllers\DashboardController::class, 'dismissNotification'])->name('dashboard.dismiss-notification');

    // Rota do Kanban (protegida por configuração)
    Route::get('/kanban', function () {
        $userSettings = \App\Models\UserSettings::getForUser(auth()->id());
        
        if (!$userSettings->enable_kanban) {
            abort(403, 'A visão Kanban está desabilitada nas suas configurações.');
        }
        
        return view('kanban');
    })->name('kanban');

    // Rota de Sprints (protegida por configuração)
    Route::get('/sprints', function () {
        $user = auth()->user();
        $userSettings = UserSettings::getForUser($user->id);

        if (!$userSettings->enable_sprints) {
            abort(403, 'A funcionalidade de Sprints está desabilitada nas suas configurações.');
        }

        return view('sprints');
    })->name('sprints');

    // API para configurações do usuário
    Route::get('/api/user-settings', [App\Http\Controllers\Api\UserSettingsController::class, 'index']);
    Route::post('/api/user-settings', [App\Http\Controllers\Api\UserSettingsController::class, 'update']);
    Route::get('/settings/system', function () {
        return view('settings.system');
    })->name('settings.system');
    Route::get('/settings/profile', SettingsProfile::class)->name('settings.profile');
    Route::get('/settings/password', Password::class)->name('settings.password');
    Route::get('/settings/appearance', Appearance::class)->name('settings.appearance');
    
    // Rota independente de perfil
    Route::get('/profile', Profile::class)->name('profile');
});

/*
|--------------------------------------------------------------------------
| Rotas de Relatórios (Protegidas por autenticação)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/projetos', [RelatorioController::class, 'projetos'])->name('relatorios.projetos');
    Route::get('/relatorios/tarefas', [RelatorioController::class, 'tarefas'])->name('relatorios.tarefas');
});

/*
|--------------------------------------------------------------------------
| Rotas de Projetos (Protegidas por autenticação)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::resource('projetos', ProjetoController::class);

    // Rotas específicas para projetos
    Route::patch('projetos/{id}/ativar', [ProjetoController::class, 'ativar'])->name('projetos.ativar');
    Route::patch('projetos/{id}/inativar', [ProjetoController::class, 'inativar'])->name('projetos.inativar');
});

/*
|--------------------------------------------------------------------------
| Rotas de Tarefas (Protegidas por autenticação)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::resource('tarefas', TarefaController::class);

    // Rotas específicas para tarefas
    Route::patch('tarefas/{id}/status', [TarefaController::class, 'alterarStatus'])->name('tarefas.status');
    Route::get('projetos/{projetoId}/tarefas', [TarefaController::class, 'porProjeto'])->name('projetos.tarefas');
});

/*
|--------------------------------------------------------------------------
| Rotas de Compromissos (Protegidas por autenticação)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::resource('compromissos', CompromissoController::class);

    // Rotas específicas para compromissos
    Route::patch('compromissos/{compromisso}/status', [CompromissoController::class, 'updateStatus'])->name('compromissos.status');
    Route::get('compromissos-calendar', [CompromissoController::class, 'calendar'])->name('compromissos.calendar');
    Route::get('compromissos-hoje', [CompromissoController::class, 'today'])->name('compromissos.today');
    Route::get('compromissos-proximos', [CompromissoController::class, 'upcoming'])->name('compromissos.upcoming');
});
