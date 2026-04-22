<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <!-- Register Card -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-5">
                    <!-- Logo e Título -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-person-plus" style="font-size: 3rem; color: var(--primary-color);"></i>
                        </div>
                        <h2 class="fw-bold mb-2" style="color: var(--primary-color);">Criar Conta</h2>
                        <p class="text-muted mb-0">Preencha os dados abaixo para criar sua conta</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Register Form -->
                    <form method="POST" wire:submit="register">
                        @csrf
                        
                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold" style="color: var(--sidebar-text);">
                                <i class="bi bi-person me-2"></i>Nome Completo
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                id="name"
                                wire:model="name"
                                placeholder="Seu nome completo"
                                required 
                                autofocus
                                autocomplete="name"
                                style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px 16px; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 0.2rem rgba(16, 185, 129, 0.25)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                            >
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: var(--sidebar-text);">
                                <i class="bi bi-envelope me-2"></i>E-mail
                            </label>
                            <input 
                                type="email" 
                                class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                id="email"
                                wire:model="email"
                                placeholder="seu@email.com"
                                required 
                                autocomplete="email"
                                style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px 16px; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 0.2rem rgba(16, 185, 129, 0.25)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold" style="color: var(--sidebar-text);">
                                <i class="bi bi-lock me-2"></i>Senha
                            </label>
                            <div class="position-relative">
                                <input 
                                    type="password" 
                                    class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                    id="password"
                                    wire:model="password"
                                    placeholder="Sua senha"
                                    required
                                    autocomplete="new-password"
                                    style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px 16px; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 0.2rem rgba(16, 185, 129, 0.25)'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                                >
                                <button 
                                    type="button" 
                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-3" 
                                    onclick="togglePassword('password')"
                                    style="color: var(--secondary-color); text-decoration: none;"
                                >
                                    <i class="bi bi-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold" style="color: var(--sidebar-text);">
                                <i class="bi bi-lock-fill me-2"></i>Confirmar Senha
                            </label>
                            <div class="position-relative">
                                <input 
                                    type="password" 
                                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation"
                                    wire:model="password_confirmation"
                                    placeholder="Confirme sua senha"
                                    required
                                    autocomplete="new-password"
                                    style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px 16px; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 0.2rem rgba(16, 185, 129, 0.25)'"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                                >
                                <button 
                                    type="button" 
                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-3" 
                                    onclick="togglePassword('password_confirmation')"
                                    style="color: var(--secondary-color); text-decoration: none;"
                                >
                                    <i class="bi bi-eye" id="passwordConfirmationToggleIcon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Requirements -->
                        <div class="mb-4">
                            <div class="card" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 8px;">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2" style="color: var(--primary-color); font-size: 0.9rem;">
                                        <i class="bi bi-info-circle me-1"></i>Requisitos da senha:
                                    </h6>
                                    <ul class="mb-0" style="font-size: 0.8rem; color: var(--secondary-color);">
                                        <li>Mínimo de 8 caracteres</li>
                                        <li>Pelo menos uma letra maiúscula</li>
                                        <li>Pelo menos uma letra minúscula</li>
                                        <li>Pelo menos um número</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="btn w-100 py-3 fw-bold text-white border-0 mb-4" 
                            style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); border-radius: 12px; font-size: 1.1rem; transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.3)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                        >
                            <i class="bi bi-person-plus me-2"></i>
                            Criar Conta
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Já tem uma conta? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">
                                Fazer login
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <p class="text-white-50 mb-0">
                    <i class="bi bi-shield-check me-1"></i>
                    Seus dados estão seguros conosco
                </p>
            </div>
        </div>
    </div>
</div>