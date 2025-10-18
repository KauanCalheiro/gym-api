<?php

namespace Database\Factories\Location;

use App\Models\Location\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        return [
            'name' => strtoupper($this->faker->country()),
            'code' => strtoupper($this->faker->countryCode()),
        ];
    }
}
