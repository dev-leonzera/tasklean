<div>
    <!-- Mensagens -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Seleção e Ações -->
    <div class="row mb-4 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-bold">Projeto</label>
            <select wire:model.live="selectedProjetoId" class="form-select">
                <option value="">Todos os projetos</option>
                @foreach($projetos as $projeto)
                    <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Sprint</label>
            <div class="input-group">
                <select wire:model.live="selectedSprintId" class="form-select">
                    <option value="">Selecione um sprint</option>
                    @foreach($sprints as $s)
                        <option value="{{ $s->id }}">{{ $s->nome }} ({{ $s->data_inicio->format('d/m') }} - {{ $s->data_fim->format('d/m') }})</option>
                    @endforeach
                </select>
                <button class="btn btn-success" wire:click="$set('showCreateSprintModal', true)">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
        </div>
        <div class="col-md-4 text-end">
            @if($selectedSprint)
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="opacity-75">Progresso</small>
                            <span class="fw-bold">{{ $stats['completion_percent'] }}%</span>
                        </div>
                        <div class="progress mt-1" style="height: 6px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-white" style="width: {{ $stats['completion_percent'] }}%"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($selectedSprint)
        <!-- Informações do Sprint -->
        <div class="alert alert-info py-2 shadow-sm border-0 d-flex justify-content-between align-items-center mb-4">
            <div>
                <i class="bi bi-calendar-week me-2"></i>
                <strong>{{ $selectedSprint->nome }}</strong>: 
                {{ $selectedSprint->data_inicio->format('d/m/Y') }} até {{ $selectedSprint->data_fim->format('d/m/Y') }}
                <span class="badge bg-white text-info ms-2">{{ $selectedSprint->status }}</span>
            </div>
            <div>
                <span class="badge bg-primary">{{ $sprintTasks->count() }} tarefas</span>
            </div>
        </div>

        <!-- Listagem de Tarefas -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Tarefa</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sprintTasks as $tarefa)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $tarefa->titulo }}</div>
                                    <small class="text-muted">{{ $tarefa->projeto->titulo }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $tarefa->status == 'concluida' ? 'success' : ($tarefa->status == 'em desenvolvimento' ? 'info' : 'secondary') }}">
                                        {{ Str::title($tarefa->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($tarefa->status != 'concluida')
                                        <button wire:click="markAsCompleted({{ $tarefa->id }})" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    @endif
                                    <button wire:click="removeTaskFromSprint({{ $tarefa->id }})" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    Nenhuma tarefa vinculada a este sprint.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-3 shadow-sm mb-4">
            <i class="bi bi-calendar-x display-4 text-muted"></i>
            <h5 class="mt-3">Nenhum sprint selecionado</h5>
            <p class="text-muted">Selecione um sprint acima ou crie um novo para começar a planejar.</p>
        </div>
    @endif

    <!-- Adicionar Tarefas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold">Tarefas Disponíveis (Backlog)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @forelse($availableTasks as $tarefa)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold">{{ $tarefa->titulo }}</div>
                                    <small class="text-muted">{{ $tarefa->projeto->titulo }}</small>
                                </td>
                                <td class="text-end pe-3">
                                    <button wire:click="openAddTaskModal({{ $tarefa->id }})" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i>Adicionar ao Sprint
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center py-4 text-muted">Nenhuma tarefa disponível no backlog.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Criar Sprint -->
    @if($showCreateSprintModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Novo Sprint</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('showCreateSprintModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome do Sprint</label>
                            <input type="text" wire:model="newSprintName" class="form-control" placeholder="Ex: Sprint 1 - Core Features">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Projeto</label>
                            <select wire:model="newSprintProjetoId" class="form-select">
                                <option value="">Selecione um projeto</option>
                                @foreach($projetos as $p)
                                    <option value="{{ $p->id }}">{{ $p->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Início</label>
                                <input type="date" wire:model="newSprintStart" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fim</label>
                                <input type="date" wire:model="newSprintEnd" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showCreateSprintModal', false)">Cancelar</button>
                        <button type="button" class="btn btn-success" wire:click="createSprint">Criar Sprint</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Modal Simples Adicionar Tarefa -->
    @if($showAddTaskModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-question-circle text-primary display-4"></i>
                        </div>
                        <h5>Adicionar ao Sprint?</h5>
                        <p class="text-muted">A tarefa será vinculada ao sprint selecionado e o status será alterado para pendente.</p>
                        <div class="d-grid gap-2">
                            <button wire:click="addTaskToSprint" class="btn btn-primary">Confirmar</button>
                            <button wire:click="$set('showAddTaskModal', false)" class="btn btn-light">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
