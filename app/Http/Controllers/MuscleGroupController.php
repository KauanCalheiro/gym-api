<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMuscleGroupRequest;
use App\Http\Requests\UpdateMuscleGroupRequest;
use App\Http\Resources\MuscleGroupResource;
use App\Models\MuscleGroup;
use Spatie\QueryBuilder\QueryBuilder;

class MuscleGroupController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MuscleGroup::class, 'muscle_group');
    }

    public function index()
    {
        $muscleGroups = QueryBuilder::for(MuscleGroup::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['id', 'name'])
            ->jsonPaginate();

        return MuscleGroupResource::collection($muscleGroups);
    }

    public function store(StoreMuscleGroupRequest $request)
    {
        return new MuscleGroupResource(MuscleGroup::create($request->validated()));
    }

    public function show(MuscleGroup $muscleGroup)
    {
        return new MuscleGroupResource($muscleGroup);
    }

    public function update(UpdateMuscleGroupRequest $request, MuscleGroup $muscleGroup)
    {
        $muscleGroup->update($request->validated());

        return new MuscleGroupResource($muscleGroup);
    }

    public function destroy(MuscleGroup $muscleGroup)
    {
        return $this->empty(fn () => $muscleGroup->delete());
    }
}
