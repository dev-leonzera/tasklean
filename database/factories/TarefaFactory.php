<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarefa>
 */
class TarefaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['backlog', 'pendente', 'em desenvolvimento', 'concluida'];
        $status = $this->faker->randomElement($statuses);
        
        return [
            'titulo' => $this->faker->sentence(4),
            'descricao' => $this->faker->paragraph(2),
            'status' => $status,
            'responsavel_id' => \App\Models\User::factory(),
            'user_id' => \App\Models\User::factory(),
            'data_criacao' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'data_vencimento' => $this->faker->optional(0.7)->dateTimeBetween('now', '+2 months'),
            'projeto_id' => \App\Models\Projeto::factory(),
        ];
    }

    /**
     * Tarefa pendente
     */
    public function pendente(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pendente',
        ]);
    }

    /**
     * Tarefa em desenvolvimento
     */
    public function emDesenvolvimento(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'em desenvolvimento',
        ]);
    }

    /**
     * Tarefa concluída
     */
    public function concluida(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'concluida',
        ]);
    }

    /**
     * Tarefa atrasada
     */
    public function atrasada(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $this->faker->randomElement(['pendente', 'em desenvolvimento']),
            'data_vencimento' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }
}
