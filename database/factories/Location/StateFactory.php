<?php

namespace Database\Factories\Location;

use App\Models\Location\Country;
use App\Models\Location\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'name' => strtoupper($this->faker->unique()->state()),
            'code' => strtoupper($this->faker->unique()->stateAbbr()),
        ];
    }
}
