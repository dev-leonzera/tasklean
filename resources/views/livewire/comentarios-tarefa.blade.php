<div class="mt-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-chat-dots me-2"></i>Discussão da Tarefa</h6>
    
    <div class="comments-list mb-3" style="max-height: 300px; overflow-y: auto;">
        @forelse($comentarios as $comentario)
            <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                        {{ substr($comentario->user->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-grow-1 ms-2">
                    <div class="bg-light p-2 rounded">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-bold">{{ $comentario->user->name }}</small>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $comentario->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="small">{{ $comentario->conteudo }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-3 text-muted small italic">
                Nenhum comentário ainda. Comece a discussão!
            </div>
        @endforelse
    </div>

    <form wire:submit.prevent="adicionarComentario">
        <div class="input-group input-group-sm">
            <input type="text" wire:model="novoComentario" class="form-control" placeholder="Escreva um comentário...">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-send"></i>
            </button>
        </div>
        @error('novoComentario') <small class="text-danger">{{ $message }}</small> @enderror
    </form>
</div>
