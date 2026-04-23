<?php

namespace Tests\Feature;

use App\Models\Projeto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjetoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_list_their_projects(): void
    {
        Projeto::factory()->count(3)->create(['user_id' => $this->user->id]);
        Projeto::factory()->count(2)->create(); // Outro usuário

        $response = $this->actingAs($this->user)->get(route('projetos.index'));

        $response->assertStatus(200);
        $response->assertViewHas('projetos');
        $this->assertCount(3, $response->viewData('projetos'));
    }

    public function test_user_can_create_a_project(): void
    {
        $data = [
            'titulo' => 'Novo Projeto de Teste',
            'responsavel_id' => $this->user->id,
            'ativo' => true,
        ];

        $response = $this->actingAs($this->user)->post(route('projetos.store'), $data);

        $response->assertRedirect(route('projetos.index'));
        $this->assertDatabaseHas('projetos', [
            'titulo' => 'Novo Projeto de Teste',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_their_project(): void
    {
        $projeto = Projeto::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('projetos.show', $projeto->id));

        $response->assertStatus(200);
        $response->assertSee($projeto->titulo);
    }

    public function test_user_cannot_view_others_projects(): void
    {
        $projeto = Projeto::factory()->create(); // Outro usuário

        $response = $this->actingAs($this->user)->get(route('projetos.show', $projeto->id));

        $response->assertStatus(404);
    }

    public function test_user_can_update_their_project(): void
    {
        $projeto = Projeto::factory()->create(['user_id' => $this->user->id]);
        $newData = ['titulo' => 'Título Atualizado'];

        $response = $this->actingAs($this->user)->put(route('projetos.update', $projeto->id), $newData);

        $response->assertRedirect(route('projetos.index'));
        $this->assertDatabaseHas('projetos', [
            'id' => $projeto->id,
            'titulo' => 'Título Atualizado',
        ]);
    }

    public function test_user_can_delete_their_project(): void
    {
        $projeto = Projeto::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('projetos.destroy', $projeto->id));

        $response->assertRedirect(route('projetos.index'));
        $this->assertDatabaseMissing('projetos', ['id' => $projeto->id]);
    }

    public function test_user_can_activate_and_inactivate_project(): void
    {
        $projeto = Projeto::factory()->create(['user_id' => $this->user->id, 'ativo' => true]);

        // Inativar
        $this->actingAs($this->user)->patch(route('projetos.inativar', $projeto->id));
        $this->assertDatabaseHas('projetos', ['id' => $projeto->id, 'ativo' => false]);

        // Ativar
        $this->actingAs($this->user)->patch(route('projetos.ativar', $projeto->id));
        $this->assertDatabaseHas('projetos', ['id' => $projeto->id, 'ativo' => true]);
    }
}
