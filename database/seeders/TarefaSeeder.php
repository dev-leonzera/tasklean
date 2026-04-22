<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Projeto;
use App\Models\Tarefa;

class TarefaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar projetos existentes
        $projetos = Projeto::all();
        $users = User::all();
        
        if ($projetos->isEmpty() || $users->isEmpty()) {
            return;
        }

        $projeto1 = $projetos->first();
        $user1 = $users->first();
        $user2 = $users->last();

        // Tarefas para o primeiro projeto
        Tarefa::create([
            'titulo' => 'Configurar banco de dados',
            'descricao' => 'Configurar SQLite e criar migrations',
            'status' => 'concluida',
            'responsavel_id' => $user1->id,
            'projeto_id' => $projeto1->id,
            'user_id' => $projeto1->user_id,
            'data_criacao' => now()->subDays(25),
            'data_vencimento' => now()->subDays(20),
        ]);

        Tarefa::create([
            'titulo' => 'Implementar autenticação',
            'descricao' => 'Criar sistema de login e registro de usuários',
            'status' => 'em desenvolvimento',
            'responsavel_id' => $user2->id,
            'projeto_id' => $projeto1->id,
            'user_id' => $projeto1->user_id,
            'data_criacao' => now()->subDays(10),
            'data_vencimento' => now()->addDays(5),
        ]);

        Tarefa::create([
            'titulo' => 'Criar dashboard principal',
            'descricao' => 'Desenvolver interface principal do sistema',
            'status' => 'pendente',
            'responsavel_id' => $user1->id,
            'projeto_id' => $projeto1->id,
            'user_id' => $projeto1->user_id,
            'data_criacao' => now()->subDays(5),
            'data_vencimento' => now()->addDays(10),
        ]);
        
        Tarefa::create([
            'titulo' => 'Revisar documentação',
            'descricao' => 'Atualizar README',
            'status' => 'backlog',
            'responsavel_id' => $user1->id,
            'projeto_id' => $projeto1->id,
            'user_id' => $projeto1->user_id,
            'data_criacao' => now(),
        ]);
    }
}
