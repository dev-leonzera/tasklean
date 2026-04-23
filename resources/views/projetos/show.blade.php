@extends('layouts.app')

@section('title', $projeto->titulo . ' - Tasklean')
@section('page-title', $projeto->titulo)

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('tarefas.create', ['projeto_id' => $projeto->id]) }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Nova Tarefa
        </a>
        <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-secondary px-4 shadow-sm">
            <i class="bi bi-pencil-square me-2"></i> Editar Projeto
        </a>
        <a href="{{ route('projetos.index') }}" class="btn btn-secondary px-4 shadow-sm">
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
                        <li class="breadcrumb-item"><a href="{{ route('projetos.index') }}" class="text-decoration-none text-muted">Projetos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard do Projeto</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-1 fw-800 text-dark">
                    {{ $projeto->titulo }}
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-person-badge me-1"></i> Responsável: <strong>{{ $projeto->responsavel->name ?? 'Sem responsável' }}</strong>
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge-premium {{ $projeto->ativo ? 'success' : 'secondary' }} px-4 py-2 fs-6">
                    {{ $projeto->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Estatísticas Rápidas -->
        <div class="row g-4 mb-4">
            @php
                $totalTarefas = $projeto->tarefas->count();
                $concluidas = $projeto->tarefas->where('status', 'concluida')->count();
                $percentualConcluido = $totalTarefas > 0 ? ($concluidas / $totalTarefas) * 100 : 0;
            @endphp
            <div class="col-md-4">
                <div class="metric-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="task-card-icon primary">
                            <i class="bi bi-list-task"></i>
                        </div>
                    </div>
                    <div class="h3 fw-800 mb-1">{{ $totalTarefas }}</div>
                    <div class="text-muted small fw-bold text-uppercase">Total de Tarefas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="task-card-icon success">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                    <div class="h3 fw-800 mb-1">{{ $concluidas }}</div>
                    <div class="text-muted small fw-bold text-uppercase">Concluídas</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="task-card-icon info">
                            <i class="bi bi-percent"></i>
                        </div>
                    </div>
                    <div class="h3 fw-800 mb-1">{{ round($percentualConcluido) }}%</div>
                    <div class="text-muted small fw-bold text-uppercase">Progresso</div>
                </div>
            </div>
        </div>

        <!-- Lista de Tarefas -->
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-list-check"></i> Tarefas do Projeto
                </h5>
                <a href="{{ route('tarefas.create', ['projeto_id' => $projeto->id]) }}" class="btn btn-sm btn-soft-primary rounded-pill px-3 fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Nova Tarefa
                </a>
            </div>

            @if($projeto->tarefas->count() > 0)
                <div class="activity-list">
                    @foreach($projeto->tarefas as $tarefa)
                        <div class="activity-item bg-white border-0 shadow-sm p-4 rounded-4 mb-3 transition-all hover-translate-y">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $statusColor = match($tarefa->status) {
                                                'backlog' => 'secondary',
                                                'pendente' => 'warning',
                                                'em desenvolvimento' => 'info',
                                                'concluida' => 'success',
                                                default => 'primary'
                                            };
                                        @endphp
                                        <div class="task-card-icon {{ $statusColor }} me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-{{ $tarefa->status === 'concluida' ? 'check-lg' : 'list-task' }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">
                                                <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none text-dark hover-primary">
                                                    {{ $tarefa->titulo }}
                                                </a>
                                            </h6>
                                            <div class="text-muted small">
                                                <i class="bi bi-person me-1"></i> {{ $tarefa->responsavel->name ?? 'Sem responsável' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <span class="badge-premium {{ $statusColor }} py-1 px-3" style="font-size: 0.7rem;">
                                        {{ ucfirst($tarefa->status) }}
                                    </span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="{{ route('tarefas.show', $tarefa->id) }}" class="btn btn-light btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 32px;">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 border rounded-4 border-dashed bg-light">
                    <i class="bi bi-journal-x text-muted opacity-25 fs-1 mb-3"></i>
                    <p class="text-muted mb-4">Este projeto ainda não possui tarefas cadastradas.</p>
                    <a href="{{ route('tarefas.create', ['projeto_id' => $projeto->id]) }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                        Começar Agora
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Detalhes do Projeto -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-info-circle"></i> Sobre o Projeto
            </h5>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="task-card-icon primary">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">Data de Lançamento</div>
                        <div class="text-muted small">{{ $projeto->data_criacao->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="task-card-icon info">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">Última Modificação</div>
                        <div class="text-muted small">{{ $projeto->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações do Projeto -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-lightning-charge"></i> Operações
            </h5>
            <div class="d-grid gap-3">
                @if($projeto->ativo)
                    <form action="{{ route('projetos.inativar', $projeto->id) }}" method="POST" class="d-grid">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-light text-warning fw-bold py-3 rounded-pill border">
                            <i class="bi bi-pause-circle me-2"></i> Inativar Projeto
                        </button>
                    </form>
                @else
                    <form action="{{ route('projetos.ativar', $projeto->id) }}" method="POST" class="d-grid">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-light text-success fw-bold py-3 rounded-pill border">
                            <i class="bi bi-play-circle me-2"></i> Ativar Projeto
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-light fw-bold py-3 rounded-pill border">
                    <i class="bi bi-pencil me-2"></i> Configurações do Projeto
                </a>
            </div>
        </div>

        <!-- Zona de Risco -->
        <div class="chart-container border-dashed border-danger bg-soft-danger bg-opacity-10">
            <h5 class="section-title text-danger mb-4">
                <i class="bi bi-exclamation-triangle"></i> Zona Crítica
            </h5>
            <p class="text-muted small mb-4">A exclusão de um projeto removerá permanentemente todas as suas tarefas e dados relacionados.</p>
            <form action="{{ route('projetos.destroy', $projeto->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-100 fw-bold py-3 rounded-pill" 
                        onclick="return confirm('Tem certeza que deseja excluir este projeto permanentemente? Esta ação não pode ser desfeita.')">
                    <i class="bi bi-trash me-2"></i> Excluir Projeto
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
