<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartSchedulerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicia o scheduler do Laravel para executar tarefas agendadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando scheduler do Laravel...');
        $this->info('Pressione Ctrl+C para parar');
        
        while (true) {
            $this->call('schedule:run');
            sleep(60); // Verificar a cada minuto
        }
    }
}
