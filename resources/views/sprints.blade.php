@extends('layouts.app')

@section('title', 'Sprints - Tasklean')
@section('page-title', 'Sprints')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="h3 mb-1 fw-bold text-primary">
                    <i class="bi bi-calendar-week me-2"></i>Sprints
                </h1>
                <p class="text-muted mb-0">Gerencie suas tarefas em sprints de forma visual e interativa</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('settings.system') }}?tab=interface" class="btn btn-outline-secondary">
                    <i class="bi bi-gear me-1"></i> Configurações
                </a>
            </div>
        </div>
    </div>
</div>

@livewire('sprint-board')
@endsection
