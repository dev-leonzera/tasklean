<div>
    <div class="row g-4">
        <!-- Gerar Novo Convite -->
        <div class="col-md-4">
            <div class="card card-premium h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Gerar Novo Link</h6>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Regra de Acesso</label>
                        <select wire:model="regra" class="form-select border-0 bg-light">
                            <option value="membro">Membro</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Limite de Usos</label>
                        <input type="number" wire:model="max_usos" class="form-control border-0 bg-light" min="1">
                        <div class="form-text extra-small">Quantas pessoas podem entrar usando este link.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Expiração (dias)</label>
                        <input type="number" wire:model="expires_in" class="form-control border-0 bg-light" min="1">
                    </div>

                    <button wire:click="gerarLink" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-link-45deg me-2"></i> Gerar Link
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Convites Ativos -->
        <div class="col-md-8">
            <div class="card card-premium h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Links Ativos</h6>
                    
                    @if($convites->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-link-45deg display-4 text-muted mb-3 d-block"></i>
                            <p class="text-muted">Nenhum link de convite gerado.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="small text-muted text-uppercase">
                                        <th>Link</th>
                                        <th>Regra</th>
                                        <th>Usos</th>
                                        <th>Expira em</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($convites as $convite)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <code class="bg-light p-1 rounded extra-small me-2">{{ substr($convite->token, 0, 8) }}...</code>
                                                    <button onclick="copyToClipboard('{{ route('team.join', $convite->token) }}')" class="btn btn-sm btn-light border-0" title="Copiar Link">
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark text-capitalize">{{ $convite->regra }}</span></td>
                                            <td>
                                                <div class="extra-small">
                                                    <span class="{{ $convite->isFull() ? 'text-danger' : 'text-success' }}">
                                                        {{ $convite->usos }}
                                                    </span> / {{ $convite->max_usos }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="extra-small {{ $convite->isExpired() ? 'text-danger' : '' }}">
                                                    {{ $convite->expires_at->format('d/m/Y') }}
                                                    @if($convite->isExpired())
                                                        <br><span class="extra-small">(Expirado)</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <button wire:click="revogar({{ $convite->id }})" wire:confirm="Tem certeza que deseja revogar este convite?" class="btn btn-sm btn-outline-danger border-0">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copiado para a área de transferência!');
            });
        }
    </script>
</div>
