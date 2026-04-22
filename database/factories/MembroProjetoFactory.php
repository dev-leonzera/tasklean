<?php

namespace Database\Factories;

use App\Models\MembroProjeto;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembroProjetoFactory extends Factory
{
    protected $model = MembroProjeto::class;

    public function definition(): array
    {
        return [
            'projeto_id' => Projeto::factory(),
            'user_id' => User::factory(),
            'regra' => $this->faker->randomElement(['admin', 'editor', 'visualizador']),
        ];
    }
}
