<?php

namespace App\Http\Requests;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreExerciseLogRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'date' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                "exists:{$this->user->getTable()},{$this->user->getKeyName()}",
            ],
            'exercise_id' => [
                'required',
                "exists:{$this->exercise->getTable()},{$this->exercise->getKeyName()}",
            ],
            'date' => [
                'required',
                'date',
            ],
            'sets' => [
                'required',
                'integer',
                'min:1',
            ],
            'reps' => [
                'required',
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
