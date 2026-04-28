<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RealtimeManager extends Component
{
    public function getListeners()
    {
        $userId = Auth::id();
        $listeners = [
            "echo-private:user.{$userId},TarefaCriada" => 'handleTarefaCriada',
            "echo-private:user.{$userId},TarefaAtualizada" => 'handleTarefaAtualizada',
            "echo-private:user.{$userId},TarefaExcluida" => 'handleTarefaExcluida',
        ];

        // Adicionar ouvintes para cada time do usuário
        $times = Auth::user()->times()->pluck('times.id')->merge(
            Auth::user()->ownedTimes()->pluck('id')
        )->unique();

        foreach ($times as $timeId) {
            $listeners["echo-private:time.{$timeId},TarefaCriada"] = 'handleTarefaCriada';
            $listeners["echo-private:time.{$timeId},TarefaAtualizada"] = 'handleTarefaAtualizada';
            $listeners["echo-private:time.{$timeId},TarefaExcluida"] = 'handleTarefaExcluida';
        }

        return $listeners;
    }

    public function handleTarefaCriada($data)
    {
        logger('TarefaCriada recebida no RealtimeManager', $data);
        
        // Apenas para outros usuários
        if (Auth::id() !== ($data['user_id'] ?? null)) {
            // 1. Adicionar ao sistema de notificações (persiste na sessão)
            \App\Helpers\NotificationHelper::info(
                'Nova Tarefa',
                "{$data['user_nome']} criou a tarefa: {$data['titulo']}",
                ['url' => route('tarefas.index'), 'text' => 'Ver Tarefas']
            );

            // 2. Notificar o frontend para atualizar o sino e a lista
            $this->dispatch('nova-notificacao-realtime', [
                'type' => 'info',
                'title' => 'Nova Tarefa',
                'message' => "{$data['user_nome']} criou a tarefa: {$data['titulo']}",
                'timestamp' => now()->toISOString(),
                'hash' => md5('info' . 'Nova Tarefa' . substr("{$data['user_nome']} criou a tarefa: {$data['titulo']}", 0, 50)),
                'action' => ['url' => route('tarefas.index'), 'text' => 'Ver Tarefas']
            ]);
        }

        $this->dispatch('tarefa-criada-realtime', $data);
    }

    public function handleTarefaAtualizada($data)
    {
        logger('TarefaAtualizada recebida no RealtimeManager', $data);

        // Apenas para outros usuários
        if (Auth::id() !== ($data['user_id'] ?? null)) {
            // 1. Adicionar ao sistema de notificações
            \App\Helpers\NotificationHelper::info(
                'Tarefa Atualizada',
                "{$data['user_nome']} atualizou a tarefa: {$data['titulo']}",
                ['url' => route('tarefas.index'), 'text' => 'Ver Tarefas']
            );

            // 2. Notificar o frontend
            $this->dispatch('nova-notificacao-realtime', [
                'type' => 'info',
                'title' => 'Tarefa Atualizada',
                'message' => "{$data['user_nome']} atualizou a tarefa: {$data['titulo']}",
                'timestamp' => now()->toISOString(),
                'hash' => md5('info' . 'Tarefa Atualizada' . substr("{$data['user_nome']} atualizou a tarefa: {$data['titulo']}", 0, 50)),
                'action' => ['url' => route('tarefas.index'), 'text' => 'Ver Tarefas']
            ]);
        }

        $this->dispatch('tarefa-atualizada-realtime', $data);
    }

    public function handleTarefaExcluida($data)
    {
        $this->dispatch('tarefa-excluida-realtime', $data);
    }

    public function render()
    {
        return view('livewire.realtime-manager');
    }
}
