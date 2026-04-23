@extends('layouts.app')

@section('title', 'Meus Times - Tasklean')

@section('actions')
    <a href="{{ route('times.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Novo Time
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Meus Times</h2>
            <p class="text-muted">Gerencie seus times e colabore com sua equipe.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($times as $time)
            <div class="col-md-6 col-xl-4">
                <div class="card card-premium h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    {{ substr($time->nome, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="mb-0">
                                        <a href="{{ route('times.show', $time->slug) }}" class="text-decoration-none text-dark stretched-link">
                                            {{ $time->nome }}
                                        </a>
                                    </h5>
                                    <span class="badge badge-premium {{ $time->owner_id === auth()->id() ? 'success' : 'info' }}">
                                        {{ $time->owner_id === auth()->id() ? 'Proprietário' : 'Membro' }}
                                    </span>
                                </div>
                            </div>
                            <div class="dropdown" style="z-index: 10;">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('times.show', $time->slug) }}"><i class="bi bi-eye me-2"></i> Detalhes</a></li>
                                    @can('update', $time)
                                        <li><a class="dropdown-item" href="{{ route('times.edit', $time->slug) }}"><i class="bi bi-pencil me-2"></i> Editar</a></li>
                                    @endcan
                                    @can('delete', $time)
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('times.destroy', $time->slug) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este time? Todos os vínculos serão perdidos.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i> Excluir</button>
                                            </form>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </div>

                        <p class="text-muted small mb-4">
                            {{ Str::limit($time->descricao, 100) ?: 'Sem descrição definida.' }}
                        </p>

                        <div class="d-flex gap-3">
                            <div class="text-center">
                                <div class="fw-bold h5 mb-0">{{ $time->membros_count }}</div>
                                <div class="text-muted small">Membros</div>
                            </div>
                            <div class="vr"></div>
                            <div class="text-center">
                                <div class="fw-bold h5 mb-0">{{ $time->projetos_count }}</div>
                                <div class="text-muted small">Projetos</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-group d-flex">
                                @foreach($time->membros->take(5) as $membro)
                                    <div class="user-avatar border border-2 border-white" style="width: 28px; height: 28px; font-size: 0.7rem; margin-left: -8px;" title="{{ $membro->name }}">
                                        {{ substr($membro->name, 0, 1) }}
                                    </div>
                                @endforeach
                                @if($time->membros_count > 5)
                                    <div class="user-avatar bg-light text-muted border border-2 border-white" style="width: 28px; height: 28px; font-size: 0.7rem; margin-left: -8px;">
                                        +{{ $time->membros_count - 5 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card card-premium p-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3>Nenhum time encontrado</h3>
                    <p class="text-muted">Você ainda não faz parte de nenhum time. Crie um novo time para começar a colaborar.</p>
                    <div class="mt-3">
                        <a href="{{ route('times.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i> Criar Meu Primeiro Time
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
