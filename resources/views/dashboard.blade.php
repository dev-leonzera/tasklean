@extends('layouts.app')

@section('title', 'Dashboard - Tasklean')
@section('page-title', 'Dashboard')

@section('content')
<!-- Dashboard Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </h1>
                <p class="text-muted mb-0">Visão geral dos seus projetos e tarefas</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">{{ now()->format('d/m/Y') }}</span>
                <a href="{{ route('relatorios.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-file-earmark-text me-1"></i> Relatórios
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Métricas Principais -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card primary h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-white-50 small fw-semibold text-uppercase">Projetos Ativos</div>
                    <div class="text-white fw-bold fs-2">{{ $projetosAtivos }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="bi bi-folder text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('projetos.index') }}" class="text-white text-decoration-none small">
                    Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card success h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-white-50 small fw-semibold text-uppercase">Tarefas Concluídas</div>
                    <div class="text-white fw-bold fs-2">{{ $tarefasConcluidas ?? 0 }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('tarefas.index') }}" class="text-white text-decoration-none small">
                    Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card danger h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-white-50 small fw-semibold text-uppercase">Tarefas Atrasadas</div>
                    <div class="text-white fw-bold fs-2">{{ $tarefasAtrasadas->count() }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="bi bi-exclamation-triangle text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('tarefas.index') }}" class="text-white text-decoration-none small">
                    Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card metric-card info h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-white-50 small fw-semibold text-uppercase">Compromissos Hoje</div>
                    <div class="text-white fw-bold fs-2">{{ $compromissosHoje }}</div>
                </div>
                <div class="flex-shrink-0">
                    <i class="bi bi-calendar-day text-white" style="font-size: 2.5rem; opacity: 0.8;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('compromissos.today') }}" class="text-white text-decoration-none small">
                    Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tarefas para Hoje e Atrasadas -->
<div class="row mb-4">
    <!-- Tarefas para Hoje -->
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-calendar-day me-2"></i>Tarefas para Hoje
                </h5>
            </div>
            @if($tarefasParaHoje->count() > 0)
                <div class="activity-list">
                    @foreach($tarefasParaHoje as $tarefa)
                        <div class="activity-item">
                            <div class="activity-dot warning"></div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark">
                                    <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none">
                                        {{ Str::limit($tarefa->titulo, 30) }}
                                    </a>
                                </div>
                                <div class="small text-muted">{{ $tarefa->projeto->titulo }}</div>
                                <div class="small text-muted">
                                    <strong>Responsável:</strong> {{ $tarefa->responsavel }}
                                    @if($tarefa->data_vencimento)
                                        | <strong>Vencimento:</strong> {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-calendar-check text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Nenhuma tarefa para hoje!</p>
                    <a href="{{ route('tarefas.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Criar Nova Tarefa
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tarefas Atrasadas -->
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Tarefas Atrasadas
                </h5>
            </div>
            @if($tarefasAtrasadas->count() > 0)
                <div class="activity-list">
                    @foreach($tarefasAtrasadas as $tarefa)
                        <div class="activity-item">
                            <div class="activity-dot danger"></div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark">
                                    <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none">
                                        {{ Str::limit($tarefa->titulo, 30) }}
                                    </a>
                                </div>
                                <div class="small text-muted">{{ $tarefa->projeto->titulo }}</div>
                                <div class="small text-danger">
                                    <strong>Atrasada há:</strong> {{ $tarefa->data_vencimento->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Nenhuma tarefa atrasada!</p>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-calendar-day me-2"></i>Compromissos para Hoje
                </h5>
            </div>
            @if($compromissosParaHoje->count() > 0)
                <div class="activity-list">
                    @foreach($compromissosParaHoje as $compromisso)
                        <div class="activity-item">
                            <div class="activity-dot info"></div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark">
                                    <a href="{{ route('compromissos.show', $compromisso->id) }}" class="text-decoration-none">
                                        {{ Str::limit($compromisso->titulo, 30) }}
                                    </a>
                                </div>
                                <div class="small text-muted">
                                    <strong>Tipo:</strong> {{ $compromisso->tipo_formatado }}
                                    @if($compromisso->local)
                                        | <strong>Local:</strong> {{ Str::limit($compromisso->local, 20) }}
                                    @endif
                                </div>
                                <div class="small text-muted">
                                    <strong>Horário:</strong> {{ $compromisso->hora_inicio->format('H:i') }}
                                    @if($compromisso->hora_fim)
                                        - {{ $compromisso->hora_fim->format('H:i') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-calendar-check text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Nenhum compromisso para hoje!</p>
                    <a href="{{ route('compromissos.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Criar Novo Compromisso
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Compromissos Próximos -->
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-calendar-week me-2"></i>Próximos Compromissos
                </h5>
            </div>
            @if($compromissosProximosLista->count() > 0)
                <div class="activity-list">
                    @foreach($compromissosProximosLista as $compromisso)
                        <div class="activity-item">
                            <div class="activity-dot secondary"></div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark">
                                    <a href="{{ route('compromissos.show', $compromisso->id) }}" class="text-decoration-none">
                                        {{ Str::limit($compromisso->titulo, 30) }}
                                    </a>
                                </div>
                                <div class="small text-muted">
                                    <strong>Tipo:</strong> {{ $compromisso->tipo_formatado }}
                                    | <strong>Prioridade:</strong> {{ $compromisso->prioridade_formatada }}
                                </div>
                                <div class="small text-muted">
                                    <strong>Data:</strong> {{ $compromisso->data_inicio->format('d/m/Y') }}
                                    | <strong>Horário:</strong> {{ $compromisso->hora_inicio->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-calendar-event text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted">Nenhum compromisso próximo!</p>
                    <a href="{{ route('compromissos.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Criar Novo Compromisso
                    </a>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">Progresso dos Projetos</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Exportar</a></li>
                        <li><a class="dropdown-item" href="#">Configurar</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                @foreach($projetosComEstatisticas->where('ativo', true)->take(4) as $projeto)
                    <div class="col-md-6 mb-3">
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>{{ Str::limit($projeto->titulo, 20) }}</span>
                                <span class="fw-bold text-primary">{{ $projeto->percentual_concluido }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill primary" style="width: {{ $projeto->percentual_concluido }}%"></div>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">Atividades Recentes</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Ver todas</a></li>
                        <li><a class="dropdown-item" href="#">Filtrar</a></li>
                    </ul>
                </div>
            </div>
            <div class="activity-list">
                @foreach($tarefasRecentes->take(6) as $index => $tarefa)
                    <div class="activity-item">
                        <div class="activity-dot {{ $index % 4 == 0 ? 'success' : ($index % 4 == 1 ? 'info' : ($index % 4 == 2 ? 'warning' : 'danger')) }}"></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-dark">
                                <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none">
                                    {{ Str::limit($tarefa->titulo, 30) }}
                                </a>
                            </div>
                            <div class="small text-muted">{{ $tarefa->projeto->titulo }}</div>
                            <div class="small text-muted">{{ $tarefa->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('tarefas.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver todas as atividades <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection