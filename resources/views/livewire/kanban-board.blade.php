<div>
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3">
                <label for="projeto-filtro" class="form-label mb-0 fw-bold">Filtrar por Projeto:</label>
                <select wire:model.live="projetoId" id="projeto-filtro" class="form-select" style="max-width: 300px;">
                    <option value="">Todos os projetos</option>
                    @foreach($projetos as $projeto)
                        <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                    @endforeach
                </select>
                @if($projetoId)
                    <button wire:click="limparFiltro" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle"></i> Limpar
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Board Kanban -->
    <div class="kanban-board">
        <div class="row g-3">
            <!-- Coluna Backlog -->
            <div class="col-md-3">
                <div class="kanban-column h-100">
                    <div class="kanban-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul"></i> Backlog
                            <span class="badge bg-light text-dark ms-2">{{ count($tarefasBacklog) }}</span>
                        </h5>
                    </div>
                    <div class="kanban-body" data-status="backlog">
                        @forelse($tarefasBacklog as $tarefa)
                            <div class="kanban-card" 
                                 data-tarefa-id="{{ $tarefa['id'] }}" 
                                 data-status="backlog"
                                 draggable="true">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $tarefa['titulo'] }}</h6>
                                        @if($tarefa['descricao'])
                                            <p class="card-text small text-muted">{{ Str::limit($tarefa['descricao'], 100) }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder"></i> {{ $tarefa['projeto']['titulo'] ?? 'Sem projeto' }}
                                            </small>
                                            @if($tarefa['data_vencimento'])
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($tarefa['data_vencimento'])->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        </div>
                                        
                                        @if(isset($tarefa['responsavel']))
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> {{ $tarefa['responsavel']['name'] ?? 'Sem responsável' }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('tarefas.edit', $tarefa['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <div class="kanban-actions">
                                                <button class="btn btn-sm btn-outline-warning" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'pendente')"
                                                        title="Mover para Pendente">
                                                    <i class="bi bi-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-2">Nenhuma tarefa em backlog</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Coluna Pendentes -->
            <div class="col-md-3">
                <div class="kanban-column h-100">
                    <div class="kanban-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-clock"></i> Pendentes
                            <span class="badge bg-light text-dark ms-2">{{ count($tarefasPendentes) }}</span>
                        </h5>
                    </div>
                    <div class="kanban-body" data-status="pendente">
                        @forelse($tarefasPendentes as $tarefa)
                            <div class="kanban-card" 
                                 data-tarefa-id="{{ $tarefa['id'] }}" 
                                 data-status="pendente"
                                 draggable="true">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $tarefa['titulo'] }}</h6>
                                        @if($tarefa['descricao'])
                                            <p class="card-text small text-muted">{{ Str::limit($tarefa['descricao'], 100) }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder"></i> {{ $tarefa['projeto']['titulo'] ?? 'Sem projeto' }}
                                            </small>
                                            @if($tarefa['data_vencimento'])
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($tarefa['data_vencimento'])->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        </div>
                                        
                                        @if(isset($tarefa['responsavel']))
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> {{ $tarefa['responsavel']['name'] ?? 'Sem responsável' }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('tarefas.edit', $tarefa['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <div class="kanban-actions">
                                                <button class="btn btn-sm btn-outline-secondary me-1" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'backlog')"
                                                        title="Voltar para Backlog">
                                                    <i class="bi bi-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'em desenvolvimento')"
                                                        title="Mover para Em Andamento">
                                                    <i class="bi bi-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-2">Nenhuma tarefa pendente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Coluna Em Desenvolvimento -->
            <div class="col-md-3">
                <div class="kanban-column h-100">
                    <div class="kanban-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-gear"></i> Em Andamento
                            <span class="badge bg-light text-dark ms-2">{{ count($tarefasEmDesenvolvimento) }}</span>
                        </h5>
                    </div>
                    <div class="kanban-body" data-status="em desenvolvimento">
                        @forelse($tarefasEmDesenvolvimento as $tarefa)
                            <div class="kanban-card" 
                                 data-tarefa-id="{{ $tarefa['id'] }}" 
                                 data-status="em desenvolvimento"
                                 draggable="true">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $tarefa['titulo'] }}</h6>
                                        @if($tarefa['descricao'])
                                            <p class="card-text small text-muted">{{ Str::limit($tarefa['descricao'], 100) }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder"></i> {{ $tarefa['projeto']['titulo'] ?? 'Sem projeto' }}
                                            </small>
                                            @if($tarefa['data_vencimento'])
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($tarefa['data_vencimento'])->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        </div>
                                        
                                        @if(isset($tarefa['responsavel']))
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> {{ $tarefa['responsavel']['name'] ?? 'Sem responsável' }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('tarefas.edit', $tarefa['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <div class="kanban-actions">
                                                <button class="btn btn-sm btn-outline-secondary me-1" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'backlog')"
                                                        title="Voltar para Backlog">
                                                    <i class="bi bi-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning me-1" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'pendente')"
                                                        title="Voltar para Pendente">
                                                    <i class="bi bi-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'concluida')"
                                                        title="Marcar como Concluída">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-2">Nenhuma tarefa em andamento</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Coluna Concluídas -->
            <div class="col-md-3">
                <div class="kanban-column h-100">
                    <div class="kanban-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-check-circle"></i> Concluídas
                            <span class="badge bg-light text-dark ms-2">{{ count($tarefasConcluidas) }}</span>
                        </h5>
                    </div>
                    <div class="kanban-body" data-status="concluida">
                        @forelse($tarefasConcluidas as $tarefa)
                            <div class="kanban-card" 
                                 data-tarefa-id="{{ $tarefa['id'] }}" 
                                 data-status="concluida"
                                 draggable="true">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title text-decoration-line-through">{{ $tarefa['titulo'] }}</h6>
                                        @if($tarefa['descricao'])
                                            <p class="card-text small text-muted">{{ Str::limit($tarefa['descricao'], 100) }}</p>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-folder"></i> {{ $tarefa['projeto']['titulo'] ?? 'Sem projeto' }}
                                            </small>
                                            @if($tarefa['data_vencimento'])
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($tarefa['data_vencimento'])->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        </div>
                                        
                                        @if(isset($tarefa['responsavel']))
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> {{ $tarefa['responsavel']['name'] ?? 'Sem responsável' }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('tarefas.edit', $tarefa['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <div class="kanban-actions">
                                                <button class="btn btn-sm btn-outline-secondary me-1" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'backlog')"
                                                        title="Voltar para Backlog">
                                                    <i class="bi bi-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning me-1" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'pendente')"
                                                        title="Voltar para Pendente">
                                                    <i class="bi bi-arrow-left"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" 
                                                        onclick="moverTarefa({{ $tarefa['id'] }}, 'em desenvolvimento')"
                                                        title="Voltar para Em Andamento">
                                                    <i class="bi bi-arrow-left"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-2">Nenhuma tarefa concluída</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .kanban-board {
        min-height: 70vh;
    }

    .kanban-column {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .kanban-header {
        padding: 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .kanban-body {
        padding: 1rem;
        min-height: 400px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .kanban-card {
        margin-bottom: 1rem;
        cursor: move;
        transition: all 0.2s ease;
    }

    .kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .kanban-card.dragging {
        opacity: 0.5;
        transform: rotate(5deg);
    }

    .kanban-body.drag-over {
        background-color: rgba(0,123,255,0.1);
        border: 2px dashed #007bff;
        border-radius: 0.5rem;
    }

    .kanban-actions {
        display: flex;
        gap: 0.25rem;
    }

    /* Scrollbar personalizada */
    .kanban-body::-webkit-scrollbar {
        width: 6px;
    }

    .kanban-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .kanban-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .kanban-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar drag and drop
        const cards = document.querySelectorAll('.kanban-card');
        const columns = document.querySelectorAll('.kanban-body');
        
        cards.forEach(card => {
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
        });
        
        columns.forEach(column => {
            column.addEventListener('dragover', handleDragOver);
            column.addEventListener('drop', handleDrop);
            column.addEventListener('dragenter', handleDragEnter);
            column.addEventListener('dragleave', handleDragLeave);
        });
    });

    let draggedCard = null;

    function handleDragStart(e) {
        draggedCard = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.outerHTML);
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        draggedCard = null;
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    }

    function handleDragEnter(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    }

    function handleDragLeave(e) {
        this.classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        
        if (draggedCard) {
            const newStatus = this.dataset.status;
            const oldStatus = draggedCard.dataset.status;
            const tarefaId = draggedCard.dataset.tarefaId;
            
            if (newStatus !== oldStatus) {
                // Mover a tarefa via Livewire
                moverTarefa(tarefaId, newStatus);
            }
        }
    }

    function moverTarefa(tarefaId, novoStatus) {
        // Emitir evento para o Livewire
        Livewire.dispatch('tarefa-movida', {
            tarefaId: tarefaId,
            novoStatus: novoStatus
        });
    }

    // Escutar eventos do Livewire
    Livewire.on('tarefa-atualizada', (data) => {
        // Mostrar notificação de sucesso
        if (typeof createRealtimeNotification === 'function') {
            createRealtimeNotification(
                'success',
                'Tarefa Movida',
                `A tarefa "${data.titulo}" foi movida para ${data.novo_status}.`
            );
        }
    });
    </script>
</div>
