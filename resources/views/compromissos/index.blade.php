@extends('layouts.app')

@section('title', 'Compromissos - Tasklean')
@section('page-title', 'Compromissos')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('compromissos.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Novo Compromisso
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
                    Olá! 👋 Aqui está sua agenda.
                </h1>
                <p class="text-muted mb-0">Gerencie sua agenda de compromissos de forma visual e organizada</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-bold d-flex align-items-center" style="background: var(--primary-light)">
                    {{ $compromissos->total() }} compromissos cadastrados
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="chart-container mb-5 {{ request()->anyFilled(['status', 'tipo', 'busca', 'ordenacao']) ? '' : 'd-none' }}" id="filtersCard">
    <div class="d-flex align-items-center mb-4">
        <h5 class="section-title mb-0">
            <i class="bi bi-funnel"></i> Filtros de Busca
        </h5>
    </div>
    <form method="GET" action="{{ route('compromissos.index') }}" id="filtersForm">
        <div class="row g-4">
            <div class="col-md-3">
                <label class="form-label">Status</label>
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
                <label class="form-label">Tipo</label>
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
                <label class="form-label">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="busca" id="searchInput" 
                           placeholder="Título do compromisso..." value="{{ request('busca') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ordenar por</label>
                <select class="form-select" name="ordenacao" id="ordenacaoFilter">
                    <option value="data_inicio" {{ request('ordenacao') === 'data_inicio' ? 'selected' : '' }}>Data</option>
                    <option value="titulo" {{ request('ordenacao') === 'titulo' ? 'selected' : '' }}>Título</option>
                    <option value="prioridade" {{ request('ordenacao') === 'prioridade' ? 'selected' : '' }}>Prioridade</option>
                    <option value="status" {{ request('ordenacao') === 'status' ? 'selected' : '' }}>Status</option>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('compromissos.index') }}" class="btn btn-secondary px-4">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Limpar
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check2-circle me-1"></i> Aplicar Filtros
            </button>
        </div>
    </form>
</div>

<div class="row">
    @forelse($compromissos as $compromisso)
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="chart-container p-0 overflow-hidden h-100 d-flex flex-column border-0 shadow-sm card-premium">
                @php
                    $statusColor = match($compromisso->status) {
                        'agendado' => 'info',
                        'em_andamento' => 'warning',
                        'concluido' => 'success',
                        'cancelado' => 'danger',
                        'adiado' => 'secondary',
                        default => 'primary'
                    };
                    $statusLabel = match($compromisso->status) {
                        'agendado' => 'Agendado',
                        'em_andamento' => 'Em Andamento',
                        'concluido' => 'Concluído',
                        'cancelado' => 'Cancelado',
                        'adiado' => 'Adiado',
                        default => ucfirst($compromisso->status)
                    };
                @endphp
                
                <div class="p-4 border-bottom bg-light bg-opacity-50">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge-premium {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4">
                                <li><a class="dropdown-item" href="{{ route('compromissos.show', $compromisso) }}"><i class="bi bi-eye me-2"></i> Ver Detalhes</a></li>
                                <li><a class="dropdown-item" href="{{ route('compromissos.edit', $compromisso) }}"><i class="bi bi-pencil me-2"></i> Editar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('compromissos.destroy', $compromisso) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Excluir</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="fw-800 text-dark mb-1">
                        <a href="{{ route('compromissos.show', $compromisso) }}" class="text-decoration-none text-dark hover-primary">
                            {{ Str::limit($compromisso->titulo, 45) }}
                        </a>
                    </h5>
                    <div class="task-meta">
                        <span class="task-meta-item">
                            <i class="bi bi-tag"></i> {{ $compromisso->tipo_formatado }}
                        </span>
                    </div>
                </div>
                
                <div class="p-4 flex-grow-1">
                    <div class="activity-list">
                        <div class="activity-item bg-white border-0 shadow-none mb-2 p-2">
                            <div class="task-card-icon {{ $statusColor }} bg-opacity-10" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                <i class="bi bi-calendar3"></i>
                            </div>
                            <div class="flex-grow-1 small fw-semibold text-muted">
                                {{ $compromisso->data_inicio->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="activity-item bg-white border-0 shadow-none mb-2 p-2">
                            <div class="task-card-icon {{ $statusColor }} bg-opacity-10" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="flex-grow-1 small fw-semibold text-muted">
                                {{ $compromisso->hora_inicio->format('H:i') }}
                                @if($compromisso->hora_fim)
                                    - {{ $compromisso->hora_fim->format('H:i') }}
                                @endif
                            </div>
                        </div>
                        @if($compromisso->local)
                            <div class="activity-item bg-white border-0 shadow-none mb-0 p-2">
                                <div class="task-card-icon {{ $statusColor }} bg-opacity-10" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="flex-grow-1 small fw-semibold text-muted text-truncate">
                                    {{ Str::limit($compromisso->local, 35) }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="p-4 border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        @php
                            $prioridadeColor = match($compromisso->prioridade) {
                                'baixa' => 'success',
                                'media' => 'warning',
                                'alta' => 'danger',
                                'urgente' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge rounded-pill px-3 py-1.5 fw-bold bg-soft-{{ $prioridadeColor }} text-{{ $prioridadeColor }}" style="font-size: 0.7rem; background-color: var(--{{ $prioridadeColor }}-light)">
                            <i class="bi bi-flag-fill me-1"></i> {{ $compromisso->prioridade_formatada }}
                        </span>
                        <a href="{{ route('compromissos.show', $compromisso) }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-primary">
                            Ver Mais <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="chart-container text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-calendar-x text-muted opacity-25" style="font-size: 5rem;"></i>
                </div>
                <h3 class="fw-800 text-dark">Nenhum compromisso encontrado</h3>
                <p class="text-muted mb-4">Que tal agendar seu próximo compromisso agora mesmo?</p>
                <a href="{{ route('compromissos.create') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i> Agendar Agora
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Paginação -->
@if($compromissos->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
        <div class="text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Mostrando <strong>{{ $compromissos->firstItem() ?? 0 }}</strong> a <strong>{{ $compromissos->lastItem() ?? 0 }}</strong> 
            de <strong>{{ $compromissos->total() }}</strong> compromissos
        </div>
        <div>
            {{ $compromissos->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    function toggleFilters() {
        const filtersCard = document.getElementById('filtersCard');
        filtersCard.classList.toggle('d-none');
        filtersCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
</script>
@endsection
