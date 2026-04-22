<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-folder me-2"></i>Configurações de Projeto
        </h5>
        <p class="card-text text-muted">Configure padrões e regras para projetos e tarefas</p>
    </div>
    <div class="card-body">
        <form wire:submit="save">
            
            <!-- Configurações Padrão -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Configurações Padrão</h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Status Padrão de Novas Tarefas</label>
                        <select wire:model="default_task_status" class="form-select">
                            <option value="backlog">Backlog</option>
                            <option value="pendente">Pendente</option>
                            <option value="em desenvolvimento">Em Andamento</option>
                            <option value="concluida">Concluída</option>
                        </select>
                        <small class="text-muted">Status inicial ao criar uma nova tarefa</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Duração Padrão de Sprints (semanas)</label>
                        <select wire:model="default_sprint_duration" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'semana' : 'semanas' }}</option>
                            @endfor
                        </select>
                        <small class="text-muted">Duração padrão ao criar um novo sprint</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fuso Horário</label>
                        <select wire:model="timezone" class="form-select">
                            <option value="America/Sao_Paulo">São Paulo (GMT-3)</option>
                            <option value="America/New_York">Nova York (GMT-5)</option>
                            <option value="Europe/London">Londres (GMT+0)</option>
                            <option value="Europe/Paris">Paris (GMT+1)</option>
                            <option value="Asia/Tokyo">Tóquio (GMT+9)</option>
                            <option value="UTC">UTC (GMT+0)</option>
                        </select>
                        <small class="text-muted">Fuso horário para exibição de datas e horários</small>
                    </div>
                </div>
            </div>

            <!-- Dias Úteis -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Dias Úteis</h6>
                
                <div class="card">
                    <div class="card-body">
                        <label class="form-label">Selecione os dias úteis da semana</label>
                        <div class="row g-2">
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
                                <div class="col-md-3 col-sm-6">
                                    <button type="button" 
                                            wire:click="toggleWorkingDay('{{ $day }}')"
                                            class="btn w-100 {{ in_array($day, $working_days) ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ $label }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Dias considerados úteis para cálculos de prazo</small>
                    </div>
                </div>
            </div>

            <!-- Validações -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Validações</h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">Responsável Obrigatório</h6>
                                        <p class="card-text small text-muted mb-0">Exigir responsável ao criar tarefas</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="require_task_assignee" id="require-assignee">
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
                                        <h6 class="card-title mb-1">Data de Vencimento Obrigatória</h6>
                                        <p class="card-text small text-muted mb-0">Exigir data de vencimento ao criar tarefas</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model="require_task_due_date" id="require-due-date">
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
