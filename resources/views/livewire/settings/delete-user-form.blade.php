<section class="mt-5 pt-5 border-top">
    <div class="mb-4">
        <h5 class="fw-800 text-danger mb-1">{{ __('Excluir Conta') }}</h5>
        <p class="text-muted small mb-0">{{ __('Exclua permanentemente sua conta e todos os seus recursos do Tasklean.') }}</p>
    </div>

    <button type="button" class="btn btn-outline-danger px-4 py-2 rounded-pill fw-bold" 
            data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        <i class="bi bi-person-x me-2"></i> {{ __('Excluir Minha Conta') }}
    </button>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg card-premium">
                <form method="POST" wire:submit="deleteUser">
                    <div class="modal-header border-bottom bg-light bg-opacity-50 p-4">
                        <h5 class="modal-title fw-800 text-dark" id="confirmUserDeletionLabel">
                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                            {{ __('Confirmar Exclusão') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">
                            {{ __('Uma vez que sua conta for excluída, todos os seus dados serão permanentemente removidos. Por favor, insira sua senha para confirmar que deseja excluir sua conta.') }}
                        </p>

                        <x-auth.form-input 
                            wireModel="password" 
                            :label="__('Sua Senha')" 
                            type="password" 
                            required 
                            icon="bi bi-shield-lock"
                            error="{{ $errors->first('password') }}"
                        />
                    </div>
                    <div class="modal-footer border-top-0 p-4 gap-2">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold">{{ __('Sim, Excluir Minha Conta') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
