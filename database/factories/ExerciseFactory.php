<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition(): array
    {
        $file = new UploadedFile(
            $this->faker->filePath(),
            $this->faker->word() . '.gif',
        );

        return [
            'muscle_group_id' => $this->faker->randomElement(MuscleGroup::pluck('id')->toArray()),
            'name'            => $this->faker->randomElement([
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
            ]) . ' ' . $this->faker->numberBetween(1, 999999),
            'gif' => $file->storeAs('exercises', $file->getClientOriginalName(), Filesystem::VISIBILITY_PUBLIC),
        ];
    }
}
