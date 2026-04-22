<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    /**
     * Obtém as configurações do usuário
     */
    public function index()
    {
        $user = Auth::user();
        $settings = UserSettings::getForUser($user->id);
        
        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }
    
    /**
     * Atualiza as configurações do usuário
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = UserSettings::getForUser($user->id);
        
        $validated = $request->validate([
            'enable_sprints' => 'sometimes|boolean',
            'enable_kanban' => 'sometimes|boolean',
            'notifications_enabled' => 'sometimes|boolean',
            'email_notifications' => 'sometimes|boolean',
            'task_due_today' => 'sometimes|boolean',
            'task_overdue' => 'sometimes|boolean',
            'task_long_development' => 'sometimes|boolean',
            'notification_frequency' => 'sometimes|integer|min:5|max:1440',
        ]);
        
        $settings->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Configurações atualizadas com sucesso!',
            'settings' => $settings
        ]);
    }
}