@extends('layouts.app')

@section('title', 'Compromissos de Hoje - Tasklean')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-calendar-day me-2"></i>Compromissos de Hoje
            </h2>
            <p class="text-muted mb-0">{{ now()->format('d/m/Y') }} - {{ now()->format('l') }}</p>
        </div>
        <div>
            <a href="{{ route('compromissos.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-calendar-event me-1"></i>Ver Todos
            </a>
        </div>
    </div>

    @if($compromissos->count() > 0)
        <div class="row">
            @foreach($compromissos as $compromisso)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 card-hover">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">{{ $compromisso->titulo }}</h6>
                                @php
                                    $prioridadeColors = [
                                        'baixa' => 'success',
                                        'media' => 'warning',
                                        'alta' => 'danger',
                                        'urgente' => 'dark'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $prioridadeColors[$compromisso->prioridade] ?? 'secondary' }}">
                                    {{ $compromisso->prioridade_formatada }}
                                </span>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $compromisso->hora_inicio->format('H:i') }} - {{ $compromisso->hora_fim->format('H:i') }}
                                </small>
                            </div>

                            @if($compromisso->descricao)
                                <p class="card-text small">{{ Str::limit($compromisso->descricao, 100) }}</p>
                            @endif

                            @if($compromisso->local)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $compromisso->local }}
                                    </small>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info">{{ $compromisso->tipo_formatado }}</span>
                                <span class="badge bg-{{ $compromisso->status === 'concluido' ? 'success' : 'primary' }}">
                                    {{ $compromisso->status_formatado }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100">
                                <a href="{{ route('compromissos.show', $compromisso) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a href="{{ route('compromissos.edit', $compromisso) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-calendar-check text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Nenhum compromisso para hoje</h4>
            <p class="text-muted">Que tal criar um novo compromisso?</p>
            <a href="{{ route('compromissos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Criar Compromisso
            </a>
        </div>
    @endif
@endsection
