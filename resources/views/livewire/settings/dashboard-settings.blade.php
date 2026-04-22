<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Configurações do Dashboard
        </h5>
        <p class="card-text text-muted">Personalize a exibição do dashboard principal</p>
    </div>
    <div class="card-body">
        <form wire:submit="save">
            
            <!-- Métricas Exibidas -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Métricas Exibidas</h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Projetos Ativos</h6>
                                        <p class="card-text small text-muted mb-0">Mostrar card de projetos ativos</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="show_projects_metric" id="show-projects">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Tarefas Concluídas</h6>
                                        <p class="card-text small text-muted mb-0">Mostrar card de tarefas concluídas</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="show_completed_tasks" id="show-completed">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Backlog</h6>
                                        <p class="card-text small text-muted mb-0">Mostrar card de tarefas em backlog</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="show_backlog_metric" id="show-backlog">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Tarefas Atrasadas</h6>
                                        <p class="card-text small text-muted mb-0">Mostrar card de tarefas atrasadas</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="show_overdue_tasks" id="show-overdue">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações de Exibição -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Configurações de Exibição</h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Limite de Atividades Recentes</label>
                        <select wire:model="recent_activities_limit" class="form-select">
                            @for($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'atividade' : 'atividades' }}</option>
                            @endfor
                        </select>
                        <small class="text-muted">Quantas atividades recentes mostrar no dashboard</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Intervalo de Auto-refresh (segundos)</label>
                        <select wire:model="auto_refresh_interval" class="form-select">
                            <option value="10">10 segundos</option>
                            <option value="30">30 segundos</option>
                            <option value="60">1 minuto</option>
                            <option value="120">2 minutos</option>
                            <option value="300">5 minutos</option>
                        </select>
                        <small class="text-muted">Intervalo para atualização automática do dashboard</small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Configurações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof Livewire !== 'undefined') {
        Livewire.on('settings-saved', () => {
            window.location.reload();
        });
    } else {
        document.addEventListener('livewire:init', () => {
            Livewire.on('settings-saved', () => {
                window.location.reload();
            });
        });
    }
</script>
