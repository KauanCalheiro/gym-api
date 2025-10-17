<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\StoreWorkoutExerciseRequest;
use App\Http\Requests\UpdateWorkoutExerciseRequest;
use App\Http\Resources\WorkoutExerciseResource;
use App\Models\WorkoutExercise;
use App\Services\Model\WorkoutExerciseService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WorkoutExerciseController extends Controller
{
    protected WorkoutExerciseService $workoutExerciseService;

    public function __construct(WorkoutExerciseService $workoutExerciseService)
    {
        $this->authorizeResource(WorkoutExercise::class, 'workoutExercise');
        $this->workoutExerciseService = $workoutExerciseService;
    }

    public function index()
    {
        $workoutExercises = QueryBuilder::for(WorkoutExercise::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'exercise_id',
                'workout_id',
                'sets',
                'reps',
                'weight',
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'id',
                        'exercise_id',
                        'workout_id',
                        'sets',
                        'reps',
                        'weight',
                        'exercise.name',
                        'workout.name',
                    ]),
                ),
            ])
            ->allowedIncludes([
                'exercise',
                'exercise.muscleGroup',
                'workout',
                'workout.user',
            ])
            ->defaultSort('id')
            ->jsonPaginate();

        return WorkoutExerciseResource::collection($workoutExercises);
    }

    public function store(StoreWorkoutExerciseRequest $request)
    {
        return new WorkoutExerciseResource(
            $this->workoutExerciseService->create($request)->get(),
        );
    }

    public function show(WorkoutExercise $workoutExercise)
    {
        $workoutExercise = QueryBuilder::for(WorkoutExercise::class)
            ->where('id', $workoutExercise->id)
            ->allowedIncludes([
                'exercise',
                'exercise.muscleGroup',
                'workout',
                'workout.user',
            ])
            ->firstOrFail();

        return new WorkoutExerciseResource($workoutExercise);
    }

    public function update(UpdateWorkoutExerciseRequest $request, WorkoutExercise $workoutExercise)
    {
        return new WorkoutExerciseResource(
            $this->workoutExerciseService->set($workoutExercise)
                ->update($request)
                ->get(),
        );
    }

    public function destroy(WorkoutExercise $workoutExercise)
    {
        return $this->empty(fn () => $workoutExercise->delete());
    }
}
