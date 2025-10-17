<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use App\Models\WorkoutExercise;
use Illuminate\Auth\Access\Response;

class WorkoutExercisePolicy
{
    public function viewAny(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_WORKOUT_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function view(User $user, WorkoutExercise $workoutExercise)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_WORKOUT_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function create(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::CREATE_WORKOUT_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function update(User $user, WorkoutExercise $workoutExercise)
    {
        if (!$user->hasPermissionTo(PermissionEnum::UPDATE_WORKOUT_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function delete(User $user, WorkoutExercise $workoutExercise)
    {
        if (!$user->hasPermissionTo(PermissionEnum::DELETE_WORKOUT_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }
}
