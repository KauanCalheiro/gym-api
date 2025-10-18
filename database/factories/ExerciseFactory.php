<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition(): array
    {
        return [
            'muscle_group_id' => MuscleGroup::factory(),
            'name' => $this->faker->unique()->randomElement([
                'Supino Reto',
                'Supino Inclinado',
                'Supino Declinado',
                'Crucifixo',
                'Puxada',
                'Remada',
                'Desenvolvimento',
                'Elevação Lateral',
                'Rosca Direta',
                'Rosca Martelo',
                'Tríceps Testa',
                'Tríceps Corda',
                'Agachamento',
                'Leg Press',
                'Stiff',
            ]) . ' ' . $this->faker->unique()->numberBetween(1, 999999),
            'gif' => $this->faker->filePath() . '.gif',
        ];
    }
}
