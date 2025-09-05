<?php

use App\Models\MuscleGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected MuscleGroup $muscleGroup;

    public function __construct()
    {
        $this->muscleGroup = new MuscleGroup();
    }

    public function up(): void
    {
        Schema::create($this->muscleGroup->getTable(), function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->muscleGroup->getTable());
    }
};
