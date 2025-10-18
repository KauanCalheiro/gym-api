<?php

namespace Database\Factories\Location;

use App\Models\Location\City;
use App\Models\Location\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'state_id' => State::factory(),
            'name' => strtoupper($this->faker->unique()->city()),
        ];
    }
}
