<?php

namespace Tests\Feature;

use App\Models\Time;
use App\Models\User;
use App\Models\Projeto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_um_time()
    {
        $user = User::factory()->create();
        
        $time = Time::create([
            'nome' => 'Time de Desenvolvimento',
            'slug' => 'time-desenvolvimento',
            'descricao' => 'Responsável pelo core do sistema',
            'owner_id' => $user->id,
        ]);

        $this->assertDatabaseHas('times', [
            'nome' => 'Time de Desenvolvimento',
            'slug' => 'time-desenvolvimento',
            'owner_id' => $user->id,
        ]);
    }

    public function test_time_pertence_a_um_dono()
    {
        $user = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $user->id]);

        $this->assertInstanceOf(User::class, $time->owner);
        $this->assertEquals($user->id, $time->owner->id);
    }

    public function test_time_pode_ter_membros()
    {
        $time = Time::factory()->create();
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            $time->membros()->attach($user->id, ['regra' => 'membro']);
        }

        $this->assertCount(3, $time->membros);
    }

    public function test_time_pode_ter_projetos()
    {
        $time = Time::factory()->create();
        $projeto = Projeto::factory()->create(['time_id' => $time->id]);

        $this->assertInstanceOf(Time::class, $projeto->time);
        $this->assertEquals($time->id, $projeto->time->id);
        $this->assertTrue($time->projetos->contains($projeto));
    }
}
