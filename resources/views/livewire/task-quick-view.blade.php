<div x-data="{ open: @entangle('isOpen') }"
     x-show="open"
     x-on:keydown.escape.window="open = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-dark bg-opacity-40 backdrop-blur-sm transition-opacity"
         wire:click="close"></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-4 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
             style="max-height: 90vh; display: flex; flex-direction: column;">
            
            @if($tarefa)
                <!-- Header -->
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem;">
                            {{ $tarefa->projeto->titulo ?? 'Geral' }}
                        </span>
                        <span class="text-muted small">#{{ $tarefa->id }}</span>
                    </div>
                    <button wire:click="close" class="btn-close shadow-none" style="font-size: 0.8rem;"></button>
                </div>

                <!-- Body (Scrollable) -->
                <div class="p-4 overflow-auto custom-scrollbar" style="flex-grow: 1;">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h4 class="fw-800 text-dark mb-0">{{ $tarefa->titulo }}</h4>
                            @php
                                $statusColor = match($tarefa->status) {
                                    'backlog' => 'secondary',
                                    'pendente' => 'warning',
                                    'em desenvolvimento' => 'info',
                                    'concluida' => 'success',
                                    default => 'primary'
                                };
                            @endphp
                            <span class="badge bg-soft-{{ $statusColor }} text-{{ $statusColor }} rounded-pill px-3 py-1 fw-bold">
                                {{ ucfirst($tarefa->status) }}
                            </span>
                        </div>
                        
                        @if($tarefa->descricao)
                            <p class="text-muted small lh-base mb-4 bg-light p-3 rounded-4 border-0">
                                {{ $tarefa->descricao }}
                            </p>
                        @else
                            <p class="text-muted small italic mb-4 opacity-50">Sem descrição fornecida.</p>
                        @endif

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem; border-radius: 10px;">
                                        {{ substr($tarefa->responsavel->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-muted extra-small fw-bold text-uppercase" style="font-size: 0.6rem;">Responsável</div>
                                        <div class="text-dark small fw-bold">{{ $tarefa->responsavel->name ?? 'Não atribuído' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="text-muted extra-small fw-bold text-uppercase" style="font-size: 0.6rem;">Prazo</div>
                                <div class="text-dark small fw-bold {{ $tarefa->isAtrasada() ? 'text-danger' : '' }}">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $tarefa->data_vencimento ? $tarefa->data_vencimento->format('d/m/Y') : 'Sem prazo' }}
                                </div>
                            </div>
                        </div>

                        <!-- Comentários integrados -->
                        <div class="mt-5 border-top pt-4">
                            <livewire:comentarios-tarefa :tarefa-id="$tarefa->id" :key="'comments-'.$tarefa->id" />
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 bg-light bg-opacity-50 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('tarefas.show', $tarefa->id) }}" class="btn btn-soft-primary btn-sm rounded-pill px-4 fw-bold">
                        <i class="bi bi-box-arrow-up-right me-2"></i> Ver Detalhes Completos
                    </a>
                    <button wire:click="close" class="btn btn-secondary btn-sm rounded-pill px-4 fw-bold">
                        Fechar
                    </button>
                </div>
            @endif
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</div>
