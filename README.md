# Tasklean - Sistema de Gestão Ágil de Projetos

Sistema completo de gestão de projetos e tarefas desenvolvido com **Laravel MVC** e **PostgreSQL**.

## 🚀 Tecnologias Utilizadas

- **Framework**: Laravel 12.x (MVC)
- **Banco de Dados**: PostgreSQL
- **ORM**: Eloquent
- **Frontend**: Blade Templates + Bootstrap 5
- **Validação**: Form Requests
- **Testes**: Factories e Seeders
- **Pagamentos**: Laravel Cashier (Stripe)

## 📋 Funcionalidades

### Projetos
- ✅ Criar, editar, listar e remover projetos
- ✅ Ativar/inativar projetos
- ✅ Relacionamento com tarefas

### Tarefas
- ✅ Criar, editar, listar e remover tarefas
- ✅ Alterar status das tarefas
- ✅ Listar tarefas atrasadas
- ✅ Listar tarefas em desenvolvimento
- ✅ Relacionamento com projetos

## 🗄️ Estrutura do Banco de Dados

### Tabela `projetos`
- `id` - Chave primária
- `titulo` - Título do projeto
- `ativo` - Status ativo/inativo (boolean)
- `responsavel` - Nome do responsável
- `data_criacao` - Data de criação
- `created_at` - Timestamp de criação
- `updated_at` - Timestamp de atualização

### Tabela `tarefas`
- `id` - Chave primária
- `titulo` - Título da tarefa
- `descricao` - Descrição da tarefa (opcional)
- `status` - Status: pendente, em desenvolvimento, concluida
- `data_criacao` - Data de criação
- `data_vencimento` - Data de vencimento (opcional)
- `responsavel` - Nome do responsável
- `projeto_id` - Chave estrangeira para projetos
- `created_at` - Timestamp de criação
- `updated_at` - Timestamp de atualização

## 🛠️ Instalação e Configuração

### 1. Configurar Banco PostgreSQL

Crie um banco de dados PostgreSQL e configure o arquivo `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tasklean
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

### 2. Instalar Dependências

```bash
composer install
```

### 3. Configurar Aplicação

```bash
php artisan key:generate
```

### 4. Configurar Laravel Cashier (Stripe)

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
STRIPE_KEY=pk_test_sua_chave_publica_aqui
STRIPE_SECRET=sk_test_sua_chave_secreta_aqui
STRIPE_WEBHOOK_SECRET=whsec_seu_webhook_secret_aqui

# Opcional: Configurações adicionais do Cashier
CASHIER_PATH=stripe
CASHIER_CURRENCY=brl
CASHIER_CURRENCY_LOCALE=pt_BR
```

**Nota**: Para obter suas chaves do Stripe:
1. Acesse [Stripe Dashboard](https://dashboard.stripe.com/)
2. Vá em **Developers** > **API keys**
3. Copie as chaves de teste (ou produção) e adicione ao `.env`

### 5. Executar Migrations

```bash
php artisan migrate
```

Isso criará as tabelas necessárias para o Cashier:
- Colunas adicionais na tabela `users` (stripe_id, pm_type, pm_last_four, trial_ends_at)
- Tabela `subscriptions`
- Tabela `subscription_items`

### 6. Popular Banco com Dados de Teste

```bash
php artisan db:seed
```

### 7. Iniciar Servidor

```bash
php artisan serve
```

O sistema estará disponível em: `http://localhost:8000`

## 🖥️ Interface Web

O sistema possui uma interface web completa e responsiva com:

### Páginas Principais
- **Dashboard de Projetos** (`/projetos`) - Lista todos os projetos com estatísticas
- **Gestão de Tarefas** (`/tarefas`) - Lista todas as tarefas com filtros
- **Tarefas Atrasadas** (`/tarefas/atrasadas`) - Visualização de tarefas em atraso
- **Tarefas em Desenvolvimento** (`/tarefas/em-desenvolvimento`) - Tarefas em andamento

### Funcionalidades da Interface
- ✅ **Navegação intuitiva** com sidebar responsiva
- ✅ **Filtros em tempo real** para tarefas (status, projeto, busca)
- ✅ **Formulários validados** com feedback visual
- ✅ **Cards interativos** com hover effects
- ✅ **Badges de status** coloridos
- ✅ **Alertas de sucesso/erro** com auto-dismiss
- ✅ **Confirmação de exclusão** via JavaScript
- ✅ **Design responsivo** para mobile e desktop

### Recursos Visuais
- **Bootstrap 5** para componentes modernos
- **Bootstrap Icons** para ícones consistentes
- **Cores semânticas** (verde=sucesso, vermelho=perigo, amarelo=aviso, azul=info)
- **Layout em cards** para melhor organização
- **Sidebar fixa** para navegação rápida

## 🧪 Testando o Sistema

### Usando a Interface Web

1. **Acesse**: `http://localhost:8000`
2. **Navegue** pelos projetos e tarefas
3. **Crie** novos projetos e tarefas
4. **Teste** os filtros e funcionalidades

## 🚀 Próximos Passos

O sistema está preparado para expansões futuras como:

- 🔐 Sistema de autenticação de usuários
- 📊 Dashboards e relatórios
- 🏷️ Sistema de prioridades para tarefas
- 📅 Calendário de tarefas
- 👥 Gestão de equipes
- 📱 Interface web responsiva
- 🔔 Notificações por email

## 📝 Convenções de Commits

Este projeto segue o padrão Conventional Commits:

- `feat:` nova funcionalidade
- `fix:` correção de bug
- `refactor:` alteração de código sem mudança de comportamento
- `test:` criação/ajuste de testes
- `docs:` documentação

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'feat: add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request
