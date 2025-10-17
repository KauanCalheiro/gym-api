<?php

namespace App\Providers;

use App\Models\Exercise;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use App\Policies\CityPolicy;
use App\Policies\CountryPolicy;
use App\Policies\ExercisePolicy;
use App\Policies\MuscleGroupPolicy;
use App\Policies\StatePolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkoutPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class        => UserPolicy::class,
        Country::class     => CountryPolicy::class,
        State::class       => StatePolicy::class,
        City::class        => CityPolicy::class,
        Exercise::class    => ExercisePolicy::class,
        MuscleGroup::class => MuscleGroupPolicy::class,
        Workout::class     => WorkoutPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
