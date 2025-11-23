<?php

namespace Tests\Feature;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonPagination;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class ExerciseTest extends TestCase
{
    use RefreshDatabase;
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->authenticate(JwtApiAuthenticatable::class);

        $this->model = new Exercise();
        $this->route = 'exercise';
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
            route("{$this->route}.index", ['filter[name]' => 'supino']),
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
        $exercise = Exercise::factory()->create();

        $response = $this->getJson(route("{$this->route}.show", $exercise->id));

        $exerciseArray = $exercise->toArray();
        unset($exerciseArray['gif']);

        $response->assertStatus(200)
            ->assertJsonFragment($exerciseArray);
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", -1));

        $response->assertStatus(404);
    }

    public function test_cria_registro()
    {
        $exercise      = Exercise::factory()->make();
        $exercise->gif = UploadedFile::fake()->create('exercise.gif');

        $response = $this->postJson(route("{$this->route}.store"), $exercise->toArray());

        $array = $exercise->toArray();
        unset($array['gif']);

        $response->assertStatus(201)
            ->assertJsonFragment($array);

        $this->assertDatabaseHas($this->table, $array);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $response = $this->postJson(route("{$this->route}.store"), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'muscle_group_id', 'gif']);
    }

    public function test_atualiza_registro()
    {
        $exercise             = Exercise::factory()->create();
        $updatedExercise      = Exercise::factory()->make();
        $updatedExercise->gif = UploadedFile::fake()->create('updated_exercise.gif');

        $response = $this->putJson(route("{$this->route}.update", $exercise->id), $updatedExercise->toArray());

        $exercise->refresh();

        $array = $exercise->toArray();
        unset($array['gif']);

        $response->assertStatus(200)
            ->assertJsonFragment($array);

        $this->assertDatabaseHas($this->table, $array);
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $exercise = Exercise::factory()->create();

        $data = [
            'muscle_group_id' => -1,
        ];

        $response = $this->putJson(route("{$this->route}.update", $exercise->id), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['muscle_group_id']);
    }

    public function test_deleta_registro()
    {
        $exercise = Exercise::factory()->create();

        $response = $this->deleteJson(route("{$this->route}.destroy", $exercise->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, [
            'id' => $exercise->id,
        ]);
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404);
    }
}
