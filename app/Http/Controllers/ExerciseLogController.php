<?php

namespace App\Http\Controllers;

use App\Helpers\Spatie\QueryBuilder\Filters\Search\SearchFilter;
use App\Http\Requests\StoreExerciseLogRequest;
use App\Http\Requests\UpdateExerciseLogRequest;
use App\Http\Resources\ExerciseLogResource;
use App\Models\ExerciseLog;
use App\Services\Model\ExerciseLogService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExerciseLogController extends Controller
{
    protected ExerciseLogService $exerciseLogService;

    public function __construct(ExerciseLogService $exerciseLogService)
    {
        $this->authorizeResource(ExerciseLog::class, 'exercise_log');
        $this->exerciseLogService = $exerciseLogService;
    }

    public function index()
    {
        $exerciseLogs = QueryBuilder::for(ExerciseLog::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'user_id',
                'exercise_id',
                'date',
                'sets',
                'reps',
                'weight',
                AllowedFilter::custom(
                    'search',
                    new SearchFilter([
                        'id',
                        'user_id',
                        'exercise_id',
                        'date',
                        'sets',
                        'reps',
                        'weight',
                        'user.name',
                        'exercise.name',
                    ]),
                ),
            ])
            ->allowedIncludes([
                'user',
                'exercise',
            ])
            ->defaultSort('-date')
            ->jsonPaginate();

        return ExerciseLogResource::collection($exerciseLogs);
    }

    public function store(StoreExerciseLogRequest $request)
    {
        return new ExerciseLogResource(
            $this->exerciseLogService->create($request)->get(),
        );
    }

    public function show(ExerciseLog $exerciseLog)
    {
        $exerciseLog = QueryBuilder::for(ExerciseLog::class)
            ->where('id', $exerciseLog->id)
            ->allowedIncludes([
                'user',
                'exercise',
            ])
            ->firstOrFail();

        return new ExerciseLogResource($exerciseLog);
    }

    public function update(UpdateExerciseLogRequest $request, ExerciseLog $exerciseLog)
    {
        return new ExerciseLogResource(
            $this->exerciseLogService->set($exerciseLog)
                ->update($request)
                ->get(),
        );
    }

    public function destroy(ExerciseLog $exerciseLog)
    {
        return $this->empty(fn () => $exerciseLog->delete());
    }
}
