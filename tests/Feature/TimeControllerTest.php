<?php

namespace Tests\Feature;

use App\Models\Time;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_pode_ver_lista_de_times()
    {
        $user = User::factory()->create();
        Time::factory()->count(3)->create(['owner_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('times.index'));

        $response->assertStatus(200);
        $response->assertViewIs('times.index');
    }

    public function test_usuario_pode_criar_time()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post(route('times.store'), [
            'nome' => 'Novo Time',
            'descricao' => 'Descrição do time'
        ]);

        $response->assertRedirect(route('times.index'));
        $this->assertDatabaseHas('times', [
            'nome' => 'Novo Time',
            'owner_id' => $user->id
        ]);
    }

    public function test_apenas_membro_ou_dono_pode_ver_detalhes_do_time()
    {
        $dono = User::factory()->create();
        $estranho = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $dono->id]);

        $this->actingAs($dono)->get(route('times.show', $time->slug))->assertStatus(200);
        $this->actingAs($estranho)->get(route('times.show', $time->slug))->assertStatus(403);
    }

    public function test_apenas_dono_pode_deletar_time()
    {
        $dono = User::factory()->create();
        $outro = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $dono->id]);

        $this->actingAs($outro)->delete(route('times.destroy', $time->slug))->assertStatus(403);
        $this->actingAs($dono)->delete(route('times.destroy', $time->slug))->assertRedirect(route('times.index'));
        
        $this->assertDatabaseMissing('times', ['id' => $time->id]);
    }
}
