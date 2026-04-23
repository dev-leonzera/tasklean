@extends('layouts.app')

@section('title', 'Criar Novo Time - Tasklean')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('times.index') }}">Times</a></li>
                    <li class="breadcrumb-item active">Novo Time</li>
                </ol>
            </nav>

            <div class="card card-premium">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="user-avatar bg-primary-light text-primary me-3" style="width: 48px; height: 48px;">
                            <i class="bi bi-people-fill h4 mb-0"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">Criar Novo Time</h3>
                            <p class="text-muted mb-0">Defina os detalhes básicos da sua nova equipe.</p>
                        </div>
                    </div>

                    <form action="{{ route('times.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="nome" class="form-label">Nome do Time</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Ex: Time de Design, Marketing, etc." required autofocus>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="descricao" class="form-label">Descrição (Opcional)</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="4" placeholder="Descreva brevemente o propósito deste time...">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('times.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i> Criar Time
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
