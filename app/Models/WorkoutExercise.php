<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkoutExercise extends Model
{
    use SoftDeletes;

    public $table = 'workout_exercise';

    protected $fillable = [
        'exercise_id',
        'workout_id',
        'sets',
        'reps',
        'weight',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}
