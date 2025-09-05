<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuscleGroup extends Model
{
    protected $table = 'muscle_group';

    protected $fillable = [
        'name',
    ];
}
