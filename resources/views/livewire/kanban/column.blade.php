<div class="kanban-column-container h-100">
    <div class="kanban-col-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-800 text-dark mb-0 d-flex align-items-center">
                <i class="bi bi-{{ $icon }} text-{{ $color }} me-2"></i>
                {{ $title }}
            </h6>
            <span class="badge bg-soft-{{ $color }} text-{{ $color }} rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem; background-color: var(--{{ $color === 'secondary' ? 'secondary' : $color }}-light)">
                {{ count($tarefas) }}
            </span>
        </div>
    </div>
    <div class="kanban-col-body" data-status="{{ $status }}">
        @forelse($tarefas as $tarefa)
            <div class="kanban-item-card" 
                 data-tarefa-id="{{ $tarefa['id'] }}" 
                 data-status="{{ $status }}"
                 draggable="true">
                
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-soft-{{ $color }} text-{{ $color }} rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.6rem; background-color: var(--{{ $color === 'secondary' ? 'secondary' : $color }}-light)">
                        {{ $tarefa['projeto']['titulo'] ?? 'Geral' }}
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown" style="line-height: 1;">
                            <i class="bi bi-three-dots" style="font-size: 0.8rem;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 small">
                            <li><a class="dropdown-item" href="{{ route('tarefas.show', $tarefa['id']) }}"><i class="bi bi-eye me-2"></i> Ver</a></li>
                            <li><a class="dropdown-item" href="{{ route('tarefas.edit', $tarefa['id']) }}"><i class="bi bi-pencil me-2"></i> Editar</a></li>
                        </ul>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem; line-height: 1.4;">
                    {{ $tarefa['titulo'] }}
                </h6>

                @if($tarefa['descricao'])
                    <p class="text-muted small mb-3" style="font-size: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $tarefa['descricao'] }}
                    </p>
                @endif

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-light">
                    <div class="d-flex align-items-center">
                        @if(isset($tarefa['responsavel']))
                            <div class="user-initials" title="{{ $tarefa['responsavel']['name'] }}">
                                {{ substr($tarefa['responsavel']['name'], 0, 1) }}
                            </div>
                            <span class="text-muted extra-small ms-2 d-none d-xl-inline" style="font-size: 0.65rem;">
                                {{ explode(' ', $tarefa['responsavel']['name'])[0] }}
                            </span>
                        @else
                            <div class="user-initials bg-light text-muted" title="Sem responsável">
                                <i class="bi bi-person"></i>
                            </div>
                        @endif
                    </div>
                    
                    @if($tarefa['data_vencimento'])
                        <div class="small {{ \Carbon\Carbon::parse($tarefa['data_vencimento'])->isPast() && $status !== 'concluida' ? 'text-danger' : 'text-muted' }}" style="font-size: 0.7rem;">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ \Carbon\Carbon::parse($tarefa['data_vencimento'])->format('d/m') }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5 opacity-25">
                <i class="bi bi-plus-circle-dotted fs-1 text-muted"></i>
                <p class="small fw-bold text-muted mt-2">Vazio</p>
            </div>
        @endforelse
    </div>
</div>
