<?php

namespace Database\Factories;

use App\Models\Compromisso;
use App\Models\ParticipanteCompromisso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipanteCompromissoFactory extends Factory
{
    protected $model = ParticipanteCompromisso::class;

    public function definition(): array
    {
        return [
            'compromisso_id' => Compromisso::factory(),
            'user_id' => User::factory(),
        ];
    }
}
