<?php

namespace App\Services\Model;

use App\Http\Requests\StoreExerciseLogRequest;
use App\Http\Requests\UpdateExerciseLogRequest;
use App\Models\ExerciseLog;

class ExerciseLogService
{
    protected ExerciseLog $exerciseLog;

    public function __construct(?ExerciseLog $exerciseLog = null)
    {
        $this->exerciseLog = $exerciseLog ?? new ExerciseLog();
    }

    public function create(StoreExerciseLogRequest $request): self
    {
        $data = $request->validated();

        $this->exerciseLog = ExerciseLog::create($data);

        return $this;
    }

    public function update(UpdateExerciseLogRequest $request): self
    {
        if (!$this->exerciseLog->exists) {
            throw new \RuntimeException('ExerciseLog not set. Use set() method first.');
        }

        $data = $request->validated();

        $this->exerciseLog->update($data);

        return $this;
    }

    public function get(): ExerciseLog
    {
        return $this->exerciseLog;
    }

    public function set(ExerciseLog $exerciseLog): self
    {
        $this->exerciseLog = $exerciseLog;

        return $this;
    }
}
