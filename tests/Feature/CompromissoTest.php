<?php

namespace Tests\Feature;

use App\Models\Compromisso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompromissoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_list_their_appointments(): void
    {
        Compromisso::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('compromissos.index'));

        $response->assertStatus(200);
        $response->assertViewHas('compromissos');
        $this->assertCount(3, $response->viewData('compromissos'));
    }

    public function test_user_can_create_an_appointment(): void
    {
        $data = [
            'titulo' => 'Reunião Importante',
            'data_inicio' => now()->format('Y-m-d'),
            'hora_inicio' => '14:00',
            'tipo' => 'reuniao',
            'prioridade' => 'alta',
        ];

        $response = $this->actingAs($this->user)->post(route('compromissos.store'), $data);

        $response->assertRedirect(route('compromissos.index'));
        $this->assertDatabaseHas('compromissos', [
            'titulo' => 'Reunião Importante',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_today_appointments(): void
    {
        Compromisso::factory()->create([
            'user_id' => $this->user->id,
            'data_inicio' => now()->format('Y-m-d')
        ]);

        $response = $this->actingAs($this->user)->get(route('compromissos.today'));

        $response->assertStatus(200);
        $response->assertViewHas('compromissos');
        $this->assertCount(1, $response->viewData('compromissos'));
    }

    public function test_user_can_view_upcoming_appointments(): void
    {
        Compromisso::factory()->create([
            'user_id' => $this->user->id,
            'data_inicio' => now()->addDays(2)->format('Y-m-d')
        ]);

        $response = $this->actingAs($this->user)->get(route('compromissos.upcoming'));

        $response->assertStatus(200);
        $response->assertViewHas('compromissos');
        $this->assertCount(1, $response->viewData('compromissos'));
    }

    public function test_user_can_update_appointment_status_api(): void
    {
        $compromisso = Compromisso::factory()->create(['user_id' => $this->user->id, 'status' => 'agendado']);

        $response = $this->actingAs($this->user)->patch(route('compromissos.status', $compromisso->id), [
            'status' => 'concluido'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('compromissos', [
            'id' => $compromisso->id,
            'status' => 'concluido',
        ]);
    }

    public function test_user_can_get_calendar_data_api(): void
    {
        Compromisso::factory()->create([
            'user_id' => $this->user->id,
            'data_inicio' => now()->format('Y-m-d')
        ]);

        $response = $this->actingAs($this->user)->get(route('compromissos.calendar', [
            'start' => now()->startOfMonth()->format('Y-m-d'),
            'end' => now()->endOfMonth()->format('Y-m-d'),
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }
}
