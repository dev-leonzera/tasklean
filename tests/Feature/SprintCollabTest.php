<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\Sprint;
use App\Models\Tarefa;
use App\Models\Time;
use App\Models\User;
use App\Models\UserSettings;
use App\Livewire\SprintBoard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SprintCollabTest extends TestCase
{
    use RefreshDatabase;

    public function test_membro_do_time_pode_ver_sprints_de_projeto_compartilhado()
    {
        $dono = User::factory()->create();
        $membro = User::factory()->create();
        $membro->settings()->update([
            'enable_sprints' => true,
            'enable_kanban' => true,
        ]);

        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($membro->id, ['regra' => 'membro']);

        $projeto = Projeto::factory()->create([
            'user_id' => $dono->id, 
            'time_id' => $time->id,
            'ativo' => true
        ]);
        
        $sprint = Sprint::factory()->create([
            'projeto_id' => $projeto->id, 
            'nome' => 'Sprint de Colaboracao',
            'data_inicio' => now(),
            'data_fim' => now()->addWeeks(2)
        ]);

        Livewire::actingAs($membro)
            ->test(SprintBoard::class)
            ->assertSee('Sprint de Colaboracao');
    }

    public function test_admin_do_time_pode_criar_sprint_em_projeto_compartilhado()
    {
        $dono = User::factory()->create();
        $admin = User::factory()->create();
        $admin->settings()->update([
            'enable_sprints' => true,
            'enable_kanban' => true,
        ]);

        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($admin->id, ['regra' => 'admin']);

        $projeto = Projeto::factory()->create([
            'user_id' => $dono->id, 
            'time_id' => $time->id,
            'ativo' => true
        ]);

        Livewire::actingAs($admin)
            ->test(SprintBoard::class)
            ->set('newSprintName', 'Sprint do Coordenador')
            ->set('newSprintProjetoId', $projeto->id)
            ->set('newSprintStart', now()->format('Y-m-d'))
            ->set('newSprintEnd', now()->addWeeks(1)->format('Y-m-d'))
            ->call('createSprint')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('sprints', ['nome' => 'Sprint do Coordenador']);
    }

    public function test_membro_comum_nao_pode_criar_sprint()
    {
        $dono = User::factory()->create();
        $membro = User::factory()->create();
        $membro->settings()->update([
            'enable_sprints' => true,
            'enable_kanban' => true,
        ]);

        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($membro->id, ['regra' => 'membro']);

        $projeto = Projeto::factory()->create([
            'user_id' => $dono->id, 
            'time_id' => $time->id,
            'ativo' => true
        ]);

        Livewire::actingAs($membro)
            ->test(SprintBoard::class)
            ->set('newSprintName', 'Tentativa Proibida')
            ->set('newSprintProjetoId', $projeto->id)
            ->call('createSprint')
            ->assertForbidden();

        $this->assertDatabaseMissing('sprints', ['nome' => 'Tentativa Proibida']);
    }

    public function test_admin_pode_adicionar_tarefa_a_sprint()
    {
        $dono = User::factory()->create();
        $admin = User::factory()->create();
        $admin->settings()->update([
            'enable_sprints' => true,
            'enable_kanban' => true,
        ]);

        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($admin->id, ['regra' => 'admin']);

        $projeto = Projeto::factory()->create(['user_id' => $dono->id, 'time_id' => $time->id]);
        $sprint = Sprint::factory()->create(['projeto_id' => $projeto->id]);
        $tarefa = Tarefa::factory()->create(['projeto_id' => $projeto->id, 'user_id' => $dono->id]);

        Livewire::actingAs($admin)
            ->test(SprintBoard::class)
            ->set('selectedSprintId', $sprint->id)
            ->set('taskToAdd', $tarefa->id)
            ->call('addTaskToSprint')
            ->assertHasNoErrors();

        $this->assertEquals($sprint->id, $tarefa->fresh()->sprint_id);
    }
}
