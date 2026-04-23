<div class="row">
    <!-- Sidebar de Configurações -->
    <div class="col-xl-3 col-lg-4 mb-4">
        <div class="chart-container p-3 border-0 shadow-sm card-premium">
            <h6 class="fw-800 text-muted small text-uppercase mb-4 px-3 pt-2">Menu do Perfil</h6>
            <div class="nav flex-column nav-pills gap-2">
                <a class="nav-link py-3 px-4 rounded-4 fw-bold {{ request()->routeIs('settings.profile') ? 'active bg-primary' : 'text-dark hover-light' }}" 
                   href="{{ route('settings.profile') }}">
                    <i class="bi bi-person-circle me-2"></i> {{ __('Perfil') }}
                </a>
                <a class="nav-link py-3 px-4 rounded-4 fw-bold {{ request()->routeIs('settings.password') ? 'active bg-primary' : 'text-dark hover-light' }}" 
                   href="{{ route('settings.password') }}">
                    <i class="bi bi-shield-lock me-2"></i> {{ __('Senha e Segurança') }}
                </a>
                <a class="nav-link py-3 px-4 rounded-4 fw-bold {{ request()->routeIs('settings.appearance') ? 'active bg-primary' : 'text-dark hover-light' }}" 
                   href="{{ route('settings.appearance') }}">
                    <i class="bi bi-palette me-2"></i> {{ __('Aparência') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="col-xl-9 col-lg-8">
        <div class="chart-container p-4 border-0 shadow-sm card-premium h-100">
            <div class="mb-5">
                <h4 class="fw-800 text-dark mb-1">{{ $heading ?? '' }}</h4>
                <p class="text-muted mb-0">{{ $subheading ?? '' }}</p>
            </div>

            <div class="w-100" style="max-width: 600px;">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<style>
    .nav-pills .nav-link {
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .nav-pills .nav-link.active {
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }
    .hover-light:hover {
        background-color: rgba(0,0,0,0.03);
        color: var(--primary-color) !important;
    }
</style>
