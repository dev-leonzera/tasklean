<div>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <!-- Onboarding Card -->
                <div class="card border-0 shadow-lg login-card">
                    <div class="card-body p-5">
                        <!-- Logo e Título -->
                        <div class="text-center mb-5">
                            <div class="mb-3">
                                <img src="{{ asset('images/logo-tasklean.svg') }}" alt="TaskLean" height="64" class="logo-icon">
                            </div>
                            <h2 class="fw-bold mb-2">Bem-vindo ao Tasklean!</h2>
                            <p class="text-muted">Vamos configurar seu espaço de trabalho em poucos passos.</p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small fw-semibold text-success">Passo {{ $currentStep }} de 2</span>
                                <span class="small text-muted">{{ round(($currentStep / 2) * 100) }}% concluído</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 10px; background-color: #e2e8f0;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ ($currentStep / 2) * 100 }}%; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); border-radius: 10px; transition: width 0.5s ease;" 
                                     aria-valuenow="{{ ($currentStep / 2) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <form wire:submit.prevent="nextStep">
                            @if($currentStep === 1)
                                <!-- Step 1: Team -->
                                <div class="step-content">
                                    <h4 class="fw-bold mb-3">Seu Time</h4>
                                    <p class="text-muted mb-4 small">Um time é onde você colabora em projetos e sprints.</p>
                                    
                                    <x-auth.form-input
                                        label="Nome do Time/Empresa"
                                        placeholder="Ex: Tech Solutions Inc."
                                        wireModel="teamName"
                                        required
                                        icon="bi bi-people"
                                        error="{{ $errors->first('teamName') }}"
                                    />

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold" style="color: var(--sidebar-text);">
                                            <i class="bi bi-card-text me-2"></i>Descrição (Opcional)
                                        </label>
                                        <textarea wire:model="teamDescription" rows="3" class="form-control" 
                                                  placeholder="Descreva brevemente o que seu time faz..."
                                                  style="border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px 16px; transition: all 0.3s ease;"
                                                  onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 0.2rem rgba(16, 185, 129, 0.25)'"
                                                  onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"></textarea>
                                    </div>
                                </div>
                            @elseif($currentStep === 2)
                                <!-- Step 2: Project -->
                                <div class="step-content">
                                    <h4 class="fw-bold mb-3">Seu Primeiro Projeto</h4>
                                    <p class="text-muted mb-4 small">Projetos organizam suas tarefas e sprints no Tasklean.</p>
                                    
                                    <x-auth.form-input
                                        label="Título do Projeto"
                                        placeholder="Ex: Lançamento do Novo App"
                                        wireModel="projectTitle"
                                        required
                                        icon="bi bi-briefcase"
                                        error="{{ $errors->first('projectTitle') }}"
                                    />
                                </div>
                            @endif

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                                @if($currentStep > 1)
                                    <button type="button" wire:click="previousStep" class="btn btn-link auth-link p-0 text-decoration-none">
                                        <i class="bi bi-arrow-left me-1"></i> Voltar
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                <button type="submit" class="btn btn-login text-white px-5" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="nextStep">
                                        {{ $currentStep === 2 ? 'Finalizar e Começar' : 'Próximo Passo' }}
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </span>
                                    <span wire:loading wire:target="nextStep" class="spinner-border spinner-border-sm" role="status"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <p class="footer-text mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Você poderá alterar essas configurações depois
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .step-content {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</div>
