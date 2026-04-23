<?php

namespace Database\Seeders;

use App\Models\Time;
use App\Models\User;
use App\Models\Projeto;
use Illuminate\Database\Seeder;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            return;
        }

        // Criar um time principal para o primeiro usuário
        $mainUser = $users->first();
        $otherUsers = $users->skip(1)->take(3);

        $team = Time::factory()->create([
            'nome' => 'Time de Desenvolvimento Core',
            'owner_id' => $mainUser->id,
        ]);

        // Adicionar membros
        foreach ($otherUsers as $user) {
            $team->membros()->attach($user->id, [
                'regra' => $user->id % 2 === 0 ? 'admin' : 'membro'
            ]);
        }

        // Vincular alguns projetos do usuário ao time
        $projetos = Projeto::where('user_id', $mainUser->id)->take(2)->get();
        foreach ($projetos as $projeto) {
            $projeto->update(['time_id' => $team->id]);
        }

        // Criar mais alguns times aleatórios
        Time::factory()->count(2)->create();
    }
}
