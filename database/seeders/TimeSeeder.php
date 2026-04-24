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
        $leon = User::where('email', 'leon@leonzera.com')->first();

        if (!$leon) {
            return;
        }

        // 1. Criar o Time usando a factory
        $team = Time::factory()->create([
            'nome' => 'Tasklean Elite Team',
            'owner_id' => $leon->id,
        ]);

        // O dono também deve ser membro (opcional, dependendo da lógica do app, 
        // mas geralmente o owner é implícito ou explicitamente o primeiro admin)
        // No Tasklean, vamos garantir que ele esteja na tabela membros_time se necessário.
        \App\Models\MembroTime::create([
            'time_id' => $team->id,
            'user_id' => $leon->id,
            'regra' => 'admin', // Owner é sempre admin
        ]);

        // 2. Criar 3 Admins usando factory de MembroTime
        \App\Models\MembroTime::factory()
            ->count(3)
            ->admin()
            ->create([
                'time_id' => $team->id,
            ]);

        // 3. Criar 5 Membros usando factory de MembroTime
        \App\Models\MembroTime::factory()
            ->count(5)
            ->membro()
            ->create([
                'time_id' => $team->id,
            ]);

        // Vincular alguns projetos do owner ao time
        $projetos = Projeto::where('user_id', $leon->id)->take(3)->get();
        foreach ($projetos as $projeto) {
            $projeto->update(['time_id' => $team->id]);
        }
    }
}
