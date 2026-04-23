<?php

namespace Database\Factories;

use App\Models\Time;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Time>
 */
class TimeFactory extends Factory
{
    protected $model = Time::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nome = $this->faker->company;
        return [
            'nome' => $nome,
            'slug' => Str::slug($nome) . '-' . Str::random(5),
            'descricao' => $this->faker->sentence,
            'owner_id' => User::factory(),
        ];
    }
}
