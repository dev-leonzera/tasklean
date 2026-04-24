<div>
    <!-- Mensagens -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Seleção e Ações -->
    <div class="chart-container mb-4">
        <div class="row align-items-end g-4">
            <div class="col-md-4">
                <label class="form-label fw-bold text-dark small text-uppercase">Filtrar por Projeto</label>
                <select wire:model.live="selectedProjetoId" class="form-select border-0 bg-light rounded-3">
                    <option value="">Todos os Projetos</option>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold text-dark small text-uppercase">Selecionar Sprint</label>
                <div class="input-group">
                    <select wire:model.live="selectedSprintId" class="form-select border-0 bg-light rounded-start-3">
                        <option value="">Escolha um sprint ativo...</option>
                        @foreach($sprints as $s)
                            <option value="{{ $s->id }}">{{ $s->nome }} ({{ $s->data_inicio->format('d/m') }} - {{ $s->data_fim->format('d/m') }})</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary px-4 rounded-end-3" wire:click="$set('showCreateSprintModal', true)" title="Novo Sprint">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                @if($selectedSprint)
                    <div class="bg-light p-3 rounded-3 border-0 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-800 text-muted text-uppercase" style="font-size: 0.65rem;">Progresso do Ciclo</span>
                            <span class="fw-800 text-primary" style="font-size: 0.8rem;">{{ $stats['completion_percent'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar bg-primary rounded-pill" style="width: {{ $stats['completion_percent'] }}%;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($selectedSprint)
        <!-- Cabeçalho do Sprint -->
        <div class="chart-container mb-4 py-4 bg-soft-primary bg-opacity-10 border-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="task-card-icon primary me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <h5 class="fw-800 text-dark mb-0">{{ $selectedSprint->nome }}</h5>
                        <p class="text-muted small mb-0">
                            {{ $selectedSprint->data_inicio->format('d/m/Y') }} — {{ $selectedSprint->data_fim->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge-premium {{ $selectedSprint->status === 'ativo' ? 'success' : 'warning' }} px-3">
                        {{ strtoupper($selectedSprint->status) }}
                    </span>
                    <span class="badge-premium info px-3">
                        {{ $sprintTasks->count() }} TAREFAS VINCULADAS
                    </span>
                </div>
            </div>
        </div>

        <!-- Listagem de Tarefas do Sprint -->
        <div class="chart-container p-0 overflow-hidden mb-5">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                <h6 class="fw-800 text-dark mb-0">Tarefas em Execução</h6>
            </div>
            <div class="activity-list p-4">
                @forelse($sprintTasks as $tarefa)
                    <div class="activity-item bg-white border rounded-4 p-3 mb-3 shadow-sm transition-all hover-translate-y cursor-pointer"
                         wire:click="$dispatch('openTaskQuickView', { id: {{ $tarefa->id }} })">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center">
                                    <div class="task-card-icon {{ $tarefa->status == 'concluida' ? 'success' : 'primary' }} bg-opacity-10 me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-{{ $tarefa->status == 'concluida' ? 'check-lg' : 'lightning-charge' }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 {{ $tarefa->status == 'concluida' ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $tarefa->titulo }}
                                        </h6>
                                        <span class="small text-muted">{{ $tarefa->projeto->titulo }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <span class="badge-premium {{ $tarefa->status == 'concluida' ? 'success' : ($tarefa->status == 'em desenvolvimento' ? 'info' : 'warning') }} py-1 px-3" style="font-size: 0.7rem;">
                                    {{ ucfirst($tarefa->status) }}
                                </span>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="btn-group">
                                    @if($tarefa->status != 'concluida')
                                        <button wire:click.stop="markAsCompleted({{ $tarefa->id }})" class="btn btn-sm btn-soft-success rounded-circle me-2" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    @endif
                                    <button wire:click.stop="removeTaskFromSprint({{ $tarefa->id }})" class="btn btn-sm btn-soft-danger rounded-circle" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <p class="text-muted mb-0">Nenhuma tarefa vinculada ao sprint atual.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <div class="chart-container text-center py-5 mb-5 border-dashed">
            <i class="bi bi-calendar-x text-muted opacity-25" style="font-size: 4rem;"></i>
            <h5 class="fw-800 text-dark mt-3">Planejamento de Sprint</h5>
            <p class="text-muted">Selecione um sprint ativo ou crie um novo para gerenciar as tarefas do ciclo.</p>
        </div>
    @endif

    <!-- Backlog Disponível -->
    <div class="chart-container p-0 overflow-hidden">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-800 text-dark mb-0">Tarefas Disponíveis (Backlog)</h6>
            <span class="badge bg-light text-dark rounded-pill px-3">{{ $availableTasks->count() }}</span>
        </div>
        <div class="activity-list p-4">
            @forelse($availableTasks as $tarefa)
                <div class="activity-item bg-light bg-opacity-50 border-0 p-3 mb-3 rounded-4 cursor-pointer"
                     wire:click="$dispatch('openTaskQuickView', { id: {{ $tarefa->id }} })">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h6 class="fw-bold text-dark mb-1">{{ $tarefa->titulo }}</h6>
                            <span class="small text-muted"><i class="bi bi-folder me-1"></i> {{ $tarefa->projeto->titulo }}</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <button wire:click="openAddTaskModal({{ $tarefa->id }})" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                <i class="bi bi-plus-lg me-1"></i> Adicionar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 opacity-50">
                    <i class="bi bi-check-all fs-2 text-success"></i>
                    <p class="small fw-bold text-muted mt-2">Tudo planejado! Backlog limpo.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modais -->
    @if($showCreateSprintModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 p-4">
                        <h5 class="fw-800 text-dark mb-0">Novo Ciclo de Sprint</h5>
                        <button type="button" class="btn-close" wire:click="$set('showCreateSprintModal', false)"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nome do Sprint</label>
                            <input type="text" wire:model="newSprintName" class="form-control border-0 bg-light p-3" placeholder="Ex: Sprint 1 - MVP">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Projeto</label>
                            <select wire:model="newSprintProjetoId" class="form-select border-0 bg-light p-3">
                                <option value="">Selecione um projeto...</option>
                                @foreach($projetos as $p)
                                    <option value="{{ $p->id }}">{{ $p->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Início</label>
                                <input type="date" wire:model="newSprintStart" class="form-control border-0 bg-light p-3">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Término</label>
                                <input type="date" wire:model="newSprintEnd" class="form-control border-0 bg-light p-3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold" wire:click="$set('showCreateSprintModal', false)">Cancelar</button>
                        <button type="button" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm" wire:click="createSprint">Criar Sprint</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showAddTaskModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="p-4 text-center">
                        <div class="task-card-icon primary mx-auto mb-4" style="width: 64px; height: 64px;">
                            <i class="bi bi-plus-lg fs-3"></i>
                        </div>
                        <h5 class="fw-800 text-dark">Adicionar ao Sprint?</h5>
                        <p class="text-muted small">Esta tarefa será movida para o ciclo atual de trabalho.</p>
                        <div class="d-grid gap-2 mt-4">
                            <button wire:click="addTaskToSprint" class="btn btn-primary py-3 rounded-pill fw-bold shadow-sm">Confirmar Adição</button>
                            <button wire:click="$set('showAddTaskModal', false)" class="btn btn-light py-2 rounded-pill fw-bold">Voltar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
