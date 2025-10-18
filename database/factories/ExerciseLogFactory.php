<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseLogFactory extends Factory
{
    protected $model = ExerciseLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'exercise_id' => Exercise::factory(),
            'date' => $this->faker->date(),
            'sets' => $this->faker->numberBetween(3, 5),
            'reps' => $this->faker->numberBetween(8, 15),
            'weight' => $this->faker->randomFloat(1, 10, 100),
        ];
    }
}
