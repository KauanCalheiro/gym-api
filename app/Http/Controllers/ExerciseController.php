<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Services\Model\ExerciseService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExerciseController extends Controller
{
    protected ExerciseService $exerciseService;

    public function __construct(ExerciseService $exerciseService)
    {
        $this->authorizeResource(Exercise::class, 'exercise');
        $this->exerciseService = $exerciseService;
    }

    public function index()
    {
        $exercises = QueryBuilder::for(Exercise::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'name',
                'muscle_group_id',
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'id',
                        'name',
                        'muscle_group_id',
                        'muscleGroup.name',
                    ]),
                ),
            ])
            ->allowedIncludes([
                'muscleGroup',
            ])
            ->defaultSort('name')
            ->jsonPaginate();

        return ExerciseResource::collection($exercises);
    }

    public function store(StoreExerciseRequest $request)
    {
        return new ExerciseResource(
            $this->exerciseService->create($request)->get(),
        );
    }

    public function show(Exercise $exercise)
    {
        $exercise = QueryBuilder::for(Exercise::class)
            ->where('id', $exercise->id)
            ->allowedIncludes([
                'muscleGroup',
            ])
            ->firstOrFail();

        return new ExerciseResource($exercise);
    }

    public function update(UpdateExerciseRequest $request, Exercise $exercise)
    {
        return new ExerciseResource(
            $this->exerciseService->set($exercise)
                ->update($request)
                ->get(),
        );
    }

    public function destroy(Exercise $exercise)
    {
        return $this->empty(fn () => $exercise->delete());
    }
}
