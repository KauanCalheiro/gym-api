<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\MuscleGroup;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MuscleGroupPolicy
{
    public function viewAny(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_MUSCLE_GROUP)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function view(User $user, MuscleGroup $muscleGroup)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_MUSCLE_GROUP)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function create(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::CREATE_MUSCLE_GROUP)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function update(User $user, MuscleGroup $muscleGroup)
    {
        if (!$user->hasPermissionTo(PermissionEnum::UPDATE_MUSCLE_GROUP)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function delete(User $user, MuscleGroup $muscleGroup)
    {
        if (!$user->hasPermissionTo(PermissionEnum::DELETE_MUSCLE_GROUP)) {
            return Response::deny();
        }

        return Response::allow();
    }
}
