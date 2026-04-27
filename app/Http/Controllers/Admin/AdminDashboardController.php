<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Time;
use App\Models\Projeto;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Subscription;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_users' => User::count(),
            'total_teams' => Time::count(),
            'total_projects' => Projeto::count(),
            'total_tasks' => Tarefa::count(),
            'active_subscriptions' => Subscription::where('stripe_status', 'active')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        // Recent activity (Last 10 users)
        $recentUsers = User::latest()->take(10)->get();

        // System Info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database_connection' => config('database.default'),
        ];

        return view('admin.dashboard', compact('metrics', 'recentUsers', 'systemInfo'));
    }
}
