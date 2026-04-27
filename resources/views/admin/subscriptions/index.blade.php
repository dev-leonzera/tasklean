@extends('layouts.app')

@section('title', 'Gestão de Assinaturas - Tasklean Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Gestão de Assinaturas</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">Assinaturas</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="https://dashboard.stripe.com" target="_blank" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-box-arrow-up-right me-2"></i> Abrir Stripe Dashboard
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-premium p-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                        <i class="bi bi-credit-card fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Assinaturas</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-premium p-3 border-success border-opacity-25">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 me-3">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Ativas</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['active'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-premium p-3 border-danger border-opacity-25">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 me-3">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Canceladas</div>
                        <div class="h4 fw-bold mb-0">{{ $stats['canceled'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-premium">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Usuário</th>
                            <th>Plano / Stripe ID</th>
                            <th>Status</th>
                            <th>Início</th>
                            <th>Próximo Ciclo</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                        {{ $sub->user->initials() ?? 'U' }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-truncate" style="max-width: 150px;">{{ $sub->user->name ?? 'Usuário Excluído' }}</div>
                                        <small class="text-muted">{{ $sub->user->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-uppercase">{{ $sub->type ?? 'Padrão' }}</div>
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">{{ $sub->stripe_id }}</small>
                            </td>
                            <td>
                                @if($sub->stripe_status === 'active')
                                    <span class="badge bg-success">Ativa</span>
                                @elseif($sub->stripe_status === 'canceled')
                                    <span class="badge bg-danger">Cancelada</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $sub->stripe_status }}</span>
                                @endif
                            </td>
                            <td>{{ $sub->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($sub->ends_at)
                                    <span class="text-danger small">Expira em {{ $sub->ends_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-success small">Recorrente</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.users.index', ['search' => $sub->user->email ?? '']) }}" class="btn btn-sm btn-light rounded-pill px-3">
                                    Ver Usuário
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-credit-card-2-back fs-1 d-block mb-3"></i>
                                    Nenhuma assinatura encontrada no banco de dados.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($subscriptions->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
