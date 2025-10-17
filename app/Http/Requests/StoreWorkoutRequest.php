<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutRequest extends FormRequest
{
    private User    $user;
    private Workout $workout;

    public function __construct()
    {
        $this->user    = new User();
        $this->workout = new Workout();
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                "exists:{$this->user->getTable()},{$this->user->getKeyName()}",
            ],
            'name' => [
                'required',
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
