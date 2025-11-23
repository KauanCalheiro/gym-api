<?php

namespace App\Providers;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use App\Policies\ExerciseLogPolicy;
use App\Policies\ExercisePolicy;
use App\Policies\MuscleGroupPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkoutExercisePolicy;
use App\Policies\WorkoutPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class            => UserPolicy::class,
        Exercise::class        => ExercisePolicy::class,
        ExerciseLog::class     => ExerciseLogPolicy::class,
        MuscleGroup::class     => MuscleGroupPolicy::class,
        Workout::class         => WorkoutPolicy::class,
        WorkoutExercise::class => WorkoutExercisePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
