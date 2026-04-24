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
        $role = $user->getHighestRole();
        
        // Dados adicionais para Owner
        $suasTarefas = collect();
        $saudeStats = null;

        // Definir as queries base conforme o papel para filtrar o que é exibido no dashboard
        if ($role === 'owner') {
            // Owner vê tudo nos times que possui ou participa
            $projetoBase = Projeto::accessibleBy($user);
            $tarefaBase = Tarefa::accessibleBy($user);
            
            // Suas próprias tarefas para o widget compacto
            $suasTarefas = Tarefa::where('responsavel_id', $user->id)->pendentes()->take(5)->get();
        } elseif ($role === 'admin') {
            // Admin vê projetos que gerencia ou criou
            $projetoBase = Projeto::where(function($q) use ($user) {
                $q->where('responsavel_id', $user->id)
                  ->orWhere('user_id', $user->id);
            });
            $tarefaBase = Tarefa::whereHas('projeto', function($q) use ($user) {
                $q->where('responsavel_id', $user->id)
                  ->orWhere('user_id', $user->id);
            });
        } else {
            // Membro vê projetos que participa e tarefas que é responsável
            $projetoBase = Projeto::whereHas('membros', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $tarefaBase = Tarefa::where('responsavel_id', $user->id);
        }
        
        // Estatísticas baseadas no filtro de papel
        $totalProjetos = (clone $projetoBase)->count();
        $projetosAtivos = (clone $projetoBase)->ativos()->count();
        $totalTarefas = (clone $tarefaBase)->count();
        
        // Tarefas por status
        $tarefasBacklog = (clone $tarefaBase)->backlog()->count();
        $tarefasPendentes = (clone $tarefaBase)->pendentes()->count();
        $tarefasConcluidas = (clone $tarefaBase)->concluidas()->count();
        
        // Tarefas atrasadas
        $tarefasAtrasadas = (clone $tarefaBase)
            ->atrasadas()
            ->with(['projeto', 'responsavel'])
            ->latest()
            ->take(10)
            ->get();
        
        // Tarefas para hoje
        $tarefasParaHoje = (clone $tarefaBase)
            ->paraHoje()
            ->with(['projeto', 'responsavel'])
            ->orderBy('data_vencimento')
            ->take(10)
            ->get();
        
        // Projetos recentes
        $projetosRecentes = (clone $projetoBase)
            ->with(['tarefas'])
            ->latest()
            ->take(5)
            ->get();
        
        // Tarefas recentes
        $tarefasRecentes = (clone $tarefaBase)
            ->with(['projeto', 'responsavel'])
            ->latest()
            ->take(5)
            ->get();
        
        // Compromissos (sempre do usuário logado)
        $compromissosHoje = Compromisso::where('user_id', $user->id)->today()->count();
        $compromissosParaHoje = Compromisso::where('user_id', $user->id)
            ->today()
            ->orderBy('hora_inicio')
            ->get();
        $compromissosProximosLista = Compromisso::where('user_id', $user->id)
            ->upcoming(7)
            ->orderBy('data_inicio')
            ->orderBy('hora_inicio')
            ->take(5)
            ->get();
        
        // Estatísticas por projeto e Saúde (especialmente para Owner)
        $projetosComEstatisticas = (clone $projetoBase)
            ->withCount([
                'tarefas as total_tarefas',
                'tarefas as tarefas_atrasadas' => function($query) {
                    $query->atrasadas();
                },
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
                
                // Calcular Saúde do Projeto
                $atrasoPercent = $projeto->total_tarefas > 0 
                    ? ($projeto->tarefas_atrasadas / $projeto->total_tarefas) * 100 
                    : 0;
                
                if ($projeto->tarefas_atrasadas == 0) {
                    $projeto->saude = 'em_dia';
                } elseif ($atrasoPercent > 25) {
                    $projeto->saude = 'critico';
                } else {
                    $projeto->saude = 'alerta';
                }
                
                return $projeto;
            });

        if ($role === 'owner') {
            $saudeStats = [
                'em_dia' => $projetosComEstatisticas->where('saude', 'em_dia')->count(),
                'alerta' => $projetosComEstatisticas->where('saude', 'alerta')->count(),
                'critico' => $projetosComEstatisticas->where('saude', 'critico')->count(),
            ];
        }

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
            'compromissosProximosLista',
            'role',
            'suasTarefas',
            'saudeStats'
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
