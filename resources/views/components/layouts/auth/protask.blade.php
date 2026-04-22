<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Faça login no TaskLean - Sistema de gerenciamento de tarefas e projetos">
    <meta name="theme-color" content="#10b981">
    <title>{{ $title ?? 'Login - TaskLean' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-tasklean-icon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-tasklean-icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f0fdf4;
            --dark-bg: #064e3b;
            --sidebar-bg: #ffffff;
            --sidebar-text: #374151;
            --sidebar-active: #10b981;
            --header-bg: #ffffff;
            --border-color: #d1fae5;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Dark Mode Styles */
        [data-bs-theme="dark"] {
            --light-bg: #064e3b;
            --sidebar-bg: #065f46;
            --sidebar-text: #d1fae5;
            --sidebar-active: #10b981;
            --header-bg: #065f46;
            --border-color: #047857;
        }

        [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            color: #d1fae5;
        }

        /* Login Card Styles */
        .login-card {
            border-radius: 20px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .login-card {
            background: rgba(6, 78, 59, 0.95);
            color: #d1fae5;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        /* Form Styles */
        .form-control {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25) !important;
        }

        [data-bs-theme="dark"] .form-control {
            background-color: #047857;
            border-color: #047857;
            color: #d1fae5;
        }

        [data-bs-theme="dark"] .form-control:focus {
            background-color: #047857;
            border-color: var(--primary-color) !important;
        }

        .form-label {
            font-weight: 600;
            color: var(--sidebar-text);
            margin-bottom: 0.5rem;
        }

        [data-bs-theme="dark"] .form-label {
            color: #d1fae5;
        }

        /* Button Styles */
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        }

        .btn-login:focus {
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25) !important;
        }

        /* Checkbox Styles */
        .form-check-input:checked {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        /* Link Styles */
        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Loading Animation */
        .btn.loading {
            position: relative;
            color: transparent !important;
        }

        .btn.loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Alert Styles */
        .alert {
            border-radius: 12px;
            border: none;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                margin: 1rem;
                border-radius: 16px;
            }
            
            .container-fluid {
                padding: 1rem;
            }
        }

        /* Logo Animation */
        .logo-icon {
            animation: pulse 2s infinite;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .logo-icon:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.15));
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Dark mode logo adjustments */
        [data-bs-theme="dark"] .logo-icon {
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        [data-bs-theme="dark"] .logo-icon:hover {
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.4));
        }

        /* Footer Text */
        .footer-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        [data-bs-theme="dark"] .footer-text {
            color: rgba(255, 255, 255, 0.6);
        }

    </style>
</head>
<body>
    {{ $slot }}

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Add loading state to form submission
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    form.addEventListener('submit', function() {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                    });
                }
            });
        });

        // Dark mode detection and application
        document.addEventListener('DOMContentLoaded', function() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const savedTheme = localStorage.getItem('theme');
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
        });

        // Password toggle functionality
        function togglePassword(fieldId = 'password') {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + 'ToggleIcon');
            
            if (passwordInput && toggleIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.className = 'bi bi-eye-slash';
                    toggleIcon.setAttribute('aria-label', 'Ocultar senha');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.className = 'bi bi-eye';
                    toggleIcon.setAttribute('aria-label', 'Mostrar senha');
                }
            }
        }

        // Enhanced form validation feedback
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[required]');
            inputs.forEach(function(input) {
                input.addEventListener('invalid', function(e) {
                    e.preventDefault();
                    this.classList.add('is-invalid');
                    
                    // Add custom validation message
                    const feedback = this.parentNode.querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.style.display = 'block';
                    }
                });
                
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });
        });

        // Keyboard navigation improvements
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                const form = e.target.closest('form');
                if (form) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.click();
                    }
                }
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
