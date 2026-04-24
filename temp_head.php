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
