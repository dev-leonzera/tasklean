<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Projeto extends Model
{
    use HasFactory;
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'ativo',
        'responsavel_id',
        'user_id',
        'time_id',
        'data_criacao',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ativo' => 'boolean',
        'data_criacao' => 'datetime',
    ];

    /**
     * Relacionamentos
     */
    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class);
    }

    public function membros(): HasMany
    {
        return $this->hasMany(MembroProjeto::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(TagProjeto::class);
    }

    public function time(): BelongsTo
    {
        return $this->belongsTo(Time::class);
    }

    /**
     * Scope para projetos ativos
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para projetos inativos
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }

    /**
     * Scope para projetos acessíveis pelo usuário (dono ou membro do time)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAccessibleBy($query, User $user)
    {
        return $query->where('user_id', $user->id)
            ->orWhereHas('time', function($q) use ($user) {
                $q->where('owner_id', $user->id)
                  ->orWhereHas('membros', function($m) use ($user) {
                      $m->where('user_id', $user->id);
                  });
            });
    }
}
