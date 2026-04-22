<?php

namespace Database\Factories;

use App\Models\Projeto;
use App\Models\TagProjeto;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagProjetoFactory extends Factory
{
    protected $model = TagProjeto::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->word(),
            'cor' => $this->faker->safeHexColor(),
            'projeto_id' => Projeto::factory(),
        ];
    }
}
