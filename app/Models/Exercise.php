<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use SoftDeletes;

    public $table = 'exercise';

    protected $fillable = [
        'muscle_group_id',
        'name',
        'gif',
    ];

    public function muscleGroup()
    {
        return $this->belongsTo(MuscleGroup::class);
    }
}
