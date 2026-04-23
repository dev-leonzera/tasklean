@extends('layouts.app')

@section('title', $tarefa->titulo . ' - Tasklean')
@section('page-title', $tarefa->titulo)

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('tarefas.edit', $tarefa->id) }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-pencil-square me-2"></i> Editar
        </a>
        <a href="{{ route('tarefas.index') }}" class="btn btn-secondary px-4 shadow-sm">
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
                        <li class="breadcrumb-item"><a href="{{ route('tarefas.index') }}" class="text-decoration-none text-muted">Tarefas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-1 fw-800 text-dark">
                    {{ $tarefa->titulo }}
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-folder me-1 text-primary"></i> 
                    <a href="{{ route('projetos.show', $tarefa->projeto_id) }}" class="text-decoration-none text-muted fw-bold">
                        {{ $tarefa->projeto->titulo }}
                    </a>
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @php
                    $statusColor = match($tarefa->status) {
                        'backlog' => 'secondary',
                        'pendente' => 'warning',
                        'em desenvolvimento' => 'info',
                        'concluida' => 'success',
                        default => 'primary'
                    };
                @endphp
                <span class="badge-premium {{ $statusColor }} px-4 py-2 fs-6">
                    {{ ucfirst($tarefa->status) }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Descrição -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-card-text"></i> Descrição da Tarefa
            </h5>
            @if($tarefa->descricao)
                <div class="bg-light rounded-4 p-4">
                    <p class="text-dark mb-0 lead" style="line-height: 1.8;">
                        {{ $tarefa->descricao }}
                    </p>
                </div>
            @else
                <div class="text-center py-5 border rounded-4 border-dashed bg-light">
                    <i class="bi bi-chat-left-dots text-muted opacity-25 fs-1 mb-3"></i>
                    <p class="text-muted mb-0 italic">Nenhuma descrição detalhada fornecida para esta tarefa.</p>
                </div>
            @endif
        </div>

        <!-- Atividades e Metadados -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-info-circle"></i> Informações do Registro
            </h5>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="task-card-icon primary">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">Responsável</div>
                                <div class="text-muted small">{{ $tarefa->responsavel->name ?? 'Ninguém atribuído' }}</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="task-card-icon success">
                                <i class="bi bi-calendar-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">Data de Criação</div>
                                <div class="text-muted small">{{ $tarefa->data_criacao->format('d/m/Y \à\s H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="task-card-icon {{ $tarefa->isAtrasada() ? 'danger' : 'info' }}">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">Prazo de Entrega</div>
                                <div class="text-muted small">
                                    @if($tarefa->data_vencimento)
                                        {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                        @if($tarefa->isAtrasada())
                                            <span class="text-danger fw-bold ms-2">(Em Atraso)</span>
                                        @endif
                                    @else
                                        Sem prazo definido
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="task-card-icon secondary">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">Última Atualização</div>
                                <div class="text-muted small">{{ $tarefa->updated_at->format('d/m/Y \à\s H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Alterar Status Rápido -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-gear"></i> Status da Tarefa
            </h5>
            <form action="{{ route('tarefas.status', $tarefa->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="bg-light p-3 rounded-4 mb-3 border">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2">Progresso Atual</label>
                    <select name="status" class="form-select border-0 bg-transparent fw-bold text-dark" onchange="this.form.submit()">
                        <option value="backlog" {{ $tarefa->status === 'backlog' ? 'selected' : '' }}>Backlog</option>
                        <option value="pendente" {{ $tarefa->status === 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="em desenvolvimento" {{ $tarefa->status === 'em desenvolvimento' ? 'selected' : '' }}>Em Desenvolvimento</option>
                        <option value="concluida" {{ $tarefa->status === 'concluida' ? 'selected' : '' }}>Concluída</option>
                    </select>
                </div>
                <p class="text-muted small mb-0 px-2"><i class="bi bi-info-circle me-1"></i> A alteração de status é salva automaticamente ao selecionar.</p>
            </form>
        </div>

        <!-- Projeto Relacionado -->
        <div class="chart-container mb-4">
            <h5 class="section-title mb-4">
                <i class="bi bi-folder-symlink"></i> Projeto Pai
            </h5>
            <div class="bg-light p-4 rounded-4 border-0">
                <h6 class="fw-800 text-dark mb-2">{{ $tarefa->projeto->titulo }}</h6>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge-premium {{ $tarefa->projeto->ativo ? 'success' : 'secondary' }} py-1 px-3" style="font-size: 0.65rem;">
                        {{ $tarefa->projeto->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                    <span class="text-muted small ms-3">
                        <i class="bi bi-list-task me-1"></i> {{ $tarefa->projeto->tarefas->count() }} tarefas
                    </span>
                </div>
                <div class="d-grid mt-4">
                    <a href="{{ route('projetos.show', $tarefa->projeto_id) }}" class="btn btn-outline-primary fw-bold rounded-pill">
                        Ver Projeto Completo <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Ações de Exclusão -->
        <div class="chart-container border-dashed border-danger bg-soft-danger bg-opacity-10">
            <h5 class="section-title text-danger mb-4">
                <i class="bi bi-exclamation-octagon"></i> Zona de Risco
            </h5>
            <form action="{{ route('tarefas.destroy', $tarefa->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-light text-danger w-100 fw-bold border-danger py-2 rounded-pill" 
                        onclick="return confirm('Tem certeza que deseja excluir esta tarefa permanentemente?')">
                    <i class="bi bi-trash me-2"></i> Excluir Tarefa
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
