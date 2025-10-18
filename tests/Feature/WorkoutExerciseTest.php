<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutExercise;
use Illuminate\Database\Eloquent\Model;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonPagination;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class WorkoutExerciseTest extends TestCase
{
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticate(JwtApiAuthenticatable::class);

        $this->model = new WorkoutExercise();
        $this->route = 'workout-exercise';
        $this->table = $this->model->getTable();
    }

    public function test_listagem_com_paginacao()
    {
        $response = $this->getJson(route("{$this->route}.index"));

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_paginacao_e_filtros()
    {
        $response = $this->getJson(
            route("{$this->route}.index", ['filter[sets]' => 3]),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_erro_listagem_com_filtros_incorretos()
    {
        $response = $this->getJson(
            route("{$this->route}.index", ['filter[invalid_field]' => 'test']),
        );

        $response->assertStatus(400);
    }

    public function test_exibe_registro_existente()
    {
        $user        = User::factory()->create();
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);
        $exercise    = Exercise::create([
            'muscle_group_id' => $muscleGroup->id,
            'name'            => 'Supino Reto',
            'gif'             => 'supino.gif',
        ]);
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino A',
            'description' => 'Treino focado em peitorais',
        ]);

        $workoutExercise = WorkoutExercise::create([
            'exercise_id' => $exercise->id,
            'workout_id'  => $workout->id,
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $response = $this->getJson(route("{$this->route}.show", $workoutExercise->id));

        $response->assertStatus(200)
            ->assertJsonFragment($workoutExercise->toArray());
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", -1));

        $response->assertStatus(404);
    }

    public function test_cria_registro()
    {
        $user        = User::factory()->create();
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);
        $exercise    = Exercise::create([
            'muscle_group_id' => $muscleGroup->id,
            'name'            => 'Supino Reto',
            'gif'             => 'supino.gif',
        ]);
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino A',
            'description' => 'Treino focado em peitorais',
        ]);

        $data = [
            'exercise_id' => $exercise->id,
            'workout_id'  => $workout->id,
            'sets'        => 4,
            'reps'        => 12,
            'weight'      => 50.5,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'exercise_id' => null,
            'workout_id'  => null,
            'sets'        => 0,
            'reps'        => 0,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['exercise_id', 'workout_id', 'sets', 'reps']);
    }

    public function test_atualiza_registro()
    {
        $user        = User::factory()->create();
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);
        $exercise    = Exercise::create([
            'muscle_group_id' => $muscleGroup->id,
            'name'            => 'Supino Reto',
            'gif'             => 'supino.gif',
        ]);
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino A',
            'description' => 'Treino focado em peitorais',
        ]);

        $workoutExercise = WorkoutExercise::create([
            'exercise_id' => $exercise->id,
            'workout_id'  => $workout->id,
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $data = [
            'sets'   => 5,
            'reps'   => 15,
            'weight' => 60.0,
        ];

        $response = $this->putJson(route("{$this->route}.update", $workoutExercise->id), $data);

        $workoutExercise->refresh();

        $response->assertStatus(200)
            ->assertJsonFragment($workoutExercise->toArray());

        $this->assertDatabaseHas($this->table, $workoutExercise->toArray());
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $user        = User::factory()->create();
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);
        $exercise    = Exercise::create([
            'muscle_group_id' => $muscleGroup->id,
            'name'            => 'Supino Reto',
            'gif'             => 'supino.gif',
        ]);
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino A',
            'description' => 'Treino focado em peitorais',
        ]);

        $workoutExercise = WorkoutExercise::create([
            'exercise_id' => $exercise->id,
            'workout_id'  => $workout->id,
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $data = [
            'sets'        => -1,
            'exercise_id' => -1,
        ];

        $response = $this->putJson(route("{$this->route}.update", $workoutExercise->id), $data);

        $response->assertStatus(422);
    }

    public function test_deleta_registro()
    {
        $user        = User::factory()->create();
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);
        $exercise    = Exercise::create([
            'muscle_group_id' => $muscleGroup->id,
            'name'            => 'Supino Reto',
            'gif'             => 'supino.gif',
        ]);
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino A',
            'description' => 'Treino focado em peitorais',
        ]);

        $workoutExercise = WorkoutExercise::create([
            'exercise_id' => $exercise->id,
            'workout_id'  => $workout->id,
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $response = $this->deleteJson(route("{$this->route}.destroy", $workoutExercise->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, [
            'id' => $workoutExercise->id,
        ]);
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404);
    }
}
