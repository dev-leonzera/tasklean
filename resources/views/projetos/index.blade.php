@extends('layouts.app')

@section('title', 'Projetos - Tasklean')
@section('page-title', 'Projetos')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('projetos.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Novo Projeto
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
                    Seus Projetos 📁
                </h1>
                <p class="text-muted mb-0">Organize suas frentes de trabalho e colabore com sua equipe.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-bold d-flex align-items-center" style="background: var(--primary-light)">
                    {{ $projetos->count() }} projetos ativos
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="chart-container mb-5 d-none" id="filtersCard">
    <div class="d-flex align-items-center mb-4">
        <h5 class="section-title mb-0">
            <i class="bi bi-funnel"></i> Filtrar Projetos
        </h5>
    </div>
    <form method="GET" action="{{ route('projetos.index') }}">
        <div class="row g-4">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">Todos os Status</option>
                    <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Responsável</label>
                <input type="text" class="form-control" name="responsavel" placeholder="Nome do responsável..." value="{{ request('responsavel') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Buscar por título</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="busca" placeholder="Digite para buscar..." value="{{ request('busca') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ordenar por</label>
                <select class="form-select" name="ordenacao">
                    <option value="data_criacao" {{ request('ordenacao') === 'data_criacao' ? 'selected' : '' }}>Data</option>
                    <option value="titulo" {{ request('ordenacao') === 'titulo' ? 'selected' : '' }}>Título</option>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('projetos.index') }}" class="btn btn-secondary px-4">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpar
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check2-circle me-1"></i> Aplicar Filtros
            </button>
        </div>
    </form>
</div>

@if($projetos->count() > 0)
    <div class="row">
        @foreach($projetos as $projeto)
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="chart-container p-0 overflow-hidden h-100 d-flex flex-column border-0 shadow-sm card-premium">
                    <div class="p-4 border-bottom bg-light bg-opacity-50">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge-premium {{ $projeto->ativo ? 'success' : 'secondary' }}">
                                {{ $projeto->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4">
                                    <li><a class="dropdown-item" href="{{ route('projetos.show', $projeto->id) }}"><i class="bi bi-eye me-2"></i> Ver Detalhes</a></li>
                                    <li><a class="dropdown-item" href="{{ route('projetos.edit', $projeto->id) }}"><i class="bi bi-pencil me-2"></i> Editar</a></li>
                                </ul>
                            </div>
                        </div>
                        <h5 class="fw-800 text-dark mb-1">
                            <a href="{{ route('projetos.show', $projeto->id) }}" class="text-decoration-none text-dark hover-primary">
                                {{ Str::limit($projeto->titulo, 45) }}
                            </a>
                        </h5>
                        <div class="task-meta">
                            <span class="task-meta-item">
                                <i class="bi bi-calendar3"></i> Criado em {{ $projeto->data_criacao->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-4 flex-grow-1">
                        <div class="activity-list">
                            <div class="activity-item bg-white border-0 shadow-none mb-3 p-2">
                                <div class="task-card-icon primary bg-opacity-10" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Responsável</div>
                                    <div class="small fw-semibold text-dark">{{ $projeto->responsavel->name ?? 'Sem responsável' }}</div>
                                </div>
                            </div>
                            
                            <div class="activity-item bg-white border-0 shadow-none mb-0 p-2">
                                <div class="task-card-icon info bg-opacity-10" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="bi bi-list-task"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Tarefas</div>
                                    <div class="small fw-semibold text-dark">{{ $projeto->tarefas->count() }} registradas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 border-top">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="progress flex-grow-1 me-3" style="height: 6px;">
                                @php
                                    $totalTasks = $projeto->tarefas->count();
                                    $completedTasks = $projeto->tarefas->where('status', 'concluida')->count();
                                    $percent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                @endphp
                                <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="small fw-bold text-dark">{{ $percent }}%</span>
                        </div>
                    </div>

                    <div class="p-4 bg-light bg-opacity-25 border-top text-center">
                        <a href="{{ route('projetos.show', $projeto->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm">
                            Gerenciar Projeto <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="chart-container text-center py-5">
        <div class="mb-4">
            <i class="bi bi-folder-x text-muted opacity-25" style="font-size: 5rem;"></i>
        </div>
        <h3 class="fw-800 text-dark">Nenhum projeto encontrado</h3>
        <p class="text-muted mb-4">Você ainda não tem frentes de trabalho cadastradas.</p>
        <a href="{{ route('projetos.create') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Criar Primeiro Projeto
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
