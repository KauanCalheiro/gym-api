<?php

namespace App\Http\Requests;

use App\Models\MuscleGroup;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExerciseRequest extends FormRequest
{
    private MuscleGroup $muscleGroup;

    public function __construct()
    {
        $this->muscleGroup = new MuscleGroup();
    }

    public function rules(): array
    {
        return [
            'muscle_group_id' => [
                'sometimes',
                "exists:{$this->muscleGroup->getTable()},{$this->muscleGroup->getKeyName()}",
            ],
            'name' => [
                'sometimes',
                'string',
                'max:255',
                "unique:{$this->exercise->getTable()},name,{$this->exercise->id}",
            ],
            'gif' => [
                'sometimes',
                'file',
                'mimes:gif',
            ],
        ];
    }
}
