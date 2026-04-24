<div>
    <!-- Filtros Superiores -->
    <div class="chart-container mb-4 py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="task-card-icon primary" style="width: 40px; height: 40px;">
                        <i class="bi bi-funnel"></i>
                    </div>
                    <div>
                        <label for="projeto-filtro" class="form-label mb-0 fw-800 small text-uppercase text-muted">Filtrar por Projeto</label>
                        <select wire:model.live="projetoId" id="projeto-filtro" class="form-select border-0 fw-bold p-0 bg-transparent text-dark" style="box-shadow: none;">
                            <option value="">Todos os Projetos Ativos</option>
                            @foreach($projetos as $projeto)
                                <option value="{{ $projeto->id }}">{{ $projeto->titulo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                @if($projetoId)
                    <button wire:click="limparFiltro" class="btn btn-soft-danger rounded-pill px-4 fw-bold">
                        <i class="bi bi-x-lg me-2"></i> Limpar Filtros
                    </button>
                @endif
                <button wire:click="$refresh" class="btn btn-soft-primary rounded-pill px-4 fw-bold ms-2">
                    <i class="bi bi-arrow-clockwise me-2"></i> Atualizar
                </button>
            </div>
        </div>
    </div>

    <!-- Board Kanban -->
    <div class="kanban-board-wrapper">
        <div class="row g-4 flex-nowrap overflow-auto pb-4" style="min-height: 75vh;">
            
            <!-- Coluna: Backlog -->
            @include('livewire.kanban.column', [
                'title' => 'Backlog',
                'icon' => 'list-ul',
                'color' => 'secondary',
                'status' => 'backlog',
                'tarefas' => $tarefasBacklog
            ])

            <!-- Coluna: Pendentes -->
            @include('livewire.kanban.column', [
                'title' => 'Pendentes',
                'icon' => 'clock',
                'color' => 'warning',
                'status' => 'pendente',
                'tarefas' => $tarefasPendentes
            ])

            <!-- Coluna: Em Andamento -->
            @include('livewire.kanban.column', [
                'title' => 'Em Andamento',
                'icon' => 'gear',
                'color' => 'info',
                'status' => 'em desenvolvimento',
                'tarefas' => $tarefasEmDesenvolvimento
            ])

            <!-- Coluna: Concluídas -->
            @include('livewire.kanban.column', [
                'title' => 'Concluídas',
                'icon' => 'check2-circle',
                'color' => 'success',
                'status' => 'concluida',
                'tarefas' => $tarefasConcluidas
            ])

        </div>
    </div>

    <style>
        .kanban-board-wrapper {
            margin: 0 -1.5rem;
            padding: 0 1.5rem;
        }
        
        .kanban-column-container {
            min-width: 320px;
            max-width: 320px;
            display: flex;
            flex-direction: column;
        }
        
        .kanban-col-header {
            background: rgba(248, 249, 250, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            border-bottom: none;
        }
        
        .kanban-col-body {
            background: #f8f9fa;
            border-radius: 0 0 20px 20px;
            padding: 1rem;
            flex-grow: 1;
            border: 1px solid var(--border-color);
            border-top: none;
            min-height: 600px;
            max-height: calc(100vh - 280px);
            overflow-y: auto;
        }
        
        .kanban-item-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: grab;
        }
        
        .kanban-item-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-color);
        }
        
        .cursor-pointer {
            cursor: pointer !important;
        }
        
        .kanban-item-card.dragging {
            opacity: 0.5;
            transform: scale(0.95);
        }
        
        .kanban-col-body.drag-over {
            background: rgba(99, 102, 241, 0.05);
            border: 2px dashed var(--primary-color);
        }

        .user-initials {
            width: 28px;
            height: 28px;
            background: var(--primary-light);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 800;
        }

        /* Scrollbar */
        .kanban-col-body::-webkit-scrollbar {
            width: 5px;
        }
        .kanban-col-body::-webkit-scrollbar-track {
            background: transparent;
        }
        .kanban-col-body::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initKanban();
            
            Livewire.on('reinit-kanban', () => {
                setTimeout(initKanban, 100);
            });
        });

        function initKanban() {
            const cards = document.querySelectorAll('.kanban-item-card');
            const columns = document.querySelectorAll('.kanban-col-body');
            
            cards.forEach(card => {
                card.addEventListener('dragstart', function(e) {
                    this.classList.add('dragging');
                    e.dataTransfer.setData('tarefaId', this.dataset.tarefaId);
                    e.dataTransfer.setData('oldStatus', this.dataset.status);
                });
                
                card.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                });
            });
            
            columns.forEach(column => {
                column.addEventListener('dragover', e => e.preventDefault());
                
                column.addEventListener('dragenter', function() {
                    this.classList.add('drag-over');
                });
                
                column.addEventListener('dragleave', function() {
                    this.classList.remove('drag-over');
                });
                
                column.addEventListener('drop', function(e) {
                    this.classList.remove('drag-over');
                    const tarefaId = e.dataTransfer.getData('tarefaId');
                    const oldStatus = e.dataTransfer.getData('oldStatus');
                    const newStatus = this.dataset.status;
                    
                    if (oldStatus !== newStatus) {
                        Livewire.dispatch('tarefa-movida', {
                            tarefaId: tarefaId,
                            novoStatus: newStatus
                        });
                    }
                });
            });
        }
    </script>
</div>
