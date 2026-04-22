@extends('layouts.app')

@section('title', $projeto->titulo . ' - Tasklean')
@section('page-title', $projeto->titulo)

@section('actions')
    <!-- Ações movidas para Ações Rápidas -->
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                    <i class="bi bi-folder me-2"></i>{{ $projeto->titulo }}
                </h1>
                <p class="text-muted mb-0">Detalhes e gerenciamento do projeto</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge {{ $projeto->ativo ? 'bg-success' : 'bg-secondary' }} me-3">
                    {{ $projeto->ativo ? 'Ativo' : 'Inativo' }}
                </span>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-download me-1"></i> Exportar
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="chart-container mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações do Projeto
                </h5>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Responsável</span>
                        </div>
                        <p class="mb-0">{{ $projeto->responsavel }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-calendar-plus text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Criado em</span>
                        </div>
                        <p class="mb-0">{{ $projeto->data_criacao->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Última atualização</span>
                        </div>
                        <p class="mb-0">{{ $projeto->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-list-task text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Total de tarefas</span>
                        </div>
                        <p class="mb-0 fw-bold text-primary fs-5">{{ $projeto->tarefas->count() }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-hourglass-split text-warning me-2"></i>
                            <span class="fw-semibold text-muted">Pendentes</span>
                        </div>
                        <p class="mb-0 fw-bold text-warning">{{ $projeto->tarefas->where('status', 'pendente')->count() }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-gear text-info me-2"></i>
                            <span class="fw-semibold text-muted">Em desenvolvimento</span>
                        </div>
                        <p class="mb-0 fw-bold text-info">{{ $projeto->tarefas->where('status', 'em desenvolvimento')->count() }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span class="fw-semibold text-muted">Concluídas</span>
                        </div>
                        <p class="mb-0 fw-bold text-success">{{ $projeto->tarefas->where('status', 'concluida')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarefas do Projeto -->
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-list-task me-2"></i>Tarefas do Projeto
                </h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Exportar</a></li>
                        <li><a class="dropdown-item" href="#">Filtrar</a></li>
                    </ul>
                </div>
            </div>
            <div>
                @if($projeto->tarefas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold">Título</th>
                                    <th class="fw-semibold">Status</th>
                                    <th class="fw-semibold">Responsável</th>
                                    <th class="fw-semibold">Vencimento</th>
                                    <th class="fw-semibold">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projeto->tarefas as $tarefa)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none fw-semibold text-primary">
                                                {{ $tarefa->titulo }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'backlog' => 'bg-secondary',
                                                    'pendente' => 'bg-warning',
                                                    'em desenvolvimento' => 'bg-info',
                                                    'concluida' => 'bg-success'
                                                ];
                                                $statusLabels = [
                                                    'backlog' => 'Backlog',
                                                    'pendente' => 'Pendente',
                                                    'em desenvolvimento' => 'Em Desenvolvimento',
                                                    'concluida' => 'Concluída'
                                                ];
                                            @endphp
                                            <span class="badge {{ $statusClasses[$tarefa->status] }} rounded-pill">
                                                {{ $statusLabels[$tarefa->status] }}
                                            </span>
                                        </td>
                                        <td class="text-muted">{{ $tarefa->responsavel }}</td>
                                        <td>
                                            @if($tarefa->data_vencimento)
                                                @if($tarefa->isAtrasada())
                                                    <span class="text-danger fw-semibold">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ $tarefa->data_vencimento->format('d/m/Y') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Sem vencimento</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('tarefas.show', $tarefa->id) }}" 
                                                   class="btn btn-outline-primary btn-sm" 
                                                   title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('tarefas.edit', $tarefa->id) }}" 
                                                   class="btn btn-outline-secondary btn-sm" 
                                                   title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-list-task display-4 text-muted"></i>
                        <h5 class="mt-3 text-muted">Nenhuma tarefa encontrada</h5>
                        <p class="text-muted">Este projeto ainda não possui tarefas.</p>
                        <a href="{{ route('tarefas.create', ['projeto_id' => $projeto->id]) }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Criar Primeira Tarefa
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Estatísticas -->
        <div class="chart-container mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Estatísticas
                </h5>
            </div>
            @php
                $totalTarefas = $projeto->tarefas->count();
                $concluidas = $projeto->tarefas->where('status', 'concluida')->count();
                $percentualConcluido = $totalTarefas > 0 ? ($concluidas / $totalTarefas) * 100 : 0;
            @endphp
            
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold text-muted">Progresso Geral</span>
                    <span class="fw-bold text-primary">{{ number_format($percentualConcluido, 1) }}%</span>
                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill primary" style="width: {{ $percentualConcluido }}%"></div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-4">
                    <div class="stat-card text-center p-3 border rounded-3">
                        <div class="text-warning fs-3 fw-bold">{{ $projeto->tarefas->where('status', 'pendente')->count() }}</div>
                        <small class="text-muted fw-semibold">Pendentes</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-card text-center p-3 border rounded-3">
                        <div class="text-info fs-3 fw-bold">{{ $projeto->tarefas->where('status', 'em desenvolvimento')->count() }}</div>
                        <small class="text-muted fw-semibold">Em Dev</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-card text-center p-3 border rounded-3">
                        <div class="text-success fs-3 fw-bold">{{ $projeto->tarefas->where('status', 'concluida')->count() }}</div>
                        <small class="text-muted fw-semibold">Concluídas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('tarefas.create', ['projeto_id' => $projeto->id]) }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Nova Tarefa
                </a>
                
                @if($projeto->ativo)
                    <form action="{{ route('projetos.inativar', $projeto->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-pause me-1"></i> Inativar Projeto
                        </button>
                    </form>
                @else
                    <form action="{{ route('projetos.ativar', $projeto->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-play me-1"></i> Ativar Projeto
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('projetos.edit', $projeto->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Editar Projeto
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
