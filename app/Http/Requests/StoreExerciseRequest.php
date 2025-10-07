<?php

namespace App\Http\Requests;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreExerciseRequest extends FormRequest
{
    private MuscleGroup $muscleGroup;
    private Exercise    $exercise;

    public function __construct()
    {
        $this->muscleGroup = new MuscleGroup();
        $this->exercise    = new Exercise();
    }

    public function rules(): array
    {
        return [
            'muscle_group_id' => [
                'required',
                "exists:{$this->muscleGroup->getTable()},{$this->muscleGroup->getKeyName()}",
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                "unique:{$this->exercise->getTable()},name",
            ],
            'gif' => [
                'required',
                'file',
                'mimes:gif',
            ],
        ];
    }
}
