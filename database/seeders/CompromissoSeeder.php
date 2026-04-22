<?php

namespace Database\Seeders;

use App\Models\Compromisso;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompromissoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar compromissos para usuários existentes
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Execute o UserSeeder primeiro.');
            return;
        }

        foreach ($users as $user) {
            // Criar alguns compromissos para hoje
            Compromisso::factory()
                ->count(2)
                ->today()
                ->create(['user_id' => $user->id]);

            // Criar compromissos para esta semana
            Compromisso::factory()
                ->count(3)
                ->thisWeek()
                ->create(['user_id' => $user->id]);

            // Criar compromissos próximos
            Compromisso::factory()
                ->count(5)
                ->upcoming()
                ->create(['user_id' => $user->id]);

            // Criar alguns compromissos concluídos
            Compromisso::factory()
                ->count(3)
                ->completed()
                ->create(['user_id' => $user->id]);

            // Criar algumas reuniões específicas
            Compromisso::factory()
                ->count(2)
                ->meeting()
                ->create(['user_id' => $user->id]);

            // Criar alguns eventos específicos
            Compromisso::factory()
                ->count(2)
                ->event()
                ->create(['user_id' => $user->id]);

            // Criar compromissos de alta prioridade
            Compromisso::factory()
                ->count(2)
                ->highPriority()
                ->create(['user_id' => $user->id]);
        }

        $this->command->info('Compromissos criados com sucesso!');
    }
}
