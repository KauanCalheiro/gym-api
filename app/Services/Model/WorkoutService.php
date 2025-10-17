<?php

namespace App\Services\Model;

use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Models\Workout;

class WorkoutService
{
    protected Workout $workout;

    public function __construct(?Workout $workout = null)
    {
        $this->workout = $workout ?? new Workout();
    }

    public function create(StoreWorkoutRequest $request): self
    {
        $data = $request->validated();

        $this->workout = Workout::create($data);

        return $this;
    }

    public function update(UpdateWorkoutRequest $request): self
    {
        if (!$this->workout->exists) {
            throw new \RuntimeException('Workout not set. Use set() method first.');
        }

        $data = $request->validated();

        $this->workout->update($data);

        return $this;
    }

    public function get(): Workout
    {
        return $this->workout;
    }

    public function set(Workout $workout): self
    {
        $this->workout = $workout;

        return $this;
    }
}
