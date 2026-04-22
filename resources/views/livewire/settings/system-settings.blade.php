<div>
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 fw-bold text-primary">
                        <i class="bi bi-gear me-2"></i>Configurações do Sistema
                    </h1>
                    <p class="text-muted mb-0">Personalize o comportamento e aparência do ProTask</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'interface' ? 'active' : '' }}" 
                            wire:click="setActiveTab('interface')" 
                            type="button">
                        <i class="bi bi-palette me-2"></i>Interface
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'notifications' ? 'active' : '' }}" 
                            wire:click="setActiveTab('notifications')" 
                            type="button">
                        <i class="bi bi-bell me-2"></i>Notificações
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'dashboard' ? 'active' : '' }}" 
                            wire:click="setActiveTab('dashboard')" 
                            type="button">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'project' ? 'active' : '' }}" 
                            wire:click="setActiveTab('project')" 
                            type="button">
                        <i class="bi bi-folder me-2"></i>Projeto
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="row">
        <div class="col-12">
            <!-- Mensagens de Sucesso -->
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="tab-content" id="settingsTabContent">
                
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
