<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkoutRequest extends FormRequest
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'sometimes',
                "exists:{$this->user->getTable()},{$this->user->getKeyName()}",
            ],
            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
