<?php

namespace Tests\Feature;

use App\Models\ExerciseLog;
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
        $exerciseLog = ExerciseLog::factory()->create();

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
        $exerciseLog = ExerciseLog::factory()->make();

        $response = $this->postJson(route("{$this->route}.store"), $exerciseLog->toArray());

        $response->assertStatus(201)
            ->assertJsonFragment($exerciseLog->toArray());

        $this->assertDatabaseHas($this->table, $exerciseLog->toArray());
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $response = $this->postJson(route("{$this->route}.store"), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'exercise_id', 'sets', 'reps']);
    }

    public function test_atualiza_registro()
    {
        $exerciseLog = ExerciseLog::factory()->create();
        $updatedData = ExerciseLog::factory()->make();

        $response = $this->putJson(route("{$this->route}.update", $exerciseLog->id), $updatedData->toArray());

        $exerciseLog->refresh();

        $response->assertStatus(200)
            ->assertJsonFragment($exerciseLog->toArray());

        $this->assertDatabaseHas($this->table, $exerciseLog->toArray());
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $exerciseLog = ExerciseLog::factory()->create();

        $data = [
            'sets'    => -1,
            'user_id' => -1,
        ];

        $response = $this->putJson(route("{$this->route}.update", $exerciseLog->id), $data);

        $response->assertStatus(422);
    }

    public function test_deleta_registro()
    {
        $exerciseLog = ExerciseLog::factory()->create();

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
