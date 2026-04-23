<?php

namespace Tests\Feature;

use App\Models\Time;
use App\Models\User;
use App\Models\Projeto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjetoPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_dono_do_projeto_pode_ver_projeto()
    {
        $user = User::factory()->create();
        $projeto = Projeto::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('view', $projeto));
    }

    public function test_membro_do_time_pode_ver_projeto_do_time()
    {
        $donoTime = User::factory()->create();
        $membro = User::factory()->create();
        $estranho = User::factory()->create();

        $time = Time::factory()->create(['owner_id' => $donoTime->id]);
        $time->membros()->attach($membro->id, ['regra' => 'membro']);

        $projeto = Projeto::factory()->create([
            'user_id' => $donoTime->id,
            'time_id' => $time->id
        ]);

        $this->assertTrue($membro->can('view', $projeto));
        $this->assertFalse($estranho->can('view', $projeto));
    }

    public function test_apenas_dono_ou_admin_do_time_pode_editar_projeto_do_time()
    {
        $donoProjeto = User::factory()->create();
        $adminTime = User::factory()->create();
        $membroTime = User::factory()->create();

        $time = Time::factory()->create(['owner_id' => $donoProjeto->id]);
        $time->membros()->attach($adminTime->id, ['regra' => 'admin']);
        $time->membros()->attach($membroTime->id, ['regra' => 'membro']);

        $projeto = Projeto::factory()->create([
            'user_id' => $donoProjeto->id,
            'time_id' => $time->id
        ]);

        $this->assertTrue($donoProjeto->can('update', $projeto));
        $this->assertTrue($adminTime->can('update', $projeto));
        $this->assertFalse($membroTime->can('update', $projeto));
    }
}
