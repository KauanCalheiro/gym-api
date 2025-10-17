<?php

namespace App\Services\Model;

use App\Http\Requests\StoreWorkoutExerciseRequest;
use App\Http\Requests\UpdateWorkoutExerciseRequest;
use App\Models\WorkoutExercise;

class WorkoutExerciseService
{
    protected WorkoutExercise $workoutExercise;

    public function __construct(?WorkoutExercise $workoutExercise = null)
    {
        $this->workoutExercise = $workoutExercise ?? new WorkoutExercise();
    }

    public function create(StoreWorkoutExerciseRequest $request): self
    {
        $data = $request->validated();

        $this->workoutExercise = WorkoutExercise::create($data);

        return $this;
    }

    public function update(UpdateWorkoutExerciseRequest $request): self
    {
        if (!$this->workoutExercise->exists) {
            throw new \RuntimeException('WorkoutExercise not set. Use set() method first.');
        }

        $data = $request->validated();

        $this->workoutExercise->update($data);

        return $this;
    }

    public function get(): WorkoutExercise
    {
        return $this->workoutExercise;
    }

    public function set(WorkoutExercise $workoutExercise): self
    {
        $this->workoutExercise = $workoutExercise;

        return $this;
    }
}
