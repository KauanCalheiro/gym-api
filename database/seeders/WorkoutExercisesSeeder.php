<?php

namespace Database\Seeders;

use App\Models\WorkoutExercise;
use Illuminate\Database\Seeder;

class WorkoutExercisesSeeder extends Seeder
{
    public function run(): void
    {
        WorkoutExercise::factory(200)->create();
    }
}
