<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'exercise_id' => $this->exercise_id,
            'workout_id'  => $this->workout_id,
            'sets'        => $this->sets,
            'reps'        => $this->reps,
            'weight'      => $this->weight,
            'exercise'    => new ExerciseResource($this->whenLoaded('exercise')),
            'workout'     => new WorkoutResource($this->whenLoaded('workout')),
        ];
    }
}
