<?php

namespace Tests\Feature;

use App\Models\MuscleGroup;
use Illuminate\Database\Eloquent\Model;
use Tests\Contracts\CrudTestContract;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonPagination;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class MuscleGroupTest extends TestCase
    // implements CrudTestContract
{
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticate(JwtApiAuthenticatable::class);

        $this->model = new MuscleGroup();
        $this->route = 'muscle-group';
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
            route("{$this->route}.index", ['filter[name]' => 'peito']),
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
        $muscleGroup = MuscleGroup::factory()->create();

        $response = $this->getJson(route("{$this->route}.show", $muscleGroup->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $muscleGroup->name]);
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", -1));

        $response->assertStatus(404);
    }

    public function test_cria_registro()
    {
        $muscleGroup = MuscleGroup::factory()->make();

        $response = $this->postJson(route("{$this->route}.store"), $muscleGroup->toArray());

        $response->assertStatus(201)
            ->assertJsonFragment($muscleGroup->toArray());

        $this->assertDatabaseHas($this->table, $muscleGroup->toArray());
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $response = $this->postJson(route("{$this->route}.store"), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_atualiza_registro()
    {
        $muscleGroup        = MuscleGroup::factory()->create();
        $updatedMuscleGroup = MuscleGroup::factory()->make();

        $response = $this->putJson(route("{$this->route}.update", $muscleGroup->id), $updatedMuscleGroup->toArray());

        $response->assertStatus(200)
            ->assertJsonFragment($updatedMuscleGroup->toArray());

        $muscleGroup->refresh();

        $this->assertDatabaseHas($this->table, $muscleGroup->toArray());
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $muscleGroup = MuscleGroup::factory()->create();

        $data = [
            'name' => '',
        ];

        $response = $this->putJson(route("{$this->route}.update", $muscleGroup->id), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_deleta_registro()
    {
        $muscleGroup = MuscleGroup::factory()->create();

        $response = $this->deleteJson(route("{$this->route}.destroy", $muscleGroup->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, ['id' => $muscleGroup->id]);
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404);
    }
}
