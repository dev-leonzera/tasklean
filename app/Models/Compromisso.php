<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compromisso extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'hora_inicio',
        'hora_fim',
        'local',
        'tipo',
        'status',
        'prioridade',
        'lembrete',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fim' => 'datetime:H:i',
        'lembrete' => 'datetime',
    ];

    /**
     * Relacionamento: Compromisso pertence a um usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para compromissos do usuário autenticado
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para compromissos por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para compromissos por tipo
     */
    public function scopeByType($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para compromissos por prioridade
     */
    public function scopeByPriority($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    /**
     * Scope para compromissos de hoje
     */
    public function scopeToday($query)
    {
        return $query->whereDate('data_inicio', today());
    }

    /**
     * Scope para compromissos da semana
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('data_inicio', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope para compromissos do mês
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('data_inicio', now()->month)
                    ->whereYear('data_inicio', now()->year);
    }

    /**
     * Scope para compromissos próximos (próximos 7 dias)
     */
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->whereBetween('data_inicio', [
            now(),
            now()->addDays($days)
        ]);
    }

    /**
     * Scope para compromissos vencidos
     */
    public function scopeOverdue($query)
    {
        return $query->where('data_fim', '<', now())
                    ->where('status', '!=', 'concluido');
    }

    /**
     * Accessor para data e hora formatada
     */
    public function getDataHoraInicioAttribute()
    {
        return $this->data_inicio->format('d/m/Y') . ' ' . $this->hora_inicio->format('H:i');
    }

    /**
     * Accessor para data e hora fim formatada
     */
    public function getDataHoraFimAttribute()
    {
        $dataFim = $this->data_fim ?? $this->data_inicio;
        $horaFim = $this->hora_fim ?? $this->hora_inicio;
        
        return $dataFim->format('d/m/Y') . ' ' . $horaFim->format('H:i');
    }

    /**
     * Accessor para duração do compromisso
     */
    public function getDuracaoAttribute()
    {
        $inicio = $this->data_inicio->setTimeFromTimeString($this->hora_inicio->format('H:i:s'));
        $dataFim = $this->data_fim ?? $this->data_inicio;
        $horaFim = $this->hora_fim ?? $this->hora_inicio;
        $fim = $dataFim->setTimeFromTimeString($horaFim->format('H:i:s'));
        
        return $inicio->diffForHumans($fim, true);
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        $statusMap = [
            'agendado' => 'Agendado',
            'em_andamento' => 'Em Andamento',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
            'adiado' => 'Adiado',
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * Accessor para prioridade formatada
     */
    public function getPrioridadeFormatadaAttribute()
    {
        $prioridadeMap = [
            'baixa' => 'Baixa',
            'media' => 'Média',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        return $prioridadeMap[$this->prioridade] ?? $this->prioridade;
    }

    /**
     * Accessor para tipo formatado
     */
    public function getTipoFormatadoAttribute()
    {
        $tipoMap = [
            'reuniao' => 'Reunião',
            'evento' => 'Evento',
            'tarefa' => 'Tarefa',
            'lembrete' => 'Lembrete',
            'compromisso_pessoal' => 'Compromisso Pessoal',
            'outro' => 'Outro',
        ];

        return $tipoMap[$this->tipo] ?? $this->tipo;
    }

    /**
     * Verifica se o compromisso está próximo (próximas 24 horas)
     */
    public function isUpcoming()
    {
        $inicio = $this->data_inicio->setTimeFromTimeString($this->hora_inicio->format('H:i:s'));
        return $inicio->isAfter(now()) && $inicio->isBefore(now()->addDay());
    }

    /**
     * Verifica se o compromisso está vencido
     */
    public function isOverdue()
    {
        $dataFim = $this->data_fim ?? $this->data_inicio;
        $horaFim = $this->hora_fim ?? $this->hora_inicio;
        $fim = $dataFim->setTimeFromTimeString($horaFim->format('H:i:s'));
        return $fim->isBefore(now()) && $this->status !== 'concluido';
    }

    /**
     * Verifica se o compromisso está em andamento
     */
    public function isInProgress()
    {
        $inicio = $this->data_inicio->setTimeFromTimeString($this->hora_inicio->format('H:i:s'));
        $dataFim = $this->data_fim ?? $this->data_inicio;
        $horaFim = $this->hora_fim ?? $this->hora_inicio;
        $fim = $dataFim->setTimeFromTimeString($horaFim->format('H:i:s'));
        
        return now()->between($inicio, $fim) && $this->status === 'em_andamento';
    }
}
