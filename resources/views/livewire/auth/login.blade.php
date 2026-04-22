<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">
            <!-- Login Card -->
            <div class="card border-0 shadow-lg login-card">
                <div class="card-body p-5">
                    <!-- Logo e Título -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <img src="{{ asset('images/logo-tasklean.svg') }}" alt="TaskLean" height="64" class="logo-icon">
                        </div>
                        <p class="text-muted mb-0">Faça login em sua conta</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" wire:submit="login" novalidate>
                        @csrf
                        
                        <!-- Email Field -->
                        <x-auth.form-input
                            type="email"
                            name="email"
                            label="E-mail"
                            placeholder="seu@email.com"
                            wireModel="email"
                            error="{{ $errors->first('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            icon="bi bi-envelope"
                            class="{{ $emailValid ? 'is-valid' : '' }}"
                        />

                        <!-- Password Field -->
                        <x-auth.password-input
                            name="password"
                            label="Senha"
                            placeholder="Sua senha"
                            wireModel="password"
                            error="{{ $errors->first('password') }}"
                            required
                            showPassword="{{ $showPassword }}"
                            onTogglePassword="togglePasswordVisibility"
                            class="{{ $passwordValid ? 'is-valid' : '' }}"
                        />

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <x-auth.checkbox-input
                                name="remember"
                                label="Lembrar-me"
                                wireModel="remember"
                            />
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="auth-link">
                                    Esqueceu a senha?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <x-auth.submit-button
                            text="Entrar"
                            icon="bi bi-box-arrow-in-right"
                            loading="{{ $isLoading }}"
                            disabled="{{ $isLoading }}"
                        />
                    </form>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Não tem uma conta? 
                                <a href="{{ route('register') }}" class="auth-link">
                                    Criar conta
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <p class="footer-text mb-0">
                    <i class="bi bi-shield-check me-1"></i>
                    Seus dados estão seguros conosco
                </p>
            </div>
        </div>
    </div>
</div>