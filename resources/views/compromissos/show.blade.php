@extends('layouts.app')

@section('title', $compromisso->titulo . ' - Tasklean')
@section('page-title', $compromisso->titulo)

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('compromissos.edit', $compromisso) }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-pencil-square me-2"></i> Editar
        </a>
        <a href="{{ route('compromissos.index') }}" class="btn btn-secondary px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Voltar
        </a>
    </div>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('compromissos.index') }}" class="text-decoration-none text-muted">Compromissos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-1 fw-800 text-dark">
                    {{ $compromisso->titulo }}
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar3 me-1"></i> {{ $compromisso->data_inicio->format('d/m/Y') }} 
                    <i class="bi bi-clock ms-2 me-1"></i> {{ $compromisso->hora_inicio->format('H:i') }}
                    @if($compromisso->hora_fim) - {{ $compromisso->hora_fim->format('H:i') }} @endif
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @php
                    $statusColor = match($compromisso->status) {
                        'agendado' => 'info',
                        'em_andamento' => 'warning',
                        'concluido' => 'success',
                        'cancelado' => 'danger',
                        'adiado' => 'secondary',
                        default => 'primary'
                    };
                @endphp
                <span class="badge-premium {{ $statusColor }} px-4 py-2 fs-6">
                    {{ $compromisso->status_formatado }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Descrição e Observações -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-card-text"></i> Descrição do Compromisso
            </h5>
            @if($compromisso->descricao)
                <div class="bg-light rounded-4 p-4 mb-4">
                    <p class="text-dark mb-0 lead" style="line-height: 1.8;">
                        {{ $compromisso->descricao }}
                    </p>
                </div>
            @else
                <div class="text-center py-4 border rounded-4 border-dashed bg-light">
                    <p class="text-muted mb-0 italic">Nenhuma descrição detalhada fornecida.</p>
                </div>
            @endif

            @if($compromisso->observacoes)
                <h5 class="section-title mt-5 mb-4">
                    <i class="bi bi-chat-left-text"></i> Observações Internas
                </h5>
                <div class="bg-light rounded-4 p-4 border-start border-4 border-primary">
                    <p class="text-muted mb-0">
                        {{ $compromisso->observacoes }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Atividades Relacionadas ou Histórico (Se houver) -->
        <div class="chart-container">
            <h5 class="section-title mb-4">
                <i class="bi bi-clock-history"></i> Informações Adicionais
            </h5>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="task-card-icon success">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">Criado em</div>
                        <div class="text-muted small">{{ $compromisso->created_at->format('d/m/Y \à\s H:i') }}</div>
                    </div>
                </div>
                @if($compromisso->updated_at->ne($compromisso->created_at))
                    <div class="activity-item">
                        <div class="task-card-icon info">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark">Última atualização</div>
                            <div class="text-muted small">{{ $compromisso->updated_at->format('d/m/Y \à\s H:i') }}</div>
                        </div>
                    </div>
                @endif
                <div class="activity-item">
                    <div class="task-card-icon primary">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">Duração Estimada</div>
                        <div class="text-muted small">{{ $compromisso->duracao }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Card de Detalhes Rápidos -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-info-circle"></i> Detalhes do Evento
            </h5>
            <div class="activity-list">
                <div class="activity-item bg-white border mb-3">
                    <div class="task-card-icon {{ $statusColor }} bg-opacity-10">
                        <i class="bi bi-tag"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase">Tipo</div>
                        <div class="fw-bold text-dark">{{ $compromisso->tipo_formatado }}</div>
                    </div>
                </div>

                <div class="activity-item bg-white border mb-3">
                    @php
                        $prioridadeColor = match($compromisso->prioridade) {
                            'baixa' => 'success',
                            'media' => 'warning',
                            'alta' => 'danger',
                            'urgente' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <div class="task-card-icon {{ $prioridadeColor }} bg-opacity-10">
                        <i class="bi bi-flag"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted small fw-bold text-uppercase">Prioridade</div>
                        <div class="fw-bold text-{{ $prioridadeColor }}">{{ $compromisso->prioridade_formatada }}</div>
                    </div>
                </div>

                @if($compromisso->local)
                    <div class="activity-item bg-white border mb-3">
                        <div class="task-card-icon info bg-opacity-10">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small fw-bold text-uppercase">Localização</div>
                            <div class="fw-bold text-dark">{{ $compromisso->local }}</div>
                        </div>
                    </div>
                @endif

                @if($compromisso->lembrete)
                    <div class="activity-item bg-white border mb-0">
                        <div class="task-card-icon warning bg-opacity-10">
                            <i class="bi bi-bell"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small fw-bold text-uppercase">Lembrete Configurado</div>
                            <div class="fw-bold text-dark">{{ $compromisso->lembrete->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ações do Card -->
        <div class="chart-container">
            <h5 class="section-title mb-4">
                <i class="bi bi-lightning-charge"></i> Ações Rápidas
            </h5>
            <div class="d-grid gap-3">
                <a href="{{ route('compromissos.edit', $compromisso) }}" class="btn btn-primary py-3 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-pencil me-2"></i> Editar Compromisso
                </a>
                
                <form action="{{ route('compromissos.destroy', $compromisso) }}" method="POST" class="d-grid">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light text-danger border py-3 rounded-pill fw-bold" 
                            onclick="return confirm('Tem certeza que deseja excluir este compromisso permanentemente?')">
                        <i class="bi bi-trash me-2"></i> Excluir Registro
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
