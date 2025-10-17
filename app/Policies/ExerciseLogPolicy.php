<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\ExerciseLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExerciseLogPolicy
{
    public function viewAny(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_EXERCISE_LOG)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function view(User $user, ExerciseLog $exerciseLog)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_EXERCISE_LOG)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function create(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::CREATE_EXERCISE_LOG)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function update(User $user, ExerciseLog $exerciseLog)
    {
        if (!$user->hasPermissionTo(PermissionEnum::UPDATE_EXERCISE_LOG)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function delete(User $user, ExerciseLog $exerciseLog)
    {
        if (!$user->hasPermissionTo(PermissionEnum::DELETE_EXERCISE_LOG)) {
            return Response::deny();
        }

        return Response::allow();
    }
}
