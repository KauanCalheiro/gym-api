<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutExerciseFactory extends Factory
{
    protected $model = WorkoutExercise::class;

    public function definition(): array
    {
        return [
            'exercise_id' => Exercise::factory(),
            'workout_id' => Workout::factory(),
            'sets' => $this->faker->numberBetween(3, 5),
            'reps' => $this->faker->numberBetween(8, 15),
            'weight' => $this->faker->randomFloat(1, 10, 100),
        ];
    }
}
