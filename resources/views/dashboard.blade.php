@extends('layouts.app')

@section('title', 'Dashboard - Tasklean')
@section('page-title', 'Dashboard')

@section('content')
@php
    $roleDisplay = [
        'owner' => ['label' => 'Proprietário', 'color' => 'primary', 'desc' => 'Visão completa e saúde dos seus projetos.'],
        'admin' => ['label' => 'Administrador', 'color' => 'info', 'desc' => 'Acompanhe os projetos sob sua gestão.'],
        'member' => ['label' => 'Membro', 'color' => 'success', 'desc' => 'Foque em suas entregas para hoje.'],
    ][$role] ?? ['label' => 'Membro', 'color' => 'success', 'desc' => 'Foque em suas entregas para hoje.'];
@endphp

<!-- Dashboard Header -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center mb-1">
                    <h1 class="h2 mb-0 fw-800 text-dark">
                        Olá, {{ explode(' ', auth()->user()->name ?? 'Usuário')[0] }}! 👋
                    </h1>
                    <span class="badge bg-soft-{{ $roleDisplay['color'] }} text-{{ $roleDisplay['color'] }} ms-3 px-3 py-2 rounded-pill fw-bold border border-{{ $roleDisplay['color'] }} border-opacity-25" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                        {{ \Illuminate\Support\Str::upper($roleDisplay['label']) }}
                    </span>
                </div>
                <p class="text-muted mb-0">{{ $roleDisplay['desc'] }}</p>
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

@if($role === 'owner')
    <!-- Métricas de Saúde para Owner -->
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card success h-100 border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-shield-check text-white fs-3"></i>
                        </div>
                        <span class="text-white text-opacity-75 small fw-bold">EM DIA</span>
                    </div>
                    <h2 class="text-white fw-800 mb-1">{{ $saudeStats['em_dia'] ?? 0 }}</h2>
                    <p class="text-white text-opacity-75 mb-0 small">Projetos sem atrasos</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card warning h-100 border-0" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-shield-exclamation text-white fs-3"></i>
                        </div>
                        <span class="text-white text-opacity-75 small fw-bold">ALERTA</span>
                    </div>
                    <h2 class="text-white fw-800 mb-1">{{ $saudeStats['alerta'] ?? 0 }}</h2>
                    <p class="text-white text-opacity-75 mb-0 small">Algumas tarefas atrasadas</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card danger h-100 border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-shield-x text-white fs-3"></i>
                        </div>
                        <span class="text-white text-opacity-75 small fw-bold">CRÍTICO</span>
                    </div>
                    <h2 class="text-white fw-800 mb-1">{{ $saudeStats['critico'] ?? 0 }}</h2>
                    <p class="text-white text-opacity-75 mb-0 small">Muitos atrasos detectados</p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card primary h-100 border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-bar-chart text-white fs-3"></i>
                        </div>
                        <span class="text-white text-opacity-75 small fw-bold">ENTREGA</span>
                    </div>
                    <h2 class="text-white fw-800 mb-1">{{ $totalTarefas > 0 ? round(($tarefasConcluidas / $totalTarefas) * 100) : 0 }}%</h2>
                    <p class="text-white text-opacity-75 mb-0 small">Tarefas totais concluídas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Coluna Principal (Esquerda) -->
        <div class="col-lg-8">
            <!-- Status dos Projetos Ativos -->
            <div class="chart-container mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-kanban"></i>Status dos Projetos Ativos
                    </h5>
                    <a href="{{ route('projetos.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ver Todos</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0 ps-0">Projeto</th>
                                <th class="border-0">Progresso</th>
                                <th class="border-0 text-center">Saúde</th>
                                <th class="border-0 text-end pe-0">Atrasos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projetosComEstatisticas->where('ativo', true)->take(6) as $projeto)
                                <tr>
                                    <td class="ps-0 py-3">
                                        <div class="fw-bold text-dark">{{ $projeto->titulo }}</div>
                                        <div class="small text-muted">{{ $projeto->total_tarefas }} tarefas totais</div>
                                    </td>
                                    <td style="min-width: 150px;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 6px; background-color: #f1f5f9; border-radius: 10px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $projeto->percentual_concluido }}%; border-radius: 10px;"></div>
                                            </div>
                                            <span class="ms-3 small fw-bold text-dark">{{ $projeto->percentual_concluido }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($projeto->saude === 'em_dia')
                                            <span class="badge bg-soft-success text-success rounded-pill px-3">Em Dia</span>
                                        @elseif($projeto->saude === 'alerta')
                                            <span class="badge bg-soft-warning text-warning rounded-pill px-3">Alerta</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger rounded-pill px-3">Crítico</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-0">
                                        @if($projeto->tarefas_atrasadas > 0)
                                            <span class="text-danger fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $projeto->tarefas_atrasadas }}</span>
                                        @else
                                            <span class="text-success"><i class="bi bi-check-circle"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Atividades Recentes do Time (Lado a Lado com Projetos) -->
            <div class="chart-container mb-4">
                <h5 class="section-title mb-4"><i class="bi bi-lightning-charge"></i>Atividades Recentes do Time</h5>
                <div class="row">
                    @foreach($tarefasRecentes->take(6) as $tarefa)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded-4 h-100">
                                <div class="user-avatar me-3" style="width: 32px; height: 32px; flex-shrink: 0;">{{ substr($tarefa->responsavel->name ?? '?', 0, 1) }}</div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-bold text-dark small text-truncate">{{ $tarefa->responsavel->name }}</div>
                                    <div class="text-muted text-truncate" style="font-size: 0.85rem;">Criou: {{ Str::limit($tarefa->titulo, 30) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Coluna Lateral (Direita) -->
        <div class="col-lg-4">
            <!-- Suas Tarefas (Compacto) -->
            <div class="chart-container mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-800 text-dark mb-0 d-flex align-items-center">
                        <i class="bi bi-list-check me-2 text-primary"></i>Suas Tarefas
                    </h6>
                    <a href="{{ route('tarefas.index', ['responsavel' => auth()->id()]) }}" class="btn btn-link btn-sm p-0 text-decoration-none text-primary fw-bold">Ver todas</a>
                </div>
                <div class="activity-list">
                    @forelse($suasTarefas as $tarefa)
                        <div class="activity-item pb-3 mb-3 border-bottom border-light last-child-no-border">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="fw-bold text-dark small">
                                    <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ Str::limit($tarefa->titulo, 35) }}
                                    </a>
                                </div>
                                <span class="badge bg-soft-secondary text-secondary" style="font-size: 0.75rem;">{{ $tarefa->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-check2-all text-muted opacity-25 fs-1 mb-2 d-block"></i>
                            <p class="text-muted small">Nenhuma tarefa atribuída.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Seus Compromissos (Compacto) -->
            <div class="chart-container mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-800 text-dark mb-0 d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2 text-info"></i>Agenda de Hoje
                    </h6>
                    <a href="{{ route('compromissos.index') }}" class="btn btn-link btn-sm p-0 text-decoration-none text-info fw-bold">Ver agenda</a>
                </div>
                <div class="activity-list">
                    @forelse($compromissosParaHoje as $compromisso)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 text-center" style="min-width: 45px;">
                                <div class="fw-bold text-dark small">{{ $compromisso->hora_inicio->format('H:i') }}</div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="text-dark small fw-bold text-truncate">{{ Str::limit($compromisso->titulo, 30) }}</div>
                                <div class="text-muted text-truncate" style="font-size: 0.8rem;">{{ $compromisso->local ?: 'Sem local' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted opacity-25 fs-1 mb-2 d-block"></i>
                            <p class="text-muted small">Sem compromissos hoje.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Layout original para Admin e Member -->
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
                    <p class="text-white text-opacity-75 mb-0 small">
                        @if($role === 'admin') Projetos sob sua gestão @else Projetos que participa @endif
                    </p>
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

    <div class="row mb-4">
        <!-- Projetos Recentes -->
        <div class="col-lg-7 mb-4">
            <div class="chart-container h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0"><i class="bi bi-kanban"></i>Seus Projetos</h5>
                    <a href="{{ route('projetos.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Ver Todos</a>
                </div>
                <div class="activity-list">
                    @foreach($projetosComEstatisticas->where('ativo', true)->take(5) as $projeto)
                        <div class="activity-item mb-4">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark">{{ $projeto->titulo }}</span>
                                    <span class="small fw-bold">{{ $projeto->percentual_concluido }}%</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $projeto->percentual_concluido }}%; border-radius: 10px;"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tarefas para Hoje -->
        <div class="col-lg-5 mb-4">
            <div class="chart-container h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0"><i class="bi bi-calendar-day"></i>Para Hoje</h5>
                </div>
                @if($tarefasParaHoje->count() > 0)
                    <div class="activity-list">
                        @foreach($tarefasParaHoje as $tarefa)
                            <div class="activity-item">
                                <div class="task-card-icon warning"><i class="bi bi-clock-history"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark small">{{ Str::limit($tarefa->titulo, 35) }}</div>
                                    <div class="text-muted" style="font-size: 0.8rem;">{{ $tarefa->projeto->titulo ?? 'Sem projeto' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4"><p class="text-muted small">Tudo em dia!</p></div>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection