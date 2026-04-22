@extends('layouts.app')

@section('title', 'Editar Compromisso - Tasklean')
@section('page-title', 'Editar Compromisso')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('compromissos.show', $compromisso) }}" class="btn btn-outline-secondary">
            <i class="bi bi-eye me-1"></i> Ver
        </a>
        <a href="{{ route('compromissos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar
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
                    <i class="bi bi-pencil me-2"></i>Editar Compromisso
                </h1>
                <p class="text-muted mb-0">{{ $compromisso->titulo }}</p>
            </div>
        </div>
    </div>
</div>
<style>
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid var(--border-color);
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background: #f7fafc;
        color: var(--secondary-color);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: #e2e8f0;
        color: var(--primary-color);
        transform: translateY(-1px);
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-body p-4">
                    <form action="{{ route('compromissos.update', $compromisso) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Título *</label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                       name="titulo" value="{{ old('titulo', $compromisso->titulo) }}" required>
                                @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo *</label>
                                <select class="form-select @error('tipo') is-invalid @enderror" name="tipo" required>
                                    <option value="">Selecione...</option>
                                    <option value="reuniao" {{ old('tipo', $compromisso->tipo) === 'reuniao' ? 'selected' : '' }}>Reunião</option>
                                    <option value="evento" {{ old('tipo', $compromisso->tipo) === 'evento' ? 'selected' : '' }}>Evento</option>
                                    <option value="tarefa" {{ old('tipo', $compromisso->tipo) === 'tarefa' ? 'selected' : '' }}>Tarefa</option>
                                    <option value="lembrete" {{ old('tipo', $compromisso->tipo) === 'lembrete' ? 'selected' : '' }}>Lembrete</option>
                                    <option value="compromisso_pessoal" {{ old('tipo', $compromisso->tipo) === 'compromisso_pessoal' ? 'selected' : '' }}>Compromisso Pessoal</option>
                                    <option value="outro" {{ old('tipo', $compromisso->tipo) === 'outro' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Descrição</label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                          name="descricao" rows="3">{{ old('descricao', $compromisso->descricao) }}</textarea>
                                @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Data Início *</label>
                                <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" 
                                       name="data_inicio" value="{{ old('data_inicio', $compromisso->data_inicio->format('Y-m-d')) }}" required>
                                @error('data_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Data Fim</label>
                                <input type="date" class="form-control @error('data_fim') is-invalid @enderror" 
                                       name="data_fim" value="{{ old('data_fim', $compromisso->data_fim ? $compromisso->data_fim->format('Y-m-d') : '') }}">
                                @error('data_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hora Início *</label>
                                <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror" 
                                       name="hora_inicio" value="{{ old('hora_inicio', $compromisso->hora_inicio->format('H:i')) }}" required>
                                @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Fim</label>
                                <input type="time" class="form-control @error('hora_fim') is-invalid @enderror" 
                                       name="hora_fim" value="{{ old('hora_fim', $compromisso->hora_fim ? $compromisso->hora_fim->format('H:i') : '') }}">
                                @error('hora_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Local</label>
                                <input type="text" class="form-control @error('local') is-invalid @enderror" 
                                       name="local" value="{{ old('local', $compromisso->local) }}">
                                @error('local') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prioridade *</label>
                                <select class="form-select @error('prioridade') is-invalid @enderror" name="prioridade" required>
                                    <option value="">Selecione...</option>
                                    <option value="baixa" {{ old('prioridade', $compromisso->prioridade) === 'baixa' ? 'selected' : '' }}>Baixa</option>
                                    <option value="media" {{ old('prioridade', $compromisso->prioridade) === 'media' ? 'selected' : '' }}>Média</option>
                                    <option value="alta" {{ old('prioridade', $compromisso->prioridade) === 'alta' ? 'selected' : '' }}>Alta</option>
                                    <option value="urgente" {{ old('prioridade', $compromisso->prioridade) === 'urgente' ? 'selected' : '' }}>Urgente</option>
                                </select>
                                @error('prioridade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status">
                                    <option value="agendado" {{ old('status', $compromisso->status) === 'agendado' ? 'selected' : '' }}>Agendado</option>
                                    <option value="em_andamento" {{ old('status', $compromisso->status) === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                    <option value="concluido" {{ old('status', $compromisso->status) === 'concluido' ? 'selected' : '' }}>Concluído</option>
                                    <option value="cancelado" {{ old('status', $compromisso->status) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    <option value="adiado" {{ old('status', $compromisso->status) === 'adiado' ? 'selected' : '' }}>Adiado</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lembrete</label>
                                <input type="datetime-local" class="form-control @error('lembrete') is-invalid @enderror" 
                                       name="lembrete" value="{{ old('lembrete', $compromisso->lembrete ? $compromisso->lembrete->format('Y-m-d\TH:i') : '') }}">
                                @error('lembrete') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Observações</label>
                                <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                          name="observacoes" rows="2">{{ old('observacoes', $compromisso->observacoes) }}</textarea>
                                @error('observacoes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('compromissos.show', $compromisso) }}" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Atualizar Compromisso
                    </button>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
