<?php

namespace Database\Seeders;

use App\Models\MuscleGroup;
use DB;
use Illuminate\Database\Seeder;

class MuscleGroupSeeder extends Seeder
{
    public function run(): void
    {
        $muscleGroups = [
            ['name' => 'Peito'],
            ['name' => 'Tríceps'],
            ['name' => 'Costas'],
            ['name' => 'Bíceps'],
            ['name' => 'Quadríceps'],
            ['name' => 'Posterior de Coxa'],
            ['name' => 'Glúteos'],
            ['name' => 'Ombros'],
            ['name' => 'Panturrilhas'],
            ['name' => 'Abdômen'],
        ];

        DB::transaction(
            fn () => collect($muscleGroups)->each(
                fn ($muscleGroup) => MuscleGroup::create($muscleGroup),
            ),
        );
    }
}
