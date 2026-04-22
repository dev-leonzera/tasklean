@extends('layouts.app')

@section('title', $tarefa->titulo . ' - Tasklean')
@section('page-title', $tarefa->titulo)

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
                    <i class="bi bi-list-task me-2"></i>{{ $tarefa->titulo }}
                </h1>
                <p class="text-muted mb-0">Detalhes e gerenciamento da tarefa</p>
            </div>
            <div class="d-flex align-items-center">
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
                <span class="badge {{ $statusClasses[$tarefa->status] }} me-3 rounded-pill">
                    {{ $statusLabels[$tarefa->status] }}
                </span>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-share me-1"></i> Compartilhar
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
                    <i class="bi bi-info-circle me-2"></i>Detalhes da Tarefa
                </h5>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-folder text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Projeto</span>
                        </div>
                        <p class="mb-0">
                            <a href="{{ route('projetos.show', $tarefa->projeto_id) }}" class="text-decoration-none fw-semibold text-primary">
                                {{ $tarefa->projeto->titulo }}
                            </a>
                        </p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Responsável</span>
                        </div>
                        <p class="mb-0">{{ $tarefa->responsavel }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-calendar-plus text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Criada em</span>
                        </div>
                        <p class="mb-0">{{ $tarefa->data_criacao->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Última atualização</span>
                        </div>
                        <p class="mb-0">{{ $tarefa->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-calendar-check text-primary me-2"></i>
                            <span class="fw-semibold text-muted">Data de Vencimento</span>
                        </div>
                        @if($tarefa->data_vencimento)
                            @if($tarefa->isAtrasada())
                                <p class="mb-0 text-danger fw-semibold">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                    <small class="d-block text-muted">(Atrasada)</small>
                                </p>
                            @else
                                <p class="mb-0">
                                    {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                    @if($tarefa->data_vencimento->isToday())
                                        <small class="d-block text-warning fw-semibold">(Vence hoje!)</small>
                                    @elseif($tarefa->data_vencimento->isTomorrow())
                                        <small class="d-block text-info fw-semibold">(Vence amanhã)</small>
                                    @endif
                                </p>
                            @endif
                        @else
                            <p class="mb-0 text-muted">Não definida</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($tarefa->descricao)
                <hr class="my-4">
                <div class="mb-3">
                    <h6 class="fw-semibold text-muted mb-3">
                        <i class="bi bi-file-text me-2"></i>Descrição
                    </h6>
                    <div class="bg-light p-3 rounded-3">
                        <p class="mb-0 text-muted">{{ $tarefa->descricao }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <!-- Alterar Status -->
        <div class="chart-container mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-gear me-2"></i>Alterar Status
                </h5>
            </div>
            <form action="{{ route('tarefas.status', $tarefa->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Status atual</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="pendente" {{ $tarefa->status === 'pendente' ? 'selected' : '' }}>
                            Pendente
                        </option>
                        <option value="em desenvolvimento" {{ $tarefa->status === 'em desenvolvimento' ? 'selected' : '' }}>
                            Em Desenvolvimento
                        </option>
                        <option value="concluida" {{ $tarefa->status === 'concluida' ? 'selected' : '' }}>
                            Concluída
                        </option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Ações Rápidas -->
        <div class="chart-container mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('tarefas.edit', $tarefa->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Editar Tarefa
                </a>
                
                <a href="{{ route('projetos.show', $tarefa->projeto_id) }}" class="btn btn-outline-info">
                    <i class="bi bi-folder me-1"></i> Ver Projeto
                </a>
                
                <a href="{{ route('tarefas.create', ['projeto_id' => $tarefa->projeto_id]) }}" class="btn btn-outline-success">
                    <i class="bi bi-plus-circle me-1"></i> Nova Tarefa no Projeto
                </a>
                
                <form action="{{ route('tarefas.destroy', $tarefa->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100" data-confirm-delete>
                        <i class="bi bi-trash me-1"></i> Excluir Tarefa
                    </button>
                </form>
            </div>
        </div>

        <!-- Informações do Projeto -->
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title mb-0">
                    <i class="bi bi-folder me-2"></i>Projeto Relacionado
                </h5>
            </div>
            <div class="project-info-card">
                <h6 class="fw-bold text-primary mb-2">{{ $tarefa->projeto->titulo }}</h6>
                <p class="text-muted mb-3">
                    <i class="bi bi-person me-1"></i>{{ $tarefa->projeto->responsavel }}
                </p>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">Status:</span>
                    <span class="badge {{ $tarefa->projeto->ativo ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                        {{ $tarefa->projeto->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">Total de tarefas:</span>
                    <span class="fw-bold text-primary">{{ $tarefa->projeto->tarefas->count() }}</span>
                </div>
                
                <div class="d-grid">
                    <a href="{{ route('projetos.show', $tarefa->projeto_id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i> Ver Projeto Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
