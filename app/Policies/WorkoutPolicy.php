<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Auth\Access\Response;

class WorkoutPolicy
{
    public function viewAny(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_WORKOUT)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function view(User $user, Workout $workout)
    {
        if (!$user->hasPermissionTo(PermissionEnum::READ_WORKOUT)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function create(User $user)
    {
        if (!$user->hasPermissionTo(PermissionEnum::CREATE_WORKOUT)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function update(User $user, Workout $workout)
    {
        if (!$user->hasPermissionTo(PermissionEnum::UPDATE_WORKOUT)) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function delete(User $user, Workout $workout)
    {
        if (!$user->hasPermissionTo(PermissionEnum::DELETE_WORKOUT)) {
            return Response::deny();
        }

        return Response::allow();
    }
}
