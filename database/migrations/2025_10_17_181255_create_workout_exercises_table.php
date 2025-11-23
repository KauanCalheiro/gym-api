<?php

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    private Exercise        $exercise;
    private Workout         $workout;
    private WorkoutExercise $workoutExercise;

    public function __construct()
    {
        $this->exercise        = new Exercise();
        $this->workout         = new Workout();
        $this->workoutExercise = new WorkoutExercise();
    }

    public function up(): void
    {
        Schema::create($this->workoutExercise->getTable(), function (Blueprint $table) {
            $table->id();
            $table->integer('exercise_id');
            $table->integer('workout_id');
            $table->integer('sets');
            $table->integer('reps');
            $table->float('weight')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->workoutExercise->getTable());
    }
};
