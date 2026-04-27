@extends('layouts.app')

@section('title', 'Painel Administrativo - Tasklean')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Painel Administrativo</h2>
            <p class="text-muted">Visão geral da plataforma e métricas do sistema</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-danger p-2 px-3 rounded-pill">
                <i class="bi bi-shield-lock me-1"></i> Acesso Super Admin
            </span>
        </div>
    </div>

    <!-- Métricas Globais -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="metric-card primary p-4 text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-white text-opacity-75 mb-1">Total de Usuários</p>
                        <h3 class="fw-bold mb-0">{{ $metrics['total_users'] }}</h3>
                        <small class="text-white text-opacity-75">
                            <i class="bi bi-arrow-up-short"></i> {{ $metrics['new_users_today'] }} hoje
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card success p-4 text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-white text-opacity-75 mb-1">Assinaturas Ativas</p>
                        <h3 class="fw-bold mb-0">{{ $metrics['active_subscriptions'] }}</h3>
                        <small class="text-white text-opacity-75">Plano Pro / Business</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-credit-card fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card info p-4 text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-white text-opacity-75 mb-1">Times Criados</p>
                        <h3 class="fw-bold mb-0">{{ $metrics['total_teams'] }}</h3>
                        <small class="text-white text-opacity-75">Colaboração ativa</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-diagram-3 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card warning p-4 text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-white text-opacity-75 mb-1">Total de Projetos</p>
                        <h3 class="fw-bold mb-0">{{ $metrics['total_projects'] }}</h3>
                        <small class="text-white text-opacity-75">{{ $metrics['total_tasks'] }} tarefas totais</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-kanban fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Usuários Recentes -->
        <div class="col-lg-8">
            <div class="card card-premium h-100">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Usuários Recentes</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver Todos</a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Email</th>
                                    <th>Data Cadastro</th>
                                    <th>Status</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ $user->initials() }}
                                            </div>
                                            <span class="fw-semibold">{{ $user->name }}</span>
                                            @if($user->isAdmin())
                                                <span class="badge bg-danger-subtle text-danger ms-2" style="font-size: 0.65rem;">ADMIN</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge-premium success">Ativo</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
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
                                                    <a class="dropdown-item text-danger" href="#">
                                                        <i class="bi bi-slash-circle me-2"></i> Banir Usuário
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Sistema -->
        <div class="col-lg-4">
            <div class="card card-premium mb-4">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">Informações do Sistema</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush border-0">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom">
                            <span class="text-muted"><i class="bi bi-cpu me-2"></i> Versão PHP</span>
                            <span class="fw-semibold">{{ $systemInfo['php_version'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom">
                            <span class="text-muted"><i class="bi bi-box me-2"></i> Laravel</span>
                            <span class="fw-semibold">{{ $systemInfo['laravel_version'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-bottom">
                            <span class="text-muted"><i class="bi bi-database me-2"></i> Banco de Dados</span>
                            <span class="fw-semibold text-uppercase">{{ $systemInfo['database_connection'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-0">
                            <span class="text-muted"><i class="bi bi-server me-2"></i> Servidor</span>
                            <span class="fw-semibold">{{ Str::limit($systemInfo['server_software'], 20) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card card-premium bg-dark text-white border-0 overflow-hidden">
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <h5 class="fw-bold mb-3">Atalhos do Administrador</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-light text-start border-light border-opacity-25 rounded-3 p-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-credit-card fs-4 me-3"></i>
                                <div>
                                    <div class="fw-bold">Gestão de Receita</div>
                                    <small class="text-white text-opacity-75">Stripe Billing & Plans</small>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-light text-start border-light border-opacity-25 rounded-3 p-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-diagram-3 fs-4 me-3"></i>
                                <div>
                                    <div class="fw-bold">Auditoria de Times</div>
                                    <small class="text-white text-opacity-75">Membros e Projetos</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 p-3 text-white text-opacity-10" style="font-size: 5rem; line-height: 1; pointer-events: none;">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
