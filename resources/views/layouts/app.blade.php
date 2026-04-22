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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #4c51bf;
            --secondary-color: #718096;
            --success-color: #38a169;
            --warning-color: #ed8936;
            --danger-color: #e53e3e;
            --info-color: #3182ce;
            --light-bg: #f7fafc;
            --dark-bg: #1a202c;
            --sidebar-bg: #ffffff;
            --sidebar-text: #4a5568;
            --sidebar-active: #10b981;
            --header-bg: #ffffff;
            --border-color: #e2e8f0;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            font-size: 14px;
            line-height: 1.5;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 250px;
            transition: margin-left 0.3s ease;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            background-color: var(--light-bg);
        }

        .header {
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .status-badge {
            font-size: 0.8em;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease-in-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .metric-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .metric-card.primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .metric-card.success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .metric-card.warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .metric-card.danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }

        .metric-card.info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .metric-card.secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .metric-card.dark {
            background: linear-gradient(135deg, #343a40 0%, #212529 100%);
            color: white;
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0;
        }

        .sidebar-brand h4 {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color);
            margin: 0;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .sidebar-nav .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            margin: 0;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            background-color: #f7fafc;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .sidebar-nav .nav-link.active {
            background-color: #f0f4ff;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }


        .header-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f7fafc;
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            position: relative;
        }

        .header-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: -2px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            text-align: center;
            line-height: 1;
            padding: 0;
            margin: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: flex-start;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 1rem;
            margin-top: 0.5rem;
            flex-shrink: 0;
        }

        .activity-dot.success { background-color: var(--success-color); }
        .activity-dot.info { background-color: var(--info-color); }
        .activity-dot.warning { background-color: var(--warning-color); }
        .activity-dot.danger { background-color: var(--danger-color); }

        .progress-item {
            margin-bottom: 1rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--sidebar-text);
        }

        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background-color: #e2e8f0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-fill.primary { background-color: var(--primary-color); }
        .progress-fill.success { background-color: var(--success-color); }
        .progress-fill.warning { background-color: var(--warning-color); }
        .progress-fill.danger { background-color: var(--danger-color); }
        .progress-fill.info { background-color: var(--info-color); }


        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
                width: 250px;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Estilos para o dropdown de notificações */
        .notification-item {
            transition: background-color 0.2s ease;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .notification-item:last-child {
            border-bottom: none !important;
        }
        
        #notificationsDropdown {
            border: none;
            background: transparent;
        }
        
        #notificationsDropdown:hover {
            background-color: rgba(0,0,0,0.1);
        }
        
        #notification-count {
            font-size: 0.7em;
            min-width: 18px;
            height: 18px;
            line-height: 1;
            display: flex !important;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0;
            margin: 0;
        }

        /* Ajuste para números com mais de um dígito */
        .notification-badge:not(:empty) {
            min-width: auto;
            padding: 0 2px;
        }
        
        /* Estilos para sidebar colapsível */
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar.collapsed {
            margin-left: -250px;
        }
        
        .main-content {
            min-height: 100vh;
            transition: all 0.3s ease;
            margin-left: 250px;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 0.375rem;
        }
        
        .sidebar-toggle:hover {
            color: #495057;
            background-color: #f8f9fa;
        }
        
        .sidebar-toggle:active {
            transform: scale(0.95);
        }
        
        .sidebar-brand {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1rem;
        }
        
        .sidebar-nav .nav-link {
            color: #495057;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0.5rem;
            transition: all 0.3s ease;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: #e9ecef;
            color: #212529;
        }
        
        .sidebar-nav .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        
        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 0.5rem;
        }
        
        /* Overlay para mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
                width: 250px;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block !important;
            }
        }
        
        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none;
            }
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
                        <!-- Documentation -->
                        <!-- <a href="#" class="header-icon me-2" title="Documentação">
                            <i class="bi bi-book"></i>
                        </a> -->
                        
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
                                    <a class="dropdown-item text-center" href="#" onclick="markAllAsRead()">
                                        <i class="bi bi-check-all"></i> Marcar todas como lidas
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Messages -->
                        <!-- <div class="header-icon me-2" title="Mensagens">
                            <i class="bi bi-envelope"></i>
                        </div> -->
                        
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
                // Mobile behavior
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                // Desktop behavior
                sidebarCollapsed = !sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
                
                if (sidebarCollapsed) {
                    sidebar.style.marginLeft = '-250px';
                    mainContent.style.marginLeft = '0';
                } else {
                    sidebar.style.marginLeft = '0';
                    mainContent.style.marginLeft = '250px';
                }
            }
        }
        
        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth > 768 && sidebarCollapsed) {
                sidebar.style.marginLeft = '-250px';
                mainContent.style.marginLeft = '0';
            }
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
        
        // Confirm delete actions
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sidebar
            initializeSidebar();
            
            const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    if (!confirm('Tem certeza que deseja excluir este item?')) {
                        e.preventDefault();
                    }
                });
            });

            // Initialize all toasts
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function(toastElement) {
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: false
                });
                toast.show();
            });
            
            // Sidebar event listeners
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
            
            // Handle window resize
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                const overlay = document.getElementById('sidebarOverlay');
                
                if (window.innerWidth > 768) {
                    // Desktop: remove mobile classes
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                } else {
                    // Mobile: reset desktop state
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                    sidebarCollapsed = false;
                }
            });
        });

        // Função para criar notificação em tempo real
        function createRealtimeNotification(type, title, message, action = null) {
            const container = document.getElementById('realtime-notifications');
            const toastId = 'toast-' + Date.now();
            
            const iconMap = {
                'success': 'bi-check-circle',
                'warning': 'bi-exclamation-triangle',
                'danger': 'bi-x-circle',
                'info': 'bi-info-circle'
            };

            const bgClassMap = {
                'success': 'bg-success text-white',
                'warning': 'bg-warning text-dark',
                'danger': 'bg-danger text-white',
                'info': 'bg-info text-white'
            };

            const toastHtml = `
                <div id="${toastId}" class="toast show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header ${bgClassMap[type]}">
                        <i class="bi ${iconMap[type]} me-2"></i>
                        <strong class="me-auto">${title}</strong>
                        <small class="text-muted">${new Date().toLocaleTimeString()}</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                        ${action ? `<div class="mt-2"><a href="${action.url}" class="btn btn-sm ${action.class || 'btn-primary'}">${action.text}</a></div>` : ''}
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 8000
            });
            toast.show();

            // Remove o elemento após ser escondido
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        // Array para armazenar notificações não lidas
        let unreadNotifications = [];

        // Função para atualizar o dropdown de notificações
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
                    <li class="dropdown-item-text px-3 py-2 border-bottom notification-item" data-index="${index}">
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
                                <button class="btn btn-sm btn-outline-secondary" onclick="markAsRead(${index})" title="Marcar como lida">
                                    <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                `;
            });

            container.innerHTML = html;
            countBadge.textContent = notifications.length;
            countBadge.style.display = notifications.length > 0 ? 'flex' : 'none';
        }

        // Função para marcar notificação como lida
        function markAsRead(index) {
            unreadNotifications.splice(index, 1);
            updateNotificationsDropdown(unreadNotifications);
        }

        // Função para marcar todas como lidas
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

        // Função para adicionar nova notificação ao dropdown
        function addNotificationToDropdown(notification) {
            unreadNotifications.unshift(notification);
            updateNotificationsDropdown(unreadNotifications);
        }

        // Verificação automática de notificações baseada nas configurações do usuário
        @if(request()->routeIs('dashboard'))
        let notificationInterval;
        
        function checkNotifications() {
            fetch('/dashboard/check-notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.enabled && data.notifications.length > 0) {
                        data.notifications.forEach(function(notification) {
                            // Adicionar ao dropdown
                            addNotificationToDropdown(notification);
                            
                            // Criar toast também
                            createRealtimeNotification(
                                notification.type,
                                notification.title,
                                notification.message,
                                notification.action
                            );
                        });
                    }
                    
                    // Se as notificações foram desabilitadas, parar o intervalo
                    if (!data.enabled && notificationInterval) {
                        clearInterval(notificationInterval);
                        notificationInterval = null;
                    }
                    
                    // Atualizar intervalo se a frequência mudou
                    if (data.enabled && data.frequency && notificationInterval) {
                        const currentFrequency = data.frequency * 60000; // converter minutos para milissegundos
                        // Recriar o intervalo com a nova frequência
                        clearInterval(notificationInterval);
                        notificationInterval = setInterval(checkNotifications, currentFrequency);
                    }
                })
                .catch(error => {
                    console.log('Erro ao verificar notificações:', error);
                });
        }
        
        // Inicializar verificação automática baseada nas configurações
        document.addEventListener('DOMContentLoaded', function() {
            // Primeira verificação para obter as configurações
            fetch('/dashboard/check-notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.enabled) {
                        const frequency = data.frequency || 60; // padrão 60 minutos
                        const intervalMs = frequency * 60000; // converter para milissegundos
                        
                        // Processar notificações iniciais se houver
                        if (data.notifications.length > 0) {
                            data.notifications.forEach(function(notification) {
                                addNotificationToDropdown(notification);
                            });
                        }
                        
                        // Iniciar verificação automática
                        notificationInterval = setInterval(checkNotifications, intervalMs);
                        
                        console.log(`Notificações automáticas iniciadas a cada ${frequency} minutos`);
                    }
                })
                .catch(error => {
                    console.log('Erro ao inicializar notificações:', error);
                });
        });
        @endif

    </script>
    
    @yield('scripts')
</body>
</html>
