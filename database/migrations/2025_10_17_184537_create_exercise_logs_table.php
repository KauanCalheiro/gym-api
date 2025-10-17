<?php

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    private User        $user;
    private Exercise    $exercise;
    private ExerciseLog $exerciseLog;

    public function __construct()
    {
        $this->user        = new User();
        $this->exercise    = new Exercise();
        $this->exerciseLog = new ExerciseLog();
    }

    public function up(): void
    {
        Schema::create($this->exerciseLog->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained($this->user->getTable());
            $table->foreignId('exercise_id')->constrained($this->exercise->getTable());
            $table->timestamp('date');
            $table->integer('sets');
            $table->integer('reps');
            $table->decimal('weight', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->exerciseLog->getTable());
    }
};
