<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case CREATE_USER = 'user.create';
    case READ_USER   = 'user.read';
    case UPDATE_USER = 'user.update';
    case DELETE_USER = 'user.delete';

    case ASSIGN_USER_ROLE = 'user.assign.role';
    case REVOKE_USER_ROLE = 'user.revoke.role';
    case SYNC_USER_ROLE   = 'user.sync.role';

    case CREATE_COUNTRY = 'country.create';
    case READ_COUNTRY   = 'country.read';
    case UPDATE_COUNTRY = 'country.update';
    case DELETE_COUNTRY = 'country.delete';

    case CREATE_STATE = 'state.create';
    case READ_STATE   = 'state.read';
    case UPDATE_STATE = 'state.update';
    case DELETE_STATE = 'state.delete';

    case CREATE_CITY = 'city.create';
    case READ_CITY   = 'city.read';
    case UPDATE_CITY = 'city.update';
    case DELETE_CITY = 'city.delete';

    case CREATE_MUSCLE_GROUP = 'muscle-group.create';
    case READ_MUSCLE_GROUP   = 'muscle-group.read';
    case UPDATE_MUSCLE_GROUP = 'muscle-group.update';
    case DELETE_MUSCLE_GROUP = 'muscle-group.delete';

    case CREATE_EXERCISE = 'exercise.create';
    case READ_EXERCISE   = 'exercise.read';
    case UPDATE_EXERCISE = 'exercise.update';
    case DELETE_EXERCISE = 'exercise.delete';

    case CREATE_WORKOUT = 'workout.create';
    case READ_WORKOUT   = 'workout.read';
    case UPDATE_WORKOUT = 'workout.update';
    case DELETE_WORKOUT = 'workout.delete';
}
