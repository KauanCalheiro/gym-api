<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\MuscleGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonPagination;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class ExerciseLogTest extends TestCase
{
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticate(JwtApiAuthenticatable::class);

        $this->model = new ExerciseLog();
        $this->route = 'exercise-log';
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

        $exerciseLog = ExerciseLog::create([
            'user_id'     => $user->id,
            'exercise_id' => $exercise->id,
            'date'        => '2025-10-17',
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $response = $this->getJson(route("{$this->route}.show", $exerciseLog->id));

        $response->assertStatus(200)
            ->assertJsonFragment($exerciseLog->toArray());
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

        $data = [
            'user_id'     => $user->id,
            'exercise_id' => $exercise->id,
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'user_id'     => null,
            'exercise_id' => null,
            'sets'        => 0,
            'reps'        => 0,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'exercise_id', 'sets', 'reps']);
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

        $exerciseLog = ExerciseLog::create([
            'user_id'     => $user->id,
            'exercise_id' => $exercise->id,
            'date'        => '2025-10-17',
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $data = [
            'sets'   => 4,
            'reps'   => 12,
            'weight' => 50.0,
        ];

        $response = $this->putJson(route("{$this->route}.update", $exerciseLog->id), $data);

        $exerciseLog->refresh();

        $response->assertStatus(200)
            ->assertJsonFragment($exerciseLog->toArray());

        $this->assertDatabaseHas($this->table, $exerciseLog->toArray());
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

        $exerciseLog = ExerciseLog::create([
            'user_id'     => $user->id,
            'exercise_id' => $exercise->id,
            'date'        => '2025-10-17',
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $data = [
            'sets'    => -1,
            'user_id' => 99999,
        ];

        $response = $this->putJson(route("{$this->route}.update", $exerciseLog->id), $data);

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

        $exerciseLog = ExerciseLog::create([
            'user_id'     => $user->id,
            'exercise_id' => $exercise->id,
            'date'        => '2025-10-17',
            'sets'        => 3,
            'reps'        => 10,
            'weight'      => 45.0,
        ]);

        $response = $this->deleteJson(route("{$this->route}.destroy", $exerciseLog->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, [
            'id' => $exerciseLog->id,
        ]);
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404);
    }
}
