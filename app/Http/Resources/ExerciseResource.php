<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class ExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'muscle_group_id' => $this->muscle_group_id,
            'name'            => $this->name,
            'gif'             => url(Storage::url($this->gif)),
            'muscle_group'    => new MuscleGroupResource($this->whenLoaded('muscleGroup')),
        ];
    }
}
