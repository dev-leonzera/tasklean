@extends('layouts.app')

@section('title', 'Tarefas - Tasklean')
@section('page-title', 'Tarefas')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('tarefas.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Nova Tarefa
        </a>
        <a href="{{ route('kanban') }}" class="btn btn-secondary px-4 shadow-sm">
            <i class="bi bi-kanban me-2"></i> Kanban
        </a>
        <button class="btn btn-secondary px-4 shadow-sm" onclick="toggleFilters()">
            <i class="bi bi-funnel me-2"></i> Filtros
        </button>
    </div>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="h2 mb-1 fw-800 text-dark">
                    Gerencie suas Tarefas 🎯
                </h1>
                <p class="text-muted mb-0">Organize o backlog, acompanhe o progresso e cumpra seus prazos.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-bold d-flex align-items-center" style="background: var(--primary-light)">
                    {{ $tarefas->count() }} tarefas no total
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="chart-container mb-5 {{ request()->anyFilled(['status', 'projeto_id', 'busca']) ? '' : 'd-none' }}" id="filtersCard">
    <form method="GET" action="{{ route('tarefas.index') }}" id="filtersForm">
        <div class="d-flex align-items-center mb-4">
            <h5 class="section-title mb-0">
                <i class="bi bi-funnel"></i> Refinar Busca
            </h5>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status" onchange="this.form.submit()">
                    <option value="">Todos os Status</option>
                    <option value="backlog" {{ request('status') == 'backlog' ? 'selected' : '' }}>Backlog</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="em desenvolvimento" {{ request('status') == 'em desenvolvimento' ? 'selected' : '' }}>Em Desenvolvimento</option>
                    <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Projeto</label>
                <select class="form-select" name="projeto_id" onchange="this.form.submit()">
                    <option value="">Todos os Projetos</option>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}" {{ request('projeto_id') == $projeto->id ? 'selected' : '' }}>{{ $projeto->titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Buscar por título ou responsável</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="busca" value="{{ request('busca') }}" placeholder="Digite e aperte Enter...">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        Filtrar
                    </button>
                    <a href="{{ route('tarefas.index') }}" class="btn btn-secondary" title="Limpar Filtros">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@if($tarefas->count() > 0)
    <div class="row" id="tarefasContainer">
        @foreach($tarefas as $tarefa)
            <div class="col-xl-4 col-lg-6 mb-4 tarefa-card-item">
                
                <div class="chart-container p-0 overflow-hidden h-100 d-flex flex-column border-0 shadow-sm card-premium">
                    @php
                        $statusColor = match($tarefa->status) {
                            'backlog' => 'secondary',
                            'pendente' => 'warning',
                            'em desenvolvimento' => 'info',
                            'concluida' => 'success',
                            default => 'primary'
                        };
                    @endphp
                    
                    <div class="p-4 border-bottom bg-light bg-opacity-50">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge-premium {{ $statusColor }}">
                                {{ ucfirst($tarefa->status) }}
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4">
                                    <li><a class="dropdown-item" href="{{ route('tarefas.show', $tarefa->id) }}"><i class="bi bi-eye me-2"></i> Ver Detalhes</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tarefas.edit', $tarefa->id) }}"><i class="bi bi-pencil me-2"></i> Editar</a></li>
                                </ul>
                            </div>
                        </div>
                        <h5 class="fw-800 text-dark mb-1">
                            <a href="{{ route('tarefas.show', $tarefa->id) }}" class="text-decoration-none text-dark hover-primary">
                                {{ Str::limit($tarefa->titulo, 45) }}
                            </a>
                        </h5>
                        <div class="task-meta">
                            <span class="task-meta-item">
                                <i class="bi bi-folder"></i> {{ $tarefa->projeto->titulo }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-4 flex-grow-1">
                        <div class="activity-list">
                            <div class="activity-item bg-white border-0 shadow-none mb-2 p-2">
                                <div class="task-card-icon primary bg-opacity-10" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="flex-grow-1 small fw-semibold text-muted">
                                    {{ $tarefa->responsavel->name ?? 'Sem responsável' }}
                                </div>
                            </div>
                            
                            <div class="activity-item bg-white border-0 shadow-none mb-0 p-2">
                                <div class="task-card-icon {{ $tarefa->isAtrasada() ? 'danger' : 'info' }} bg-opacity-10" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-calendar3"></i>
                                </div>
                                <div class="flex-grow-1 small fw-semibold {{ $tarefa->isAtrasada() ? 'text-danger' : 'text-muted' }}">
                                    @if($tarefa->data_vencimento)
                                        Prazo: {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                        @if($tarefa->isAtrasada())
                                            <span class="ms-1 fw-bold">(Atrasada)</span>
                                        @endif
                                    @else
                                        Sem prazo definido
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 border-top">
                        <div class="d-flex align-items-center justify-content-between">
                            @php
                                $prioridadeColor = match($tarefa->prioridade) {
                                    'baixa' => 'success',
                                    'media' => 'warning',
                                    'alta' => 'danger',
                                    'urgente' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 py-1.5 fw-bold bg-soft-{{ $prioridadeColor }} text-{{ $prioridadeColor }}" style="font-size: 0.7rem; background-color: var(--{{ $prioridadeColor }}-light)">
                                <i class="bi bi-flag-fill me-1"></i> {{ ucfirst($tarefa->prioridade ?? 'Normal') }}
                            </span>
                            <a href="{{ route('tarefas.show', $tarefa->id) }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-primary">
                                Detalhes <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="chart-container text-center py-5">
        <div class="mb-4">
            <i class="bi bi-list-task text-muted opacity-25" style="font-size: 5rem;"></i>
        </div>
        <h3 class="fw-800 text-dark">Nenhuma tarefa encontrada</h3>
        <p class="text-muted mb-4">Seu backlog está vazio. Que tal criar uma nova tarefa para começar?</p>
        <a href="{{ route('tarefas.create') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Criar Primeira Tarefa
        </a>
    </div>
@endif
@endsection

@section('scripts')
<script>
    function toggleFilters() {
        const filtersCard = document.getElementById('filtersCard');
        filtersCard.classList.toggle('d-none');
    }
</script>
@endsection
