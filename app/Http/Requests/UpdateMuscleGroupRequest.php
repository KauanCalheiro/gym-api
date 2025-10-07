<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMuscleGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                "unique:{$this->muscle_group->getTable()},name,{$this->muscle_group->id}",
            ],
        ];
    }
}
