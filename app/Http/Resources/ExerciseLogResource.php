<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'exercise_id' => $this->exercise_id,
            'date'        => $this->date,
            'sets'        => $this->sets,
            'reps'        => $this->reps,
            'weight'      => $this->weight,
            'user'        => new UserResource($this->whenLoaded('user')),
            'exercise'    => new ExerciseResource($this->whenLoaded('exercise')),
        ];
    }
}
