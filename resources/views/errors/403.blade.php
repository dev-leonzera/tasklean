<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Proibido - Tasklean</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #f59e0b; /* Laranja para 403 */
            --primary-dark: #d97706;
            --secondary-color: #6366f1;
            --dark-text: #0f172a;
            --medium-text: #475569;
            --light-bg: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .error-illustration {
            width: 100%;
            max-width: 450px;
            margin-bottom: 2rem;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            font-size: 1.2rem;
            color: var(--medium-text);
            margin-bottom: 2.5rem;
        }

        .btn-premium {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .bg-blobs {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
        }

        .blob-1 { top: -100px; right: -100px; background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%); }
        .blob-2 { bottom: -150px; left: -150px; }
    </style>
</head>
<body>
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="error-container">
        <img src="{{ asset('images/errors/403.png') }}" alt="403 Forbidden" class="error-illustration">
        <h1>Acesso Negado</h1>
        <p>Você não tem permissão para entrar nesta área. Este conteúdo é restrito.</p>
        <a href="{{ url('/') }}" class="btn-premium">
            <i class="bi bi-house-door-fill"></i> Voltar para o Início
        </a>
    </div>
</body>
</html>
