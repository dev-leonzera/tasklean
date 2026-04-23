<?php

namespace Tests\Feature;

use App\Models\Time;
use App\Models\User;
use App\Models\Projeto;
use App\Models\Tarefa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TarefaAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_atribuir_membro_do_time_como_responsavel_pela_tarefa()
    {
        $dono = User::factory()->create();
        $membro = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($membro->id);

        $projeto = Projeto::factory()->create([
            'user_id' => $dono->id,
            'time_id' => $time->id
        ]);

        $response = $this->actingAs($dono)->post(route('tarefas.store'), [
            'titulo' => 'Tarefa do Time',
            'projeto_id' => $projeto->id,
            'responsavel_id' => $membro->id,
            'status' => 'backlog'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tarefas', [
            'titulo' => 'Tarefa do Time',
            'responsavel_id' => $membro->id
        ]);
    }

    public function test_nao_pode_atribuir_responsavel_fora_do_time_se_projeto_tem_time()
    {
        $dono = User::factory()->create();
        $estranho = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $dono->id]);

        $projeto = Projeto::factory()->create([
            'user_id' => $dono->id,
            'time_id' => $time->id
        ]);

        $response = $this->actingAs($dono)->post(route('tarefas.store'), [
            'titulo' => 'Tarefa Segura',
            'projeto_id' => $projeto->id,
            'responsavel_id' => $estranho->id,
            'status' => 'backlog'
        ]);

        $response->assertSessionHasErrors('responsavel_id');
    }
}
