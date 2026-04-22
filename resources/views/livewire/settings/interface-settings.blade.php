<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-palette me-2"></i>Configurações de Interface
        </h5>
        <p class="card-text text-muted">Personalize a aparência e funcionalidades do sistema</p>
    </div>
    <div class="card-body">
        <form wire:submit="save">
            
            <!-- Funcionalidades -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Funcionalidades</h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Habilitar Sprints</h6>
                                        <p class="card-text small text-muted mb-0">Organize tarefas em sprints de desenvolvimento</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="enable_sprints" id="enable-sprints">
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
                                        <h6 class="card-title mb-1">Habilitar Visão Kanban</h6>
                                        <p class="card-text small text-muted mb-0">Visualize tarefas em colunas organizadas por status</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="enable_kanban" id="enable-kanban">
                                    </div>
                                </div>
                            </div>
                        </div>
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
