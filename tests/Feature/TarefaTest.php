<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TarefaTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Projeto $projeto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->projeto = Projeto::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_user_can_list_their_tasks(): void
    {
        Tarefa::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'projeto_id' => $this->projeto->id,
            'responsavel_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get(route('tarefas.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tarefas');
        $this->assertCount(3, $response->viewData('tarefas'));
    }

    public function test_user_can_create_a_task(): void
    {
        $data = [
            'titulo' => 'Tarefa de Teste',
            'descricao' => 'Descrição da tarefa',
            'status' => 'pendente',
            'responsavel_id' => $this->user->id,
            'projeto_id' => $this->projeto->id,
            'data_vencimento' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->user)->post(route('tarefas.store'), $data);

        $response->assertRedirect(route('tarefas.index'));
        $this->assertDatabaseHas('tarefas', [
            'titulo' => 'Tarefa de Teste',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_their_task(): void
    {
        $tarefa = Tarefa::factory()->create([
            'user_id' => $this->user->id,
            'projeto_id' => $this->projeto->id,
            'responsavel_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get(route('tarefas.show', $tarefa->id));

        $response->assertStatus(200);
        $response->assertSee($tarefa->titulo);
    }

    public function test_user_can_update_their_task(): void
    {
        $tarefa = Tarefa::factory()->create([
            'user_id' => $this->user->id,
            'projeto_id' => $this->projeto->id,
            'responsavel_id' => $this->user->id
        ]);
        $newData = ['titulo' => 'Tarefa Atualizada'];

        $response = $this->actingAs($this->user)->put(route('tarefas.update', $tarefa->id), $newData);

        $response->assertRedirect(route('tarefas.index'));
        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefa->id,
            'titulo' => 'Tarefa Atualizada',
        ]);
    }

    public function test_user_can_change_task_status(): void
    {
        $tarefa = Tarefa::factory()->create([
            'user_id' => $this->user->id,
            'projeto_id' => $this->projeto->id,
            'responsavel_id' => $this->user->id,
            'status' => 'pendente'
        ]);

        $response = $this->actingAs($this->user)->patch(route('tarefas.status', $tarefa->id), [
            'status' => 'concluida'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tarefas', [
            'id' => $tarefa->id,
            'status' => 'concluida',
        ]);
    }

    public function test_user_can_delete_their_task(): void
    {
        $tarefa = Tarefa::factory()->create([
            'user_id' => $this->user->id,
            'projeto_id' => $this->projeto->id,
            'responsavel_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->delete(route('tarefas.destroy', $tarefa->id));

        $response->assertRedirect(route('tarefas.index'));
        $this->assertDatabaseMissing('tarefas', ['id' => $tarefa->id]);
    }

    public function test_user_can_get_tasks_by_project_api(): void
    {
        Tarefa::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'projeto_id' => $this->projeto->id,
            'responsavel_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get(route('projetos.tarefas', $this->projeto->id));

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }
}
