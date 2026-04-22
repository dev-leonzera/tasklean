@extends('layouts.app')

@section('title', 'Projetos - Tasklean')
@section('page-title', 'Projetos')

@section('actions')
    <a href="{{ route('projetos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Novo Projeto
    </a>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                    <i class="bi bi-folder me-2"></i>Projetos
                </h1>
                <p class="text-muted mb-0">Gerencie todos os seus projetos de forma organizada</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">{{ $projetos->count() }} projeto(s)</span>
                <button class="btn btn-outline-primary" onclick="toggleFilters()">
                    <i class="bi bi-funnel me-1"></i> Filtros
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    .project-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
        border: 1px solid var(--border-color);
    }
    
    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .project-header {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .project-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
        line-height: 1.3;
    }
    
    .project-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .project-body {
        padding: 1rem 1.5rem;
    }
    
    .project-info {
        color: var(--secondary-color);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    
    .project-info i {
        width: 16px;
        margin-right: 0.75rem;
        color: #a0aec0;
    }
    
    .project-actions {
        padding: 0 1.5rem 1.5rem 1.5rem;
        display: flex;
        gap: 0.75rem;
    }
    
    .action-btn {
        flex: 1;
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary-action {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .btn-primary-action:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-secondary-action {
        background: #f7fafc;
        color: var(--secondary-color);
        border: 1px solid var(--border-color);
    }
    
    .btn-secondary-action:hover {
        background: #e2e8f0;
        color: var(--primary-color);
        transform: translateY(-1px);
    }
    
    .empty-state {
        background: #f7fafc;
        border-radius: 12px;
        padding: 4rem 2rem;
        text-align: center;
        color: var(--secondary-color);
        border: 1px solid var(--border-color);
    }
    
    .empty-state i {
        color: #a0aec0;
    }
    
    .filters-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
        display: none;
    }
    
    .filters-card.show {
        display: block;
    }
</style>

<!-- Filtros -->
<div class="filters-card" id="filtersCard">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('projetos.index') }}" id="filtersForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">Todos os Status</option>
                        <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Responsável</label>
                    <input type="text" class="form-control" name="responsavel" id="responsavelFilter" 
                           placeholder="Nome do responsável..." value="{{ request('responsavel') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Buscar</label>
                    <input type="text" class="form-control" name="busca" id="searchInput" 
                           placeholder="Título do projeto..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Ordenar por</label>
                    <select class="form-select" name="ordenacao" id="ordenacaoFilter">
                        <option value="data_criacao" {{ request('ordenacao') === 'data_criacao' ? 'selected' : '' }}>Data</option>
                        <option value="titulo" {{ request('ordenacao') === 'titulo' ? 'selected' : '' }}>Título</option>
                        <option value="responsavel" {{ request('ordenacao') === 'responsavel' ? 'selected' : '' }}>Responsável</option>
                        <option value="ativo" {{ request('ordenacao') === 'ativo' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('projetos.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Limpar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($projetos as $projeto)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card project-card {{ $projeto->ativo ? 'active' : 'inactive' }} h-100" 
                 data-status="{{ $projeto->ativo ? 'ativo' : 'inativo' }}" 
                 data-responsavel="{{ strtolower($projeto->responsavel->name ?? '') }}" 
                 data-titulo="{{ strtolower($projeto->titulo) }}">
                <div class="project-header">
                    <h5 class="project-title">{{ $projeto->titulo }}</h5>
                    <span class="badge {{ $projeto->ativo ? 'bg-success' : 'bg-secondary' }} project-status">
                        {{ $projeto->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                
                <div class="project-body">
                    <div class="project-info">
                        <i class="bi bi-person"></i>
                        <span>{{ $projeto->responsavel->name ?? 'Sem responsável' }}</span>
                    </div>
                    
                    <div class="project-info">
                        <i class="bi bi-calendar"></i>
                        <span>{{ $projeto->data_criacao->format('d/m/Y') }}</span>
                    </div>
                    
                </div>
                
                <div class="project-actions">
                    <a href="{{ route('projetos.show', $projeto->id) }}" class="action-btn btn-primary-action">
                        Ver Projeto
                    </a>
                    <a href="{{ route('projetos.edit', $projeto->id) }}" class="action-btn btn-secondary-action">
                        Editar
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-folder-x display-1"></i>
                <h3 class="mt-3 text-muted">Nenhum projeto encontrado</h3>
                <p class="text-muted mb-4">Comece criando seu primeiro projeto!</p>
                <a href="{{ route('projetos.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Criar Primeiro Projeto
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
    // Controla a exibição dos filtros
    function toggleFilters() {
        const filtersCard = document.getElementById('filtersCard');
        filtersCard.classList.toggle('show');
    }
    
    // Mostra os filtros se houver parâmetros na URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('status') || urlParams.has('responsavel') || urlParams.has('busca') || urlParams.has('ordenacao');
        
        if (hasFilters) {
            document.getElementById('filtersCard').classList.add('show');
        }
    });
</script>
@endsection
