<?php

namespace Tests\Feature;

use App\Models\Time;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_apenas_dono_ou_membro_pode_ver_time()
    {
        $dono = User::factory()->create();
        $membro = User::factory()->create();
        $estranho = User::factory()->create();

        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($membro->id, ['regra' => 'membro']);

        $this->assertTrue($dono->can('view', $time));
        $this->assertTrue($membro->can('view', $time));
        $this->assertFalse($estranho->can('view', $time));
    }

    public function test_apenas_dono_ou_admin_pode_editar_time()
    {
        $dono = User::factory()->create();
        $admin = User::factory()->create();
        $membro = User::factory()->create();

        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($admin->id, ['regra' => 'admin']);
        $time->membros()->attach($membro->id, ['regra' => 'membro']);

        $this->assertTrue($dono->can('update', $time));
        $this->assertTrue($admin->can('update', $time));
        $this->assertFalse($membro->can('update', $time));
    }

    public function test_apenas_dono_pode_deletar_time()
    {
        $dono = User::factory()->create();
        $admin = User::factory()->create();

        $time = Time::factory()->create(['owner_id' => $dono->id]);
        $time->membros()->attach($admin->id, ['regra' => 'admin']);

        $this->assertTrue($dono->can('delete', $time));
        $this->assertFalse($admin->can('delete', $time));
    }
}
