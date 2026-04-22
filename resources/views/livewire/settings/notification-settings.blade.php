<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-bell me-2"></i>Configurações de Notificações
        </h5>
        <p class="card-text text-muted">Configure como e quando receber notificações</p>
    </div>
    <div class="card-body">
        <form wire:submit="save">
            
            <!-- Configurações Gerais -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Configurações Gerais</h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Notificações Habilitadas</h6>
                                        <p class="card-text small text-muted mb-0">Receber notificações do sistema</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="notifications_enabled" id="notifications-enabled">
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
                                        <h6 class="card-title mb-1">Notificações por Email</h6>
                                        <p class="card-text small text-muted mb-0">Receber notificações por email</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="email_notifications" id="email-notifications">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tipos de Notificações -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Tipos de Notificações</h6>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Tarefas que Vencem Hoje</h6>
                                        <p class="card-text small text-muted mb-0">Notificar sobre tarefas com vencimento hoje</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="task_due_today" id="task-due-today">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Tarefas Atrasadas</h6>
                                        <p class="card-text small text-muted mb-0">Notificar sobre tarefas em atraso</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="task_overdue" id="task-overdue">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Tarefas em Desenvolvimento há Muito Tempo</h6>
                                        <p class="card-text small text-muted mb-0">Notificar sobre tarefas em desenvolvimento há mais de 14 dias</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="task_long_development" id="task-long-development">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Frequência -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Frequência</h6>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Frequência de Verificação (minutos)</label>
                        <select wire:model="notification_frequency" class="form-select">
                            <option value="5">5 minutos</option>
                            <option value="15">15 minutos</option>
                            <option value="30">30 minutos</option>
                            <option value="60">1 hora</option>
                            <option value="120">2 horas</option>
                            <option value="240">4 horas</option>
                            <option value="480">8 horas</option>
                            <option value="1440">24 horas</option>
                        </select>
                        <small class="text-muted">Intervalo entre verificações de notificações</small>
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
