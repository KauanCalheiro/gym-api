<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workout extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'workout';

    protected $fillable = [
        'user_id',
        'name',
        'description',
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

    public function workout_exercises()
    {
        return $this->hasMany(WorkoutExercise::class, 'workout_id', 'id');
    }
}
