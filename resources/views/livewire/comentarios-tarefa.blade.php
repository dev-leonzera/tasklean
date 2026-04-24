<div class="mt-5 pt-4 border-top">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="fw-800 text-dark mb-0">
            <i class="bi bi-chat-left-dots text-primary me-2"></i> Discussão e Feedback
        </h6>
        <span class="badge bg-light text-primary rounded-pill px-3">{{ $comentarios->count() }} comentários</span>
    </div>
    
    <div class="comments-list mb-4 pe-2" style="max-height: 400px; overflow-y: auto; scrollbar-width: thin;">
        @forelse($comentarios as $comentario)
            <div class="activity-item bg-light border-0 rounded-4 p-3 mb-3 animate-fade-in group">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="user-avatar" style="width: 38px; height: 38px; font-size: 0.9rem; border-radius: 12px; background-color: var(--primary-light); color: var(--primary-color);">
                            {{ substr($comentario->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark small">{{ $comentario->user->name }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted" style="font-size: 0.65rem;">
                                    <i class="bi bi-clock me-1"></i> {{ $comentario->created_at->diffForHumans() }}
                                </span>
                                @can('delete', $comentario)
                                    <button wire:click="removerComentario({{ $comentario->id }})" 
                                            wire:confirm="Tem certeza que deseja remover este comentário?"
                                            class="btn btn-link text-danger p-0 border-0 opacity-0 group-hover-opacity-100 transition-all" 
                                            style="font-size: 0.75rem;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div class="text-muted small lh-base" style="word-break: break-word;">{{ $comentario->conteudo }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 chart-container border-0 shadow-none bg-light bg-opacity-50 rounded-4">
                <i class="bi bi-chat-square-dots text-muted opacity-25 fs-1 mb-3 d-block"></i>
                <p class="text-muted small mb-0 italic">Nenhum comentário ainda. Seja o primeiro a participar da discussão!</p>
            </div>
        @endforelse
    </div>

    <div class="chart-container p-2 border-0 bg-white shadow-sm rounded-4">
        <form wire:submit.prevent="adicionarComentario">
            <div class="d-flex gap-2">
                <input type="text" wire:model="novoComentario" class="form-control border-0 bg-light rounded-4 px-3" 
                       placeholder="Escreva sua mensagem aqui..." style="box-shadow: none;">
                <button class="btn btn-primary rounded-4 px-3" type="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove><i class="bi bi-send-fill"></i></span>
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                </button>
            </div>
            @error('novoComentario') <div class="text-danger x-small mt-2 ms-2 fw-bold"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
        </form>
    </div>
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .comments-list::-webkit-scrollbar {
            width: 4px;
        }
        .comments-list::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.1);
            border-radius: 10px;
        }
    </style>
</div>
