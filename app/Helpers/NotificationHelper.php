<?php

namespace App\Helpers;

use App\Models\Tarefa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationHelper
{
    /**
     * Adiciona uma notificação à sessão (evita duplicações)
     *
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $action
     * @return void
     */
    public static function add(string $type, string $title, string $message, array $action = null): void
    {
        $notifications = session('notifications', []);
        
        // Verificar se já existe uma notificação similar (evitar duplicações)
        $exists = collect($notifications)->contains(function ($notification) use ($type, $message) {
            return $notification['type'] === $type && 
                   str_contains($notification['message'], substr($message, 0, 50));
        });
        
        if (!$exists) {
            $notifications[] = [
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'action' => $action,
                'icon' => self::getIcon($type),
                'timestamp' => now()->toISOString()
            ];
            
            session(['notifications' => $notifications]);
        }
    }

    /**
     * Adiciona notificação de sucesso
     */
    public static function success(string $title, string $message, array $action = null): void
    {
        self::add('success', $title, $message, $action);
    }

    /**
     * Adiciona notificação de aviso
     */
    public static function warning(string $title, string $message, array $action = null): void
    {
        self::add('warning', $title, $message, $action);
    }

    /**
     * Adiciona notificação de erro
     */
    public static function danger(string $title, string $message, array $action = null): void
    {
        self::add('danger', $title, $message, $action);
    }

    /**
     * Adiciona notificação de informação
     */
    public static function info(string $title, string $message, array $action = null): void
    {
        self::add('info', $title, $message, $action);
    }

    /**
     * Limpa todas as notificações
     */
    public static function clear(): void
    {
        session()->forget('notifications');
    }

    /**
     * Retorna o ícone baseado no tipo
     */
    private static function getIcon(string $type): string
    {
        return match($type) {
            'success' => 'bi-check-circle',
            'warning' => 'bi-exclamation-triangle',
            'danger' => 'bi-x-circle',
            'info' => 'bi-info-circle',
            default => 'bi-bell'
        };
    }

    /**
     * Verifica tarefas prestes a atrasar e adiciona notificações (apenas as mais importantes)
     */
    public static function checkTarefasPrestesAtrasar(): void
    {
        // Apenas tarefas que vencem hoje (mais críticas)
        $tarefasVenceHoje = Tarefa::where('user_id', Auth::id())
            ->where('data_vencimento', Carbon::today())
            ->where('status', '!=', 'concluida')
            ->with('projeto')
            ->get();

        foreach ($tarefasVenceHoje as $tarefa) {
            self::warning(
                'Tarefa vence hoje!',
                "A tarefa '{$tarefa->titulo}' do projeto '{$tarefa->projeto->titulo}' vence hoje.",
                [
                    'url' => route('tarefas.show', $tarefa->id),
                    'text' => 'Ver Tarefa',
                    'class' => 'btn-warning'
                ]
            );
        }

        // Apenas tarefas atrasadas (mais críticas)
        $tarefasAtrasadas = Tarefa::where('user_id', Auth::id())->atrasadas()->with('projeto')->get();

        foreach ($tarefasAtrasadas as $tarefa) {
            $diasAtraso = Carbon::now()->startOfDay()->diffInDays($tarefa->data_vencimento->startOfDay(), false);
            
            // Formatar a mensagem baseada no tempo de atraso
            if ($diasAtraso == 1) {
                $mensagemAtraso = "1 dia atrasada";
            } elseif ($diasAtraso > 1) {
                $mensagemAtraso = "{$diasAtraso} dias atrasada";
            } else {
                $mensagemAtraso = "atrasada";
            }
            
            self::danger(
                'Tarefa atrasada!',
                "A tarefa '{$tarefa->titulo}' do projeto '{$tarefa->projeto->titulo}' está {$mensagemAtraso}.",
                [
                    'url' => route('tarefas.show', $tarefa->id),
                    'text' => 'Ver Tarefa',
                    'class' => 'btn-danger'
                ]
            );
        }
    }

    /**
     * Verifica projetos sem tarefas e adiciona notificações
     * DESABILITADO: Projetos sem tarefas não precisam de notificações por enquanto
     */
    public static function checkProjetosSemTarefas(): void
    {
        // Desabilitado por enquanto - projetos sem tarefas não precisam de notificações
        return;
    }

    /**
     * Verifica tarefas em desenvolvimento há muito tempo (apenas as muito antigas)
     */
    public static function checkTarefasEmDesenvolvimentoAntigas(): void
    {
        // Apenas tarefas em desenvolvimento há mais de 14 dias (menos frequente)
        $tarefasAntigas = Tarefa::where('user_id', Auth::id())
            ->emDesenvolvimento()
            ->where('updated_at', '<', Carbon::now()->subDays(14))
            ->with('projeto')
            ->get();

        foreach ($tarefasAntigas as $tarefa) {
            $diasEmDesenvolvimento = Carbon::now()->startOfDay()->diffInDays($tarefa->updated_at->startOfDay());
            
            // Formatar a mensagem baseada no tempo em desenvolvimento
            if ($diasEmDesenvolvimento == 1) {
                $mensagemDesenvolvimento = "1 dia";
            } else {
                $mensagemDesenvolvimento = "{$diasEmDesenvolvimento} dias";
            }
            
            self::warning(
                'Tarefa em desenvolvimento há muito tempo',
                "A tarefa '{$tarefa->titulo}' está em desenvolvimento há {$mensagemDesenvolvimento}.",
                [
                    'url' => route('tarefas.show', $tarefa->id),
                    'text' => 'Ver Tarefa',
                    'class' => 'btn-warning'
                ]
            );
        }
    }

    /**
     * Executa todas as verificações de notificações
     */
    public static function checkAll(): void
    {
        self::checkTarefasPrestesAtrasar();
        self::checkProjetosSemTarefas();
        self::checkTarefasEmDesenvolvimentoAntigas();
    }
}
