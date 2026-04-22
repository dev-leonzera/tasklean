@extends('layouts.app')

@section('title', 'Compromissos - Tasklean')
@section('page-title', 'Compromissos')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('compromissos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Novo Compromisso
        </a>
        <button class="btn btn-outline-primary" onclick="toggleFilters()">
            <i class="bi bi-funnel me-1"></i> Filtros
        </button>
    </div>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                <i class="bi bi-calendar-event me-2"></i>Compromissos
                </h1>
            <p class="text-muted mb-0">Gerencie sua agenda de compromissos</p>
        </div>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">{{ $compromissos->count() }} compromisso(s)</span>
            </div>
        </div>
    </div>
</div>
<style>
    .compromisso-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
        border: 1px solid var(--border-color);
    }
    
    .compromisso-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .compromisso-card.agendado {
        border-left: 4px solid #3b82f6;
    }
    
    .compromisso-card.em-andamento {
        border-left: 4px solid #f59e0b;
    }
    
    .compromisso-card.concluido {
        border-left: 4px solid #10b981;
        opacity: 0.95;
    }
    
    .compromisso-card.cancelado {
        border-left: 4px solid #ef4444;
    }
    
    .compromisso-card.adiado {
        border-left: 4px solid #6b7280;
    }
    
    .compromisso-header {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .compromisso-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
        line-height: 1.3;
    }
    
    .compromisso-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .compromisso-body {
        padding: 1rem 1.5rem;
    }
    
    .compromisso-info {
        color: var(--secondary-color);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    
    .compromisso-info i {
        width: 16px;
        margin-right: 0.75rem;
        color: #a0aec0;
    }
    
    .compromisso-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .compromisso-tag {
        background: #f7fafc;
        color: var(--secondary-color);
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
        border: 1px solid var(--border-color);
    }
    
    .compromisso-actions {
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
        <form method="GET" action="{{ route('compromissos.index') }}" id="filtersForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">Todos os Status</option>
                        <option value="agendado" {{ request('status') === 'agendado' ? 'selected' : '' }}>Agendado</option>
                        <option value="em_andamento" {{ request('status') === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="concluido" {{ request('status') === 'concluido' ? 'selected' : '' }}>Concluído</option>
                        <option value="cancelado" {{ request('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        <option value="adiado" {{ request('status') === 'adiado' ? 'selected' : '' }}>Adiado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Tipo</label>
                    <select class="form-select" name="tipo" id="tipoFilter">
                        <option value="">Todos os Tipos</option>
                        <option value="reuniao" {{ request('tipo') === 'reuniao' ? 'selected' : '' }}>Reunião</option>
                        <option value="evento" {{ request('tipo') === 'evento' ? 'selected' : '' }}>Evento</option>
                        <option value="tarefa" {{ request('tipo') === 'tarefa' ? 'selected' : '' }}>Tarefa</option>
                        <option value="lembrete" {{ request('tipo') === 'lembrete' ? 'selected' : '' }}>Lembrete</option>
                        <option value="compromisso_pessoal" {{ request('tipo') === 'compromisso_pessoal' ? 'selected' : '' }}>Compromisso Pessoal</option>
                        <option value="outro" {{ request('tipo') === 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Buscar</label>
                    <input type="text" class="form-control" name="busca" id="searchInput" 
                           placeholder="Título do compromisso..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Ordenar por</label>
                    <select class="form-select" name="ordenacao" id="ordenacaoFilter">
                        <option value="data_inicio" {{ request('ordenacao') === 'data_inicio' ? 'selected' : '' }}>Data</option>
                        <option value="titulo" {{ request('ordenacao') === 'titulo' ? 'selected' : '' }}>Título</option>
                        <option value="prioridade" {{ request('ordenacao') === 'prioridade' ? 'selected' : '' }}>Prioridade</option>
                        <option value="status" {{ request('ordenacao') === 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('compromissos.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Limpar
                        </a>
                    </div>
                </div>
            </div>
        </form>
                                        </div>
                                        </div>

<div class="row">
    @forelse($compromissos as $compromisso)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card compromisso-card {{ str_replace(' ', '-', $compromisso->status) }} h-100" 
                 data-status="{{ $compromisso->status }}" 
                 data-tipo="{{ $compromisso->tipo }}"
                 data-search="{{ strtolower($compromisso->titulo) }}">
                <div class="compromisso-header">
                    <h5 class="compromisso-title">{{ Str::limit($compromisso->titulo, 30) }}</h5>
                    @php
                        $statusClasses = [
                            'agendado' => 'bg-primary',
                            'em_andamento' => 'bg-warning',
                            'concluido' => 'bg-success',
                            'cancelado' => 'bg-danger',
                            'adiado' => 'bg-secondary'
                        ];
                        $statusLabels = [
                            'agendado' => 'Agendado',
                            'em_andamento' => 'Em Andamento',
                            'concluido' => 'Concluído',
                            'cancelado' => 'Cancelado',
                            'adiado' => 'Adiado'
                        ];
                    @endphp
                    <span class="badge {{ $statusClasses[$compromisso->status] }} compromisso-status">
                        {{ $statusLabels[$compromisso->status] }}
                                        </span>
                </div>
                
                <div class="compromisso-body">
                    <div class="compromisso-info">
                        <i class="bi bi-calendar"></i>
                        <span>{{ $compromisso->data_inicio->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="compromisso-info">
                        <i class="bi bi-clock"></i>
                        <span>{{ $compromisso->hora_inicio->format('H:i') }} - {{ $compromisso->hora_fim->format('H:i') }}</span>
                    </div>
                    
                    @if($compromisso->local)
                        <div class="compromisso-info">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ Str::limit($compromisso->local, 25) }}</span>
                        </div>
                    @endif
                    
                    <div class="compromisso-tags">
                        <span class="compromisso-tag">{{ $compromisso->tipo_formatado }}</span>
                        
                                        @php
                                            $prioridadeColors = [
                                                'baixa' => 'success',
                                                'media' => 'warning',
                                                'alta' => 'danger',
                                                'urgente' => 'dark'
                                            ];
                                        @endphp
                        <span class="compromisso-tag" style="background: var(--{{ $prioridadeColors[$compromisso->prioridade] ?? 'secondary' }}-color); color: white;">
                                            {{ $compromisso->prioridade_formatada }}
                                        </span>
                    </div>
                </div>
                
                <div class="compromisso-actions">
                    <a href="{{ route('compromissos.show', $compromisso) }}" class="action-btn btn-primary-action">
                        Ver Compromisso
                    </a>
                    <a href="{{ route('compromissos.edit', $compromisso) }}" class="action-btn btn-secondary-action">
                        Editar
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-calendar-x display-1"></i>
                <h3 class="mt-3 text-muted">Nenhum compromisso encontrado</h3>
                <p class="text-muted mb-4">Comece criando seu primeiro compromisso!</p>
                <a href="{{ route('compromissos.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Criar Primeiro Compromisso
                </a>
            </div>
        </div>
    @endforelse
                </div>

                <!-- Paginação -->
@if($compromissos->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Mostrando {{ $compromissos->firstItem() ?? 0 }} a {{ $compromissos->lastItem() ?? 0 }} 
                        de {{ $compromissos->total() }} compromissos
                    </div>
                    <div>
                        {{ $compromissos->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif
@endsection

@section('scripts')
<script>
    // Controla a exibição dos filtros
    function toggleFilters() {
        const filtersCard = document.getElementById('filtersCard');
        filtersCard.classList.toggle('show');
    }
    
    // Filtros em tempo real
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const tipoFilter = document.getElementById('tipoFilter');
        const searchInput = document.getElementById('searchInput');
        
        function filterCompromissos() {
            const statusValue = statusFilter.value;
            const tipoValue = tipoFilter.value;
            const searchValue = searchInput.value.toLowerCase();
            
            const cards = document.querySelectorAll('.compromisso-card');
            
            cards.forEach(card => {
                const status = card.dataset.status;
                const tipo = card.dataset.tipo;
                const search = card.dataset.search;
                
                let show = true;
                
                if (statusValue && status !== statusValue) show = false;
                if (tipoValue && tipo !== tipoValue) show = false;
                if (searchValue && !search.includes(searchValue)) show = false;
                
                card.closest('.col-md-6').style.display = show ? 'block' : 'none';
            });
        }
        
        statusFilter.addEventListener('change', filterCompromissos);
        tipoFilter.addEventListener('change', filterCompromissos);
        searchInput.addEventListener('input', filterCompromissos);
        
        // Mostra os filtros se houver parâmetros na URL
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('status') || urlParams.has('tipo') || urlParams.has('busca') || urlParams.has('ordenacao');
        
        if (hasFilters) {
            document.getElementById('filtersCard').classList.add('show');
        }
    });
</script>
@endsection
