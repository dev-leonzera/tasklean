<?php

namespace Database\Factories;

use App\Models\Compromisso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compromisso>
 */
class CompromissoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Compromisso::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dataInicio = $this->faker->dateTimeBetween('-30 days', '+30 days');
        
        $horaInicio = $this->faker->time('H:i');
        $horaFim = $this->faker->optional(0.8)->time('H:i');

        $tipos = ['reuniao', 'evento', 'tarefa', 'lembrete', 'compromisso_pessoal', 'outro'];
        $status = ['agendado', 'em_andamento', 'concluido', 'cancelado', 'adiado'];
        $prioridades = ['baixa', 'media', 'alta', 'urgente'];

        return [
            'user_id' => User::factory(),
            'titulo' => $this->faker->sentence(3),
            'descricao' => $this->faker->optional(0.7)->paragraph(),
            'data_inicio' => $dataInicio,
            'data_fim' => $dataInicio, // Usar a mesma data por enquanto
            'hora_inicio' => $horaInicio,
            'hora_fim' => $horaFim ?: $horaInicio,
            'local' => $this->faker->optional(0.6)->address(),
            'tipo' => $this->faker->randomElement($tipos),
            'status' => $this->faker->randomElement($status),
            'prioridade' => $this->faker->randomElement($prioridades),
            'lembrete' => null, // Simplificar por enquanto
            'observacoes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the compromisso is today.
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'data_inicio' => now()->format('Y-m-d'),
            'data_fim' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the compromisso is this week.
     */
    public function thisWeek(): static
    {
        return $this->state(fn (array $attributes) => [
            'data_inicio' => $this->faker->dateTimeBetween(now()->startOfWeek(), now()->endOfWeek()),
        ]);
    }

    /**
     * Indicate that the compromisso is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'data_inicio' => $this->faker->dateTimeBetween('now', '+7 days'),
        ]);
    }

    /**
     * Indicate that the compromisso is high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'prioridade' => $this->faker->randomElement(['alta', 'urgente']),
        ]);
    }

    /**
     * Indicate that the compromisso is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'concluido',
            'data_inicio' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'data_fim' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the compromisso is a meeting.
     */
    public function meeting(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'reuniao',
            'titulo' => 'Reunião: ' . $this->faker->sentence(2),
            'local' => $this->faker->company() . ' - Sala ' . $this->faker->numberBetween(1, 20),
        ]);
    }

    /**
     * Indicate that the compromisso is an event.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'evento',
            'titulo' => 'Evento: ' . $this->faker->sentence(2),
            'local' => $this->faker->address(),
        ]);
    }
}
