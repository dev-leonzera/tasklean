<div class="chart-container">
    <div class="d-flex align-items-center mb-4">
        <h5 class="section-title mb-0">
            <i class="bi bi-folder"></i> Regras de Projeto e Tarefas
        </h5>
    </div>
    
    <p class="text-muted mb-5">Estabeleça padrões globais para a criação de novos projetos e fluxos de trabalho.</p>

    <form wire:submit="save">
        <!-- Configurações Padrão -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Valores Padrão</h6>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="activity-item flex-column align-items-start bg-light border-0 p-4 rounded-4 h-100">
                        <label class="form-label fw-bold text-dark mb-3">Status Inicial</label>
                        <select wire:model="default_task_status" class="form-select w-100 border-0 shadow-sm rounded-3">
                            <option value="backlog">Backlog</option>
                            <option value="pendente">Pendente</option>
                            <option value="em desenvolvimento">Em Andamento</option>
                            <option value="concluida">Concluída</option>
                        </select>
                        <p class="text-muted small mt-2 mb-0">Status atribuído automaticamente a novas tarefas.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="activity-item flex-column align-items-start bg-light border-0 p-4 rounded-4 h-100">
                        <label class="form-label fw-bold text-dark mb-3">Duração de Sprint</label>
                        <select wire:model="default_sprint_duration" class="form-select w-100 border-0 shadow-sm rounded-3">
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Semana' : 'Semanas' }}</option>
                            @endfor
                        </select>
                        <p class="text-muted small mt-2 mb-0">Período padrão sugerido para novos ciclos de trabalho.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="activity-item flex-column align-items-start bg-light border-0 p-4 rounded-4 h-100">
                        <label class="form-label fw-bold text-dark mb-3">Fuso Horário</label>
                        <select wire:model="timezone" class="form-select w-100 border-0 shadow-sm rounded-3">
                            <option value="America/Sao_Paulo">São Paulo (GMT-3)</option>
                            <option value="America/New_York">Nova York (GMT-5)</option>
                            <option value="UTC">UTC (Global)</option>
                        </select>
                        <p class="text-muted small mt-2 mb-0">Garante a precisão dos prazos e horários de lembretes.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dias Úteis -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Calendário de Trabalho</h6>
            <div class="activity-item flex-column align-items-start bg-light border-0 p-4 rounded-4">
                <label class="form-label fw-bold text-dark mb-4">Dias Úteis da Semana</label>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $days = [
                            'monday' => 'Segunda',
                            'tuesday' => 'Terça',
                            'wednesday' => 'Quarta',
                            'thursday' => 'Quinta',
                            'friday' => 'Sexta',
                            'saturday' => 'Sábado',
                            'sunday' => 'Domingo'
                        ];
                    @endphp
                    @foreach($days as $day => $label)
                        <button type="button" 
                                wire:click="toggleWorkingDay('{{ $day }}')"
                                class="btn rounded-pill px-4 py-2 fw-bold transition-all {{ in_array($day, $working_days) ? 'btn-primary shadow-sm' : 'btn-white border text-muted' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                <p class="text-muted small mt-3 mb-0">Esses dias serão considerados para o cálculo automático de prazos e duração de projetos.</p>
            </div>
        </div>

        <!-- Validações -->
        <div class="mb-5">
            <h6 class="fw-800 text-dark mb-4 px-2">Restrições e Qualidade</h6>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon primary">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="require_task_assignee" id="require-assignee" style="width: 3rem; height: 1.5rem;">
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Responsável Obrigatório</h6>
                        <p class="text-muted small mb-0">Impedir a criação de tarefas sem um usuário responsável atribuído.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="activity-item bg-light border-0 p-4 rounded-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="task-card-icon info">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="form-check form-switch p-0 m-0">
                                <input class="form-check-input ms-0 mt-1" type="checkbox" wire:model="require_task_due_date" id="require-due-date" style="width: 3rem; height: 1.5rem;">
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Data de Vencimento Obrigatória</h6>
                        <p class="text-muted small mb-0">Toda nova tarefa deve obrigatoriamente possuir um prazo de entrega definido.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-5 pt-4 border-top">
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                <i class="bi bi-check2-circle me-2"></i> Salvar Configurações
            </button>
        </div>
    </form>
</div>
