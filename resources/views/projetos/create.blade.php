@extends('layouts.app')

@section('title', 'Novo Projeto - Tasklean')
@section('page-title', 'Novo Projeto')

@section('actions')
    <a href="{{ route('projetos.index') }}" class="btn btn-secondary px-4 shadow-sm">
        <i class="bi bi-arrow-left me-2"></i> Voltar para Projetos
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="chart-container p-0 overflow-hidden border-0 shadow-sm card-premium">
            <!-- Header -->
            <div class="p-4 border-bottom bg-light bg-opacity-50">
                <div class="d-flex align-items-center">
                    <div class="task-card-icon primary me-3">
                        <i class="bi bi-folder-plus"></i>
                    </div>
                    <div>
                        <h4 class="fw-800 text-dark mb-1">Iniciar Novo Projeto</h4>
                        <p class="text-muted mb-0">Defina o título e a responsabilidade inicial do projeto.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="p-4 p-lg-5">
                <form action="{{ route('projetos.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark">Título do Projeto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg @error('titulo') is-invalid @enderror" 
                                   name="titulo" value="{{ old('titulo') }}" placeholder="Ex: Redesign do Website Institucional" required>
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Membro Responsável <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg @error('responsavel_id') is-invalid @enderror" name="responsavel_id" required>
                                <option value="">Selecione o gestor...</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('responsavel_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsavel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Time Associado (Opcional)</label>
                            <select class="form-select form-select-lg @error('time_id') is-invalid @enderror" name="time_id">
                                <option value="">Projeto Pessoal (Sem Time)</option>
                                @foreach($times as $time)
                                    <option value="{{ $time->id }}" {{ (old('time_id') == $time->id || request('time_id') == $time->id) ? 'selected' : '' }}>
                                        {{ $time->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('time_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="activity-item bg-light border-0 p-4 rounded-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Status de Ativação</h6>
                                        <p class="text-muted small mb-0">Projetos inativos ficam ocultos dos fluxos de trabalho ativos.</p>
                                    </div>
                                    <div class="form-check form-switch p-0 m-0">
                                        <input class="form-check-input ms-0 mt-1" type="checkbox" name="ativo" value="1" id="ativo" 
                                               {{ old('ativo', true) ? 'checked' : '' }} style="width: 3rem; height: 1.5rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                        <a href="{{ route('projetos.index') }}" class="btn btn-light px-5 py-2 fw-bold text-muted rounded-pill border">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-check-lg me-2"></i> Criar Projeto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
