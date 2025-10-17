<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseLog extends Model
{
    use SoftDeletes;

    public $table = 'exercise_log';

    protected $fillable = [
        'user_id',
        'exercise_id',
        'date',
        'sets',
        'reps',
        'weight',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
