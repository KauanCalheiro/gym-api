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
        // Test with invalid filter - API returns 400 for invalid filters
        $response = $this->getJson(
            route("{$this->route}.index", ['filter[invalid_field]' => 'test']),
        );

        $response->assertStatus(400);
    }

    public function test_exibe_registro_existente()
    {
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);

        $response = $this->getJson(route("{$this->route}.show", $muscleGroup->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Peitoral']);
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", 99999));

        $response->assertStatus(404);
    }

    public function test_cria_registro()
    {
        $data = [
            'name' => 'Peitoral',
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Peitoral']);

        $this->assertDatabaseHas($this->table, [
            'name' => 'Peitoral',
        ]);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'name' => '', // Campo obrigatório vazio
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_atualiza_registro()
    {
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);

        $data = [
            'name' => 'Peitoral Atualizado',
        ];

        $response = $this->putJson(route("{$this->route}.update", $muscleGroup->id), $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Peitoral Atualizado']);

        $this->assertDatabaseHas($this->table, [
            'id' => $muscleGroup->id,
            'name' => 'Peitoral Atualizado',
        ]);
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);

        $data = [
            'name' => '', // Campo obrigatório vazio
        ];

        $response = $this->putJson(route("{$this->route}.update", $muscleGroup->id), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_deleta_registro()
    {
        $muscleGroup = MuscleGroup::create(['name' => 'Peitoral']);

        $response = $this->deleteJson(route("{$this->route}.destroy", $muscleGroup->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing($this->table, [
            'id' => $muscleGroup->id,
            'deleted_at' => null,
        ]);
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", 99999));

        $response->assertStatus(404);
    }
}
