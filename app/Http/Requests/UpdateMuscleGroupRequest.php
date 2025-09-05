<?php

namespace App\Http\Requests;

use App\Models\MuscleGroup;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMuscleGroupRequest extends FormRequest
{
    protected $muscleGroup;

    public function __construct(MuscleGroup $muscleGroup)
    {
        $this->muscleGroup = $muscleGroup;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                "unique:{$this->muscleGroup->getTable()},name,{$this->muscleGroup->id}",
            ],
        ];
    }
}
