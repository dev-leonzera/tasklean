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

class TarefaExcluida implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tarefaId;
    public $user_id;
    public $time_id;

    /**
     * Create a new event instance.
     */
    public function __construct(Tarefa $tarefa)
    {
        $this->tarefaId = $tarefa->id;
        $this->user_id = $tarefa->user_id;
        $this->time_id = $tarefa->projeto->time_id ?? null;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('user.' . $this->user_id)];

        if ($this->time_id) {
            $channels[] = new PrivateChannel('time.' . $this->time_id);
        }

        return $channels;
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->tarefaId,
        ];
    }
}
