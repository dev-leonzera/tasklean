<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjetoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter usuários para associar aos projetos
        $users = \App\Models\User::all();
        
        if ($users->isEmpty()) {
            return; // Não criar projetos se não houver usuários
        }

        // Criar alguns projetos específicos para o primeiro usuário
        \App\Models\Projeto::create([
            'titulo' => 'Sistema de Gestão de Projetos',
            'responsavel_id' => $users->first()->id,
            'ativo' => true,
            'data_criacao' => now()->subDays(30),
            'user_id' => $users->first()->id,
        ]);

        \App\Models\Projeto::create([
            'titulo' => 'E-commerce Mobile App',
            'responsavel_id' => $users->last()->id,
            'ativo' => true,
            'data_criacao' => now()->subDays(15),
            'user_id' => $users->first()->id,
        ]);

        \App\Models\Projeto::create([
            'titulo' => 'Migração de Sistema Legado',
            'responsavel_id' => $users->first()->id,
            'ativo' => false,
            'data_criacao' => now()->subDays(60),
            'user_id' => $users->first()->id,
        ]);

        // Criar projetos aleatórios usando Factory para cada usuário
        foreach ($users as $user) {
            \App\Models\Projeto::factory(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
