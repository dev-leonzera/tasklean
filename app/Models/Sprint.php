<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'status',
        'data_inicio',
        'data_fim',
        'projeto_id',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    /**
     * Relacionamento: Um sprint pertence a um projeto
     */
    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }

    /**
     * Relacionamento: Um sprint tem muitas tarefas
     */
    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class);
    }

    /**
     * Relacionamento: Um sprint tem muitos membros (usuários)
     */
    public function membros(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membros_sprint');
    }

    /**
     * Calcula o progresso do sprint baseado em tarefas concluídas
     */
    public function getProgressoAttribute(): int
    {
        $total = $this->tarefas()->count();
        if ($total === 0) return 0;

        $concluidas = $this->tarefas()->where('status', 'concluida')->count();
        return (int) round(($concluidas / $total) * 100);
    }
}
