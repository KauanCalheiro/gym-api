<?php

namespace App\Http\Requests;

use App\Models\MuscleGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreMuscleGroupRequest extends FormRequest
{
    protected $muscleGroup;

    public function __construct()
    {
        $this->muscleGroup = new MuscleGroup();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                "unique:{$this->muscleGroup->getTable()},name",
            ],
        ];
    }
}
