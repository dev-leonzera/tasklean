<div class="chart-container">
    <div class="d-flex align-items-center mb-4">
        <h5 class="section-title mb-0">
            <i class="bi bi-palette"></i> Configurações de Interface
        </h5>
    </div>
    
    <p class="text-muted mb-5">Habilite ou desabilite módulos específicos para simplificar ou expandir sua experiência no Tasklean.</p>

    <form wire:submit="save">
        <div class="row g-4">
            <!-- Funcionalidades -->
            <div class="col-md-6">
                <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="task-card-icon primary">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <div class="form-check form-switch p-0 m-0" style="min-height: auto;">
                            <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="enable_sprints" id="enable-sprints" style="width: 3rem; height: 1.5rem;">
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-800 text-dark mb-2">Habilitar Sprints</h6>
                        <p class="text-muted small mb-0">Ative o gerenciamento de tarefas em sprints de desenvolvimento para metodologias ágeis.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="task-card-icon info">
                            <i class="bi bi-kanban"></i>
                        </div>
                        <div class="form-check form-switch p-0 m-0" style="min-height: auto;">
                            <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="enable_kanban" id="enable-kanban" style="width: 3rem; height: 1.5rem;">
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-800 text-dark mb-2">Habilitar Visão Kanban</h6>
                        <p class="text-muted small mb-0">Visualize suas tarefas em um quadro interativo com colunas de progresso.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-5 pt-4 border-top">
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                <i class="bi bi-check2-circle me-2"></i> Salvar Preferências
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('settings-saved', () => {
            // Recarregar para aplicar mudanças no menu lateral
            window.location.reload();
        });
    });
</script>
@endpush
