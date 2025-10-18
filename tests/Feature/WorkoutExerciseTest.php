<?php

namespace Tests\Feature;

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
        $workoutExercise = WorkoutExercise::factory()->create();

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
        $workoutExercise = WorkoutExercise::factory()->make();

        $response = $this->postJson(route("{$this->route}.store"), $workoutExercise->toArray());

        $response->assertStatus(201)
            ->assertJsonFragment($workoutExercise->toArray());

        $this->assertDatabaseHas($this->table, $workoutExercise->toArray());
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $response = $this->postJson(route("{$this->route}.store"), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['exercise_id', 'workout_id', 'sets', 'reps']);
    }

    public function test_atualiza_registro()
    {
        $workoutExercise        = WorkoutExercise::factory()->create();
        $updatedWorkoutExercise = WorkoutExercise::factory()->make();

        $response = $this->putJson(route("{$this->route}.update", $workoutExercise->id), $updatedWorkoutExercise->toArray());

        $response->assertStatus(200)
            ->assertJsonFragment($updatedWorkoutExercise->toArray());

        $workoutExercise->refresh();

        $this->assertDatabaseHas($this->table, $workoutExercise->toArray());
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $workoutExercise = WorkoutExercise::factory()->create();

        $data = [
            'sets'        => -1,
            'exercise_id' => -1,
        ];

        $response = $this->putJson(route("{$this->route}.update", $workoutExercise->id), $data);

        $response->assertStatus(422);
    }

    public function test_deleta_registro()
    {
        $workoutExercise = WorkoutExercise::factory()->create();

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
