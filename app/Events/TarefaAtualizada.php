<?php

namespace App\Events;

use App\Models\Tarefa;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TarefaAtualizada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tarefa;
    public $user;
    public $changes;

    /**
     * Create a new event instance.
     */
    public function __construct(Tarefa $tarefa)
    {
        $this->tarefa = $tarefa->load(['projeto', 'user']);
        $this->user = $tarefa->user;
        $this->changes = $tarefa->getChanges();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('user.' . $this->tarefa->user_id)];

        if ($this->tarefa->projeto && $this->tarefa->projeto->time_id) {
            $channels[] = new PrivateChannel('time.' . $this->tarefa->projeto->time_id);
        }

        return $channels;
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->tarefa->id,
            'titulo' => $this->tarefa->titulo,
            'status' => $this->tarefa->status,
            'user_id' => $this->tarefa->user_id,
            'changes' => $this->changes,
            'projeto_titulo' => $this->tarefa->projeto->titulo ?? null,
            'user_nome' => $this->user->name ?? 'Sistema',
            'user_avatar' => $this->user->avatar_url ?? null,
        ];
    }
}
