<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tasklean - Gestão Ágil de Projetos')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-tasklean-icon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-tasklean-icon.svg') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --secondary-color: #6366f1;
            --secondary-dark: #4f46e5;
            --secondary-light: #e0e7ff;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --dark-text: #0f172a;
            --medium-text: #475569;
            --light-text: #94a3b8;
            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --card-shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .section-title, .sidebar-brand h4 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--white);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 260px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 260px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: var(--light-bg);
        }

        .header {
            background-color: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .card-premium {
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .card-premium:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
            border-color: var(--primary-color);
        }

        .metric-card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .metric-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1), transparent);
            z-index: 1;
        }

        .metric-card.primary { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .metric-card.success { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
        .metric-card.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .metric-card.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .metric-card.info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }

        .chart-container {
            background: var(--white);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            height: auto;
        }

        .section-title {
            font-size: 1.1rem;
            color: var(--dark-text);
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
        }

        .section-title i {
            font-size: 1.2rem;
            color: var(--primary-color);
            background: var(--primary-light);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: 12px;
        }

        .activity-item {
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            background: var(--light-bg);
            display: flex;
            align-items: center;
        }

        .activity-item:hover {
            background: var(--white);
            border-color: var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transform: translateX(4px);
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

        .task-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .task-card-icon.warning { background: #fffbeb; color: #d97706; }
        .task-card-icon.danger { background: #fef2f2; color: #dc2626; }
        .task-card-icon.success { background: #ecfdf5; color: #059669; }
        .task-card-icon.info { background: #eff6ff; color: #2563eb; }

        .task-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 4px;
            font-size: 0.8rem;
            color: var(--light-text);
        }

        .task-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .badge-premium {
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .badge-premium.warning { background: #fffbeb; color: #d97706; }
        .badge-premium.danger { background: #fef2f2; color: #dc2626; }
        .badge-premium.success { background: #ecfdf5; color: #059669; }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .sidebar-nav .nav-link {
            color: var(--medium-text);
            padding: 0.85rem 1.25rem;
            border-radius: 12px;
            margin: 0.2rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .sidebar-nav .nav-link:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .sidebar-brand {
            padding: 2.5rem 1.5rem 1.5rem;
            margin-bottom: 1rem;
        }

        .sidebar-nav .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.2rem;
            margin-right: 0.85rem;
        }

        .progress-bar-custom {
            height: 10px;
            border-radius: 5px;
            background-color: #f1f5f9;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 5px;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--light-text);
        }

        @media (max-width: 768px) {
            .sidebar { margin-left: -260px; }
            .sidebar.show { margin-left: 0; }
            .main-content { margin-left: 0; }
        }

        /* Notificações & Header Icons */
        .header-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--white);
            color: var(--medium-text);
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid var(--border-color);
        }
        .header-icon:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            transform: translateY(-2px);
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid var(--white);
            padding: 0 4px;
        }
        .notification-item {
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 4px 8px;
        }
        .notification-item:hover {
            background-color: var(--light-bg);
        }

        /* Sidebar Toggle & Overlay */
        .sidebar-toggle {
            background: var(--white);
            border: 1px solid var(--border-color);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: var(--medium-text);
            transition: all 0.2s ease;
        }
        .sidebar-toggle:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
            border-color: var(--primary-color);
        }
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
        }
        .sidebar-overlay.show {
            display: block;
        }

        /* Form Premium Styles */
        .form-label {
            font-weight: 600;
            color: var(--medium-text);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: var(--light-bg);
            box-shadow: none;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: var(--white);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
        
        .form-control::placeholder {
            color: var(--light-text);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px -1px rgba(16, 185, 129, 0.3);
            background: linear-gradient(135deg, var(--primary-dark) 0%, #047857 100%);
        }

        .btn-secondary {
            background-color: var(--white);
            color: var(--medium-text);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: var(--light-bg);
            color: var(--dark-text);
            border-color: var(--medium-text);
            transform: translateY(-2px);
        }

        .cursor-pointer {
            cursor: pointer !important;
        }
    </style>
</head>
<body>
    <!-- Overlay para mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/logo-tasklean-icon.svg') }}" alt="Tasklean" width="32" height="32" class="me-2">
                    <h4 class="mb-0 text-success fw-bold">
                        Tasklean
                    </h4>
                </div>
                <button class="sidebar-toggle d-md-none" id="sidebarClose">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
        
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('times.*') ? 'active' : '' }}" href="{{ route('times.index') }}">
                    <i class="bi bi-people"></i> Times
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('projetos.*') ? 'active' : '' }}" href="{{ route('projetos.index') }}">
                    <i class="bi bi-folder"></i> Projetos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tarefas.*') ? 'active' : '' }}" href="{{ route('tarefas.index') }}">
                    <i class="bi bi-list-task"></i> Tarefas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('compromissos.*') ? 'active' : '' }}" href="{{ route('compromissos.index') }}">
                    <i class="bi bi-calendar-event"></i> Compromissos
                </a>
            </li>
            @php
                $userSettings = \App\Models\UserSettings::getForUser(auth()->id());
            @endphp
            
            @if($userSettings->enable_kanban)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kanban') ? 'active' : '' }}" href="{{ route('kanban') }}">
                    <i class="bi bi-kanban"></i> Kanban
                </a>
            </li>
            @endif
            
            @if($userSettings->enable_sprints)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('sprints') ? 'active' : '' }}" href="{{ route('sprints') }}">
                    <i class="bi bi-calendar-week"></i> Sprints
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}" href="{{ route('relatorios.index') }}">
                    <i class="bi bi-file-earmark-text"></i> Relatórios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.system') }}">
                    <i class="bi bi-gear"></i> Configurações
                </a>
            </li>
        </ul>
        
        <!-- User info at bottom -->
        <div class="p-3 border-top" style="border-color: var(--border-color) !important;">
            <div class="d-flex align-items-center">
                <div class="user-avatar me-2">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <div class="fw-semibold" style="font-size: 0.9rem;">{{ auth()->user()->name ?? 'Usuário' }}</div>
                    <div class="text-muted" style="font-size: 0.8rem;">Logado</div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="sidebar-toggle me-3" id="sidebarToggle" title="Alternar sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <!-- Command Menu Shortcut -->
                    <div class="d-none d-lg-flex align-items-center bg-white border rounded-pill px-3 py-1.5 me-3" style="cursor: pointer; font-size: 0.85rem; transition: all 0.2s;" onclick="window.dispatchEvent(new KeyboardEvent('keydown', {key: 'k', metaKey: true, ctrlKey: true}))" onmouseover="this.style.borderColor='var(--primary-color)'" onmouseout="this.style.borderColor='var(--border-color)'">
                        <i class="bi bi-search text-muted me-2"></i>
                        <span class="text-muted me-4">Buscar ou executar comando...</span>
                        <div class="d-flex gap-1">
                            <kbd class="bg-light border rounded px-1.5 text-xs text-muted font-sans" style="font-size: 0.7rem;">⌘</kbd>
                            <kbd class="bg-light border rounded px-1.5 text-xs text-muted font-sans" style="font-size: 0.7rem;">K</kbd>
                        </div>
                    </div>
                    <div class="d-flex align-items-center d-md-none">
                        <img src="{{ asset('images/logo-tasklean-compact.svg') }}" alt="Tasklean" height="24" class="me-2">
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <!-- Botões das telas (actions) -->
                    <div class="me-3">
                        @yield('actions')
                    </div>
                    
                    <!-- Header Icons -->
                    <div class="d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-2">
                            <div class="header-icon position-relative" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notificações">
                                <i class="bi bi-bell"></i>
                                <span class="notification-badge" id="notification-count">0</span>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 350px; max-height: 400px; overflow-y: auto;">
                                <li><h6 class="dropdown-header">Notificações</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <div id="notifications-list">
                                    <li class="px-3 py-2 text-muted text-center">
                                        <i class="bi bi-bell-slash"></i><br>
                                        Nenhuma notificação
                                    </li>
                                </div>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center" href="javascript:void(0)" onclick="markAllAsRead()">
                                        <i class="bi bi-check-all"></i> Marcar todas como lidas
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <div class="user-avatar me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><h6 class="dropdown-header">{{ auth()->user()->name ?? 'Usuário' }}</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('settings.system') }}">
                                        <i class="bi bi-gear me-2"></i> Configurações
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4">
            <!-- Notificações -->
            @include('components.notifications')

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> 
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Command Menu Component -->
    <livewire:command-menu />

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Auto-hide notifications after 8 seconds
        setTimeout(function() {
            const notifications = document.querySelectorAll('#notifications-container .toast');
            notifications.forEach(function(notification) {
                const bsToast = new bootstrap.Toast(notification);
                bsToast.hide();
            });
        }, 8000);

        // Sidebar functionality
        let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                sidebarCollapsed = !sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
                
                if (sidebarCollapsed) {
                    sidebar.style.marginLeft = '-260px';
                    mainContent.style.marginLeft = '0';
                } else {
                    sidebar.style.marginLeft = '0';
                    mainContent.style.marginLeft = '260px';
                }
            }
        }
        
        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth > 768 && sidebarCollapsed) {
                sidebar.style.marginLeft = '-260px';
                mainContent.style.marginLeft = '0';
            }
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            initializeSidebar();
            
            const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    if (!confirm('Tem certeza que deseja excluir este item?')) {
                        e.preventDefault();
                    }
                });
            });

            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function(toastElement) {
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: false
                });
                toast.show();
            });
            
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
            
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                if (window.innerWidth > 768) {
                    if (sidebarCollapsed) {
                        sidebar.style.marginLeft = '-260px';
                        mainContent.style.marginLeft = '0';
                    } else {
                        sidebar.style.marginLeft = '0';
                        mainContent.style.marginLeft = '260px';
                    }
                    sidebar.classList.remove('show');
                    document.getElementById('sidebarOverlay').classList.remove('show');
                } else {
                    sidebar.style.marginLeft = '0';
                    mainContent.style.marginLeft = '0';
                }
            });
        });

        // Notifications logic
        unreadNotifications = [];
        function updateNotificationsDropdown(notifications) {
            const container = document.getElementById('notifications-list');
            const countBadge = document.getElementById('notification-count');
            
            if (notifications.length === 0) {
                container.innerHTML = `
                    <li class="px-3 py-2 text-muted text-center">
                        <i class="bi bi-bell-slash"></i><br>
                        Nenhuma notificação
                    </li>
                `;
                countBadge.style.display = 'none';
                return;
            }

            let html = '';
            notifications.forEach(function(notification, index) {
                const iconMap = {
                    'success': 'bi-check-circle text-success',
                    'warning': 'bi-exclamation-triangle text-warning',
                    'danger': 'bi-x-circle text-danger',
                    'info': 'bi-info-circle text-info'
                };

                const timeAgo = new Date(notification.timestamp).toLocaleTimeString();
                
                html += `
                    <li class="dropdown-item-text px-3 py-2 border-bottom notification-item" id="notification-${notification.hash}">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-2">
                                <i class="bi ${iconMap[notification.type]}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">${notification.title}</div>
                                <div class="small text-muted">${notification.message}</div>
                                <div class="small text-muted">${timeAgo}</div>
                                ${notification.action ? `<a href="${notification.action.url}" class="btn btn-sm ${notification.action.class || 'btn-primary'} mt-1">${notification.action.text}</a>` : ''}
                            </div>
                            <div class="flex-shrink-0">
                                <button class="btn btn-sm btn-outline-secondary" onclick="markAsRead('${notification.hash}'); event.stopPropagation();" title="Marcar como lida">
                                    <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                `;
            });

            container.innerHTML = html;
            const hasNotifications = notifications.length > 0;
            countBadge.textContent = notifications.length;
            countBadge.style.display = hasNotifications ? 'flex' : 'none';
        }

        function markAsRead(hash) {
            fetch('/dashboard/dismiss-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ hash: hash })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    unreadNotifications = unreadNotifications.filter(n => n.hash !== hash);
                    updateNotificationsDropdown(unreadNotifications);
                }
            })
            .catch(error => {
                console.log('Erro ao descartar notificação:', error);
            });
        }

        function markAllAsRead() {
            fetch('/dashboard/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    unreadNotifications = [];
                    updateNotificationsDropdown(unreadNotifications);
                }
            })
            .catch(error => {
                console.log('Erro ao marcar notificações como lidas:', error);
            });
        }

        function addNotificationToDropdown(notification) {
            const exists = unreadNotifications.some(n => n.hash === notification.hash);
            if (!exists) {
                unreadNotifications.unshift(notification);
                updateNotificationsDropdown(unreadNotifications);
            }
        }

        @if(request()->routeIs('dashboard'))
        let notificationInterval;
        
        function checkNotifications() {
            fetch('/dashboard/check-notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.enabled && data.notifications.length > 0) {
                        data.notifications.forEach(function(notification) {
                            addNotificationToDropdown(notification);
                            createRealtimeNotification(
                                notification.type,
                                notification.title,
                                notification.message,
                                notification.action
                            );
                        });
                    }
                    
                    if (!data.enabled && notificationInterval) {
                        clearInterval(notificationInterval);
                        notificationInterval = null;
                    }
                    
                    if (data.enabled && data.frequency && notificationInterval) {
                        const currentFrequency = data.frequency * 60000;
                        clearInterval(notificationInterval);
                        notificationInterval = setInterval(checkNotifications, currentFrequency);
                    }
                })
                .catch(error => {
                    console.log('Erro ao verificar notificações:', error);
                });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/dashboard/check-notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.enabled) {
                        const frequency = data.frequency || 60;
                        const intervalMs = frequency * 60000;
                        
                        if (data.notifications.length > 0) {
                            data.notifications.forEach(function(notification) {
                                addNotificationToDropdown(notification);
                            });
                        }
                        
                        notificationInterval = setInterval(checkNotifications, intervalMs);
                    }
                })
                .catch(error => {
                    console.log('Erro ao inicializar notificações:', error);
                });
        });
        @endif

        // Global Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            // Ignore if user is typing in an input, textarea, or contenteditable element
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName) || e.target.isContentEditable) {
                return;
            }

            // Ignore if any modifier key is pressed (except for specific shortcuts if needed)
            if (e.ctrlKey || e.metaKey || e.altKey || e.shiftKey) {
                return;
            }

            const key = e.key.toLowerCase();
            const shortcuts = {
                'd': "{{ route('dashboard') }}",
                'p': "{{ route('projetos.index') }}",
                't': "{{ route('tarefas.index') }}",
                'n': "{{ route('tarefas.create') }}",
                'k': "{{ route('kanban') }}",
                's': "{{ route('sprints') }}",
                'e': "{{ route('times.index') }}",
                'c': "{{ route('compromissos.index') }}",
                'r': "{{ route('relatorios.index') }}",
            };

            if (shortcuts[key]) {
                window.location.href = shortcuts[key];
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
