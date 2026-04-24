@extends('layouts.app')

@section('title', 'Kanban - Tasklean')
@section('page-title', 'Kanban')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('tarefas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nova Tarefa
        </a>
        <a href="{{ route('tarefas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list me-1"></i> Lista
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
                    <i class="bi bi-kanban me-2"></i>Kanban Board
                </h1>
                <p class="text-muted mb-0">Visualize suas tarefas em colunas organizadas por status</p>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-gear me-1"></i> Configurar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @livewire('kanban-board')
    @livewire('task-quick-view')
</div>
@endsection

@section('scripts')
<script>
    // Adicionar funcionalidades específicas do Kanban se necessário
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh a cada 30 segundos para manter dados atualizados
        setInterval(function() {
            Livewire.dispatch('$refresh');
        }, 30000);
    });
</script>
@endsection
