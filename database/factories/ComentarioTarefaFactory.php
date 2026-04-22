<?php

namespace Database\Factories;

use App\Models\ComentarioTarefa;
use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComentarioTarefaFactory extends Factory
{
    protected $model = ComentarioTarefa::class;

    public function definition(): array
    {
        return [
            'conteudo' => $this->faker->paragraph(),
            'tarefa_id' => Tarefa::factory(),
            'user_id' => User::factory(),
        ];
    }
}
