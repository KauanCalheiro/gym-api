<?php

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    private MuscleGroup $muscleGroup;
    private Exercise    $exercise;

    public function __construct()
    {
        $this->muscleGroup = new MuscleGroup();
        $this->exercise    = new Exercise();
    }

    public function up(): void
    {
        Schema::create($this->exercise->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('muscle_group_id')->constrained($this->muscleGroup->getTable());
            $table->text('name')->unique();
            $table->text('gif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->exercise->getTable());
    }
};
