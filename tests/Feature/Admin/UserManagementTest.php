<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_list_users()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Gestão de Usuários');
    }

    /** @test */
    public function admin_can_ban_and_unban_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        // Ban
        $response = $this->actingAs($admin)->post(route('admin.users.ban', $user));
        $response->assertSessionHas('success');
        $this->assertNotNull($user->fresh()->banned_at);

        // Unban
        $response = $this->actingAs($admin)->post(route('admin.users.ban', $user));
        $response->assertSessionHas('success');
        $this->assertNull($user->fresh()->banned_at);
    }

    /** @test */
    public function admin_can_impersonate_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['name' => 'John Doe']);

        $response = $this->actingAs($admin)->post(route('admin.users.impersonate', $user));

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals($user->id, auth()->id());
        $this->assertEquals($admin->id, session('impersonator_id'));
    }

    /** @test */
    public function admin_can_stop_impersonating()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        // Start impersonating
        $this->actingAs($admin)->post(route('admin.users.impersonate', $user));

        // Stop
        $response = $this->get(route('admin.users.stop-impersonating'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertEquals($admin->id, auth()->id());
        $this->assertFalse(session()->has('impersonator_id'));
    }

    /** @test */
    public function banned_user_is_redirected_to_login()
    {
        $user = User::factory()->create(['banned_at' => now()]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Sua conta foi desativada por um administrador.');
        $this->assertFalse(Auth::check());
    }
}
