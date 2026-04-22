<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Tarefa extends Model
{
    use HasFactory;
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'data_criacao',
        'data_vencimento',
        'responsavel_id',
        'projeto_id',
        'user_id',
        'sprint_id',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_criacao' => 'datetime',
        'data_vencimento' => 'datetime',
    ];

    /**
     * Relacionamentos
     */
    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioTarefa::class);
    }

    /**
     * Scope para tarefas em backlog
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBacklog($query)
    {
        return $query->where('status', 'backlog');
    }

    /**
     * Scope para tarefas pendentes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    /**
     * Scope para tarefas em desenvolvimento
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmDesenvolvimento($query)
    {
        return $query->where('status', 'em desenvolvimento');
    }

    /**
     * Scope para tarefas concluídas
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConcluidas($query)
    {
        return $query->where('status', 'concluida');
    }

    /**
     * Scope para tarefas atrasadas
     * Tarefas com data de vencimento menor que hoje e status diferente de concluída
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtrasadas($query)
    {
        return $query->where('data_vencimento', '<', Carbon::now()->startOfDay())
                    ->whereNotIn('status', ['concluida', 'backlog']);
    }

    /**
     * Scope para tarefas de hoje
     * Tarefas com data de vencimento igual a hoje e status diferente de concluída
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParaHoje($query)
    {
        return $query->whereDate('data_vencimento', Carbon::today())
                    ->whereNotIn('status', ['concluida', 'backlog']);
    }

    /**
     * Verifica se a tarefa está atrasada
     *
     * @return bool
     */
    public function isAtrasada(): bool
    {
        return $this->data_vencimento && 
               $this->data_vencimento->startOfDay()->isPast() && 
               !in_array($this->status, ['concluida', 'backlog']);
    }

    /**
     * Verifica se a tarefa está concluída
     *
     * @return bool
     */
    public function isConcluida(): bool
    {
        return $this->status === 'concluida';
    }

    /**
     * Verifica se a tarefa vence hoje
     *
     * @return bool
     */
    public function isParaHoje(): bool
    {
        return $this->data_vencimento && 
               $this->data_vencimento->isToday() && 
               !in_array($this->status, ['concluida', 'backlog']);
    }

    /**
     * Verifica se a tarefa está em backlog
     *
     * @return bool
     */
    public function isBacklog(): bool
    {
        return $this->status === 'backlog';
    }
}
