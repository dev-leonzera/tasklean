@extends('layouts.app')

@section('title', 'Novo Projeto - Tasklean')
@section('page-title', 'Novo Projeto')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle"></i> Criar Novo Projeto
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('projetos.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">
                            Título do Projeto <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo') }}" 
                               required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                                <option value="{{ $usuario->id }}" {{ old('responsavel_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('responsavel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="ativo" 
                                   name="ativo" 
                                   value="1" 
                                   {{ old('ativo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ativo">
                                Projeto Ativo
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Projetos inativos não aparecem nas listagens principais
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('projetos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Criar Projeto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
