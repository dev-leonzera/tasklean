<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\Compromisso;
use App\Models\UserSettings;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Notificações não são mais carregadas automaticamente ao acessar o dashboard
        // Elas serão verificadas apenas quando solicitado pelo usuário
        
        // Estatísticas gerais do usuário
        $totalProjetos = Projeto::accessibleBy($user)->count();
        $projetosAtivos = Projeto::accessibleBy($user)->ativos()->count();
        $totalTarefas = Tarefa::accessibleBy($user)->count();
        
        // Tarefas por status do usuário
        $tarefasBacklog = Tarefa::accessibleBy($user)->backlog()->count();
        $tarefasPendentes = Tarefa::accessibleBy($user)->pendentes()->count();
        $tarefasConcluidas = Tarefa::accessibleBy($user)->concluidas()->count();
        
        // Tarefas atrasadas do usuário
        $tarefasAtrasadas = Tarefa::accessibleBy($user)
            ->atrasadas()
            ->with(['projeto', 'responsavel'])
            ->latest()
            ->take(10)
            ->get();
        
        // Tarefas para hoje do usuário
        $tarefasParaHoje = Tarefa::accessibleBy($user)
            ->paraHoje()
            ->with(['projeto', 'responsavel'])
            ->orderBy('data_vencimento')
            ->take(10)
            ->get();
        
        // Projetos recentes do usuário
        $projetosRecentes = Projeto::accessibleBy($user)
            ->with(['tarefas'])
            ->latest()
            ->take(5)
            ->get();
        
        // Tarefas recentes do usuário
        $tarefasRecentes = Tarefa::accessibleBy($user)
            ->with(['projeto', 'responsavel'])
            ->latest()
            ->take(5)
            ->get();
        
        // Compromissos do usuário
        $compromissosHoje = Compromisso::where('user_id', $user->id)->today()->count();
        
        // Compromissos para hoje
        $compromissosParaHoje = Compromisso::where('user_id', $user->id)
            ->today()
            ->orderBy('hora_inicio')
            ->get();
        
        // Compromissos próximos (próximos 7 dias)
        $compromissosProximosLista = Compromisso::where('user_id', $user->id)
            ->upcoming(7)
            ->orderBy('data_inicio')
            ->orderBy('hora_inicio')
            ->take(5)
            ->get();
        
        // Estatísticas por projeto do usuário
        $projetosComEstatisticas = Projeto::accessibleBy($user)
            ->withCount([
                'tarefas as total_tarefas',
                'tarefas as tarefas_pendentes' => function($query) {
                    $query->where('status', 'pendente');
                },
                'tarefas as tarefas_em_desenvolvimento' => function($query) {
                    $query->where('status', 'em desenvolvimento');
                },
                'tarefas as tarefas_concluidas' => function($query) {
                    $query->where('status', 'concluida');
                }
            ])
            ->get()
            ->map(function($projeto) {
                $projeto->percentual_concluido = $projeto->total_tarefas > 0 
                    ? round(($projeto->tarefas_concluidas / $projeto->total_tarefas) * 100, 1)
                    : 0;
                return $projeto;
            });

        return view('dashboard', compact(
            'totalProjetos',
            'projetosAtivos',
            'totalTarefas',
            'tarefasBacklog',
            'tarefasPendentes',
            'tarefasConcluidas',
            'tarefasAtrasadas',
            'tarefasParaHoje',
            'projetosRecentes',
            'tarefasRecentes',
            'projetosComEstatisticas',
            'compromissosHoje',
            'compromissosParaHoje',
            'compromissosProximosLista'
        ));
    }

    /**
     * Verifica notificações via AJAX
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNotifications()
    {
        $user = Auth::user();
        $userSettings = UserSettings::getForUser($user->id);
        
        // Verificar apenas se habilitado nas configurações
        if ($userSettings->notifications_enabled) {
            // Limpar notificações anteriores
            NotificationHelper::clear();
            
            // Verificar novas notificações
            NotificationHelper::checkAll();
        }
        
        $notifications = session('notifications', []);
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'count' => count($notifications),
            'enabled' => $userSettings->notifications_enabled,
            'frequency' => $userSettings->notification_frequency
        ]);
    }

    /**
     * Marca todas as notificações como lidas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        $notifications = session('notifications', []);
        
        foreach ($notifications as $notification) {
            if (isset($notification['hash'])) {
                NotificationHelper::dismiss($notification['hash']);
            }
        }

        NotificationHelper::clear();
        
        return response()->json([
            'success' => true,
            'message' => 'Todas as notificações foram marcadas como lidas'
        ]);
    }

    /**
     * Descarta uma notificação específica
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dismissNotification(Request $request)
    {
        $hash = $request->input('hash');
        
        if ($hash) {
            NotificationHelper::dismiss($hash);
            
            // Remover da sessão também
            $notifications = session('notifications', []);
            $notifications = array_filter($notifications, function($n) use ($hash) {
                return ($n['hash'] ?? null) !== $hash;
            });
            session(['notifications' => $notifications]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notificação descartada'
        ]);
    }
}
