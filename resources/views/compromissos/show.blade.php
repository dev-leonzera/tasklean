@extends('layouts.app')

@section('title', $compromisso->titulo . ' - Tasklean')
@section('page-title', $compromisso->titulo)

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('compromissos.edit', $compromisso) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        <a href="{{ route('compromissos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                    <i class="bi bi-calendar-event me-2"></i>{{ $compromisso->titulo }}
                </h1>
                <p class="text-muted mb-0">{{ $compromisso->tipo_formatado }} • {{ $compromisso->data_inicio->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>
<style>
    .detail-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .detail-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }
    
    .detail-body {
        padding: 1.5rem;
    }
    
    .detail-item {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .detail-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .detail-value {
        color: var(--secondary-color);
        font-size: 0.95rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        transform: translateY(-1px);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
    }
</style>

<div class="row">
    <div class="col-lg-8">
        <div class="card detail-card">
            <div class="detail-header">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-file-text me-2"></i>Informações do Compromisso
                </h5>
            </div>
            <div class="detail-body">
                @if($compromisso->descricao)
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="bi bi-card-text me-2"></i>Descrição
                        </span>
                        <div class="detail-value">{{ $compromisso->descricao }}</div>
                    </div>
                @endif

                @if($compromisso->observacoes)
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="bi bi-chat-text me-2"></i>Observações
                        </span>
                        <div class="detail-value">{{ $compromisso->observacoes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card detail-card">
            <div class="detail-header">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-info-circle me-2"></i>Detalhes
                </h5>
            </div>
            <div class="detail-body">
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="bi bi-calendar me-2"></i>Data e Hora
                    </span>
                    <div class="detail-value">
                        {{ $compromisso->data_inicio->format('d/m/Y') }} às {{ $compromisso->hora_inicio->format('H:i') }}
                        @if($compromisso->data_fim && $compromisso->data_fim->ne($compromisso->data_inicio))
                            até {{ $compromisso->data_fim->format('d/m/Y') }} às {{ $compromisso->hora_fim->format('H:i') }}
                        @elseif($compromisso->hora_fim && $compromisso->hora_fim->ne($compromisso->hora_inicio))
                            às {{ $compromisso->hora_fim->format('H:i') }}
                        @endif
                    </div>
                </div>

                @if($compromisso->local)
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="bi bi-geo-alt me-2"></i>Local
                        </span>
                        <div class="detail-value">{{ $compromisso->local }}</div>
                    </div>
                @endif

                <div class="detail-item">
                    <span class="detail-label">
                        <i class="bi bi-tag me-2"></i>Tipo
                    </span>
                    <div class="detail-value">
                        <span class="badge bg-info">{{ $compromisso->tipo_formatado }}</span>
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">
                        <i class="bi bi-check-circle me-2"></i>Status
                    </span>
                    <div class="detail-value">
                        <span class="badge bg-{{ $compromisso->status === 'concluido' ? 'success' : 'primary' }}">
                            {{ $compromisso->status_formatado }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">
                        <i class="bi bi-flag me-2"></i>Prioridade
                    </span>
                    <div class="detail-value">
                        @php
                            $prioridadeColors = [
                                'baixa' => 'success',
                                'media' => 'warning',
                                'alta' => 'danger',
                                'urgente' => 'dark'
                            ];
                        @endphp
                        <span class="badge bg-{{ $prioridadeColors[$compromisso->prioridade] ?? 'secondary' }}">
                            {{ $compromisso->prioridade_formatada }}
                        </span>
                    </div>
                </div>

                @if($compromisso->lembrete)
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="bi bi-bell me-2"></i>Lembrete
                        </span>
                        <div class="detail-value">{{ $compromisso->lembrete->format('d/m/Y H:i') }}</div>
                    </div>
                @endif

                <div class="detail-item">
                    <span class="detail-label">
                        <i class="bi bi-clock me-2"></i>Duração
                    </span>
                    <div class="detail-value">{{ $compromisso->duracao }}</div>
                </div>

                <div class="detail-item">
                    <span class="detail-label">
                        <i class="bi bi-calendar-plus me-2"></i>Criado em
                    </span>
                    <div class="detail-value">{{ $compromisso->created_at->format('d/m/Y H:i') }}</div>
                </div>

                @if($compromisso->updated_at->ne($compromisso->created_at))
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="bi bi-calendar-check me-2"></i>Atualizado em
                        </span>
                        <div class="detail-value">{{ $compromisso->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ações -->
        <div class="card detail-card mt-3">
            <div class="detail-header">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-gear me-2"></i>Ações
                </h5>
            </div>
            <div class="detail-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('compromissos.edit', $compromisso) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Editar Compromisso
                    </a>
                    
                    <form action="{{ route('compromissos.destroy', $compromisso) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Tem certeza que deseja excluir este compromisso?')">
                            <i class="bi bi-trash me-1"></i> Excluir Compromisso
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
