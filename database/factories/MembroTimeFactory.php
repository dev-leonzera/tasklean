<?php

namespace Database\Factories;

use App\Models\MembroTime;
use App\Models\Time;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MembroTime>
 */
class MembroTimeFactory extends Factory
{
    protected $model = MembroTime::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'time_id' => Time::factory(),
            'user_id' => User::factory(),
            'regra' => $this->faker->randomElement(['admin', 'membro']),
        ];
    }

    /**
     * Define o membro como admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'regra' => 'admin',
        ]);
    }

    /**
     * Define o membro como membro comum.
     */
    public function membro(): static
    {
        return $this->state(fn (array $attributes) => [
            'regra' => 'membro',
        ]);
    }
}
