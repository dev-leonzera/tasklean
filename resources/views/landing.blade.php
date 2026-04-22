<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TaskLean - Gestão Ágil de Projetos</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-tasklean-icon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-tasklean-icon.svg') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --secondary-color: #718096;
            --success-color: #38a169;
            --warning-color: #ed8936;
            --danger-color: #e53e3e;
            --info-color: #3182ce;
            --light-bg: #f7fafc;
            --dark-bg: #1a202c;
            --gradient-primary: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-secondary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-accent: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            font-size: 16px;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            background: var(--gradient-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .btn-hero {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-hero-primary {
            background: white;
            color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            color: var(--primary-color);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background: white;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-bg);
            text-align: center;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .feature-icon.primary { background: var(--gradient-primary); }
        .feature-icon.secondary { background: var(--gradient-secondary); }
        .feature-icon.accent { background: var(--gradient-accent); }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--secondary-color);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* About Section */
        .about-section {
            background: var(--gradient-secondary);
            padding: 5rem 0;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .about-content {
            position: relative;
            z-index: 2;
        }

        .about-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .about-description {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            margin-bottom: 2rem;
            text-align: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .about-feature-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .about-feature-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
        }

        .about-feature-item i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
            color: white;
        }

        .about-feature-item h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .about-feature-item p {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        /* CTA Section */
        .cta-section {
            background: var(--dark-bg);
            padding: 5rem 0;
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-subtitle {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        /* Footer */
        .footer {
            background: #0f1419;
            padding: 3rem 0 2rem;
            color: white;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Floating elements */
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .floating-element:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-element:nth-child(2) { top: 60%; right: 10%; animation-delay: 2s; }
        .floating-element:nth-child(3) { bottom: 20%; left: 20%; animation-delay: 4s; }

        /* Feature Preview Cards */
        .feature-preview-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .feature-preview-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .feature-preview-card i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .feature-preview-card span {
            font-size: 1.1rem;
            font-weight: 600;
            display: block;
        }

        /* Pricing Section */
        .pricing-section {
            padding: 5rem 0;
            background: var(--light-bg);
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .pricing-card.featured {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .pricing-card.featured::before {
            content: 'Mais Popular';
            position: absolute;
            top: 20px;
            right: -35px;
            background: var(--gradient-primary);
            color: white;
            padding: 5px 40px;
            font-size: 0.85rem;
            font-weight: 600;
            transform: rotate(45deg);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .pricing-card.featured:hover {
            transform: scale(1.08) translateY(-10px);
        }

        .pricing-plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 0.5rem;
        }

        .pricing-plan-description {
            color: var(--secondary-color);
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .pricing-price {
            margin-bottom: 2rem;
        }

        .pricing-amount {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--dark-bg);
            line-height: 1;
        }

        .pricing-currency {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--secondary-color);
            vertical-align: top;
        }

        .pricing-period {
            font-size: 1rem;
            color: var(--secondary-color);
            margin-top: 0.5rem;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            text-align: left;
        }

        .pricing-features li {
            padding: 0.75rem 0;
            color: var(--dark-bg);
            font-size: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .pricing-features li i {
            color: var(--primary-color);
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .pricing-button {
            width: 100%;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-color);
            display: inline-block;
            margin-top: 1.5rem;
        }

        .pricing-button.primary {
            background: var(--gradient-primary);
            color: white;
        }

        .pricing-button.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .pricing-button.outline {
            background: transparent;
            color: var(--primary-color);
        }

        .pricing-button.outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .feature-card {
                margin-bottom: 2rem;
            }

            .about-title {
                font-size: 2rem;
            }

            .about-description {
                font-size: 1rem;
            }

            .about-features {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .pricing-card.featured {
                transform: scale(1);
            }

            .pricing-card.featured:hover {
                transform: translateY(-10px);
            }

            .pricing-amount {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-tasklean-icon.svg') }}" alt="TaskLean" width="32" height="32" class="me-2">
                <span class="fw-bold text-success fs-4">TaskLean</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Recursos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Preços</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-success ms-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Entrar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <!-- Floating elements -->
        <div class="floating-element">
            <i class="bi bi-kanban" style="font-size: 3rem; color: white;"></i>
        </div>
        <div class="floating-element">
            <i class="bi bi-calendar-event" style="font-size: 2.5rem; color: white;"></i>
        </div>
        <div class="floating-element">
            <i class="bi bi-list-task" style="font-size: 2rem; color: white;"></i>
        </div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content fade-in-up">
                        <h1 class="hero-title">
                            Gerencie seus projetos de forma <span class="text-warning">ágil</span> e <span class="text-warning">eficiente</span>
                        </h1>
                        <p class="hero-subtitle">
                            TaskLean é a plataforma completa para gestão de projetos, tarefas e compromissos. 
                            Organize seu trabalho com metodologias ágeis e aumente sua produtividade.
                        </p>
                        <div class="hero-buttons">
                            <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                                <i class="bi bi-rocket-takeoff me-2"></i>
                                Começar Agora
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <!-- Ilustração com ícones representando funcionalidades -->
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 400px;">
                            <div class="row g-4 w-100">
                                <div class="col-6">
                                    <div class="feature-preview-card">
                                        <i class="bi bi-kanban"></i>
                                        <span>Kanban</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="feature-preview-card">
                                        <i class="bi bi-calendar-week"></i>
                                        <span>Sprints</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="feature-preview-card">
                                        <i class="bi bi-list-task"></i>
                                        <span>Tarefas</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="feature-preview-card">
                                        <i class="bi bi-graph-up"></i>
                                        <span>Métricas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Recursos Poderosos</h2>
                    <p class="section-subtitle">
                        Tudo que você precisa para gerenciar projetos de forma profissional e eficiente
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon primary">
                            <i class="bi bi-kanban"></i>
                        </div>
                        <h3 class="feature-title">Quadro Kanban</h3>
                        <p class="feature-description">
                            Visualize o progresso das suas tarefas com nosso quadro Kanban intuitivo. 
                            Arraste e solte para mover tarefas entre colunas facilmente.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon secondary">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <h3 class="feature-title">Sprints Ágeis</h3>
                        <p class="feature-description">
                            Organize seu trabalho em sprints e acompanhe o progresso com métricas 
                            detalhadas e relatórios de velocidade da equipe.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon accent">
                            <i class="bi bi-list-task"></i>
                        </div>
                        <h3 class="feature-title">Gestão de Tarefas</h3>
                        <p class="feature-description">
                            Crie, organize e acompanhe tarefas com prioridades, prazos e 
                            responsáveis. Mantenha tudo organizado e nunca perca o foco.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon primary">
                            <i class="bi bi-folder"></i>
                        </div>
                        <h3 class="feature-title">Projetos Organizados</h3>
                        <p class="feature-description">
                            Agrupe tarefas em projetos e mantenha uma visão clara do progresso. 
                            Configure status personalizados e acompanhe métricas importantes.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon secondary">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h3 class="feature-title">Compromissos</h3>
                        <p class="feature-description">
                            Gerencie reuniões, deadlines e compromissos importantes. 
                            Integre com seu calendário e nunca perca um prazo importante.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon accent">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h3 class="feature-title">Relatórios e Métricas</h3>
                        <p class="feature-description">
                            Acompanhe sua produtividade com dashboards detalhados, 
                            gráficos de progresso e relatórios personalizáveis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-content">
                <h2 class="about-title">Sobre o TaskLean</h2>
                <p class="about-description">
                    O TaskLean foi criado com a missão de simplificar a gestão de projetos e aumentar a produtividade 
                    de equipes e profissionais. Nossa plataforma combina metodologias ágeis com uma interface intuitiva, 
                    permitindo que você se concentre no que realmente importa: entregar resultados de qualidade.
                </p>
                <p class="about-description">
                    Acreditamos que a gestão de projetos não precisa ser complicada. Por isso, desenvolvemos uma 
                    solução completa, acessível e fácil de usar, que se adapta às necessidades de cada usuário.
                </p>
                
                <div class="about-features">
                    <div class="about-feature-item">
                        <i class="bi bi-lightbulb"></i>
                        <h4>Inovação</h4>
                        <p>Utilizamos as melhores práticas e tecnologias para oferecer uma experiência única</p>
                    </div>
                    <div class="about-feature-item">
                        <i class="bi bi-shield-check"></i>
                        <h4>Confiabilidade</h4>
                        <p>Seus dados estão seguros conosco, com backup automático e criptografia</p>
                    </div>
                    <div class="about-feature-item">
                        <i class="bi bi-people"></i>
                        <h4>Foco no Usuário</h4>
                        <p>Desenvolvido pensando em você, com feedback constante da nossa comunidade</p>
                    </div>
                    <div class="about-feature-item">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h4>Evolução Constante</h4>
                        <p>Estamos sempre melhorando e adicionando novos recursos baseados em necessidades reais</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Planos e Preços</h2>
                    <p class="section-subtitle">
                        Escolha o plano ideal para suas necessidades. Todos os planos incluem suporte completo e atualizações.
                    </p>
                </div>
            </div>
            
            <div class="row g-4 justify-content-center">
                <!-- Plano Básico -->
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <h3 class="pricing-plan-name">Básico</h3>
                        <p class="pricing-plan-description">Perfeito para começar</p>
                        <div class="pricing-price">
                            <span class="pricing-currency">R$</span>
                            <span class="pricing-amount">0</span>
                            <div class="pricing-period">por mês</div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> Até 3 projetos</li>
                            <li><i class="bi bi-check-circle-fill"></i> Até 50 tarefas</li>
                            <li><i class="bi bi-check-circle-fill"></i> Quadro Kanban</li>
                            <li><i class="bi bi-check-circle-fill"></i> Relatórios básicos</li>
                            <li><i class="bi bi-check-circle-fill"></i> Suporte por email</li>
                        </ul>
                        <a href="{{ route('register') }}" class="pricing-button outline">
                            Começar Grátis
                        </a>
                    </div>
                </div>
                
                <!-- Plano Profissional -->
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card featured">
                        <h3 class="pricing-plan-name">Profissional</h3>
                        <p class="pricing-plan-description">Para profissionais e equipes</p>
                        <div class="pricing-price">
                            <span class="pricing-currency">R$</span>
                            <span class="pricing-amount">29</span>
                            <div class="pricing-period">por mês</div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> Projetos ilimitados</li>
                            <li><i class="bi bi-check-circle-fill"></i> Tarefas ilimitadas</li>
                            <li><i class="bi bi-check-circle-fill"></i> Quadro Kanban avançado</li>
                            <li><i class="bi bi-check-circle-fill"></i> Sprints e métricas</li>
                            <li><i class="bi bi-check-circle-fill"></i> Relatórios detalhados</li>
                            <li><i class="bi bi-check-circle-fill"></i> Compromissos e calendário</li>
                            <li><i class="bi bi-check-circle-fill"></i> Suporte prioritário</li>
                        </ul>
                        <a href="{{ route('register') }}" class="pricing-button primary">
                            Assinar Agora
                        </a>
                    </div>
                </div>
                
                <!-- Plano Empresarial -->
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <h3 class="pricing-plan-name">Empresarial</h3>
                        <p class="pricing-plan-description">Para grandes equipes</p>
                        <div class="pricing-price">
                            <span class="pricing-currency">R$</span>
                            <span class="pricing-amount">99</span>
                            <div class="pricing-period">por mês</div>
                        </div>
                        <ul class="pricing-features">
                            <li><i class="bi bi-check-circle-fill"></i> Tudo do Profissional</li>
                            <li><i class="bi bi-check-circle-fill"></i> Múltiplas equipes</li>
                            <li><i class="bi bi-check-circle-fill"></i> Permissões avançadas</li>
                            <li><i class="bi bi-check-circle-fill"></i> API completa</li>
                            <li><i class="bi bi-check-circle-fill"></i> Integrações personalizadas</li>
                            <li><i class="bi bi-check-circle-fill"></i> Suporte 24/7</li>
                            <li><i class="bi bi-check-circle-fill"></i> Treinamento dedicado</li>
                        </ul>
                        <a href="{{ route('register') }}" class="pricing-button outline">
                            Falar com Vendas
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="text-muted" style="font-size: 0.95rem;">
                        <i class="bi bi-shield-check me-2"></i>
                        Todos os planos incluem garantia de 30 dias. Cancele quando quiser.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="cta-title">Pronto para aumentar sua produtividade?</h2>
                    <p class="cta-subtitle">
                        Junte-se a centenas de profissionais que já transformaram sua gestão de projetos com o TaskLean.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                            <i class="bi bi-rocket-takeoff me-2"></i>
                            Começar Gratuitamente
                        </a>
                        <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Já tenho conta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">TaskLean</div>
                    <p class="text-white-50" style="opacity: 0.9; font-size: 1rem; line-height: 1.6;">
                        A plataforma completa para gestão ágil de projetos. 
                        Organize, execute e acompanhe seus projetos com eficiência.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Produto</h6>
                    <ul class="footer-links">
                        <li><a href="#features">Recursos</a></li>
                        <li><a href="#about">Sobre</a></li>
                        <li><a href="#pricing">Preços</a></li>
                        <li><a href="#">API</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Suporte</h6>
                    <ul class="footer-links">
                        <li><a href="#">Central de Ajuda</a></li>
                        <li><a href="#">Documentação</a></li>
                        <li><a href="#">Contato</a></li>
                        <li><a href="#">Status</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Empresa</h6>
                    <ul class="footer-links">
                        <li><a href="#">Sobre Nós</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Carreiras</a></li>
                        <li><a href="#">Imprensa</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Legal</h6>
                    <ul class="footer-links">
                        <li><a href="#">Privacidade</a></li>
                        <li><a href="#">Termos</a></li>
                        <li><a href="#">Cookies</a></li>
                        <li><a href="#">Segurança</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0" style="opacity: 0.9;">&copy; 2024 TaskLean. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex gap-3 justify-content-md-end">
                        <a href="#" class="text-white-50" style="opacity: 0.8; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.color='var(--primary-color)'" onmouseout="this.style.opacity='0.8'; this.style.color='rgba(255,255,255,0.5)'"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white-50" style="opacity: 0.8; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.color='var(--primary-color)'" onmouseout="this.style.opacity='0.8'; this.style.color='rgba(255,255,255,0.5)'"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-white-50" style="opacity: 0.8; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.color='var(--primary-color)'" onmouseout="this.style.opacity='0.8'; this.style.color='rgba(255,255,255,0.5)'"><i class="bi bi-github"></i></a>
                        <a href="#" class="text-white-50" style="opacity: 0.8; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.color='var(--primary-color)'" onmouseout="this.style.opacity='0.8'; this.style.color='rgba(255,255,255,0.5)'"><i class="bi bi-discord"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.backgroundColor = 'white';
                navbar.style.backdropFilter = 'none';
            }
        });
    </script>
</body>
</html>
