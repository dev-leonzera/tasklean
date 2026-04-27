<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Time;
use App\Models\Projeto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Onboarding\Wizard;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_unboarded_user_is_redirected_to_onboarding(): void
    {
        $user = User::factory()->needsOnboarding()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('onboarding.wizard'));
    }

    public function test_boarded_user_can_access_dashboard(): void
    {
        $user = User::factory()->create(); // onboarding_completed_at is set by default in factory

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_user_can_complete_onboarding_wizard(): void
    {
        $user = User::factory()->needsOnboarding()->create();

        Livewire::actingAs($user)
            ->test(Wizard::class)
            ->set('teamName', 'Meu Novo Time')
            ->set('teamDescription', 'Descrição do time')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('projectTitle', 'Meu Primeiro Projeto')
            ->call('nextStep')
            ->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertNotNull($user->onboarding_completed_at);
        $this->assertDatabaseHas('times', ['nome' => 'Meu Novo Time', 'owner_id' => $user->id]);
        
        $time = Time::where('nome', 'Meu Novo Time')->first();
        $this->assertDatabaseHas('projetos', [
            'titulo' => 'Meu Primeiro Projeto', 
            'user_id' => $user->id,
            'time_id' => $time->id
        ]);
    }
}
