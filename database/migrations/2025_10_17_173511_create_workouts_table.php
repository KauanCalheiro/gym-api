<?php

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    private User    $user;
    private Workout $workout;

    public function __construct()
    {
        $this->user    = new User();
        $this->workout = new Workout();
    }

    public function up(): void
    {
        Schema::create($this->workout->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained($this->user->getTable());
            $table->text('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->workout->getTable());
    }
};
