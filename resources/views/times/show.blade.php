@extends('layouts.app')

@section('title', $time->nome . ' - Tasklean')

@section('actions')
    @can('update', $time)
        <a href="{{ route('times.edit', $time->slug) }}" class="btn btn-secondary me-2">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
    @endcan
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteMemberModal">
        <i class="bi bi-person-plus me-2"></i> Convidar Membro
    </button>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Sidebar do Time -->
        <div class="col-xl-4">
            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($time->nome, 0, 1) }}
                        </div>
                        <h3 class="mb-1">{{ $time->nome }}</h3>
                        <p class="text-muted small">Criado em {{ $time->created_at->format('d/m/Y') }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge badge-premium success">Ativo</span>
                            <span class="badge badge-premium info">{{ $time->membros_count }} Membros</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="form-label text-uppercase small fw-bold">Descrição</h6>
                        <p class="text-muted">{{ $time->descricao ?: 'Sem descrição definida.' }}</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="form-label text-uppercase small fw-bold">Proprietário</h6>
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                {{ substr($time->owner->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $time->owner->name }}</div>
                                <div class="text-muted extra-small">{{ $time->owner->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projetos do Time -->
            <div class="card card-premium">
                <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Projetos do Time</h5>
                    <a href="{{ route('projetos.create', ['time_id' => $time->id]) }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-plus"></i>
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        @forelse($time->projetos as $projeto)
                            <a href="{{ route('projetos.show', $projeto->id) }}" class="list-group-item list-group-item-action px-0 border-0 d-flex align-items-center mb-2">
                                <div class="task-card-icon info me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-folder2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold mb-0">{{ $projeto->titulo }}</div>
                                    <div class="text-muted extra-small">{{ $projeto->tarefas_count ?? $projeto->tarefas()->count() }} tarefas</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                            </a>
                        @empty
                            <p class="text-muted text-center py-3">Nenhum projeto vinculado.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Membros -->
        <div class="col-xl-8">
            <div class="card card-premium">
                <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <ul class="nav nav-tabs border-0" id="teamTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold border-0 bg-transparent px-0 me-4" id="members-tab" data-bs-toggle="tab" data-bs-target="#members" type="button" role="tab">
                                Membros da Equipe
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold border-0 bg-transparent px-0" id="invitations-tab" data-bs-toggle="tab" data-bs-target="#invitations" type="button" role="tab">
                                Gerenciar Convites
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="tab-content" id="teamTabsContent">
                        <!-- Aba de Membros -->
                        <div class="tab-pane fade show active" id="members" role="tabpanel">
                            <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">Membro</th>
                                    <th class="border-0">E-mail</th>
                                    <th class="border-0">Regra</th>
                                    <th class="border-0">Entrou em</th>
                                    <th class="border-0 rounded-end text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dono (fixo) -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="background: var(--secondary-color);">
                                                {{ substr($time->owner->name, 0, 1) }}
                                            </div>
                                            <div class="fw-semibold">{{ $time->owner->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $time->owner->email }}</td>
                                    <td><span class="badge bg-dark">Proprietário</span></td>
                                    <td>{{ $time->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end">-</td>
                                </tr>
                                @foreach($time->membros as $membro)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    {{ substr($membro->name, 0, 1) }}
                                                </div>
                                                <div class="fw-semibold">{{ $membro->name }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $membro->email }}</td>
                                        <td>
                                            @can('update', $time)
                                                <form action="{{ route('times.membros.update-regra', [$time->slug, $membro->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="regra" class="form-select form-select-sm border-0 bg-light" onchange="this.form.submit()" style="width: auto;">
                                                        <option value="membro" {{ $membro->pivot->regra === 'membro' ? 'selected' : '' }}>Membro</option>
                                                        <option value="admin" {{ $membro->pivot->regra === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    </select>
                                                </form>
                                            @else
                                                <span class="badge bg-light text-dark text-capitalize">{{ $membro->pivot->regra }}</span>
                                            @endcan
                                        </td>
                                        <td>{{ $membro->pivot->created_at->format('d/m/Y') }}</td>
                                        <td class="text-end">
                                            @can('update', $time)
                                                <form action="{{ route('times.membros.destroy', [$time->slug, $membro->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Remover este membro do time?')">
                                                        <i class="bi bi-person-x"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        </div>

                        <!-- Aba de Convites -->
                        <div class="tab-pane fade" id="invitations" role="tabpanel">
                            <div class="mt-4">
                                @livewire('times.manage-invitations', ['time' => $time])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Convite -->
<div class="modal fade" id="inviteMemberModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title fw-bold">Convidar para o Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('times.membros.store', $time->slug) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Insira o e-mail do usuário que você deseja adicionar a este time.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">E-mail do Usuário</label>
                        <input type="email" name="email" class="form-control" placeholder="usuario@email.com" required>
                        <div class="form-text">O usuário já deve estar cadastrado no Tasklean.</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Regra de Acesso</label>
                        <div class="d-flex gap-3">
                            <div class="form-check card-premium p-2 flex-grow-1">
                                <input class="form-check-input ms-0 me-2" type="radio" name="regra" id="regraMembro" value="membro" checked>
                                <label class="form-check-label" for="regraMembro">
                                    <strong>Membro</strong><br>
                                    <span class="extra-small text-muted">Acesso aos projetos e tarefas.</span>
                                </label>
                            </div>
                            <div class="form-check card-premium p-2 flex-grow-1">
                                <input class="form-check-input ms-0 me-2" type="radio" name="regra" id="regraAdmin" value="admin">
                                <label class="form-check-label" for="regraAdmin">
                                    <strong>Admin</strong><br>
                                    <span class="extra-small text-muted">Gerencia membros e configurações.</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Enviar Convite</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.75rem; }
    .nav-tabs .nav-link { color: var(--text-muted); border-bottom: 2px solid transparent !important; transition: all 0.3s ease; }
    .nav-tabs .nav-link.active { color: var(--primary-color) !important; border-bottom: 2px solid var(--primary-color) !important; }
    .nav-tabs .nav-link:hover:not(.active) { color: var(--primary-color); opacity: 0.8; }
</style>
@endsection
