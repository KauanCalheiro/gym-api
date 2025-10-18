<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutFactory extends Factory
{
    protected $model = Workout::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement([
                'Treino A - Peito e Tríceps',
                'Treino B - Costas e Bíceps',
                'Treino C - Pernas',
                'Treino D - Ombros e Abdômen',
                'Treino Full Body',
            ]),
            'description' => $this->faker->sentence(),
        ];
    }
}
