@extends('layouts.app')

@section('title', 'Auditoria de Times - Tasklean Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Auditoria de Times</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Times</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.teams.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Nome do time..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="card card-premium">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Time</th>
                            <th>Proprietário</th>
                            <th>Membros</th>
                            <th>Projetos</th>
                            <th>Data Criação</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $time)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-success">{{ $time->nome }}</div>
                                <small class="text-muted">{{ $time->slug }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 24px; height: 24px; font-size: 0.6rem;">
                                        {{ $time->owner->initials() ?? 'O' }}
                                    </div>
                                    <span>{{ $time->owner->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                    {{ $time->membros->count() }} membros
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info rounded-pill">
                                    {{ $time->projetos->count() }} projetos
                                </span>
                            </td>
                            <td>{{ $time->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('times.show', $time->slug) }}" class="btn btn-sm btn-light rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Ver Detalhes
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-diagram-3 fs-1 d-block mb-3"></i>
                                    Nenhum time encontrado.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($teams->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $teams->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
