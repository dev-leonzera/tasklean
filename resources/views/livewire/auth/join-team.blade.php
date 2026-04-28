<div class="container-fluid d-flex align-items-center justify-content-center py-5">
    <div class="login-card p-4 p-md-5 w-100" style="max-width: 500px;">
        <div class="text-center mb-5">
            <div class="logo-icon mb-4 d-inline-block">
                <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center mx-auto" style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: 700;">
                    {{ substr($time->nome, 0, 1) }}
                </div>
            </div>
            <h2 class="h3 fw-bold mb-2">Convite para Time</h2>
            <p class="text-muted">
                Você foi convidado a participar do time <span class="fw-bold text-dark">{{ $time->nome }}</span> no TaskLean.
            </p>
        </div>

        @auth
            <div class="text-center py-4">
                <div class="user-avatar mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <h5 class="mb-4">Olá, {{ auth()->user()->name }}!</h5>
                <p class="mb-4 text-muted small">Ao aceitar, você entrará como <strong>{{ $convite->regra }}</strong>.</p>
                
                <button wire:click="registerAndJoin" class="btn btn-login w-100 text-white">
                    <i class="bi bi-check2-circle me-2"></i> Aceitar Convite e Entrar
                </button>
            </div>
        @else
            <form wire:submit="registerAndJoin" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nome Completo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input wire:model="name" type="text" class="form-control border-start-0" placeholder="Seu nome" required>
                    </div>
                    @error('name') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input wire:model="email" type="email" class="form-control border-start-0" placeholder="seu@email.com" required>
                    </div>
                    @error('email') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                        <input wire:model="password" type="password" id="password" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                        <button class="input-group-text bg-light border-start-0" type="button" onclick="togglePassword('password')">
                            <i class="bi bi-eye text-muted" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                    @error('password') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Confirmar Senha</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                        <button class="input-group-text bg-light border-start-0" type="button" onclick="togglePassword('password_confirmation')">
                            <i class="bi bi-eye text-muted" id="password_confirmationToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 pt-3">
                    <button type="submit" class="btn btn-login w-100 text-white shadow-sm">
                        Criar Conta e Participar do Time
                    </button>
                </div>

                <div class="col-12 text-center mt-4">
                    <p class="mb-0 text-muted small">
                        Já possui uma conta? <a href="{{ route('login') }}" class="auth-link">Faça Login</a> e volte ao link.
                    </p>
                </div>
            </form>
        @endauth
    </div>
    <style>
        .extra-small { font-size: 0.75rem; }
    </style>
</div>
