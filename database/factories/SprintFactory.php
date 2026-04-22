<?php

namespace Database\Factories;

use App\Models\Projeto;
use App\Models\Sprint;
use Illuminate\Database\Eloquent\Factories\Factory;

class SprintFactory extends Factory
{
    protected $model = Sprint::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['planejada', 'em_andamento', 'concluida']),
            'data_inicio' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'data_fim' => $this->faker->dateTimeBetween('now', '+1 month'),
            'projeto_id' => Projeto::factory(),
        ];
    }
}
