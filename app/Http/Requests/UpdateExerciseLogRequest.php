<?php

namespace App\Http\Requests;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciseLogRequest extends FormRequest
{
    private User        $user;
    private Exercise    $exercise;
    private ExerciseLog $exerciseLog;

    public function __construct()
    {
        $this->user        = new User();
        $this->exercise    = new Exercise();
        $this->exerciseLog = new ExerciseLog();
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'sometimes',
                "exists:{$this->user->getTable()},{$this->user->getKeyName()}",
            ],
            'exercise_id' => [
                'sometimes',
                "exists:{$this->exercise->getTable()},{$this->exercise->getKeyName()}",
            ],
            'sets' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'reps' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'weight' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}
