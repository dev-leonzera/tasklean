<div>
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h1 class="h2 mb-1 fw-800 text-dark">
                        <i class="bi bi-gear me-2 text-primary"></i> Configurações
                    </h1>
                    <p class="text-muted mb-0">Personalize o comportamento, notificações e aparência do Tasklean</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Tabs Navigation -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="chart-container p-2 bg-light border-0 shadow-none rounded-pill d-inline-flex gap-1">
                <button class="btn {{ $activeTab === 'interface' ? 'btn-primary shadow-sm' : 'btn-link text-muted' }} rounded-pill px-4 fw-bold text-decoration-none" 
                        wire:click="setActiveTab('interface')" type="button">
                    <i class="bi bi-palette me-2"></i> Interface
                </button>
                <button class="btn {{ $activeTab === 'notifications' ? 'btn-primary shadow-sm' : 'btn-link text-muted' }} rounded-pill px-4 fw-bold text-decoration-none" 
                        wire:click="setActiveTab('notifications')" type="button">
                    <i class="bi bi-bell me-2"></i> Notificações
                </button>
                <button class="btn {{ $activeTab === 'dashboard' ? 'btn-primary shadow-sm' : 'btn-link text-muted' }} rounded-pill px-4 fw-bold text-decoration-none" 
                        wire:click="setActiveTab('dashboard')" type="button">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </button>
                <button class="btn {{ $activeTab === 'project' ? 'btn-primary shadow-sm' : 'btn-link text-muted' }} rounded-pill px-4 fw-bold text-decoration-none" 
                        wire:click="setActiveTab('project')" type="button">
                    <i class="bi bi-folder me-2"></i> Projeto
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="row">
        <div class="col-12">
            <!-- Mensagens de Sucesso -->
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                            <i class="bi bi-check2-circle fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Sucesso!</h6>
                            <p class="mb-0 small opacity-75">{{ session('message') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="tab-content" id="settingsTabContent">
                <div class="fade show active">
                    @if($activeTab === 'interface')
                        @livewire('settings.interface-settings')
                    @endif

                    @if($activeTab === 'notifications')
                        @livewire('settings.notification-settings')
                    @endif

                    @if($activeTab === 'dashboard')
                        @livewire('settings.dashboard-settings')
                    @endif

                    @if($activeTab === 'project')
                        @livewire('settings.project-settings')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
