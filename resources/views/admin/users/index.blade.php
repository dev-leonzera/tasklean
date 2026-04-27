@extends('layouts.app')

@section('title', 'Gestão de Usuários - Tasklean Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Gestão de Usuários</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Usuários</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Nome ou email..." value="{{ request('search') }}">
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
                            <th class="ps-4">Usuário</th>
                            <th>Email</th>
                            <th>Data Cadastro</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3" style="width: 36px; height: 36px;">
                                        {{ $user->initials() }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->username ?? '@sem-username' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($user->banned_at)
                                    <span class="badge bg-danger">Banido</span>
                                @else
                                    <span class="badge-premium success">Ativo</span>
                                @endif
                            </td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge bg-danger-subtle text-danger">Super Admin</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary">Usuário</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <form action="{{ route('admin.users.impersonate', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-person-bounding-box me-2"></i> Impersonate
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.users.ban', $user) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item {{ $user->banned_at ? 'text-success' : 'text-danger' }}">
                                                    @if($user->banned_at)
                                                        <i class="bi bi-check-circle me-2"></i> Reativar Usuário
                                                    @else
                                                        <i class="bi bi-slash-circle me-2"></i> Banir Usuário
                                                    @endif
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-3"></i>
                                    Nenhum usuário encontrado.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
