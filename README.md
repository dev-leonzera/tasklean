# ⚡ Tasklean

**Tasklean** é um sistema de gestão ágil de projetos de alto desempenho, focado em desenvolvedores e equipes que buscam uma interface premium, minimalista e extremamente rápida.

![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![Tailwind Version](https://img.shields.io/badge/Tailwind-4.0-38B2AC?style=for-the-badge&logo=tailwind-css)
![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![Livewire Version](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire)

---

## ✨ Diferenciais & Funcionalidades

### 🎨 Interface Premium (Dev-Centric)
- **Glassmorphism UI**: Design moderno com efeitos de transparência, desfoque e profundidade.
- **Micro-animações**: Transições suaves e feedback visual instantâneo para uma UX fluida.
- **Dark Mode Nativo**: Suporte completo e otimizado para temas claros e escuros.
- **Ilustrações 3D**: Experiência visual rica com ilustrações personalizadas para estados vazios e erros.

### 🚀 Produtividade de Elite
- **Menu de Comando (Cmd+K)**: Navegação ultra-rápida via teclado para qualquer módulo do sistema.
- **Kanban Dinâmico**: Gestão visual de tarefas com estados configuráveis e filtros inteligentes.
- **Gestão de Sprints**: Planejamento e execução de ciclos ágeis integrados ao fluxo de trabalho.
- **Atalhos de Teclado**: Atalhos globais para criação rápida de tarefas e troca de contextos.

### 👥 Colaboração & Times
- **Módulo de Equipes**: Criação de múltiplos times com gestão independente de projetos.
- **RBAC (Role-Based Access Control)**: Controle de acesso granular (Admin, Editor, Viewer).
- **Gestão de Membros**: Sistema de convites e atribuição de responsabilidades.

### 🔔 Notificações & Dashboards
- **Inteligência de Prazos**: Alertas críticos para tarefas vencidas ou próximas do vencimento.
- **Dashboard 360º**: Visão centralizada de métricas de produtividade, tarefas e compromissos.
- **Central de Notificações**: Sistema persistente com cache para alta performance.

---

## 🛠️ Stack Tecnológica

- **Core**: [Laravel 12.x](https://laravel.com)
- **Frontend**: [Livewire 3](https://livewire.laravel.com) + [Flux UI](https://fluxui.dev) + [Volt](https://livewire.laravel.com/docs/volt)
- **Styling**: [Tailwind CSS 4.0](https://tailwindcss.com) (Otimizado com Vite)
- **Banco de Dados**: SQLite (Desenvolvimento) / PostgreSQL (Suportado)
- **Pagamentos**: [Laravel Cashier (Stripe)](https://laravel.com/docs/billing)
- **Asset Bundler**: [Vite 6](https://vitejs.dev)

---

## 🚀 Instalação e Configuração

### 1. Requisitos
- PHP 8.2+
- Composer
- Node.js & NPM

### 2. Setup Inicial
```bash
# Clone o repositório
git clone https://github.com/leonzera/tasklean.git
cd tasklean

# Instale as dependências
composer install
npm install
```

### 3. Configuração de Ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Banco de Dados
```bash
# O sistema utiliza SQLite por padrão em desenvolvimento
touch database/database.sqlite
php artisan migrate --seed
```

### 5. Rodar em Desenvolvimento
O projeto utiliza um comando unificado para rodar Servidor, Queue e Vite simultaneamente:
```bash
composer dev
```
O sistema estará disponível em: `http://localhost:8000`

---

## 🧪 Testes
O projeto possui uma suíte completa de testes automatizados:
```bash
composer test
```

---

## 📝 Convenções de Commits
Seguimos o padrão **Conventional Commits**:
- `feat:` Nova funcionalidade
- `fix:` Correção de bug
- `refactor:` Melhoria de código sem alteração funcional
- `docs:` Alterações na documentação
- `style:` Ajustes visuais e de design (CSS/Blade)
- `test:` Adição ou correção de testes

---

## 🤝 Contribuição
1. Faça um **Fork** do projeto
2. Crie uma **Branch** para sua feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** suas mudanças (`git commit -m 'feat: add some AmazingFeature'`)
4. **Push** para a branch (`git push origin feature/AmazingFeature`)
5. Abra um **Pull Request**

---

Desenvolvido por [leonzera](https://github.com/leonzera)

