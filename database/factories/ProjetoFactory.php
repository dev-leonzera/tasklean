<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Projeto>
 */
class ProjetoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'responsavel_id' => \App\Models\User::factory(),
            'user_id' => \App\Models\User::factory(),
            'ativo' => $this->faker->boolean(80), // 80% chance de ser ativo
            'data_criacao' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indica que o projeto está ativo
     */
    public function ativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'ativo' => true,
        ]);
    }

    /**
     * Indica que o projeto está inativo
     */
    public function inativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'ativo' => false,
        ]);
    }
}
