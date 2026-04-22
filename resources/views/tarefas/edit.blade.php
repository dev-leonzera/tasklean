@extends('layouts.app')

@section('title', 'Editar Tarefa - Tasklean')
@section('page-title', 'Editar Tarefa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil"></i> Editar Tarefa: {{ $tarefa->titulo }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tarefas.update', $tarefa->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">
                            Título da Tarefa <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo', $tarefa->titulo) }}" 
                               required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" 
                                  name="descricao" 
                                  rows="3">{{ old('descricao', $tarefa->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="projeto_id" class="form-label">
                                    Projeto <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('projeto_id') is-invalid @enderror" 
                                        id="projeto_id" 
                                        name="projeto_id" 
                                        required>
                                    <option value="">Selecione um projeto</option>
                                    @foreach($projetos as $projeto)
                                        <option value="{{ $projeto->id }}" 
                                                {{ old('projeto_id', $tarefa->projeto_id) == $projeto->id ? 'selected' : '' }}>
                                            {{ $projeto->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('projeto_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="responsavel_id" class="form-label">
                                    Responsável <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('responsavel_id') is-invalid @enderror" 
                                        id="responsavel_id" 
                                        name="responsavel_id" 
                                        required>
                                    <option value="">Selecione um responsável</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('responsavel_id', $tarefa->responsavel_id) == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsavel_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status">
                                    <option value="backlog" {{ old('status', $tarefa->status) === 'backlog' ? 'selected' : '' }}>
                                        Backlog
                                    </option>
                                    <option value="pendente" {{ old('status', $tarefa->status) === 'pendente' ? 'selected' : '' }}>
                                        Pendente
                                    </option>
                                    <option value="em desenvolvimento" {{ old('status', $tarefa->status) === 'em desenvolvimento' ? 'selected' : '' }}>
                                        Em Desenvolvimento
                                    </option>
                                    <option value="concluida" {{ old('status', $tarefa->status) === 'concluida' ? 'selected' : '' }}>
                                        Concluída
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                                <input type="date" 
                                       class="form-control @error('data_vencimento') is-invalid @enderror" 
                                       id="data_vencimento" 
                                       name="data_vencimento" 
                                       value="{{ old('data_vencimento', $tarefa->data_vencimento ? $tarefa->data_vencimento->format('Y-m-d') : '') }}">
                                @error('data_vencimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Deixe em branco se não houver prazo definido
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            <strong>Criada em:</strong> {{ $tarefa->data_criacao->format('d/m/Y H:i') }}<br>
                            <strong>Última atualização:</strong> {{ $tarefa->updated_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
