@extends('layouts.app')

@section('title', 'Próximos Compromissos - Tasklean')
@section('page-title', 'Próximos Dias')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('compromissos.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Novo Compromisso
        </a>
        <a href="{{ route('compromissos.index') }}" class="btn btn-secondary px-4 shadow-sm">
            <i class="bi bi-calendar-event me-2"></i> Ver Agenda Completa
        </a>
    </div>
@endsection

@section('content')
<!-- Page Header -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="h2 mb-1 fw-800 text-dark">
                    Próximos Compromissos 🗓️
                </h1>
                <p class="text-muted mb-0">Visão geral dos seus compromissos para os próximos 7 dias</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fw-bold d-flex align-items-center" style="background: var(--primary-light)">
                    {{ $compromissos->count() }} eventos agendados
                </span>
            </div>
        </div>
    </div>
</div>

@if($compromissos->count() > 0)
    <div class="row">
        @foreach($compromissos->groupBy(function($item) {
            return $item->data_inicio->format('Y-m-d');
        }) as $data => $compromissosDoDia)
            <div class="col-12 mb-5">
                <div class="d-flex align-items-center mb-4">
                    <h5 class="section-title mb-0">
                        <i class="bi bi-calendar-date"></i>
                        {{ \Carbon\Carbon::parse($data)->locale('pt_BR')->translatedFormat('d \d\e F') }}
                        <span class="text-muted small ms-2">({{ \Carbon\Carbon::parse($data)->locale('pt_BR')->translatedFormat('l') }})</span>
                    </h5>
                    <div class="flex-grow-1 ms-4 border-bottom opacity-25"></div>
                </div>

                <div class="row">
                    @foreach($compromissosDoDia as $compromisso)
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
                                @endphp
                                
                                <div class="p-4 border-bottom bg-light bg-opacity-50">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge-premium {{ $statusColor }}">
                                            {{ $compromisso->status_formatado }}
                                        </span>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4">
                                                <li><a class="dropdown-item" href="{{ route('compromissos.show', $compromisso) }}"><i class="bi bi-eye me-2"></i> Ver Detalhes</a></li>
                                                <li><a class="dropdown-item" href="{{ route('compromissos.edit', $compromisso) }}"><i class="bi bi-pencil me-2"></i> Editar</a></li>
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
                                
                                <div class="p-4 border-top mt-auto">
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
                                            Detalhes <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="chart-container text-center py-5">
        <div class="mb-4">
            <i class="bi bi-calendar-x text-muted opacity-25" style="font-size: 5rem;"></i>
        </div>
        <h3 class="fw-800 text-dark">Nenhum compromisso nos próximos dias</h3>
        <p class="text-muted mb-4">Sua agenda está tranquila para a próxima semana.</p>
        <a href="{{ route('compromissos.create') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Agendar Agora
        </a>
    </div>
@endif
@endsection
