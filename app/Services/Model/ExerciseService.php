<?php

namespace App\Services\Model;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\Exercise;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Str;

class ExerciseService
{
    public const STORAGE_PATH = 'exercises';

    protected Exercise $exercise;

    public function __construct(?Exercise $exercise = null)
    {
        $this->exercise = $exercise ?? new Exercise();
    }

    public function create(StoreExerciseRequest $request): self
    {
        $data = $request->validated();

        $data['gif'] = $this->storeGif($request->file('gif'), $request->input('name'));

        $this->exercise = Exercise::create($data);

        return $this;
    }

    public function update(UpdateExerciseRequest $request): self
    {
        if (!$this->exercise->exists) {
            throw new \RuntimeException('Exercise not set. Use set() method first.');
        }

        $data = $request->validated();

        if ($request->hasFile('gif')) {
            $data['gif'] = $this->storeGif($request->file('gif'), $request->input('name', $this->exercise->name));
        }

        $this->exercise->update($data);

        return $this;
    }

    private function storeGif(UploadedFile $file, string $name): string
    {
        return $file->storeAs(
            self::STORAGE_PATH,
            Str::slug($name) . '.' . $file->extension(),
            Filesystem::VISIBILITY_PUBLIC,
        );
    }

    public function get(): Exercise
    {
        return $this->exercise;
    }

    public function set(Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }
}
