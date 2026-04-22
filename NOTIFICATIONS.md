# 🔔 Sistema de Notificações - ProTask

## 📋 **Resumo**

O sistema de notificações do ProTask pode funcionar de **duas formas**:

1. **Manual** (atual): Notificações geradas quando o usuário acessa o dashboard
2. **Automática** (configurável): Notificações geradas em intervalos regulares

## ⚙️ **Configuração Atual**

### **Frequência Manual:**
- **Dashboard**: A cada acesso à página principal
- **Botão de notificações**: A cada clique no sino
- **Comando**: `php artisan notifications:check`

### **Tipos de Notificações (Otimizados):**
- ✅ **Tarefas que vencem hoje** (críticas)
- ❌ ~~Tarefas que vencem amanhã~~ (removido para reduzir spam)
- ✅ **Tarefas atrasadas** (críticas)
- ❌ ~~Projetos sem tarefas~~ (desabilitado por enquanto)
- ✅ **Tarefas em desenvolvimento há muito tempo** (apenas 14+ dias)

## 🚀 **Configuração Automática**

### **1. Intervalos Configuráveis:**

```bash
# Arquivo .env
NOTIFICATION_CHECK_INTERVAL=60        # Verificar a cada 60 minutos (1 hora)
TASK_REMINDER_HOURS=24               # Lembrar 24h antes do vencimento
OVERDUE_CHECK_HOURS=1                # Verificar atrasos a cada 1 hora
NOTIFICATION_SCHEDULER_ENABLED=true  # Habilitar scheduler
```

### **2. Iniciar Scheduler:**

```bash
# Terminal 1: Servidor web
php artisan serve

# Terminal 2: Scheduler (executar em paralelo)
php artisan scheduler:start
```

### **3. Configurações Avançadas:**

```php
// config/notifications.php
'intervals' => [
    'check_notifications' => 15,  // minutos
    'task_reminder_hours' => 24,  // horas antes
    'overdue_check_hours' => 1,   // horas para atrasos
],
```

## 📊 **Monitoramento**

### **Verificar Status:**
```bash
# Ver notificações atuais
php artisan notifications:check

# Ver logs do scheduler
tail -f storage/logs/laravel.log
```

### **Testar Configuração:**
```bash
# Executar uma verificação manual
php artisan notifications:check

# Verificar se o scheduler está funcionando
php artisan schedule:list
```

## 🔧 **Personalização**

### **Alterar Frequência:**
```php
// routes/console.php
Schedule::command('notifications:check')
    ->everyFiveMinutes()    // A cada 5 minutos
    ->everyTenMinutes()     // A cada 10 minutos
    ->hourly()              // A cada hora
    ->daily()               // Diariamente
    ->weekly()              // Semanalmente
```

### **Adicionar Novos Tipos:**
```php
// app/Helpers/NotificationHelper.php
public static function checkNovoTipo(): void
{
    // Sua lógica aqui
    self::add('novo_tipo', 'Título', 'Mensagem');
}
```

## ⚠️ **Considerações Importantes**

### **Produção:**
- Use **Cron Jobs** no servidor para executar `php artisan schedule:run`
- Configure **Supervisor** para manter o scheduler rodando
- Monitore **logs** para verificar funcionamento

### **Desenvolvimento:**
- Use `php artisan scheduler:start` para testes
- Configure intervalos menores para testes
- Verifique notificações no dashboard

## 📈 **Estatísticas**

### **Dados Atuais:**
- **31 projetos** no banco
- **26 tarefas** no banco
- **3 tarefas para hoje**
- **Notificações**: Geradas sob demanda

### **Com Scheduler Ativo:**
- **Verificação automática** a cada 15 minutos
- **Notificações em tempo real** para usuários
- **Monitoramento contínuo** do sistema
