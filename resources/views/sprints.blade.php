@extends('layouts.app')

@section('title', 'Sprints - Tasklean')
@section('page-title', 'Sprints')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h5 class="section-title mb-1">
                    <i class="bi bi-calendar-week"></i> Sprints
                </h5>
                <p class="text-muted mb-0 ms-lg-5">Gerencie suas tarefas em sprints de forma visual e interativa</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('settings.system') }}?tab=interface" class="btn btn-secondary">
                    <i class="bi bi-gear me-1"></i> Configurações
                </a>
            </div>
        </div>
    </div>
</div>

@livewire('sprint-board')
@endsection
