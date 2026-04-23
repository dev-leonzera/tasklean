@extends('layouts.app')

@section('title', 'Dashboard - Tasklean')
@section('page-title', 'Dashboard')

@section('content')
<!-- Dashboard Header -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-1 fw-800 text-dark">
                    Olá, {{ explode(' ', auth()->user()->name ?? 'Usuário')[0] }}! 👋
                </h1>
                <p class="text-muted mb-0">Aqui está o que está acontecendo com seus projetos hoje.</p>
            </div>
            <div class="d-none d-md-flex align-items-center">
                <div class="text-end me-4">
                    <div class="text-dark fw-bold">{{ now()->locale('pt_BR')->translatedFormat('d \d\e F') }}</div>
                    <div class="text-muted small">{{ ucfirst(now()->locale('pt_BR')->translatedFormat('l')) }}</div>
                </div>
                <a href="{{ route('tarefas.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Nova Tarefa
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Métricas Principais -->
<div class="row mb-5">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card primary h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-folder2 text-white fs-3"></i>
                    </div>
                    <span class="text-white text-opacity-75 small fw-bold">PROJETOS</span>
                </div>
                <h2 class="text-white fw-800 mb-1">{{ $projetosAtivos }}</h2>
                <p class="text-white text-opacity-75 mb-0 small">Projetos em andamento</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card success h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-check2-circle text-white fs-3"></i>
                    </div>
                    <span class="text-white text-opacity-75 small fw-bold">CONCLUÍDAS</span>
                </div>
                <h2 class="text-white fw-800 mb-1">{{ $tarefasConcluidas ?? 0 }}</h2>
                <p class="text-white text-opacity-75 mb-0 small">Tarefas finalizadas</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card danger h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-clock text-white fs-3"></i>
                    </div>
                    <span class="text-white text-opacity-75 small fw-bold">ATRASADAS</span>
                </div>
                <h2 class="text-white fw-800 mb-1">{{ $tarefasAtrasadas->count() }}</h2>
                <p class="text-white text-opacity-75 mb-0 small">Requerem atenção</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card info h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-calendar3 text-white fs-3"></i>
                    </div>
                    <span class="text-white text-opacity-75 small fw-bold">AGENDA</span>
                </div>
                <h2 class="text-white fw-800 mb-1">{{ $compromissosHoje }}</h2>
                <p class="text-white text-opacity-75 mb-0 small">Compromissos hoje</p>
            </div>
        </div>
    </div>
</div>

<!-- Tarefas para Hoje e Atrasadas -->
<div class="row mb-4">
    <!-- Tarefas para Hoje -->
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-calendar-day"></i>Tarefas para Hoje
                </h5>
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-bold" style="background: var(--primary-light)">
                    {{ $tarefasParaHoje->count() }} tarefas
                </span>
            </div>
            @if($tarefasParaHoje->count() > 0)
                <div class="activity-list">
                    @foreach($tarefasParaHoje as $tarefa)
                        <div class="activity-item">
                            <div class="task-card-icon warning">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-dark mb-1">
                                            <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none text-dark hover-primary">
                                                {{ Str::limit($tarefa->titulo, 45) }}
                                            </a>
                                        </div>
                                        <div class="task-meta">
                                            <span class="task-meta-item">
                                                <i class="bi bi-folder2"></i> {{ $tarefa->projeto->titulo ?? 'Sem projeto' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="user-avatar" title="Responsável: {{ $tarefa->responsavel->name ?? 'Sem responsável' }}">
                                        {{ substr($tarefa->responsavel->name ?? '?', 0, 1) }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-3">
                                    <span class="badge-premium warning me-2">Hoje</span>
                                    @if($tarefa->data_vencimento)
                                        <span class="text-muted small">
                                            <i class="bi bi-calendar-event me-1"></i>{{ $tarefa->data_vencimento->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-calendar-check text-success" style="font-size: 3.5rem; opacity: 0.3;"></i>
                    </div>
                    <h6 class="fw-bold">Tudo em dia!</h6>
                    <p class="text-muted small">Nenhuma tarefa pendente para hoje.</p>
                    <a href="{{ route('tarefas.create') }}" class="btn btn-primary btn-sm px-4 rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i> Criar Tarefa
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tarefas Atrasadas -->
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-exclamation-triangle"></i>Tarefas Atrasadas
                </h5>
                <span class="badge bg-soft-danger text-danger px-3 py-2 rounded-pill fw-bold" style="background: #fee2e2">
                    {{ $tarefasAtrasadas->count() }} pendentes
                </span>
            </div>
            @if($tarefasAtrasadas->count() > 0)
                <div class="activity-list">
                    @foreach($tarefasAtrasadas as $tarefa)
                        <div class="activity-item">
                            <div class="task-card-icon danger">
                                <i class="bi bi-fire"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-dark mb-1">
                                            <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none text-dark hover-primary">
                                                {{ Str::limit($tarefa->titulo, 45) }}
                                            </a>
                                        </div>
                                        <div class="task-meta">
                                            <span class="task-meta-item">
                                                <i class="bi bi-folder2"></i> {{ $tarefa->projeto->titulo ?? 'Sem projeto' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="user-avatar" title="Responsável: {{ $tarefa->responsavel->name ?? 'Sem responsável' }}">
                                        {{ substr($tarefa->responsavel->name ?? '?', 0, 1) }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-3">
                                    <span class="badge-premium danger me-2">Atrasada</span>
                                    <span class="text-danger small fw-semibold">
                                        <i class="bi bi-clock-history me-1"></i>Há {{ $tarefa->data_vencimento ? $tarefa->data_vencimento->diffForHumans(null, true) : '?' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-shield-check text-success" style="font-size: 3.5rem; opacity: 0.3;"></i>
                    </div>
                    <h6 class="fw-bold">Incrível!</h6>
                    <p class="text-muted small">Você não tem nenhuma tarefa atrasada.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Compromissos para Hoje e Próximos -->
<div class="row mb-4">
    <!-- Compromissos para Hoje -->
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-calendar2-check"></i>Compromissos Hoje
                </h5>
                <span class="badge bg-soft-info text-info px-3 py-2 rounded-pill fw-bold" style="background: #e0f2fe">
                    {{ $compromissosParaHoje->count() }} eventos
                </span>
            </div>
            @if($compromissosParaHoje->count() > 0)
                <div class="activity-list">
                    @foreach($compromissosParaHoje as $compromisso)
                        <div class="activity-item">
                            <div class="task-card-icon info">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark mb-1">
                                    <a href="{{ route('compromissos.show', $compromisso->id) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ Str::limit($compromisso->titulo, 45) }}
                                    </a>
                                </div>
                                <div class="task-meta">
                                    <span class="task-meta-item">
                                        <i class="bi bi-tag"></i> {{ $compromisso->tipo_formatado }}
                                    </span>
                                    @if($compromisso->local)
                                        <span class="task-meta-item">
                                            <i class="bi bi-geo-alt"></i> {{ Str::limit($compromisso->local, 25) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center mt-3">
                                    <span class="badge-premium info me-3">
                                        {{ $compromisso->hora_inicio->format('H:i') }}
                                        @if($compromisso->hora_fim)
                                            - {{ $compromisso->hora_fim->format('H:i') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-calendar-x text-info" style="font-size: 3.5rem; opacity: 0.3;"></i>
                    </div>
                    <h6 class="fw-bold">Agenda livre!</h6>
                    <p class="text-muted small">Nenhum compromisso marcado para hoje.</p>
                    <a href="{{ route('compromissos.create') }}" class="btn btn-info btn-sm px-4 rounded-pill text-white">
                        <i class="bi bi-plus-lg me-1"></i> Agendar
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Compromissos Próximos -->
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-calendar3"></i>Próximos Dias
                </h5>
            </div>
            @if($compromissosProximosLista->count() > 0)
                <div class="activity-list">
                    @foreach($compromissosProximosLista as $compromisso)
                        <div class="activity-item">
                            <div class="task-card-icon success">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark mb-1">
                                    <a href="{{ route('compromissos.show', $compromisso->id) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ Str::limit($compromisso->titulo, 45) }}
                                    </a>
                                </div>
                                <div class="task-meta">
                                    <span class="task-meta-item">
                                        <i class="bi bi-calendar-date"></i> {{ $compromisso->data_inicio->format('d/m/Y') }}
                                    </span>
                                    <span class="task-meta-item">
                                        <i class="bi bi-flag"></i> {{ $compromisso->prioridade_formatada }}
                                    </span>
                                </div>
                                <div class="mt-3">
                                    <span class="badge-premium success">
                                        {{ $compromisso->hora_inicio->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-calendar4-week text-success" style="font-size: 3.5rem; opacity: 0.3;"></i>
                    </div>
                    <h6 class="fw-bold">Tudo calmo...</h6>
                    <p class="text-muted small">Sem compromissos agendados para os próximos dias.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Gráficos e Atividades -->
<div class="row mb-4">
    <!-- Gráfico de Progresso dos Projetos -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-bar-chart-steps"></i>Progresso dos Projetos
                </h5>
                <a href="{{ route('projetos.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ver todos</a>
            </div>
            <div class="row">
                @foreach($projetosComEstatisticas->where('ativo', true)->take(4) as $projeto)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 bg-light rounded-4 p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark">{{ Str::limit($projeto->titulo, 25) }}</span>
                                <span class="badge bg-white text-primary border rounded-pill">{{ $projeto->percentual_concluido }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: {{ $projeto->percentual_concluido }}%"></div>
                            </div>
                            <div class="mt-3 small text-muted d-flex justify-content-between">
                                <span>{{ $projeto->tarefas_concluidas ?? 0 }} concluídas</span>
                                <span>{{ $projeto->total_tarefas ?? 0 }} total</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-lightning-charge"></i>Atividades
                </h5>
            </div>
            <div class="activity-list">
                @foreach($tarefasRecentes->take(6) as $index => $tarefa)
                    <div class="d-flex align-items-start mb-4">
                        <div class="user-avatar me-3" style="width: 32px; height: 32px; font-size: 0.7rem;">
                            {{ substr($tarefa->responsavel->name ?? '?', 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark small">{{ $tarefa->responsavel->name ?? 'Usuário' }}</span>
                                <span class="text-muted" style="font-size: 0.7rem;">{{ $tarefa->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-muted small mb-0">
                                Criou a tarefa <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-primary text-decoration-none fw-semibold">{{ Str::limit($tarefa->titulo, 25) }}</a>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-3 pt-3 border-top">
                <a href="{{ route('tarefas.index') }}" class="btn btn-link btn-sm text-decoration-none text-primary fw-bold">
                    Ver histórico completo <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection