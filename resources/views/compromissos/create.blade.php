@extends('layouts.app')

@section('title', 'Novo Compromisso - Tasklean')
@section('page-title', 'Novo Compromisso')

@section('actions')
    <a href="{{ route('compromissos.index') }}" class="btn btn-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Voltar para Agenda
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="chart-container p-0 overflow-hidden border-0 shadow-sm card-premium">
            <!-- Header -->
            <div class="p-4 border-bottom bg-light bg-opacity-50">
                <div class="d-flex align-items-center">
                    <div class="task-card-icon primary me-3">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <div>
                        <h4 class="fw-800 text-dark mb-1">Agendar Novo Compromisso</h4>
                        <p class="text-muted mb-0">Preencha os detalhes abaixo para organizar seu novo evento ou tarefa.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="p-4 p-lg-5">
                <form action="{{ route('compromissos.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Título e Tipo -->
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-dark">Título do Compromisso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('titulo') is-invalid @enderror" 
                                   name="titulo" value="{{ old('titulo') }}" placeholder="Ex: Reunião de Alinhamento Semanal" required>
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Tipo <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg @error('tipo') is-invalid @enderror" name="tipo" required>
                                <option value="">Selecione o tipo...</option>
                                <option value="reuniao" {{ old('tipo') === 'reuniao' ? 'selected' : '' }}>Reunião</option>
                                <option value="evento" {{ old('tipo') === 'evento' ? 'selected' : '' }}>Evento</option>
                                <option value="tarefa" {{ old('tipo') === 'tarefa' ? 'selected' : '' }}>Tarefa</option>
                                <option value="lembrete" {{ old('tipo') === 'lembrete' ? 'selected' : '' }}>Lembrete</option>
                                <option value="compromisso_pessoal" {{ old('tipo') === 'compromisso_pessoal' ? 'selected' : '' }}>Compromisso Pessoal</option>
                                <option value="outro" {{ old('tipo') === 'outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <!-- Descrição -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Descrição / Pauta</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      name="descricao" rows="4" placeholder="Descreva brevemente o que será tratado neste compromisso...">{{ old('descricao') }}</textarea>
                            @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Data e Hora -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark">Data Início <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" 
                                   name="data_inicio" value="{{ old('data_inicio', now()->format('Y-m-d')) }}" required>
                            @error('data_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark">Hora Início <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror" 
                                   name="hora_inicio" value="{{ old('hora_inicio', '09:00') }}" required>
                            @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark">Data Fim (Opcional)</label>
                            <input type="date" class="form-control @error('data_fim') is-invalid @enderror" 
                                   name="data_fim" value="{{ old('data_fim') }}">
                            @error('data_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark">Hora Fim (Opcional)</label>
                            <input type="time" class="form-control @error('hora_fim') is-invalid @enderror" 
                                   name="hora_fim" value="{{ old('hora_fim', '10:00') }}">
                            @error('hora_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Local e Prioridade -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Local ou Link da Reunião</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0 @error('local') is-invalid @enderror" 
                                       name="local" value="{{ old('local') }}" placeholder="Ex: Sala 02 ou Google Meet Link">
                            </div>
                            @error('local') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Prioridade <span class="text-danger">*</span></label>
                            <select class="form-select @error('prioridade') is-invalid @enderror" name="prioridade" required>
                                <option value="baixa" {{ old('prioridade') === 'baixa' ? 'selected' : '' }}>🟢 Baixa</option>
                                <option value="media" {{ old('prioridade', 'media') === 'media' ? 'selected' : '' }}>🟡 Média</option>
                                <option value="alta" {{ old('prioridade') === 'alta' ? 'selected' : '' }}>🟠 Alta</option>
                                <option value="urgente" {{ old('prioridade') === 'urgente' ? 'selected' : '' }}>🔴 Urgente</option>
                            </select>
                            @error('prioridade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Lembrete e Status -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Configurar Lembrete</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-bell text-muted"></i></span>
                                <input type="datetime-local" class="form-control border-start-0 ps-0 @error('lembrete') is-invalid @enderror" 
                                       name="lembrete" value="{{ old('lembrete') }}">
                            </div>
                            @error('lembrete') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Status Inicial</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status">
                                <option value="agendado" {{ old('status') === 'agendado' ? 'selected' : '' }}>Agendado</option>
                                <option value="em_andamento" {{ old('status') === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                <option value="concluido" {{ old('status') === 'concluido' ? 'selected' : '' }}>Concluído</option>
                                <option value="adiado" {{ old('status') === 'adiado' ? 'selected' : '' }}>Adiado</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Observações -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Observações Internas (Privado)</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                      name="observacoes" rows="2" placeholder="Notas que apenas você verá...">{{ old('observacoes') }}</textarea>
                            @error('observacoes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                        <a href="{{ route('compromissos.index') }}" class="btn btn-light px-5 py-2 fw-bold text-muted rounded-pill border">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-check-lg me-2"></i> Criar Compromisso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
