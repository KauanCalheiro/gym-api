<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseLog extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'exercise_log';

    protected $fillable = [
        'user_id',
        'exercise_id',
        'date',
        'sets',
        'reps',
        'weight',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
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
