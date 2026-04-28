<?php

namespace Tests\Feature;

use App\Models\Time;
use App\Models\User;
use App\Models\ConviteTime;
use App\Livewire\Auth\JoinTeam;
use App\Livewire\Times\ManageInvitations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_invitation_link()
    {
        $user = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(ManageInvitations::class, ['time' => $time])
            ->set('regra', 'membro')
            ->set('max_usos', 5)
            ->set('expires_in', 7)
            ->call('gerarLink')
            ->assertDispatched('toast-show');

        $this->assertDatabaseHas('convite_times', [
            'time_id' => $time->id,
            'regra' => 'membro',
            'max_usos' => 5,
        ]);
    }

    public function test_guest_can_register_and_join_via_link()
    {
        $time = Time::factory()->create();
        $convite = ConviteTime::create([
            'time_id' => $time->id,
            'token' => 'valid-token',
            'regra' => 'membro',
            'max_usos' => 1,
            'expires_at' => now()->addDays(1),
        ]);

        Livewire::test(JoinTeam::class, ['token' => 'valid-token'])
            ->set('name', 'Novo Usuario')
            ->set('email', 'novo@usuario.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('registerAndJoin')
            ->assertRedirect(route('dashboard'));

        $user = User::where('email', 'novo@usuario.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($time->membros()->where('user_id', $user->id)->exists());
        $this->assertEquals(1, $convite->fresh()->usos);
    }

    public function test_authenticated_user_can_join_via_link()
    {
        $user = User::factory()->create();
        $time = Time::factory()->create();
        $convite = ConviteTime::create([
            'time_id' => $time->id,
            'token' => 'valid-token',
            'regra' => 'admin',
            'max_usos' => 1,
            'expires_at' => now()->addDays(1),
        ]);

        $this->actingAs($user);

        Livewire::test(JoinTeam::class, ['token' => 'valid-token'])
            ->call('registerAndJoin')
            ->assertRedirect(route('dashboard'));

        $this->assertTrue($time->membros()->where('user_id', $user->id)->exists());
        $this->assertEquals('admin', $time->membros()->where('user_id', $user->id)->first()->pivot->regra);
        $this->assertEquals(1, $convite->fresh()->usos);
    }

    public function test_cannot_join_with_expired_token()
    {
        $time = Time::factory()->create();
        $convite = ConviteTime::create([
            'time_id' => $time->id,
            'token' => 'expired-token',
            'regra' => 'membro',
            'max_usos' => 1,
            'expires_at' => now()->subDays(1),
        ]);

        $response = $this->get(route('team.join', 'expired-token'));
        $response->assertStatus(404);
    }

    public function test_cannot_join_with_full_token()
    {
        $time = Time::factory()->create();
        $convite = ConviteTime::create([
            'time_id' => $time->id,
            'token' => 'full-token',
            'regra' => 'membro',
            'max_usos' => 1,
            'usos' => 1,
            'expires_at' => now()->addDays(1),
        ]);

        $response = $this->get(route('team.join', 'full-token'));
        $response->assertStatus(404);
    }

    public function test_admin_can_revoke_invitation()
    {
        $user = User::factory()->create();
        $time = Time::factory()->create(['owner_id' => $user->id]);
        $convite = ConviteTime::create([
            'time_id' => $time->id,
            'token' => 'revoke-me',
            'regra' => 'membro',
            'max_usos' => 1,
            'expires_at' => now()->addDays(1),
        ]);

        $this->actingAs($user);

        Livewire::test(ManageInvitations::class, ['time' => $time])
            ->call('revogar', $convite->id)
            ->assertDispatched('toast-show');

        $this->assertDatabaseMissing('convite_times', ['id' => $convite->id]);
    }
}
