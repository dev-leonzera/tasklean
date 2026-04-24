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
                                    <a class="dropdown-item text-center" href="javascript:void(0)" onclick="markAllAsRead()">
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
                // Mobile behavior
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                // Desktop behavior
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

            // Atalhos de Teclado Globais (Sequências)
            let keyBuffer = '';
            let keyTimeout;

            document.addEventListener('keydown', function(e) {
                // Não disparar atalhos se o usuário estiver digitando em um input ou textarea
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName) || e.target.isContentEditable) {
                    return;
                }

                // Limpar buffer se demorar muito entre as teclas (500ms)
                clearTimeout(keyTimeout);
                keyBuffer += e.key.toLowerCase();
                keyTimeout = setTimeout(() => { keyBuffer = ''; }, 500);

                const shortcuts = {
                    'd': "{{ route('dashboard') }}",
                    'p': "{{ route('projetos.index') }}",
                    't': "{{ route('tarefas.index') }}",
                    'n': "{{ route('tarefas.create') }}",
                    'k': "{{ route('kanban') }}",
                };

                if (shortcuts[keyBuffer]) {
                    window.location.href = shortcuts[keyBuffer];
                    keyBuffer = '';
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

        // Função para marcar notificação como lida
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
            // Evitar duplicados no array local
            const exists = unreadNotifications.some(n => n.hash === notification.hash);
            if (!exists) {
                unreadNotifications.unshift(notification);
                updateNotificationsDropdown(unreadNotifications);
            }
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
