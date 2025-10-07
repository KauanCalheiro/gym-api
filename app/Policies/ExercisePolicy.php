<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExercisePolicy
{
    public function viewAny(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function view(User $user, Exercise $exercise)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function create(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::CREATE_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function update(User $user, Exercise $exercise)
    {
        if (!$user->hasPermissionTo(PermissionEnum::UPDATE_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function delete(User $user, Exercise $exercise)
    {
        if (!$user->hasPermissionTo(PermissionEnum::DELETE_EXERCISE)) {
            return Response::deny();
        }

        return Response::allow();
    }
}
