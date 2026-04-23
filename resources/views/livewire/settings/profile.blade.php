<section class="w-full">
    <!-- Header Especial para Perfil -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center gap-4 p-4 chart-container border-0 shadow-sm card-premium bg-light bg-opacity-50">
                <div class="user-avatar" style="width: 80px; height: 80px; font-size: 2rem; border-radius: 20px;">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h3 class="fw-800 text-dark mb-1">{{ auth()->user()->name }}</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-envelope me-1"></i> {{ auth()->user()->email }}
                        <span class="mx-2">|</span>
                        <i class="bi bi-calendar-check me-1"></i> Membro desde {{ auth()->user()->created_at->format('M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <x-settings.layout :heading="__('Informações do Perfil')" :subheading="__('Atualize seu nome de exibição e endereço de e-mail principal.')">
        <form wire:submit="updateProfileInformation" class="mt-4">
            <div class="mb-4">
                <x-auth.form-input 
                    wireModel="name" 
                    :label="__('Nome Completo')" 
                    type="text" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    icon="bi bi-person"
                    error="{{ $errors->first('name') }}"
                />
            </div>

            <div class="mb-4">
                <x-auth.form-input 
                    wireModel="email" 
                    :label="__('Endereço de E-mail')" 
                    type="email" 
                    required 
                    autocomplete="email" 
                    icon="bi bi-envelope"
                    error="{{ $errors->first('email') }}"
                />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mt-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <p class="mb-1 fw-bold">E-mail não verificado.</p>
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold" wire:click.prevent="resendVerificationNotification">
                                    Clique aqui para reenviar o e-mail de verificação.
                                </button>
                            </div>
                        </div>

                        @if (session('status') === 'verification-link-sent')
                            <div class="mt-2 text-success small fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Um novo link de verificação foi enviado.
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3 mt-5">
                <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-check2-circle me-2"></i> {{ __('Salvar Alterações') }}
                </button>

                <x-action-message class="text-success fw-bold" on="profile-updated">
                    <i class="bi bi-check-lg me-1"></i> {{ __('Salvo com sucesso.') }}
                </x-action-message>
            </div>
        </form>

        @livewire('settings.delete-user-form')
    </x-settings.layout>
</section>
