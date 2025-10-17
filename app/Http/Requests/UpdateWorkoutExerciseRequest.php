<?php

namespace App\Http\Requests;

use App\Models\Exercise;
use App\Models\Workout;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkoutExerciseRequest extends FormRequest
{
    private Exercise $exercise;
    private Workout  $workout;

    public function __construct()
    {
        $this->exercise = new Exercise();
        $this->workout  = new Workout();
    }

    public function rules(): array
    {
        return [
            'exercise_id' => [
                'sometimes',
                "exists:{$this->exercise->getTable()},{$this->exercise->getKeyName()}",
            ],
            'workout_id' => [
                'sometimes',
                "exists:{$this->workout->getTable()},{$this->workout->getKeyName()}",
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
