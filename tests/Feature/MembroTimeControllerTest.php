<?php

namespace Tests\Feature;

use App\Models\Time;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembroTimeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dono_pode_adicionar_membro()
    {
        $dono = User::factory()->create();
        $novoMembro = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $dono->id]);

        $response = $this->actingAs($dono)->post(route('times.membros.store', $time->slug), [
            'email' => $novoMembro->email,
            'regra' => 'membro'
        ]);

        $response->assertRedirect(route('times.show', $time->slug));
        $this->assertTrue($time->membros->contains($novoMembro));
    }

    public function test_nao_pode_adicionar_membro_que_nao_existe()
    {
        $dono = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $dono->id]);

        $response = $this->actingAs($dono)->post(route('times.membros.store', $time->slug), [
            'email' => 'naoexiste@email.com',
            'regra' => 'membro'
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_apenas_dono_ou_admin_pode_remover_membro()
    {
        $dono = User::factory()->create();
        $membro = User::factory()->create();
        $outro = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($membro->id);

        $this->actingAs($outro)->delete(route('times.membros.destroy', [$time->slug, $membro->id]))
            ->assertStatus(403);

        $this->actingAs($dono)->delete(route('times.membros.destroy', [$time->slug, $membro->id]))
            ->assertRedirect(route('times.show', $time->slug));

        $this->assertFalse($time->fresh()->membros->contains($membro));
    }
}
