@extends('layouts.app')

@section('title', 'Tarefas - Tasklean')
@section('page-title', 'Tarefas')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('tarefas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nova Tarefa
        </a>
        <a href="{{ route('kanban') }}" class="btn btn-outline-secondary">
            <i class="bi bi-kanban me-1"></i> Kanban
        </a>
    </div>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                    <i class="bi bi-list-task me-2"></i>Tarefas
                </h1>
                <p class="text-muted mb-0">Organize e acompanhe todas as suas tarefas</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">{{ $tarefas->count() }} tarefa(s)</span>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i> Filtros
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    .task-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
        border: 1px solid var(--border-color);
    }
    
    .task-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .task-card.pendente {
        border-left: 4px solid #f59e0b;
    }
    
    .task-card.em-desenvolvimento {
        border-left: 4px solid #3b82f6;
    }
    
    .task-card.concluida {
        border-left: 4px solid #10b981;
        opacity: 0.95;
    }
    
    .task-card.backlog {
        border-left: 4px solid #6b7280;
    }
    
    .task-header {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .task-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
        line-height: 1.3;
    }
    
    .task-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .task-body {
        padding: 1rem 1.5rem;
    }
    
    .task-info {
        color: var(--secondary-color);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    
    .task-info i {
        width: 16px;
        margin-right: 0.75rem;
        color: #a0aec0;
    }
    
    .task-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .task-tag {
        background: #f7fafc;
        color: var(--secondary-color);
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
        border: 1px solid var(--border-color);
    }
    
    .task-actions {
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
    }
</style>

<div class="row">
    <div class="col-12">
        <!-- Filtros -->
        <div class="filters-card">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos os Status</option>
                            <option value="pendente">Pendente</option>
                            <option value="em desenvolvimento">Em Desenvolvimento</option>
                            <option value="concluida">Concluída</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Projeto</label>
                        <select class="form-select" id="projetoFilter">
                            <option value="">Todos os Projetos</option>
                            @foreach($tarefas->pluck('projeto')->unique() as $projeto)
                                <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Buscar</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Título ou responsável...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">&nbsp;</label>
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i> Limpar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Tarefas -->
        @if($tarefas->count() > 0)
            <div class="row" id="tarefasContainer">
                @foreach($tarefas as $tarefa)
                    <div class="col-md-6 col-lg-4 mb-4 tarefa-card" 
                         data-status="{{ $tarefa->status }}" 
                         data-projeto="{{ $tarefa->projeto_id }}"
                         data-search="{{ strtolower($tarefa->titulo . ' ' . $tarefa->responsavel) }}">
                        <div class="card task-card {{ str_replace(' ', '-', $tarefa->status) }} h-100">
                            <div class="task-header">
                                <h6 class="task-title">{{ Str::limit($tarefa->titulo, 30) }}</h6>
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
                                        'em desenvolvimento' => 'Em Dev',
                                        'concluida' => 'Concluída'
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$tarefa->status] }} task-status">
                                    {{ $statusLabels[$tarefa->status] }}
                                </span>
                            </div>
                            
                            <div class="task-body">
                                <div class="task-info">
                                    <i class="bi bi-folder"></i>
                                    <span>
                                        <a href="{{ route('projetos.show', $tarefa->projeto_id) }}" class="text-decoration-none">
                                            {{ $tarefa->projeto->titulo }}
                                        </a>
                                    </span>
                                </div>
                                
                                <div class="task-info">
                                    <i class="bi bi-person"></i>
                                    <span>{{ $tarefa->responsavel }}</span>
                                </div>
                                
                                <div class="task-tags">
                                    <span class="task-tag">{{ $tarefa->data_criacao->format('d/m/Y') }}</span>
                                    
                                    @if($tarefa->data_vencimento)
                                        @if($tarefa->isAtrasada())
                                            <span class="task-tag" style="background: #fef2f2; color: #dc2626; border-color: #fecaca;">
                                                Vencida
                                            </span>
                                        @else
                                            <span class="task-tag">
                                                Vence {{ $tarefa->data_vencimento->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    @endif
                                    
                                    @if($tarefa->prioridade)
                                        <span class="task-tag">
                                            {{ ucfirst($tarefa->prioridade) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="task-actions">
                                <a href="{{ route('tarefas.show', $tarefa->id) }}" class="action-btn btn-primary-action">
                                    Ver Tarefa
                                </a>
                                <a href="{{ route('tarefas.edit', $tarefa->id) }}" class="action-btn btn-secondary-action">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-list-task display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">Nenhuma tarefa encontrada</h3>
                <p class="text-muted">Comece criando sua primeira tarefa!</p>
                <a href="{{ route('tarefas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Criar Primeira Tarefa
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Filtros em tempo real
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const projetoFilter = document.getElementById('projetoFilter');
        const searchInput = document.getElementById('searchInput');
        
        function filterTarefas() {
            const statusValue = statusFilter.value;
            const projetoValue = projetoFilter.value;
            const searchValue = searchInput.value.toLowerCase();
            
            const cards = document.querySelectorAll('.tarefa-card');
            
            cards.forEach(card => {
                const status = card.dataset.status;
                const projeto = card.dataset.projeto;
                const search = card.dataset.search;
                
                let show = true;
                
                if (statusValue && status !== statusValue) show = false;
                if (projetoValue && projeto !== projetoValue) show = false;
                if (searchValue && !search.includes(searchValue)) show = false;
                
                card.style.display = show ? 'block' : 'none';
            });
        }
        
        statusFilter.addEventListener('change', filterTarefas);
        projetoFilter.addEventListener('change', filterTarefas);
        searchInput.addEventListener('input', filterTarefas);
    });
    
    function clearFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('projetoFilter').value = '';
        document.getElementById('searchInput').value = '';
        
        // Mostrar todas as tarefas
        document.querySelectorAll('.tarefa-card').forEach(card => {
            card.style.display = 'block';
        });
    }
</script>
@endsection
