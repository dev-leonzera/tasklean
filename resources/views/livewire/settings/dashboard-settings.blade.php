<div class="chart-container">
    <div class="d-flex align-items-center mb-4">
        <h5 class="section-title mb-0">
            <i class="bi bi-speedometer2"></i> Painel de Controle (Dashboard)
        </h5>
    </div>
    
    <p class="text-muted mb-5">Personalize quais métricas e informações são prioritárias na sua visão geral inicial.</p>

    <form wire:submit="save">
        <!-- Métricas Visíveis -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Cartões de Métricas</h6>
            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon primary">
                                <i class="bi bi-folder2-open"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="show_projects_metric" id="show-projects">
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Projetos Ativos</h6>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon success">
                                <i class="bi bi-check2-square"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="show_completed_tasks" id="show-completed">
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Concluídas</h6>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon info">
                                <i class="bi bi-layers"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="show_backlog_metric" id="show-backlog">
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Backlog</h6>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon danger">
                                <i class="bi bi-alarm"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="show_overdue_tasks" id="show-overdue">
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Atrasadas</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preferências de Listagem -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Comportamento do Painel</h6>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="activity-item bg-light border-0 p-4 rounded-4">
                        <label class="form-label fw-bold text-dark">Limite de Atividades Recentes</label>
                        <select wire:model="recent_activities_limit" class="form-select border-0 shadow-sm rounded-3">
                            @for($i = 5; $i <= 25; $i+=5)
                                <option value="{{ $i }}">Mostrar {{ $i }} últimas atividades</option>
                            @endfor
                        </select>
                        <p class="text-muted small mt-2 mb-0">Define a quantidade de registros históricos exibidos no feed de atividades.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="activity-item bg-light border-0 p-4 rounded-4">
                        <label class="form-label fw-bold text-dark">Atualização Automática</label>
                        <select wire:model="auto_refresh_interval" class="form-select border-0 shadow-sm rounded-3">
                            <option value="10">A cada 10 segundos</option>
                            <option value="30">A cada 30 segundos</option>
                            <option value="60">A cada 1 minuto</option>
                            <option value="300">A cada 5 minutos</option>
                            <option value="0">Desativar refresh automático</option>
                        </select>
                        <p class="text-muted small mt-2 mb-0">Mantém os dados do dashboard sincronizados sem precisar recarregar a página.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-5 pt-4 border-top">
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                <i class="bi bi-check2-circle me-2"></i> Salvar Dashboard
            </button>
        </div>
    </form>
</div>
