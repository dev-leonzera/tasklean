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
        $totalProjetos = Projeto::where('user_id', $user->id)->count();
        $projetosAtivos = Projeto::where('user_id', $user->id)->ativos()->count();
        $totalTarefas = Tarefa::where('user_id', $user->id)->count();
        
        // Tarefas por status do usuário
        $tarefasBacklog = Tarefa::where('user_id', $user->id)->backlog()->count();
        $tarefasPendentes = Tarefa::where('user_id', $user->id)->pendentes()->count();
        $tarefasConcluidas = Tarefa::where('user_id', $user->id)->concluidas()->count();
        
        // Tarefas atrasadas do usuário
        $tarefasAtrasadas = Tarefa::where('user_id', $user->id)
            ->atrasadas()
            ->with(['projeto', 'responsavel'])
            ->get();
        
        // Tarefas para hoje do usuário
        $tarefasParaHoje = Tarefa::where('user_id', $user->id)
            ->paraHoje()
            ->with(['projeto', 'responsavel'])
            ->orderBy('data_vencimento')
            ->get();
        
        // Projetos recentes do usuário
        $projetosRecentes = Projeto::where('user_id', $user->id)
            ->with(['tarefas' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->latest()
            ->take(5)
            ->get();
        
        // Tarefas recentes do usuário
        $tarefasRecentes = Tarefa::where('user_id', $user->id)
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
        $projetosComEstatisticas = Projeto::where('user_id', $user->id)
            ->with(['tarefas' => function($query) use ($user) {
                $query->where('user_id', $user->id)->select('projeto_id', 'status');
            }])->get()->map(function($projeto) {
                $tarefas = $projeto->tarefas;
                $projeto->total_tarefas = $tarefas->count();
                $projeto->tarefas_pendentes = $tarefas->where('status', 'pendente')->count();
                $projeto->tarefas_em_desenvolvimento = $tarefas->where('status', 'em desenvolvimento')->count();
                $projeto->tarefas_concluidas = $tarefas->where('status', 'concluida')->count();
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
