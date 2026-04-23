@extends('layouts.app')

@section('title', 'Nova Tarefa - Tasklean')
@section('page-title', 'Nova Tarefa')

@section('actions')
    <a href="{{ route('tarefas.index') }}" class="btn btn-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Voltar para Lista
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
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div>
                        <h4 class="fw-800 text-dark mb-1">Criar Nova Tarefa</h4>
                        <p class="text-muted mb-0">Adicione uma nova tarefa ao seu backlog ou projeto específico.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="p-4 p-lg-5">
                <form action="{{ route('tarefas.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Título -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Título da Tarefa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('titulo') is-invalid @enderror" 
                                   name="titulo" value="{{ old('titulo') }}" placeholder="Ex: Implementar autenticação de dois fatores" required>
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <!-- Descrição -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Descrição Detalhada</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      name="descricao" rows="4" placeholder="Descreva os requisitos e passos para conclusão desta tarefa...">{{ old('descricao') }}</textarea>
                            @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Projeto e Responsável -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Projeto Relacionado <span class="text-danger">*</span></label>
                            <select class="form-select @error('projeto_id') is-invalid @enderror" name="projeto_id" required>
                                <option value="">Selecione um projeto...</option>
                                @foreach($projetos as $projeto)
                                    <option value="{{ $projeto->id }}" {{ old('projeto_id', $projetoId) == $projeto->id ? 'selected' : '' }}>
                                        {{ $projeto->titulo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('projeto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Responsável <span class="text-danger">*</span></label>
                            <select class="form-select @error('responsavel_id') is-invalid @enderror" name="responsavel_id" required>
                                <option value="">Selecione um membro...</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('responsavel_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsavel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status e Prazo -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Status Inicial</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status">
                                <option value="backlog" {{ old('status', 'backlog') === 'backlog' ? 'selected' : '' }}>Backlog</option>
                                <option value="pendente" {{ old('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="em desenvolvimento" {{ old('status') === 'em desenvolvimento' ? 'selected' : '' }}>Em Desenvolvimento</option>
                                <option value="concluida" {{ old('status') === 'concluida' ? 'selected' : '' }}>Concluída</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Data de Vencimento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event text-muted"></i></span>
                                <input type="date" class="form-control border-start-0 ps-0 @error('data_vencimento') is-invalid @enderror" 
                                       name="data_vencimento" value="{{ old('data_vencimento') }}" min="{{ date('Y-m-d') }}">
                            </div>
                            @error('data_vencimento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <p class="text-muted small mt-2 mb-0">Deixe vazio se a tarefa não tiver um prazo rígido.</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                        <a href="{{ route('tarefas.index') }}" class="btn btn-light px-5 py-2 fw-bold text-muted rounded-pill border">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-check-lg me-2"></i> Criar Tarefa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
