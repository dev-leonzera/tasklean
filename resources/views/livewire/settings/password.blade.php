<section class="w-full">
    <x-settings.layout :heading="__('Atualizar Senha')" :subheading="__('Garanta que sua conta esteja usando uma senha longa e aleatória para se manter segura.')">
        <form method="POST" wire:submit="updatePassword" class="mt-4">
            <div class="mb-4">
                <x-auth.form-input
                    wireModel="current_password"
                    :label="__('Senha Atual')"
                    type="password"
                    required
                    autocomplete="current-password"
                    icon="bi bi-shield-lock"
                    error="{{ $errors->first('current_password') }}"
                />
            </div>

            <div class="mb-4">
                <x-auth.form-input
                    wireModel="password"
                    :label="__('Nova Senha')"
                    type="password"
                    required
                    autocomplete="new-password"
                    icon="bi bi-key"
                    error="{{ $errors->first('password') }}"
                />
            </div>

            <div class="mb-4">
                <x-auth.form-input
                    wireModel="password_confirmation"
                    :label="__('Confirmar Nova Senha')"
                    type="password"
                    required
                    autocomplete="new-password"
                    icon="bi bi-check-lg"
                    error="{{ $errors->first('password_confirmation') }}"
                />
            </div>

            <div class="d-flex align-items-center gap-3 mt-5">
                <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-shield-check me-2"></i> {{ __('Alterar Senha') }}
                </button>

                <x-action-message class="text-success fw-bold" on="password-updated">
                    <i class="bi bi-check-lg me-1"></i> {{ __('Senha atualizada.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
