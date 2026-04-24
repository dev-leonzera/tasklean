<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Time;
use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\MembroTime;
use App\Models\MembroProjeto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function owner_sees_team_wide_metrics()
    {
        $owner = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $owner->id]);
        
        // Projeto que o owner não criou, mas está no time dele
        $otherUser = User::factory()->create();
        $projeto = Projeto::factory()->create([
            'time_id' => $time->id,
            'user_id' => $otherUser->id,
            'titulo' => 'Projeto do Time'
        ]);
        
        $this->actingAs($owner);
        $response = $this->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('PROPRIETÁRIO');
        $response->assertSee('Projeto do Time');
        $response->assertSee('EM DIA'); // Nova métrica de saúde
        $response->assertSee('Suas Tarefas'); // Widget compacto
    }

    /** @test */
    public function admin_sees_projects_they_manage()
    {
        $admin = User::factory()->create();
        $time = Time::factory()->create();
        
        // Vincular admin ao time
        MembroTime::create([
            'time_id' => $time->id,
            'user_id' => $admin->id,
            'regra' => 'admin'
        ]);
        
        // Projeto que o admin gerencia
        $projetoGerenciado = Projeto::factory()->create([
            'time_id' => $time->id,
            'responsavel_id' => $admin->id,
            'titulo' => 'Meu Projeto Gerenciado'
        ]);
        
        // Projeto do time que o admin NÃO gerencia
        $projetoOutro = Projeto::factory()->create([
            'time_id' => $time->id,
            'titulo' => 'Outro Projeto do Time'
        ]);
        
        $this->actingAs($admin);
        $response = $this->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('ADMINISTRADOR');
        $response->assertSee('Meu Projeto Gerenciado');
        $response->assertDontSee('Outro Projeto do Time');
    }

    /** @test */
    public function member_sees_only_projects_they_participate_in()
    {
        $member = User::factory()->create();
        $time = Time::factory()->create();
        
        // Vincular membro ao time
        MembroTime::create([
            'time_id' => $time->id,
            'user_id' => $member->id,
            'regra' => 'membro'
        ]);
        
        // Projeto que o membro participa
        $projetoParticipa = Projeto::factory()->create([
            'time_id' => $time->id,
            'titulo' => 'Projeto que Participo'
        ]);
        MembroProjeto::create([
            'projeto_id' => $projetoParticipa->id,
            'user_id' => $member->id,
            'regra' => 'membro'
        ]);
        
        // Projeto do time que o membro NÃO participa
        $projetoOutro = Projeto::factory()->create([
            'time_id' => $time->id,
            'titulo' => 'Projeto Alheio'
        ]);
        
        $this->actingAs($member);
        $response = $this->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertSee('MEMBRO');
        $response->assertSee('Projeto que Participo');
        $response->assertDontSee('Projeto Alheio');
    }
}
