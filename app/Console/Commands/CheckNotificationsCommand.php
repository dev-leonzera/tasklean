<?php

namespace App\Console\Commands;

use App\Helpers\NotificationHelper;
use Illuminate\Console\Command;

class CheckNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica e gera notificações para tarefas prestes a atrasar e outras situações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando notificações...');
        
        // Executar todas as verificações
        NotificationHelper::checkAll();
        
        $this->info('Verificação de notificações concluída!');
        
        return Command::SUCCESS;
    }
}
