<?php

namespace Database\Factories;

use App\Models\MuscleGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class MuscleGroupFactory extends Factory
{
    protected $model = MuscleGroup::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Peitoral',
                'Costas',
                'Ombros',
                'Bíceps',
                'Tríceps',
                'Pernas',
                'Abdômen',
                'Glúteos',
                'Antebraço',
                'Trapézio',
                'Panturrilha',
            ]) . ' ' . $this->faker->unique()->numberBetween(1, 999999),
        ];
    }
}
