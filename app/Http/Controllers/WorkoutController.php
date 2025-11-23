<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Http\Resources\WorkoutResource;
use App\Models\Workout;
use App\Services\Model\WorkoutService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WorkoutController extends Controller
{
    protected WorkoutService $workoutService;

    public function __construct(WorkoutService $workoutService)
    {
        $this->authorizeResource(Workout::class, 'workout');
        $this->workoutService = $workoutService;
    }

    public function index()
    {
        $workouts = QueryBuilder::for(Workout::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'name',
                'user_id',
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'id',
                        'name',
                        'user_id',
                        'user.name',
                        'description',
                    ]),
                ),
            ])
            ->allowedIncludes([
                'user',
                'workout_exercises',
                'workout_exercises.exercise',
            ])
            ->defaultSort('name')
            ->jsonPaginate();

        return WorkoutResource::collection($workouts);
    }

    public function store(StoreWorkoutRequest $request)
    {
        return new WorkoutResource(
            $this->workoutService->create($request)->get(),
        );
    }

    public function show(Workout $workout)
    {
        $workout = QueryBuilder::for(Workout::class)
            ->where('id', $workout->id)
            ->allowedIncludes([
                'user',
                'workout_exercises',
                'workout_exercises.exercise',
            ])
            ->firstOrFail();

        return new WorkoutResource($workout);
    }

    public function update(UpdateWorkoutRequest $request, Workout $workout)
    {
        return new WorkoutResource(
            $this->workoutService->set($workout)
                ->update($request)
                ->get(),
        );
    }

    public function destroy(Workout $workout)
    {
        return $this->empty(fn () => $workout->delete());
    }
}
