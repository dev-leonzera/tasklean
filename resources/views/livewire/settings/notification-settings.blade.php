<div class="chart-container">
    <div class="d-flex align-items-center mb-4">
        <h5 class="section-title mb-0">
            <i class="bi bi-bell"></i> Configurações de Notificações
        </h5>
    </div>
    
    <p class="text-muted mb-5">Controle como e quando você deseja ser alertado sobre suas tarefas e compromissos.</p>

    <form wire:submit="save">
        <!-- Configurações Gerais -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Configurações Gerais</h6>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon primary">
                                <i class="bi bi-app-indicator"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="notifications_enabled" id="notifications-enabled" style="width: 3rem; height: 1.5rem;">
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-800 text-dark mb-2">Notificações Habilitadas</h6>
                            <p class="text-muted small mb-0">Receber alertas e notificações dentro do sistema Tasklean.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon info">
                                <i class="bi bi-envelope-at"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="email_notifications" id="email-notifications" style="width: 3rem; height: 1.5rem;">
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-800 text-dark mb-2">Notificações por Email</h6>
                            <p class="text-muted small mb-0">Receber um resumo de suas atividades e alertas importantes diretamente na sua caixa de entrada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tipos de Notificações -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Eventos Notificáveis</h6>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon warning">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="task_due_today" id="task-due-today">
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-2">Vencendo Hoje</h6>
                            <p class="text-muted small mb-0">Alertar sobre tarefas com prazo para o dia atual.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon danger">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="task_overdue" id="task-overdue">
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-2">Tarefas Atrasadas</h6>
                            <p class="text-muted small mb-0">Notificar imediatamente quando uma tarefa ultrapassar o prazo.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon secondary">
                                <i class="bi bi-hourglass-bottom"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="task_long_development" id="task-long-development">
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-2">Desenvolvimento Longo</h6>
                            <p class="text-muted small mb-0">Alertar tarefas em progresso há mais de 14 dias.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Frequência -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Sincronização</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="activity-item bg-light border-0 p-4 rounded-4">
                        <label class="form-label fw-bold text-dark">Frequência de Verificação</label>
                        <select wire:model="notification_frequency" class="form-select form-select-lg border-0 shadow-sm rounded-3">
                            <option value="5">A cada 5 minutos</option>
                            <option value="15">A cada 15 minutos</option>
                            <option value="30">A cada 30 minutos</option>
                            <option value="60">A cada 1 hora</option>
                            <option value="120">A cada 2 horas</option>
                            <option value="1440">Uma vez por dia</option>
                        </select>
                        <p class="text-muted small mt-2 mb-0">Define o intervalo de tempo que o sistema busca por novos eventos para notificar.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-5 pt-4 border-top">
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                <i class="bi bi-check2-circle me-2"></i> Salvar Notificações
            </button>
        </div>
    </form>
</div>
