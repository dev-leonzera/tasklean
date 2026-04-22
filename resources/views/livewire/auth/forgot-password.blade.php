<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">
            <!-- Forgot Password Card -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-5">
                    <!-- Logo e Título -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock" style="font-size: 3rem; color: var(--primary-color);"></i>
                        </div>
                        <h2 class="fw-bold mb-2" style="color: var(--primary-color);">Recuperar Senha</h2>
                        <p class="text-muted mb-0">Digite seu e-mail para receber um link de redefinição</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Forgot Password Form -->
                    <form method="POST" wire:submit="sendPasswordResetLink">
                        @csrf
                        
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
                                autofocus
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

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="btn w-100 py-3 fw-bold text-white border-0 mb-4" 
                            style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); border-radius: 12px; font-size: 1.1rem; transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.3)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                        >
                            <i class="bi bi-send me-2"></i>
                            Enviar Link de Redefinição
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Lembrou da senha? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">
                                Voltar ao login
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