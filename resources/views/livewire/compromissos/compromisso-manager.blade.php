<div>
    <!-- Header com ações -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="bi bi-calendar-event me-2"></i>Compromissos
            </h2>
            <p class="text-muted mb-0">Gerencie sua agenda de compromissos</p>
        </div>
        <div>
            <button class="btn btn-primary" wire:click="abrirModal()">
                <i class="bi bi-plus-circle me-1"></i>Novo Compromisso
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" wire:model.live="busca" placeholder="Título, descrição ou local...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" wire:model.live="filtro_status">
                        <option value="">Todos</option>
                        @foreach($status as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" wire:model.live="filtro_tipo">
                        <option value="">Todos</option>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Prioridade</label>
                    <select class="form-select" wire:model.live="filtro_prioridade">
                        <option value="">Todas</option>
                        @foreach($prioridades as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Data Início</label>
                    <input type="date" class="form-control" wire:model.live="filtro_data_inicio">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-outline-secondary" wire:click="limparFiltros()" title="Limpar filtros">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Compromissos -->
    <div class="card">
        <div class="card-body">
            @if($compromissos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th wire:click="ordenarPor('titulo')" style="cursor: pointer;">
                                    Título
                                    @if($ordenar_por === 'titulo')
                                        <i class="bi bi-arrow-{{ $direcao_ordenacao === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="ordenarPor('data_inicio')" style="cursor: pointer;">
                                    Data/Hora
                                    @if($ordenar_por === 'data_inicio')
                                        <i class="bi bi-arrow-{{ $direcao_ordenacao === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Prioridade</th>
                                <th>Local</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compromissos as $compromisso)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $compromisso->titulo }}</strong>
                                            @if($compromisso->descricao)
                                                <br><small class="text-muted">{{ Str::limit($compromisso->descricao, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $compromisso->data_inicio->format('d/m/Y') }}</strong>
                                            <br><small class="text-muted">{{ $compromisso->hora_inicio->format('H:i') }} - {{ $compromisso->hora_fim->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $compromisso->tipo_formatado }}</span>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" wire:change="atualizarStatus({{ $compromisso->id }}, $event.target.value)">
                                            @foreach($status as $key => $label)
                                                <option value="{{ $key }}" {{ $compromisso->status === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @php
                                            $prioridadeColors = [
                                                'baixa' => 'success',
                                                'media' => 'warning',
                                                'alta' => 'danger',
                                                'urgente' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $prioridadeColors[$compromisso->prioridade] ?? 'secondary' }}">
                                            {{ $compromisso->prioridade_formatada }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($compromisso->local)
                                            <small class="text-muted">{{ Str::limit($compromisso->local, 20) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" wire:click="abrirModal({{ $compromisso->id }})" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" wire:click="excluir({{ $compromisso->id }})" 
                                                    wire:confirm="Tem certeza que deseja excluir este compromisso?" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $compromissos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Nenhum compromisso encontrado</h5>
                    <p class="text-muted">Comece criando seu primeiro compromisso!</p>
                    <button class="btn btn-primary" wire:click="abrirModal()">
                        <i class="bi bi-plus-circle me-1"></i>Criar Compromisso
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Criação/Edição -->
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingId ? 'Editar Compromisso' : 'Novo Compromisso' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="fecharModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="salvar">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Título *</label>
                                    <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                           wire:model="titulo" required>
                                    @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tipo *</label>
                                    <select class="form-select @error('tipo') is-invalid @enderror" wire:model="tipo" required>
                                        @foreach($tipos as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                              wire:model="descricao" rows="3"></textarea>
                                    @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Data Início *</label>
                                    <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" 
                                           wire:model="data_inicio" required>
                                    @error('data_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Data Fim</label>
                                    <input type="date" class="form-control @error('data_fim') is-invalid @enderror" 
                                           wire:model="data_fim">
                                    @error('data_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hora Início *</label>
                                    <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror" 
                                           wire:model="hora_inicio" required>
                                    @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Hora Fim</label>
                                    <input type="time" class="form-control @error('hora_fim') is-invalid @enderror" 
                                           wire:model="hora_fim">
                                    @error('hora_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Local</label>
                                    <input type="text" class="form-control @error('local') is-invalid @enderror" 
                                           wire:model="local">
                                    @error('local') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prioridade *</label>
                                    <select class="form-select @error('prioridade') is-invalid @enderror" wire:model="prioridade" required>
                                        @foreach($prioridades as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('prioridade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                        @foreach($status as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lembrete</label>
                                    <input type="datetime-local" class="form-control @error('lembrete') is-invalid @enderror" 
                                           wire:model="lembrete">
                                    @error('lembrete') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Observações</label>
                                    <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                              wire:model="observacoes" rows="2"></textarea>
                                    @error('observacoes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="fecharModal">Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click="salvar">
                            {{ $editingId ? 'Atualizar' : 'Criar' }} Compromisso
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
