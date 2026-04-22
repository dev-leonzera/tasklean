<?php

namespace Database\Factories;

use App\Models\MembroSprint;
use App\Models\Sprint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembroSprintFactory extends Factory
{
    protected $model = MembroSprint::class;

    public function definition(): array
    {
        return [
            'sprint_id' => Sprint::factory(),
            'user_id' => User::factory(),
        ];
    }
}
